import React, { useState, useEffect } from "react";
import { useAuth } from "@/hooks/use-auth";
import { useToast } from "@/hooks/use-toast";
import { COUNTRIES, getStatesByCountry, getDistrictsByState } from "@/data/locations";

interface UserProfileModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function UserProfileModal({ isOpen, onClose }: UserProfileModalProps) {
  const { user } = useAuth();
  const { toast } = useToast();
  const [isLoading, setIsLoading] = useState(false);
  const [activeTab, setActiveTab] = useState('profile');
  
  // Profile data
  const [profileData, setProfileData] = useState({
    displayName: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    alternateNumber: '',
    profileImg: '',
  });

  // Address data
  const [addressData, setAddressData] = useState({
    address: '',
    country: 'IN',
    state: '',
    district: '',
  });

  // Get location options
  const availableStates = getStatesByCountry(addressData.country);
  const availableDistricts = getDistrictsByState(addressData.state);

  // Load user data when modal opens
  useEffect(() => {
    if (isOpen && user) {
      setProfileData({
        displayName: user.displayName || '',
        firstName: user.firstName || '',
        lastName: user.lastName || '',
        email: user.email || '',
        phone: user.phone || '',
        alternateNumber: user.alterNumber || '',
        profileImg: user.profileImg || '',
      });
      
      setAddressData({
        address: user.address || '',
        country: user.country || 'IN',
        state: user.state || '',
        district: user.district || '',
      });
    }
  }, [isOpen, user]);

  const handleProfileUpdate = async () => {
    setIsLoading(true);
    try {
      const response = await fetch('/api/users/update-profile', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        },
        body: JSON.stringify(profileData),
      });

      if (!response.ok) {
        throw new Error('Failed to update profile');
      }

