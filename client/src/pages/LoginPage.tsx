import React from 'react';
import { useLocation, useRoute } from 'wouter';
import LoginForm from '../components/auth/LoginForm';
import { useAuth } from '@/hooks/use-auth';

const LoginPage: React.FC = () => {
  const [, setLocation] = useLocation();
  const { isAuthenticated } = useAuth();
  
  // Check for client login route pattern
  const [match, params] = useRoute('/client-login/:groupName');
  const isClientLogin = !!match;
  const groupName = params?.groupName;

  // Redirect if already authenticated
  React.useEffect(() => {
    if (isAuthenticated) {
      setLocation('/dashboard');
    }
  }, [isAuthenticated, setLocation]);

  if (isAuthenticated) {
    return null; // Will redirect
  }

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center bg-light">
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-6 col-lg-4">
            <LoginForm 
              groupName={groupName} 
              isClientLogin={isClientLogin}
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
