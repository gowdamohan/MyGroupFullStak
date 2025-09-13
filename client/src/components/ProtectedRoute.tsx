import React, { ReactNode, useEffect } from 'react';
import { useLocation } from 'wouter';
import { useAuth } from '@/hooks/use-auth';

interface ProtectedRouteProps {
  children: ReactNode;
  requiredRole?: string;
  adminOnly?: boolean;
  redirectTo?: string;
}

export function ProtectedRoute({
  children,
  requiredRole,
  adminOnly = false,
  redirectTo = '/auth/login'
}: ProtectedRouteProps) {
  const { user, isAuthenticated, isLoading } = useAuth();
  const [, setLocation] = useLocation();

  // Handle redirects in useEffect to avoid setState during render
  useEffect(() => {
    // Only redirect if we're sure the user is not authenticated
    // (not loading and no token in localStorage)
    const token = localStorage.getItem('authToken');
    if (!isLoading && !isAuthenticated && !token) {
      console.log('🔄 ProtectedRoute: Redirecting to login - no token found');
      setLocation(redirectTo);
    }
  }, [isLoading, isAuthenticated, redirectTo, setLocation]);

  // Show loading spinner while checking authentication
  if (isLoading) {
    return (
      <div className="min-vh-100 d-flex align-items-center justify-content-center">
        <div className="text-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
          <p className="mt-3 text-muted">Checking authentication...</p>
        </div>
      </div>
    );
  }

  // Redirect if not authenticated and no token exists
  const token = localStorage.getItem('authToken');
  if (!isAuthenticated && !token) {
    return null; // useEffect will handle the redirect
  }

  // Check admin access
  if (adminOnly && (!user?.isAdmin && user?.role !== 'admin')) {
    return (
      <div className="min-vh-100 d-flex align-items-center justify-content-center">
        <div className="text-center">
          <div className="alert alert-danger" role="alert">
            <h4 className="alert-heading">Access Denied</h4>
            <p>You don't have permission to access this page. Admin privileges required.</p>
            <hr />
            <button 
              className="btn btn-outline-danger"
              onClick={() => setLocation('/')}
            >
              Go to Home
            </button>
          </div>
        </div>
      </div>
    );
  }

  // Check specific role requirement
  if (requiredRole && user?.role !== requiredRole) {
    return (
      <div className="min-vh-100 d-flex align-items-center justify-content-center">
        <div className="text-center">
          <div className="alert alert-warning" role="alert">
            <h4 className="alert-heading">Insufficient Permissions</h4>
            <p>You don't have the required role ({requiredRole}) to access this page.</p>
            <p>Your current role: {user?.role}</p>
            <hr />
            <button 
              className="btn btn-outline-warning"
              onClick={() => setLocation('/')}
            >
              Go to Home
            </button>
          </div>
        </div>
      </div>
    );
  }

  // Render children if all checks pass
  return <>{children}</>;
}

// Convenience component for admin-only routes
export function AdminRoute({ children, redirectTo = '/login' }: { children: ReactNode; redirectTo?: string }) {
  return (
    <ProtectedRoute adminOnly={true} redirectTo={redirectTo}>
      {children}
    </ProtectedRoute>
  );
}

// Convenience component for role-based routes
export function RoleRoute({ 
  children, 
  role, 
  redirectTo = '/login' 
}: { 
  children: ReactNode; 
  role: string; 
  redirectTo?: string; 
}) {
  return (
    <ProtectedRoute requiredRole={role} redirectTo={redirectTo}>
      {children}
    </ProtectedRoute>
  );
}