      toast({
        title: "Success",
        description: "Profile updated successfully",
      });
    } catch (error) {
      toast({
        title: "Error",
        description: "Failed to update profile. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleAddressUpdate = async () => {
    setIsLoading(true);
    try {
      const response = await fetch('/api/users/update-address', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        },
        body: JSON.stringify(addressData),
      });

      if (!response.ok) {
        throw new Error('Failed to update address');
      }

      toast({
        title: "Success",
        description: "Address updated successfully",
      });
    } catch (error) {
      toast({
        title: "Error",
        description: "Failed to update address. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleImageUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (file) {
      // In a real app, you'd upload to a server
      const reader = new FileReader();
      reader.onload = (e) => {
        setProfileData(prev => ({
          ...prev,
          profileImg: e.target?.result as string
        }));
      };
      reader.readAsDataURL(file);
    }
  };

  if (!isOpen) return null;

  return (
    <div 
      className="modal fade show"
      style={{ display: 'block' }}
      tabIndex={-1}
      aria-labelledby="userProfileModalLabel"
    >
      <div className="modal-dialog modal-lg modal-dialog-centered">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="userProfileModalLabel">
              User Profile
            </h5>
            <button 
              type="button" 
              className="btn-close" 
              onClick={onClose}
              aria-label="Close"
            />
          </div>
          
          <div className="modal-body">
            {/* User Info Header */}
            <div className="text-center mb-4">
              <div className="position-relative d-inline-block">
                <div className="user-avatar-large mb-3">
                  {profileData.profileImg ? (
                    <img 
                      src={profileData.profileImg} 
                      alt="Profile" 
                      className="rounded-circle"
                      style={{ width: '100px', height: '100px', objectFit: 'cover' }}
                    />
                  ) : (
                    <i className="bi bi-person-circle" style={{ fontSize: '100px', color: '#6c757d' }}></i>
                  )}
                </div>
                <label 
                  htmlFor="profileImageUpload" 
                  className="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                  style={{ width: '32px', height: '32px' }}
                >
                  <i className="bi bi-camera"></i>
                </label>
                <input 
                  type="file" 
                  id="profileImageUpload"
                  className="d-none"
                  accept="image/*"
                  onChange={handleImageUpload}
                />
              </div>
              <h6 className="mb-1">{user?.displayName || 'User'}</h6>
              <p className="text-muted small mb-0">Register ID: #{user?.id || '12345'}</p>
            </div>

            {/* Tabs */}
            <ul className="nav nav-tabs mb-3" role="tablist">
              <li className="nav-item" role="presentation">
                <button 
                  className={`nav-link ${activeTab === 'profile' ? 'active' : ''}`}
                  onClick={() => setActiveTab('profile')}
                  type="button"
                >
                  <i className="bi bi-person me-2"></i>
                  Profile
                </button>
              </li>
              <li className="nav-item" role="presentation">
                <button 
                  className={`nav-link ${activeTab === 'address' ? 'active' : ''}`}
                  onClick={() => setActiveTab('address')}
                  type="button"
                >
                  <i className="bi bi-geo-alt me-2"></i>
                  Address
                </button>
              </li>
            </ul>

            {/* Profile Tab */}
            {activeTab === 'profile' && (
              <div className="tab-pane fade show active">
                <div className="row g-3">
                  <div className="col-12">
                    <div className="form-floating">
                      <input 
                        type="text" 
                        className="form-control" 
                        id="displayName" 
                        placeholder="Display Name"
                        value={profileData.displayName}
                        onChange={(e) => setProfileData(prev => ({ ...prev, displayName: e.target.value }))}
                      />
                      <label htmlFor="displayName">Display Name</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <input 
                        type="text" 
                        className="form-control" 
                        id="firstName" 
                        placeholder="First Name"
                        value={profileData.firstName}
                        onChange={(e) => setProfileData(prev => ({ ...prev, firstName: e.target.value }))}
                      />
                      <label htmlFor="firstName">First Name</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <input 
                        type="text" 
                        className="form-control" 
                        id="lastName" 
                        placeholder="Last Name"
                        value={profileData.lastName}
                        onChange={(e) => setProfileData(prev => ({ ...prev, lastName: e.target.value }))}
                      />
                      <label htmlFor="lastName">Last Name</label>
                    </div>
                  </div>
                  <div className="col-12">
                    <div className="form-floating">
                      <input 
                        type="email" 
                        className="form-control" 
                        id="email" 
                        placeholder="Email"
                        value={profileData.email}
                        onChange={(e) => setProfileData(prev => ({ ...prev, email: e.target.value }))}
                      />
                      <label htmlFor="email">Email Address</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <input 
                        type="tel" 
                        className="form-control" 
                        id="phone" 
                        placeholder="Phone"
                        value={profileData.phone}
                        onChange={(e) => setProfileData(prev => ({ ...prev, phone: e.target.value }))}
                      />
                      <label htmlFor="phone">Phone Number</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <input 
                        type="tel" 
                        className="form-control" 
                        id="alternateNumber" 
                        placeholder="Alternate Number"
                        value={profileData.alternateNumber}
                        onChange={(e) => setProfileData(prev => ({ ...prev, alternateNumber: e.target.value }))}
                      />
                      <label htmlFor="alternateNumber">Alternate Number</label>
                    </div>
                  </div>
                </div>
                
                <div className="d-flex justify-content-end mt-4">
                  <button 
                    type="button" 
                    className="btn btn-primary"
                    onClick={handleProfileUpdate}
                    disabled={isLoading}
                  >
                    {isLoading ? 'Updating...' : 'Update Profile'}
                  </button>
                </div>
              </div>
            )}

            {/* Address Tab */}
            {activeTab === 'address' && (
              <div className="tab-pane fade show active">
                <div className="row g-3">
                  <div className="col-12">
                    <div className="form-floating">
                      <textarea 
                        className="form-control" 
                        id="address" 
                        placeholder="Address"
                        style={{ height: '100px' }}
                        value={addressData.address}
                        onChange={(e) => setAddressData(prev => ({ ...prev, address: e.target.value }))}
                      />
                      <label htmlFor="address">Full Address</label>
                    </div>
                  </div>
                  <div className="col-12">
                    <div className="form-floating">
                      <select 
                        className="form-select" 
                        id="country"
                        value={addressData.country}
                        onChange={(e) => setAddressData(prev => ({ ...prev, country: e.target.value, state: '', district: '' }))}
                      >
                        {COUNTRIES.map(country => (
                          <option key={country.code} value={country.code}>
                            {country.flag} {country.name}
                          </option>
                        ))}
                      </select>
                      <label htmlFor="country">Country</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <select 
                        className="form-select" 
                        id="state"
                        value={addressData.state}
                        onChange={(e) => setAddressData(prev => ({ ...prev, state: e.target.value, district: '' }))}
                        disabled={!availableStates.length}
                      >
                        <option value="">Select State</option>
                        {availableStates.map(state => (
                          <option key={state.code} value={state.code}>
                            {state.name}
                          </option>
                        ))}
                      </select>
                      <label htmlFor="state">State</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <select 
                        className="form-select" 
                        id="district"
                        value={addressData.district}
                        onChange={(e) => setAddressData(prev => ({ ...prev, district: e.target.value }))}
                        disabled={!availableDistricts.length}
                      >
                        <option value="">Select District</option>
                        {availableDistricts.map(district => (
                          <option key={district.code} value={district.code}>
                            {district.name}
                          </option>
                        ))}
                      </select>
                      <label htmlFor="district">District</label>
                    </div>
                  </div>
                </div>
                
                <div className="d-flex justify-content-end mt-4">
                  <button 
                    type="button" 
                    className="btn btn-primary"
                    onClick={handleAddressUpdate}
                    disabled={isLoading}
                  >
                    {isLoading ? 'Updating...' : 'Update Address'}
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
