import React from 'react';
import { useAuth } from '@/hooks/use-auth';
import { ProtectedRoute } from '@/components/ProtectedRoute';

const DashboardPage: React.FC = () => {
  const { user, logout } = useAuth();
  const isAdmin = user?.role === 'admin' || user?.isAdmin;
  const isMember = user?.role === 'member' || user?.role === 'user';

  const handleLogout = async () => {
    try {
      await logout();
      // Redirect will be handled by the auth context
      window.location.href = '/login';
    } catch (error) {
      console.error('Logout error:', error);
    }
  };

  return (
    <ProtectedRoute>
      <div className="container-fluid">
        <div className="row">
          {/* Sidebar */}
          <div className="col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-0">
            <div className="p-3">
              <h5 className="mb-3">My Group Dashboard</h5>
              <nav className="nav flex-column">
                <a className="nav-link text-white active" href="#dashboard">
                  <i className="fa fa-dashboard me-2"></i>
                  Dashboard
                </a>
                <a className="nav-link text-white" href="#profile">
                  <i className="fa fa-user me-2"></i>
                  Profile
                </a>
                {isAdmin && (
                  <>
                    <a className="nav-link text-white" href="#admin">
                      <i className="fa fa-cog me-2"></i>
                      Admin Settings
                    </a>
                    <a className="nav-link text-white" href="#users">
                      <i className="fa fa-users me-2"></i>
                      User Management
                    </a>
                  </>
                )}
                <a className="nav-link text-white" href="#apps">
                  <i className="fa fa-th me-2"></i>
                  My Apps
                </a>
                <hr className="my-3" />
                <button 
                  className="btn btn-outline-light btn-sm"
                  onClick={handleLogout}
                >
                  <i className="fa fa-sign-out me-2"></i>
                  Logout
                </button>
              </nav>
            </div>
          </div>

          {/* Main Content */}
          <div className="col-md-9 col-lg-10">
            <div className="p-4">
              {/* Header */}
              <div className="d-flex justify-content-between align-items-center mb-4">
                <h1>Welcome, {user?.firstName} {user?.lastName}</h1>
                <div className="d-flex align-items-center">
                  <span className="badge bg-primary me-2">
                    {isAdmin ? 'Administrator' : isMember ? 'Member' : 'User'}
                  </span>
                  <span className="text-muted">{user?.email}</span>
                </div>
              </div>

              {/* User Info Cards */}
              <div className="row mb-4">
                <div className="col-md-4">
                  <div className="card">
                    <div className="card-body">
                      <h5 className="card-title">
                        <i className="fa fa-user text-primary me-2"></i>
                        Profile Information
                      </h5>
                      <p className="card-text">
                        <strong>Username:</strong> {user?.username}<br />
                        <strong>Email:</strong> {user?.email}<br />
                        <strong>User ID:</strong> {user?.id}
                      </p>
                    </div>
                  </div>
                </div>

                <div className="col-md-4">
                  <div className="card">
                    <div className="card-body">
                      <h5 className="card-title">
                        <i className="fa fa-users text-success me-2"></i>
                        Group Memberships
                      </h5>
                      <div className="card-text">
                        <span className="badge bg-secondary me-1 mb-1">
                          {user?.role || 'User'}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="col-md-4">
                  <div className="card">
                    <div className="card-body">
                      <h5 className="card-title">
                        <i className="fa fa-cog text-warning me-2"></i>
                        Quick Actions
                      </h5>
                      <div className="d-grid gap-2">
                        <button className="btn btn-outline-primary btn-sm">
                          Edit Profile
                        </button>
                        <button className="btn btn-outline-secondary btn-sm">
                          Change Password
                        </button>
                        {isAdmin && (
                          <button className="btn btn-outline-warning btn-sm">
                            Admin Panel
                          </button>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {/* Recent Activity */}
              <div className="card">
                <div className="card-header">
                  <h5 className="mb-0">
                    <i className="fa fa-clock-o me-2"></i>
                    Recent Activity
                  </h5>
                </div>
                <div className="card-body">
                  <div className="alert alert-info">
                    <i className="fa fa-info-circle me-2"></i>
                    Welcome to your dashboard! This is where you'll see your recent activity and manage your account.
                  </div>
                  
                  <div className="list-group">
                    <div className="list-group-item">
                      <div className="d-flex w-100 justify-content-between">
                        <h6 className="mb-1">Account Created</h6>
                        <small>Recently</small>
                      </div>
                      <p className="mb-1">Your account has been successfully created and verified.</p>
                    </div>
                    
                    <div className="list-group-item">
                      <div className="d-flex w-100 justify-content-between">
                        <h6 className="mb-1">Login Successful</h6>
                        <small>Just now</small>
                      </div>
                      <p className="mb-1">You have successfully logged into your account.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ProtectedRoute>
  );
};

export default DashboardPage;
