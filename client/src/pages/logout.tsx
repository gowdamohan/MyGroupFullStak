import { useEffect } from "react";
import { useLocation } from "wouter";
import { useAuth } from "@/hooks/use-auth";

export default function Logout() {
  const [, setLocation] = useLocation();
  const { logout } = useAuth();

  useEffect(() => {
    const handleLogout = async () => {
      try {
        await logout();
        // Redirect to login page
        setLocation('/auth/login');
      } catch (error) {
        console.error('Logout error:', error);
        // Even if logout fails, redirect to login
        setLocation('/auth/login');
      }
    };

    handleLogout();
  }, [logout, setLocation]);

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
