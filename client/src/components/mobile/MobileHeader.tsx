import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { APPS_CONFIG } from "@/config/apps";
import DynamicAppNavigation from "./DynamicAppNavigation";
import type { AppItem } from "@/lib/types";

interface User {
  id: number;
  username: string;
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  profileImg?: string;
  displayName?: string;
  address?: string;
  country?: string;
  state?: string;
  district?: string;
  education?: string;
  profession?: string;
  identificationCode?: string;
  gender?: string;
  maritalStatus?: string;
  dateOfBirth?: string;
  nationality?: string;
}

interface GroupApp {
  apps_id: number;
  name: string;
  icon: string;
  icon_id: number;
}

interface LocationData {
  global: { globalCount: number };
  national: { natioanlCount: number; country: string };
  regional: { regionalCount: number; state: string };
  local: { localCount: number; district: string };
}

interface MobileHeaderProps {
  onProfileClick: () => void;
  onSearch: (query: string) => void;
  onAppSelect?: (app: AppItem) => void;
  groupName?: string;
  logoUrl?: string;
}

// API functions for user profile and mobile header functionality
const fetchUserProfile = async (): Promise<User> => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/user/profile', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  });
  if (!response.ok) {
    throw new Error('Failed to fetch user profile');
  }
  return response.json();
};

const updateUserProfile = async (userData: Partial<User>): Promise<User> => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/user/profile', {
    method: 'PUT',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(userData),
  });
  if (!response.ok) {
    throw new Error('Failed to update user profile');
  }
  return response.json();
};

const uploadProfileImage = async (file: File): Promise<{ profileImg: string }> => {
  const token = localStorage.getItem('token');
  const formData = new FormData();
  formData.append('profileImage', file);

  const response = await fetch('/api/user/profile/image', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: formData,
  });
  if (!response.ok) {
    throw new Error('Failed to upload profile image');
  }
  return response.json();
};

// Mobile header specific API functions
const fetchMyGroupsApps = async (appsName: string = 'My Apps'): Promise<GroupApp[]> => {
  const response = await fetch('/api/mobile/get-my-groups-apps', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ apps_name: appsName }),
  });
  if (!response.ok) {
    throw new Error('Failed to fetch my groups apps');
  }
  return response.json();
};

const fetchAllMyGroupsApps = async (): Promise<any> => {
  const response = await fetch('/api/mobile/get-all-mygroups-apps');
  if (!response.ok) {
    throw new Error('Failed to fetch all mygroups apps');
  }
  return response.json();
};

const switchDarkMode = async (switchMode: number): Promise<any> => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/mobile/switch-darkmode', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ switch_mode: switchMode }),
  });
  if (!response.ok) {
    throw new Error('Failed to switch dark mode');
  }
  return response.json();
};

const fetchLocationWiseData = async (): Promise<LocationData> => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/mobile/get-location-wise-data', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  });
  if (!response.ok) {
    throw new Error('Failed to fetch location wise data');
  }
  return response.json();
};

const logoutUser = async (): Promise<void> => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/auth/logout', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  });
  if (!response.ok) {
    throw new Error('Failed to logout');
  }
  localStorage.removeItem('token');
};

