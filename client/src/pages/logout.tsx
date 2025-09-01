import { useEffect } from "react";
import { useLocation } from "wouter";
import { apiRequest } from "@/lib/queryClient";

export default function Logout() {
  const [, setLocation] = useLocation();

  useEffect(() => {
    const handleLogout = async () => {
      try {
        await apiRequest('/api/auth/logout', {
          method: 'POST'
        });
        
        // Clear any local storage or session data
        localStorage.removeItem('user');
        sessionStorage.clear();
        
        // Redirect to login page
        setLocation('/login');
      } catch (error) {
        console.error('Logout error:', error);
        // Even if logout fails, redirect to login
        setLocation('/login');
      }
    };

    handleLogout();
  }, [setLocation]);

  return (
    <div className="d-flex justify-content-center align-items-center min-vh-100">
      <div className="text-center">
        <div className="spinner-border text-primary mb-3" role="status">
          <span className="visually-hidden">Logging out...</span>
        </div>
        <h5>Logging out...</h5>
        <p className="text-muted">Please wait while we log you out safely.</p>
      </div>
    </div>
  );
}
