import { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useToast } from "@/hooks/use-toast";
import { useMutation, useQuery } from "@tanstack/react-query";
import { apiRequest } from "@/lib/queryClient";

interface AdminUser {
  id: number;
  username: string;
  firstName?: string;
  lastName?: string;
  email: string;
  isAdmin: boolean;
}

export default function AdminDashboardPage() {
  const [, setLocation] = useLocation();
  const { toast } = useToast();

  // Check authentication and admin status
  const { data: authData, isLoading: authLoading, error: authError } = useQuery({
    queryKey: ['auth', 'me'],
    queryFn: async () => {
      const response = await apiRequest('/api/auth/me');
      if (!response.ok) {
        throw new Error('Not authenticated');
      }
      return await response.json();
    },
    retry: false,
  });

  // Get admin dashboard data
  const { data: dashboardData, isLoading: dashboardLoading } = useQuery({
    queryKey: ['admin', 'dashboard'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/dashboard');
      if (!response.ok) {
        throw new Error('Failed to load dashboard');
      }
      return await response.json();
    },
    enabled: !!authData?.isAdmin,
  });

  // Logout mutation
  const logoutMutation = useMutation({
    mutationFn: async () => {
      const response = await apiRequest('/api/auth/logout', {
        method: 'POST',
      });
      return await response.json();
    },
    onSuccess: () => {
      toast({
        title: "Logged Out",
        description: "You have been successfully logged out.",
      });
      setLocation('/admin/login');
    },
    onError: () => {
      toast({
        title: "Logout Error",
        description: "There was an error logging out.",
        variant: "destructive",
      });
    }
  });

  // Redirect if not authenticated or not admin
  useEffect(() => {
    if (authError || (authData && !authData.isAdmin)) {
      setLocation('/admin/login');
    }
  }, [authData, authError, setLocation]);

  const handleLogout = () => {
    logoutMutation.mutate();
  };

  if (authLoading) {
    return (
      <div className="min-vh-100 d-flex align-items-center justify-content-center">
        <div className="text-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
          <p className="mt-3">Verifying admin access...</p>
        </div>
      </div>
    );
  }

  if (!authData?.isAdmin) {
    return (
      <div className="min-vh-100 d-flex align-items-center justify-content-center">
        <div className="text-center">
          <h3 className="text-danger">Access Denied</h3>
          <p>You do not have admin privileges.</p>
          <button 
            className="btn btn-primary"
            onClick={() => setLocation('/admin/login')}
          >
            Go to Admin Login
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-vh-100" style={{ background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
      {/* Admin Header */}
      <nav className="navbar navbar-expand-lg navbar-dark bg-dark bg-opacity-75">
        <div className="container-fluid">
          <span className="navbar-brand">
            <i className="bi bi-shield-check text-warning me-2"></i>
            Admin Dashboard
          </span>
          
          <div className="navbar-nav ms-auto">
            <div className="nav-item dropdown">
              <a className="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                <i className="bi bi-person-circle me-2"></i>
                {authData.user?.firstName || authData.user?.username || 'Admin'}
              </a>
              <ul className="dropdown-menu dropdown-menu-end">
                <li>
                  <span className="dropdown-item-text">
                    <small className="text-muted">Logged in as:</small><br/>
                    <strong>{authData.user?.username}</strong>
                  </span>
                </li>
                <li><hr className="dropdown-divider"/></li>
                <li>
                  <button 
                    className="dropdown-item text-danger"
                    onClick={handleLogout}
                    disabled={logoutMutation.isPending}
                  >
                    <i className="bi bi-box-arrow-right me-2"></i>
                    {logoutMutation.isPending ? 'Logging out...' : 'Logout'}
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <div className="container-fluid py-4">
        <div className="row">
          {/* Sidebar */}
          <div className="col-md-3 col-lg-2">
            <div className="card bg-white bg-opacity-90">
              <div className="card-header">
                <h6 className="mb-0">
                  <i className="bi bi-list me-2"></i>
                  Admin Menu
                </h6>
              </div>
              <div className="list-group list-group-flush">
                <a href="#" className="list-group-item list-group-item-action active">
                  <i className="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
                <a href="#" className="list-group-item list-group-item-action">
                  <i className="bi bi-people me-2"></i>
                  Users
                </a>
                <a href="#" className="list-group-item list-group-item-action">
                  <i className="bi bi-database me-2"></i>
                  Database
                </a>
                <a href="#" className="list-group-item list-group-item-action">
                  <i className="bi bi-gear me-2"></i>
                  Settings
                </a>
              </div>
            </div>
          </div>

          {/* Main Dashboard Content */}
          <div className="col-md-9 col-lg-10">
            <div className="row g-4">
              {/* Welcome Card */}
              <div className="col-12">
                <div className="card bg-white bg-opacity-90">
                  <div className="card-body">
                    <h4 className="card-title">
                      <i className="bi bi-house-heart text-primary me-2"></i>
                      Welcome to Admin Dashboard
                    </h4>
                    <p className="card-text">
                      You have successfully logged in as an administrator. 
                      You now have access to all administrative functions.
                    </p>
                    {dashboardData && (
                      <div className="alert alert-success">
                        <i className="bi bi-check-circle me-2"></i>
                        Dashboard loaded at: {new Date(dashboardData.timestamp).toLocaleString()}
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* Stats Cards */}
              <div className="col-md-6 col-lg-3">
                <div className="card bg-primary text-white">
                  <div className="card-body">
                    <div className="d-flex justify-content-between">
                      <div>
                        <h6 className="card-title">Database</h6>
                        <h4>Connected</h4>
                      </div>
                      <i className="bi bi-database fs-1 opacity-50"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div className="col-md-6 col-lg-3">
                <div className="card bg-success text-white">
                  <div className="card-body">
                    <div className="d-flex justify-content-between">
                      <div>
                        <h6 className="card-title">MySQL</h6>
                        <h4>Active</h4>
                      </div>
                      <i className="bi bi-server fs-1 opacity-50"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div className="col-md-6 col-lg-3">
                <div className="card bg-warning text-dark">
                  <div className="card-body">
                    <div className="d-flex justify-content-between">
                      <div>
                        <h6 className="card-title">Admin</h6>
                        <h4>Logged In</h4>
                      </div>
                      <i className="bi bi-shield-check fs-1 opacity-50"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div className="col-md-6 col-lg-3">
                <div className="card bg-info text-white">
                  <div className="card-body">
                    <div className="d-flex justify-content-between">
                      <div>
                        <h6 className="card-title">System</h6>
                        <h4>Online</h4>
                      </div>
                      <i className="bi bi-activity fs-1 opacity-50"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