export default function MobileHeader({ onProfileClick, onSearch, onAppSelect, groupName = "MyGroup", logoUrl }: MobileHeaderProps) {
  const [, setLocation] = useLocation();
  const [showAppsMenu, setShowAppsMenu] = useState(false);
  const [showUserMenu, setShowUserMenu] = useState(false);
  const [showGroupSettings, setShowGroupSettings] = useState(false);
  const [isDarkMode, setIsDarkMode] = useState(() => {
    return localStorage.getItem('darkMode') === 'true' || document.documentElement.classList.contains('dark');
  });
  const [searchQuery, setSearchQuery] = useState("");
  const [showEditProfile, setShowEditProfile] = useState(false);
  const [editFormData, setEditFormData] = useState<Partial<User>>({});
  const [showMoreApps, setShowMoreApps] = useState(false);

  const queryClient = useQueryClient();

  // Fetch user profile data
  const { data: user, isLoading: userLoading, error: userError } = useQuery({
    queryKey: ['userProfile'],
    queryFn: fetchUserProfile,
    retry: 1,
  });

  // Fetch my groups apps
  const { data: myGroupsApps } = useQuery({
    queryKey: ['myGroupsApps'],
    queryFn: () => fetchMyGroupsApps('My Apps'),
  });

  // Fetch all mygroups apps
  const { data: allMyGroupsApps } = useQuery({
    queryKey: ['allMyGroupsApps'],
    queryFn: fetchAllMyGroupsApps,
  });

  // Fetch location wise data
  const { data: locationData } = useQuery({
    queryKey: ['locationWiseData'],
    queryFn: fetchLocationWiseData,
    enabled: !!user,
  });

  // Update profile mutation
  const updateProfileMutation = useMutation({
    mutationFn: updateUserProfile,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['userProfile'] });
      setShowEditProfile(false);
      setEditFormData({});
    },
  });

  // Upload image mutation
  const uploadImageMutation = useMutation({
    mutationFn: uploadProfileImage,
    onSuccess: (data) => {
      queryClient.invalidateQueries({ queryKey: ['userProfile'] });
    },
  });

  // Dark mode mutation
  const darkModeMutation = useMutation({
    mutationFn: switchDarkMode,
    onSuccess: (data) => {
      setIsDarkMode(data.dark_mode === 1);
      localStorage.setItem('darkMode', data.dark_mode === 1 ? 'true' : 'false');
    },
  });

  // Logout mutation
  const logoutMutation = useMutation({
    mutationFn: logoutUser,
    onSuccess: () => {
      queryClient.clear();
      setLocation('/login');
    },
  });

  // Initialize edit form data when user data is loaded
  useEffect(() => {
    if (user && !editFormData.id) {
      setEditFormData(user);
    }
  }, [user, editFormData.id]);

  const handleAppClick = (app: AppItem) => {
    if (onAppSelect) {
      onAppSelect(app);
    } else if (app.route) {
      setLocation(app.route);
    }
    setShowAppsMenu(false);
  };

  const toggleDarkMode = () => {
    const newMode = !isDarkMode;
    setIsDarkMode(newMode);
    document.documentElement.classList.toggle('dark', newMode);
    document.body.classList.toggle('dark-mode', newMode);

    // Call API to save preference
    darkModeMutation.mutate(newMode ? 1 : 0);
  };

  const handleLogout = () => {
    logoutMutation.mutate();
  };

  const handleEditProfile = () => {
    setShowEditProfile(true);
    setShowUserMenu(false);
  };

  const handleSaveProfile = () => {
    updateProfileMutation.mutate(editFormData);
  };

  const handleImageUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      uploadImageMutation.mutate(file);
    }
  };

  const handleInputChange = (field: keyof User, value: string) => {
    setEditFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  return (
    <header className="mobile-header">
      {/* Navigation Bar with Apps - Fixed horizontal layout */}
      <nav className="navbar navbar-expand-lg fixed-top navbar-dark mobile-nav-bar" style={{ background: '#057284', paddingRight: 0, paddingLeft: 0, overflowX: 'auto', overflowY: 'hidden' }}>
        <div className="mobile-nav-container">
          <div className="mobile-nav-scroll">
            {/* More Apps Button */}
            <div className="nav-item-fixed">
              <a className="nav-link text-center" onClick={() => setShowMoreApps(true)} href="#" style={{ cursor: 'pointer' }}>
                <i className="fa fa-th-large" style={{ color: '#f0e8e8' }}></i><br />
                <span style={{ fontSize: '9px', color: '#f0e8e8' }}>More</span>
              </a>
            </div>

            {/* My Groups Apps */}
            <div className="nav-apps-container">
              {myGroupsApps?.slice(0, 8).map((app, index) => (
                <div
                  key={app.apps_id}
                  className="nav-item-app text-center"
                >
                  <a className="nav-link" href="#" onClick={(e) => { e.preventDefault(); handleAppClick(app as any); }}>
                    {app.icon ? (
                      <img style={{ width: '20px' }} src={app.icon} alt={app.name} />
                    ) : (
                      <i className="bi bi-app" style={{ color: '#f0e8e8' }}></i>
                    )}
                    <br />
                    <span style={{ fontSize: '9px', color: '#f0e8e8' }}>{app.name}</span>
                  </a>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Header Logo Section */}
        <div className="header-logo" style={{ background: '#fff', width: '100%' }}>
          <div className="container">
            {/* Left user profile details */}
            <div className="d-flex align-items-start justify-content-end" style={{ padding: '0px' }}>
              <div className="wrapper" style={{ padding: 0 }}>
                <div className="btn" style={{ width: '100%', padding: 0 }}>
                  {user ? (
                    <a onClick={() => setShowUserMenu(true)} style={{ cursor: 'pointer' }}>
                      <img
                        style={{ width: '24px', height: '22px', borderRadius: '50px' }}
                        className="rounded-circle"
                        src={user.profileImg || '/assets/front/logo.jpg'}
                        alt="Profile"
                      />
                    </a>
                  ) : (
                    <i onClick={() => setLocation('/login')} style={{ fontSize: '20px', cursor: 'pointer' }} className="fa fa-user"></i>
                  )}
                </div>
              </div>
              <a style={{ marginLeft: '0.5rem' }} href={`/group/${groupName}`}>
                <img className="brand-logo" style={{ width: '70px' }} src={logoUrl || '/assets/front/logo.jpg'} alt="Logo" />
              </a>
            </div>

            {/* Right user group details */}
            <div className="d-flex align-items-end justify-content-end" style={{ padding: '0px' }}>
              <div className="wrapper" style={{ padding: 0 }}>
                <div className="btn" style={{ width: '100%', padding: 0 }}>
                  {/* Dark Mode Toggle */}
                  <i
                    onClick={toggleDarkMode}
                    className={`fa ${isDarkMode ? 'fa-sun-o' : 'fa-adjust'}`}
                    style={{ fontSize: '20px', marginRight: '1rem', cursor: 'pointer' }}
                  ></i>

                  {/* Group Settings */}
                  {user ? (
                    <a onClick={() => setShowGroupSettings(true)} style={{ cursor: 'pointer' }}>
                      <img
                        style={{ width: '24px', height: '22px', borderRadius: '50px' }}
                        className="rounded-circle"
                        src={logoUrl || '/assets/front/logo.jpg'}
                        alt="Group"
                      />
                    </a>
                  ) : (
                    <i onClick={() => setLocation('/login')} style={{ fontSize: '20px', cursor: 'pointer' }} className="fa">
                      <img className="rounded-circle" style={{ width: '22px' }} src={logoUrl || '/assets/front/logo.jpg'} alt="Group" />
                    </i>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>

      {/* Group Settings Modal */}
      {showGroupSettings && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog m-0" style={{ height: 'auto' }}>
            <div className="modal-content" style={{ height: 'auto' }}>
              <div className="modal-header" style={{ padding: '15px 30px', border: 'none', background: '#17a2b8', color: '#fff' }}>
                <img style={{ width: '35px' }} src={logoUrl || '/assets/front/logo.jpg'} alt="Logo" />
                <span><img style={{ width: '100px' }} src={logoUrl || '/assets/front/logo.jpg'} alt="Group" /></span>
                <button type="button" className="close" onClick={() => setShowGroupSettings(false)}>
                  <span style={{ color: '#fff' }}>&times;</span>
                </button>
              </div>
              <div className="modal-body" style={{ background: '#4c4444', color: '#fff' }}>
                {user && (
                  <div className="row">
                    <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                      <p><strong>ID : </strong> {user.username}</p>
                    </div>

                    <span style={{ fontSize: '30px', marginLeft: '8rem', color: '#17a2b8', fontWeight: 600, marginTop: '1rem' }}>{groupName}</span>

                    <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                      <p>Legal <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                    </div>

                    <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                      <p>Help & Support <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                    </div>

                    <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                      <p>Reviews <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                    </div>

                    <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                      <p>Ratings <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                    </div>

                    {/* Social Media Links */}
                    <div className="footer-social-icon" style={{ marginBottom: '14px', textAlign: 'center', width: '100%' }}>
                      <span>Follow us</span>
                      <div className="d-flex justify-content-center gap-2 mt-2">
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/web sq.png" style={{ width: '30px' }} /></a>
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/youtube sq.png" style={{ width: '30px' }} /></a>
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/facebook sq.png" style={{ width: '30px' }} /></a>
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/instagram sq.png" style={{ width: '30px' }} /></a>
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/Twitter sq.png" style={{ width: '30px' }} /></a>
                        <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/Linkedin sq.png" style={{ width: '30px' }} /></a>
                      </div>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* User Profile Modal - Replicating PHP profileModal1 */}
      {showUserMenu && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog m-0" style={{ height: 'auto' }}>
            <div className="modal-content" style={{ height: 'auto' }}>
              <div className="modal-header" style={{ border: 'none', background: '#17a2b8', color: '#fff' }}>
                {user && (
                  <>
                    <img
                      onClick={() => document.getElementById('fileupload')?.click()}
                      className="rounded-circle"
                      style={{ width: '34px', height: '34px', marginRight: '1rem', borderRadius: '50px', background: '#fff', cursor: 'pointer' }}
                      src={user.profileImg || '/assets/front/logo.jpg'}
                      alt="Profile"
                    />
                    <i
                      onClick={() => document.getElementById('fileupload')?.click()}
                      className="fa fa-camera"
                      style={{ cursor: 'pointer' }}
                    ></i>
                    <input
                      hidden
                      type="file"
                      id="fileupload"
                      accept="image/*"
                      onChange={handleImageUpload}
                    />
                  </>
                )}
                <h3>My {user?.displayName || 'Profile'}</h3>
                <button type="button" className="close" onClick={() => setShowUserMenu(false)}>
                  <span style={{ color: '#fff' }}>&times;</span>
                </button>
              </div>

              <div className="modal-header" style={{ padding: '0px 30px', borderBottom: '2px solid #3c3232', background: '#17a2b8', lineHeight: '3rem', borderRadius: 'initial' }}>
                <a style={{ color: '#fff' }} href="#">ID : {user?.username}</a>
              </div>

              <div className="modal-body" style={{ padding: 0 }}>
                <div className="container" style={{ padding: 0 }}>
                  <div className="row">
                    <div className="col" style={{ padding: 0 }}>
                      <a style={{ width: '100%' }} className="btn btn-warning" href="#" onClick={handleEditProfile}>Profile</a>
                    </div>
                    <div className="col" style={{ padding: 0 }}>
                      <a style={{ width: '100%' }} className="btn btn-warning" href="#">Personal</a>
                    </div>
                    <div className="col" style={{ padding: 0 }}>
                      <a style={{ width: '100%' }} className="btn btn-warning" href="#">Address</a>
                    </div>
                    <div className="col" style={{ padding: 0 }}>
                      <a style={{ width: '100%' }} className="btn btn-warning" href="#">Billing</a>
                    </div>
                  </div>
                </div>
              </div>

              <div className="modal-body" style={{ background: '#4c4444', color: '#fff' }}>
                {user && (
                  <>
                    <span style={{ fontSize: '1.2rem', color: '#fff', fontWeight: 700 }}>MY Group</span>

                    <div className="row">
                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p><a style={{ color: '#fff' }} href="/">Home</a></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p style={{ height: '4rem' }}>
                          <a style={{ color: '#fff' }} href="#" onClick={() => {}}>
                            Set Location <i style={{ fontSize: '24px', float: 'right', color: '#f27474' }} className="fa">&#xf041;</i>
                          </a>
                          <br />
                          <small style={{ position: 'relative', top: '-1.5rem' }}>
                            {locationData ? `${locationData.national.country} / ${locationData.regional.state} / ${locationData.local.district}` : 'Not set'}
                          </small>
                        </p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>Settings <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>Legal <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>Help & Support <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p><a style={{ color: '#fff' }} href="#">Share App</a></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p><a style={{ color: '#fff' }} href="/download">Download Apps</a></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>Contact Us <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i></p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>
                          <a style={{ color: '#fff' }} href="/reviews">Reviews and Ratings</a>
                          <i style={{ fontSize: '24px', float: 'right' }} className="fa">&#xf105;</i>
                        </p>
                      </div>

                      <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                        <p>
                          <a style={{ color: '#fff' }} onClick={handleLogout} href="#" className={logoutMutation.isPending ? 'disabled' : ''}>
                            {logoutMutation.isPending ? 'Logging out...' : 'Logout'}
                          </a>
                        </p>
                      </div>

                      {/* Location wise user data */}
                      {locationData && (
                        <div className="col-12" style={{ borderRight: '1px solid grey' }}>
                          <p>Total Users</p>
                          <div className="container">
                            <div className="row">
                              <table className="table" style={{ color: '#fff' }}>
                                <tbody>
                                  <tr>
                                    <th>Global</th>
                                    <td>Global</td>
                                    <td>{locationData.global.globalCount}</td>
                                  </tr>
                                  <tr>
                                    <th>National</th>
                                    <td>{locationData.national.country}</td>
                                    <td>{locationData.national.natioanlCount}</td>
                                  </tr>
                                  <tr>
                                    <th>Regional</th>
                                    <td>{locationData.regional.state}</td>
                                    <td>{locationData.regional.regionalCount}</td>
                                  </tr>
                                  <tr>
                                    <th>Local</th>
                                    <td>{locationData.local.district}</td>
                                    <td>{locationData.local.localCount}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      )}

                      {/* Social Media and App Download Section */}
                      <div className="footer-social-icon" style={{ marginBottom: '14px', textAlign: 'center', width: '100%' }}>
                        <p style={{ borderBottom: 'none' }}>Follow us</p>
                        <div className="d-flex justify-content-center gap-2">
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/web sq.png" style={{ width: '30px' }} /></a>
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/youtube sq.png" style={{ width: '30px' }} /></a>
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/facebook sq.png" style={{ width: '30px' }} /></a>
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/instagram sq.png" style={{ width: '30px' }} /></a>
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/Twitter sq.png" style={{ width: '30px' }} /></a>
                          <a href="#"><img className="social-icon-width" src="/assets/front/img/social-icon/Linkedin sq.png" style={{ width: '30px' }} /></a>
                        </div>
                      </div>

                      {/* App Download Section */}
                      <div className="container">
                        <div className="footer-social-icon" style={{ textAlign: 'center' }}>
                          <span>Download the App</span>
                        </div>
                        <div className="row">
                          <div className="col-6">
                            <div className="text-center">
                              <a href="#" style={{ fontSize: '0.8rem', marginBottom: '1rem', color: '#fff' }}>
                                <img style={{ borderRadius: '20px', width: '28px', height: '28px' }} src="/assets/front/img/My app.png" alt="MyGroup" /> MyGroup
                              </a>
                              <br />
                              <a href="https://play.google.com/store/apps/details?id=com.mygroup.apps" target="_blank">
                                <img src="/assets/front/img/play_store.png" width="130" alt="Play Store" />
                              </a>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="text-center">
                              <a href="#" style={{ fontSize: '0.8rem', marginBottom: '1rem', color: '#fff' }}>
                                <img style={{ borderRadius: '20px', width: '28px', height: '28px' }} src="/assets/front/img/my partner.png" alt="MyPartner" /> MyPartner
                              </a>
                              <br />
                              <a href="https://play.google.com/store/apps/details?id=com.mygroup.partner" target="_blank">
                                <img src="/assets/front/img/play_store.png" width="130" alt="Play Store" />
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* More Apps Modal - Replicating PHP more_groups_apps */}
      {showMoreApps && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog m-0" style={{ height: 'auto' }}>
            <div className="modal-content" style={{ height: 'auto', background: '#4c4444' }}>
              <div className="modal-header">
                <button type="button" className="close" onClick={() => setShowMoreApps(false)}>
                  <span style={{ color: '#fff' }}>&times;</span>
                </button>
              </div>
              <div className="modal-body">
                {allMyGroupsApps && Object.entries(allMyGroupsApps).map(([categoryName, apps]) => (
                  <div key={categoryName}>
                    <span style={{ fontSize: '1.2rem', color: '#fff', fontWeight: 700 }}>{categoryName}</span>
                    <hr className="mt-1" />
                    <div className="row text-center">
                      {(apps as any[]).map((app) => (
                        <div key={app.id} className="col-3">
                          <a
                            className="nav-link"
                            href="#"
                            onClick={(e) => {
                              e.preventDefault();
                              handleAppClick(app as any);
                              setShowMoreApps(false);
                            }}
                          >
                            {app.icon ? (
                              <img style={{ width: '36px' }} src={app.icon} alt={app.name} />
                            ) : (
                              <i className="bi bi-app" style={{ fontSize: '36px', color: '#fff' }}></i>
                            )}
                            <br />
                            <span style={{ fontSize: '9px', color: '#fff' }}>{app.name}</span>
                          </a>
                        </div>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Edit Profile Modal */}
      {showEditProfile && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">
                  <i className="bi bi-person-gear me-2"></i>Edit Profile
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setShowEditProfile(false)}
                ></button>
              </div>
              <div className="modal-body">
                <div className="row g-3">
                  <div className="col-md-6">
                    <label className="form-label">First Name</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.firstName || ''}
                      onChange={(e) => handleInputChange('firstName', e.target.value)}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Last Name</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.lastName || ''}
                      onChange={(e) => handleInputChange('lastName', e.target.value)}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Display Name</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.displayName || ''}
                      onChange={(e) => handleInputChange('displayName', e.target.value)}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Phone</label>
                    <input
                      type="tel"
                      className="form-control"
                      value={editFormData.phone || ''}
                      onChange={(e) => handleInputChange('phone', e.target.value)}
                    />
                  </div>
                  <div className="col-12">
                    <label className="form-label">Email</label>
                    <input
                      type="email"
                      className="form-control"
                      value={editFormData.email || ''}
                      onChange={(e) => handleInputChange('email', e.target.value)}
                    />
                  </div>
                  <div className="col-12">
                    <label className="form-label">Address</label>
                    <textarea
                      className="form-control"
                      rows={3}
                      value={editFormData.address || ''}
                      onChange={(e) => handleInputChange('address', e.target.value)}
                    />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label">Country</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.country || ''}
                      onChange={(e) => handleInputChange('country', e.target.value)}
                    />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label">State</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.state || ''}
                      onChange={(e) => handleInputChange('state', e.target.value)}
                    />
                  </div>
                  <div className="col-md-4">
                    <label className="form-label">District</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.district || ''}
                      onChange={(e) => handleInputChange('district', e.target.value)}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Education</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.education || ''}
                      onChange={(e) => handleInputChange('education', e.target.value)}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Profession</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editFormData.profession || ''}
                      onChange={(e) => handleInputChange('profession', e.target.value)}
                    />
                  </div>
                </div>
              </div>
              <div className="modal-footer">
                <button
                  type="button"
                  className="btn btn-secondary"
                  onClick={() => setShowEditProfile(false)}
                >
                  Cancel
                </button>
                <button
                  type="button"
                  className="btn btn-primary"
                  onClick={handleSaveProfile}
                  disabled={updateProfileMutation.isPending}
                >
                  {updateProfileMutation.isPending ? (
                    <>
                      <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                      Saving...
                    </>
                  ) : (
                    <>
                      <i className="bi bi-check-lg me-2"></i>
                      Save Changes
                    </>
                  )}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
      {/* Custom Styles */}
      <style>{`
        .mobile-header {
          margin-top: 24%;
          margin-bottom: 4px;
        }

        .mobile-nav-bar {
          height: auto;
          min-height: 60px;
        }

        .mobile-nav-container {
          width: 100%;
          overflow: hidden;
        }

        .mobile-nav-scroll {
          display: flex;
          align-items: center;
          width: 100%;
          overflow-x: auto;
          overflow-y: hidden;
          scrollbar-width: none;
          -ms-overflow-style: none;
          padding: 8px 0;
        }

        .mobile-nav-scroll::-webkit-scrollbar {
          display: none;
        }

        .nav-item-fixed {
          position: sticky;
          left: 0;
          background: #057284;
          z-index: 10;
          min-width: 50px;
          padding: 0 8px;
          border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-apps-container {
          display: flex;
          align-items: center;
          gap: 16px;
          padding: 0 16px;
          white-space: nowrap;
        }

        .nav-item-app {
          min-width: 60px;
          flex-shrink: 0;
        }

        .nav-item-app .nav-link {
          padding: 4px 8px;
          line-height: 1.2;
        }

        .dark-mode {
          background-color: #3c3a3a;
          color: white !important;
        }

        .dark-mode .modal-content {
          background-color: #3c3a3a;
        }

        .dark-mode a {
          color: white;
        }

        .dark-mode .list-group-item {
          background-color: #3c3a3a;
        }

        .dark-mode small {
          color: white;
        }

        .nav-tabs {
          border-bottom: none;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
          color: #ffffff;
          background-color: #0062cc;
          border-color: #dee2e6 #dee2e6 #fff;
        }

        .modal-open {
          padding-right: 0 !important;
        }

        .social-icon-width {
          width: 30px;
          height: 30px;
          margin: 0 5px;
        }

        .table th, .table td {
          color: #fff;
          border-color: #555;
        }

        .fa-camera {
          position: absolute;
          bottom: 0;
          right: 0;
          background: #007bff;
          color: white;
          border-radius: 50%;
          padding: 5px;
          font-size: 12px;
          cursor: pointer;
        }

        .mobile-camera {
          position: absolute;
          bottom: -10px;
          right: -5px;
        }

        @media (max-width: 768px) {
          .mobile-nav-bar {
            padding: 0;
          }

          .nav-item-fixed {
            min-width: 45px;
          }

          .nav-apps-container {
            gap: 12px;
            padding: 0 12px;
          }

          .nav-item-app {
            min-width: 50px;
          }
        }
      `}</style>
    </header>
  );
}
