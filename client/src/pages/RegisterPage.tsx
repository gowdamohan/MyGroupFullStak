import React from 'react';
import { useLocation, useRoute } from 'wouter';
import RegistrationForm from '../components/auth/RegistrationForm';
import { useAuth } from '@/hooks/use-auth';

const RegisterPage: React.FC = () => {
  const [, setLocation] = useLocation();
  const { isAuthenticated } = useAuth();
  
  // Check for registration route patterns
  const [match, params] = useRoute('/register-form/:groupName');
  const [godMatch, godParams] = useRoute('/god-register-form/:groupName/:appName');
  
  const groupName = params?.groupName || godParams?.groupName || 'MyGroup';
  const appName = godParams?.appName;
  
  // Default group IDs (you might want to fetch these from an API)
  const getGroupId = (name: string): number => {
    const groupMap: { [key: string]: number } = {
      'Myunions': 1,
      'Mymedia': 2,
      'Myshop': 3,
      'Mybiz': 4,
      'Mytv': 5,
      'Myjoy': 6,
      'Myneedy': 7,
      'Mygod': 8,
    };
    return groupMap[name] || 1;
  };

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
          <div className="col-md-8 col-lg-6">
            <RegistrationForm 
              groupName={groupName}
              groupId={getGroupId(groupName)}
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage;
