import React, { useState } from 'react';
import { useMutation } from '@tanstack/react-query';
import { useLocation } from 'wouter';

interface LoginFormProps {
  groupName?: string;
  isClientLogin?: boolean;
}

interface LoginData {
  username: string;
  password: string;
  remember: boolean;
}

interface LoginResponse {
  success: boolean;
  message: string;
  token?: string;
  user?: {
    id: number;
    email: string;
    username: string;
    firstName: string;
    lastName: string;
    groupId: number;
    groups?: Array<{ id: number; name: string; description: string }>;
  };
  requiresRegistration?: boolean;
  redirectUrl?: string;
  userInfo?: any;
  error?: string;
}

const LoginForm: React.FC<LoginFormProps> = ({ groupName, isClientLogin = false }) => {
  const [, setLocation] = useLocation();
  const [formData, setFormData] = useState<LoginData>({
    username: '',
    password: '',
    remember: false
  });
  const [showPassword, setShowPassword] = useState(false);
  const [errors, setErrors] = useState<{ [key: string]: string }>({});

  const loginMutation = useMutation({
    mutationFn: async (data: LoginData): Promise<LoginResponse> => {
      // Use the existing auth endpoint
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          username: data.username,
          password: data.password
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.error || 'Login failed');
      }

      return result;
    },
    onSuccess: (data: LoginResponse) => {
      if (data.success || data.token) {
        // Store token in localStorage
        if (data.token) {
          localStorage.setItem('authToken', data.token);
        }

        // Redirect to appropriate dashboard based on user role
        if (data.user?.role) {
          switch (data.user.role) {
            case 'admin':
              setLocation('/dashboard/admin');
              break;
            case 'corporate':
              setLocation('/dashboard/corporate');
              break;
            case 'regional':
              setLocation('/dashboard/regional');
              break;
            case 'branch':
              setLocation('/dashboard/branch');
              break;
            case 'head_office':
              setLocation('/dashboard/head-office');
              break;
            default:
              setLocation('/dashboard');
          }
        } else {
          setLocation('/dashboard');
        }
      } else if (data.requiresRegistration) {
        // Redirect to registration completion
        setLocation(data.redirectUrl || '/register');
      }
    },
    onError: (error: Error) => {
      setErrors({ general: error.message });
    }
  });

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
    
    // Clear error when user starts typing
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: '' }));
    }
  };

  const validateForm = (): boolean => {
    const newErrors: { [key: string]: string } = {};

    if (!formData.username.trim()) {
      newErrors.username = 'Username is required';
    }

    if (!formData.password.trim()) {
      newErrors.password = 'Password is required';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (validateForm()) {
      loginMutation.mutate(formData);
    }
  };

  const togglePasswordVisibility = () => {
    setShowPassword(!showPassword);
  };

  return (
    <div className="login-container">
      <div className="login-box animated fadeInDown">
        <div className="login-logo">
          {isClientLogin && groupName ? (
            <img 
              style={{ height: '90px' }} 
              src="/assets/front/img/logo.png" 
              alt={`${groupName} Logo`}
            />
          ) : (
            <h2>My Group</h2>
          )}
        </div>
        
        <div className="login-body">
          <div className="login-title">
            <strong>Welcome</strong>, Please login
          </div>
          
          {errors.general && (
            <div className="alert alert-danger">
              <strong>Error!</strong> {errors.general}
            </div>
          )}
          
          <form onSubmit={handleSubmit} className="form-horizontal">
            <div className="form-group">
              <div className="col-md-12">
                <input
                  type="text"
                  className={`form-control ${errors.username ? 'is-invalid' : ''}`}
                  name="username"
                  placeholder="Username"
                  value={formData.username}
                  onChange={handleInputChange}
                  disabled={loginMutation.isPending}
                />
                {errors.username && (
                  <div className="invalid-feedback">{errors.username}</div>
                )}
              </div>
            </div>
            
            <div className="form-group">
              <div className="col-md-12 position-relative">
                <input
                  type={showPassword ? 'text' : 'password'}
                  className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                  name="password"
                  placeholder="Password"
                  value={formData.password}
                  onChange={handleInputChange}
                  disabled={loginMutation.isPending}
                />
                <button
                  type="button"
                  className="btn btn-link position-absolute"
                  style={{
                    right: '10px',
                    top: '50%',
                    transform: 'translateY(-50%)',
                    padding: '0',
                    border: 'none',
                    background: 'none'
                  }}
                  onClick={togglePasswordVisibility}
                >
                  <i className={`fa ${showPassword ? 'fa-eye' : 'fa-eye-slash'}`}></i>
                </button>
                {errors.password && (
                  <div className="invalid-feedback">{errors.password}</div>
                )}
              </div>
            </div>
            
            <div className="form-group">
              <div className="col-md-6">
                <div className="form-check">
                  <input
                    type="checkbox"
                    className="form-check-input"
                    name="remember"
                    id="remember"
                    checked={formData.remember}
                    onChange={handleInputChange}
                    disabled={loginMutation.isPending}
                  />
                  <label className="form-check-label" htmlFor="remember">
                    Remember me
                  </label>
                </div>
                <p>
                  <a 
                    href={isClientLogin ? `/forgot-client/${groupName}` : '/forgot-password'} 
                    className="btn btn-link btn-block"
                  >
                    Forgot your password?
                  </a>
                </p>
              </div>
              <div className="col-md-6">
                <button 
                  type="submit" 
                  className="btn btn-info btn-block"
                  disabled={loginMutation.isPending}
                >
                  {loginMutation.isPending ? (
                    <>
                      <i className="fa fa-spinner fa-spin"></i> Logging in...
                    </>
                  ) : (
                    <>
                      <i className="fa fa-sign-in"></i> Log In
                    </>
                  )}
                </button>
              </div>
            </div>
            
            {isClientLogin && (
              <div className="text-center p-t-90">
                <a 
                  className="txt1 btn-warning btn-block" 
                  href={`/register-form/${groupName}`}
                >
                  Register
                </a>
              </div>
            )}
          </form>
        </div>
        
        <div className="login-footer">
          <div className="pull-left">
            © 2024 My Group
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginForm;
