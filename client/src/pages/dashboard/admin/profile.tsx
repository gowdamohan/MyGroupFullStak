import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";

function AdminProfile() {
  const [activeTab, setActiveTab] = useState('group');
  const { toast } = useToast();

  // Mock current user data - replace with real API
  const userQuery = useQuery({
    queryKey: ['/api/admin/profile'],
    queryFn: () => Promise.resolve({
      id: '1',
      username: 'admin',
      firstName: 'System',
      lastName: 'Administrator',
      email: 'admin@apphub.com',
      phone: '1234567890',
      role: 'admin',
      group: 'Super Admin',
      createdAt: '2024-01-15T10:30:00Z',
      groupAccountType: 'Enterprise',
      lastPasswordChange: '2024-06-01T14:20:00Z'
    })
  });

  const changePasswordMutation = useMutation({
    mutationFn: async (data: { currentPassword: string; newPassword: string; confirmPassword: string }) => {
      return apiRequest('/api/admin/change-password', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Password changed successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/profile'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to change password",
        variant: "destructive",
      });
    }
  });

  const [passwordForm, setPasswordForm] = useState({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  });

  const handlePasswordChange = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (passwordForm.newPassword !== passwordForm.confirmPassword) {
      toast({
        title: "Error",
        description: "New passwords don't match",
        variant: "destructive",
      });
      return;
    }

    if (passwordForm.newPassword.length < 8) {
      toast({
        title: "Error",
        description: "Password must be at least 8 characters long",
        variant: "destructive",
      });
      return;
    }

    changePasswordMutation.mutate(passwordForm);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile', active: true },
    { icon: 'bi-people', label: 'User Management', path: '/dashboard/admin/users' },
    { icon: 'bi-building', label: 'Organizations', path: '/dashboard/admin/organizations' },
    { icon: 'bi-graph-up', label: 'Analytics', path: '/dashboard/admin/analytics' },
    { icon: 'bi-gear', label: 'System Settings', path: '/dashboard/admin/settings' },
  ];

  return (
    <DashboardLayout 
      title="Admin Profile Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-lg-3">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Profile Sections</h5>
            </div>
            <div className="list-group list-group-flush">
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'group' ? 'active' : ''}`}
                onClick={() => setActiveTab('group')}
                data-testid="tab-group"
              >
                <i className="bi bi-people me-2"></i>Group Information
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'created' ? 'active' : ''}`}
                onClick={() => setActiveTab('created')}
                data-testid="tab-created"
              >
                <i className="bi bi-calendar me-2"></i>Account Created
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'account' ? 'active' : ''}`}
                onClick={() => setActiveTab('account')}
                data-testid="tab-account"
              >
                <i className="bi bi-building me-2"></i>Group Account
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'password' ? 'active' : ''}`}
                onClick={() => setActiveTab('password')}
                data-testid="tab-password"
              >
                <i className="bi bi-lock me-2"></i>Change Password
              </button>
            </div>
          </div>
        </div>

        <div className="col-lg-9">
          {/* Group Information Tab */}
          {activeTab === 'group' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Group Information</h5>
              </div>
              <div className="card-body">
                <div className="row g-3">
                  <div className="col-md-6">
                    <div className="bg-light p-4 rounded">
                      <h6 className="text-muted mb-2">Current Group</h6>
                      <h4 className="text-primary mb-0" data-testid="text-current-group">
                        <i className="bi bi-shield-check me-2"></i>
                        {userQuery.data?.group}
                      </h4>
                    </div>
                  </div>
                  <div className="col-md-6">
                    <div className="bg-light p-4 rounded">
                      <h6 className="text-muted mb-2">Role Level</h6>
                      <h4 className="text-success mb-0" data-testid="text-role-level">
                        <i className="bi bi-star me-2"></i>
                        Administrator
                      </h4>
                    </div>
                  </div>
                  <div className="col-12">
                    <h6 className="mb-3">Group Permissions</h6>
                    <div className="row g-3">
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>User Management</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>Content Management</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>System Settings</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>Analytics Access</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>Corporate Management</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center">
                          <i className="bi bi-check-circle text-success me-2"></i>
                          <span>Full System Access</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Account Created Tab */}
          {activeTab === 'created' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Account Created Information</h5>
              </div>
              <div className="card-body">
                <div className="row g-4">
                  <div className="col-md-6">
                    <div className="bg-light p-4 rounded text-center">
                      <i className="bi bi-calendar-check fs-1 text-primary mb-3"></i>
                      <h6 className="text-muted mb-2">Account Created</h6>
                      <h4 className="mb-0" data-testid="text-created-date">
                        {new Date(userQuery.data?.createdAt || '').toLocaleDateString('en-US', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric'
                        })}
                      </h4>
                    </div>
                  </div>
                  <div className="col-md-6">
                    <div className="bg-light p-4 rounded text-center">
                      <i className="bi bi-clock fs-1 text-info mb-3"></i>
                      <h6 className="text-muted mb-2">Account Age</h6>
                      <h4 className="mb-0" data-testid="text-account-age">
                        {Math.floor((Date.now() - new Date(userQuery.data?.createdAt || '').getTime()) / (1000 * 60 * 60 * 24))} days
                      </h4>
                    </div>
                  </div>
                  <div className="col-12">
                    <div className="table-responsive">
                      <table className="table table-striped">
                        <thead>
                          <tr>
                            <th>Detail</th>
                            <th>Information</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><strong>Username</strong></td>
                            <td data-testid="text-username">{userQuery.data?.username}</td>
                          </tr>
                          <tr>
                            <td><strong>Email</strong></td>
                            <td data-testid="text-email">{userQuery.data?.email}</td>
                          </tr>
                          <tr>
                            <td><strong>Phone</strong></td>
                            <td data-testid="text-phone">{userQuery.data?.phone}</td>
                          </tr>
                          <tr>
                            <td><strong>Role</strong></td>
                            <td>
                              <span className="badge bg-danger text-capitalize" data-testid="text-role">
                                {userQuery.data?.role}
                              </span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Group Account Tab */}
          {activeTab === 'account' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Group Account Details</h5>
              </div>
              <div className="card-body">
                <div className="row g-4">
                  <div className="col-md-4">
                    <div className="text-center p-4 border rounded">
                      <i className="bi bi-building fs-1 text-warning mb-3"></i>
                      <h6 className="text-muted mb-2">Account Type</h6>
                      <h5 className="mb-0" data-testid="text-account-type">
                        {userQuery.data?.groupAccountType}
                      </h5>
                    </div>
                  </div>
                  <div className="col-md-4">
                    <div className="text-center p-4 border rounded">
                      <i className="bi bi-people fs-1 text-success mb-3"></i>
                      <h6 className="text-muted mb-2">Total Users</h6>
                      <h5 className="mb-0" data-testid="text-total-users">1,247</h5>
                    </div>
                  </div>
                  <div className="col-md-4">
                    <div className="text-center p-4 border rounded">
                      <i className="bi bi-globe fs-1 text-info mb-3"></i>
                      <h6 className="text-muted mb-2">Regions</h6>
                      <h5 className="mb-0" data-testid="text-regions">12</h5>
                    </div>
                  </div>
                  <div className="col-12">
                    <h6 className="mb-3">Account Features</h6>
                    <div className="row g-3">
                      <div className="col-md-6">
                        <div className="d-flex align-items-center p-3 bg-light rounded">
                          <i className="bi bi-check-circle text-success me-3 fs-4"></i>
                          <div>
                            <h6 className="mb-1">Unlimited Users</h6>
                            <small className="text-muted">No user limit restrictions</small>
                          </div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="d-flex align-items-center p-3 bg-light rounded">
                          <i className="bi bi-check-circle text-success me-3 fs-4"></i>
                          <div>
                            <h6 className="mb-1">Advanced Analytics</h6>
                            <small className="text-muted">Full reporting suite</small>
                          </div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="d-flex align-items-center p-3 bg-light rounded">
                          <i className="bi bi-check-circle text-success me-3 fs-4"></i>
                          <div>
                            <h6 className="mb-1">24/7 Support</h6>
                            <small className="text-muted">Priority customer support</small>
                          </div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="d-flex align-items-center p-3 bg-light rounded">
                          <i className="bi bi-check-circle text-success me-3 fs-4"></i>
                          <div>
                            <h6 className="mb-1">Custom Branding</h6>
                            <small className="text-muted">White-label options</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Change Password Tab */}
          {activeTab === 'password' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Change Password</h5>
              </div>
              <div className="card-body">
                <div className="row">
                  <div className="col-md-8">
                    <form onSubmit={handlePasswordChange} data-testid="form-change-password">
                      <div className="mb-3">
                        <label htmlFor="currentPassword" className="form-label">Current Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="currentPassword"
                          value={passwordForm.currentPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, currentPassword: e.target.value}))}
                          required
                          data-testid="input-current-password"
                        />
                      </div>
                      <div className="mb-3">
                        <label htmlFor="newPassword" className="form-label">New Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="newPassword"
                          value={passwordForm.newPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, newPassword: e.target.value}))}
                          required
                          minLength={8}
                          data-testid="input-new-password"
                        />
                        <div className="form-text">Password must be at least 8 characters long</div>
                      </div>
                      <div className="mb-4">
                        <label htmlFor="confirmPassword" className="form-label">Confirm New Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="confirmPassword"
                          value={passwordForm.confirmPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, confirmPassword: e.target.value}))}
                          required
                          data-testid="input-confirm-password"
                        />
                      </div>
                      <button
                        type="submit"
                        className="btn btn-primary"
                        disabled={changePasswordMutation.isPending}
                        data-testid="button-change-password"
                      >
                        {changePasswordMutation.isPending ? (
                          <>
                            <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                            Changing...
                          </>
                        ) : (
                          <>
                            <i className="bi bi-key me-2"></i>
                            Change Password
                          </>
                        )}
                      </button>
                    </form>
                  </div>
                  <div className="col-md-4">
                    <div className="bg-light p-4 rounded">
                      <h6 className="mb-3">Password Security</h6>
                      <div className="mb-3">
                        <small className="text-muted">Last Changed:</small>
                        <br />
                        <strong data-testid="text-last-password-change">
                          {new Date(userQuery.data?.lastPasswordChange || '').toLocaleDateString()}
                        </strong>
                      </div>
                      <div className="mb-3">
                        <small className="text-muted">Password Requirements:</small>
                        <ul className="small mt-2">
                          <li>Minimum 8 characters</li>
                          <li>Include uppercase and lowercase</li>
                          <li>Include numbers</li>
                          <li>Include special characters</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}

export default AdminProfile;