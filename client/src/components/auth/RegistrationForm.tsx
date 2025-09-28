import React, { useState } from 'react';
import { useMutation } from '@tanstack/react-query';
import { useLocation } from 'wouter';

interface RegistrationFormProps {
  groupName?: string;
  groupId?: number;
}

interface RegistrationData {
  firstName: string;
  lastName: string;
  email: string;
  username: string;
  password: string;
  confirmPassword: string;
  phone: string;
  company: string;
}

interface RegistrationResponse {
  success: boolean;
  message: string;
  userId?: number;
  error?: string;
}

const RegistrationForm: React.FC<RegistrationFormProps> = ({
  groupName = 'MyGroup',
  groupId = 1
}) => {
  const [, setLocation] = useLocation();
  const [errors, setErrors] = useState<{ [key: string]: string }>({});

  const [formData, setFormData] = useState<RegistrationData>({
    firstName: '',
    lastName: '',
    email: '',
    username: '',
    password: '',
    confirmPassword: '',
    phone: '',
    company: ''
  });

  // Registration mutation
  const registerMutation = useMutation({
    mutationFn: async (data: RegistrationData): Promise<RegistrationResponse> => {
      const response = await fetch('/api/auth/register-simple', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...data,
          role: 'user' // Default role
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.error || 'Registration failed');
      }

      return result;
    },
    onSuccess: (data: RegistrationResponse) => {
      if (data.success) {
        // Redirect to login page with success message
        setLocation(`/new-login?message=Registration successful. Please login.`);
      }
    },
    onError: (error: Error) => {
      setErrors({ general: error.message });
    }
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    const newErrors: { [key: string]: string } = {};

    if (!formData.firstName.trim()) {
      newErrors.firstName = 'First name is required';
    }

    if (!formData.lastName.trim()) {
      newErrors.lastName = 'Last name is required';
    }

    if (!formData.email.trim()) {
      newErrors.email = 'Email is required';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Please enter a valid email';
    }

    if (!formData.username.trim()) {
      newErrors.username = 'Username is required';
    }

    if (!formData.password.trim()) {
      newErrors.password = 'Password is required';
    } else if (formData.password.length < 6) {
      newErrors.password = 'Password must be at least 6 characters';
    }

    if (formData.password !== formData.confirmPassword) {
      newErrors.confirmPassword = 'Passwords do not match';
    }

    setErrors(newErrors);

    if (Object.keys(newErrors).length === 0) {
      registerMutation.mutate(formData);
    }
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));

    // Clear error when user starts typing
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: '' }));
    }
  };

  const renderRegistrationForm = () => (
    <form onSubmit={handleSubmit} className="form-horizontal">
      <div className="login-title">
        <strong>Create Account</strong> for {groupName}
      </div>

      {errors.general && (
        <div className="alert alert-danger">
          <strong>Error!</strong> {errors.general}
        </div>
      )}

      <div className="row">
        <div className="col-md-6">
          <div className="form-group">
            <input
              type="text"
              className={`form-control ${errors.firstName ? 'is-invalid' : ''}`}
              name="firstName"
              placeholder="First Name *"
              value={formData.firstName}
              onChange={handleInputChange}
              disabled={registerMutation.isPending}
            />
            {errors.firstName && (
              <div className="invalid-feedback">{errors.firstName}</div>
            )}
          </div>
        </div>
        <div className="col-md-6">
          <div className="form-group">
            <input
              type="text"
              className={`form-control ${errors.lastName ? 'is-invalid' : ''}`}
              name="lastName"
              placeholder="Last Name *"
              value={formData.lastName}
              onChange={handleInputChange}
              disabled={registerMutation.isPending}
            />
            {errors.lastName && (
              <div className="invalid-feedback">{errors.lastName}</div>
            )}
          </div>
        </div>
      </div>

      <div className="form-group">
        <input
          type="email"
          className={`form-control ${errors.email ? 'is-invalid' : ''}`}
          name="email"
          placeholder="Email Address *"
          value={formData.email}
          onChange={handleInputChange}
          disabled={registerMutation.isPending}
        />
        {errors.email && (
          <div className="invalid-feedback">{errors.email}</div>
        )}
      </div>

      <div className="form-group">
        <input
          type="text"
          className={`form-control ${errors.username ? 'is-invalid' : ''}`}
          name="username"
          placeholder="Username *"
          value={formData.username}
          onChange={handleInputChange}
          disabled={registerMutation.isPending}
        />
        {errors.username && (
          <div className="invalid-feedback">{errors.username}</div>
        )}
      </div>

      <div className="row">
        <div className="col-md-6">
          <div className="form-group">
            <input
              type="password"
              className={`form-control ${errors.password ? 'is-invalid' : ''}`}
              name="password"
              placeholder="Password *"
              value={formData.password}
              onChange={handleInputChange}
              disabled={registerMutation.isPending}
            />
            {errors.password && (
              <div className="invalid-feedback">{errors.password}</div>
            )}
          </div>
        </div>
        <div className="col-md-6">
          <div className="form-group">
            <input
              type="password"
              className={`form-control ${errors.confirmPassword ? 'is-invalid' : ''}`}
              name="confirmPassword"
              placeholder="Confirm Password *"
              value={formData.confirmPassword}
              onChange={handleInputChange}
              disabled={registerMutation.isPending}
            />
            {errors.confirmPassword && (
              <div className="invalid-feedback">{errors.confirmPassword}</div>
            )}
          </div>
        </div>
      </div>

      <div className="form-group">
        <input
          type="tel"
          className="form-control"
          name="phone"
          placeholder="Phone Number"
          value={formData.phone}
          onChange={handleInputChange}
          disabled={registerMutation.isPending}
        />
      </div>

      <div className="form-group">
        <input
          type="text"
          className="form-control"
          name="company"
          placeholder="Company"
          value={formData.company}
          onChange={handleInputChange}
          disabled={registerMutation.isPending}
        />
      </div>

      <div className="form-group">
        <div className="col-md-6">
          <a href="/new-login" className="btn btn-secondary btn-block">
            Already have an account? Login
          </a>
        </div>
        <div className="col-md-6">
          <button
            type="submit"
            className="btn btn-success btn-block"
            disabled={registerMutation.isPending}
          >
            {registerMutation.isPending ? (
              <>
                <i className="fa fa-spinner fa-spin"></i> Creating Account...
              </>
            ) : (
              'Create Account'
            )}
          </button>
        </div>
      </div>
    </form>
  );



  return (
    <div className="login-container">
      <div className="login-box animated fadeInDown">
        <div className="login-logo">
          <img 
            style={{ height: '90px' }} 
            src="/assets/front/img/logo.png" 
            alt={`${groupName} Logo`}
          />
        </div>
        
        <div className="login-body">
          {renderRegistrationForm()}
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

export default RegistrationForm;
