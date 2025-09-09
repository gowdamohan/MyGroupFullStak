import { useState } from "react";
import { useLocation } from "wouter";
import { useToast } from "@/hooks/use-toast";
import { useMutation } from "@tanstack/react-query";
import { useAuth } from "@/hooks/use-auth";
import { apiRequest } from "@/lib/queryClient";
import type { Login, RegistrationStep1, RegistrationStep2 } from "@shared/schema";

export default function LoginPage() {
  const [, setLocation] = useLocation();
  const [isRegistering, setIsRegistering] = useState(false);
  const [registrationStep, setRegistrationStep] = useState(1);
  const { login } = useAuth();

  // Login form data
  const [formData, setFormData] = useState<Login>({
    username: '',
    password: '',
  });

  // Registration form data
  const [step1Data, setStep1Data] = useState<RegistrationStep1>({
    username: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: '',
    role: 'user',
  });

  const [step2Data, setStep2Data] = useState<RegistrationStep2>({
    gender: '',
    dateOfBirth: '',
    country: '',
    state: '',
    district: '',
    education: '',
    profession: '',
    company: '',
  });

  const { toast } = useToast();

  // Location data
  const countries = [
    { code: 'us', name: 'United States' },
    { code: 'in', name: 'India' },
    { code: 'uk', name: 'United Kingdom' },
    { code: 'ca', name: 'Canada' },
  ];

  const states = [
    { code: 'ca', name: 'California', countryCode: 'us' },
    { code: 'ny', name: 'New York', countryCode: 'us' },
    { code: 'mh', name: 'Maharashtra', countryCode: 'in' },
    { code: 'dl', name: 'Delhi', countryCode: 'in' },
    { code: 'on', name: 'Ontario', countryCode: 'ca' },
    { code: 'bc', name: 'British Columbia', countryCode: 'ca' },
  ];

  const districts = [
    { code: 'sf', name: 'San Francisco', stateCode: 'ca' },
    { code: 'la', name: 'Los Angeles', stateCode: 'ca' },
    { code: 'mumbai', name: 'Mumbai', stateCode: 'mh' },
    { code: 'pune', name: 'Pune', stateCode: 'mh' },
    { code: 'toronto', name: 'Toronto', stateCode: 'on' },
    { code: 'vancouver', name: 'Vancouver', stateCode: 'bc' },
  ];

  const filteredStates = states.filter(state => state.countryCode === step2Data.country);
  const filteredDistricts = districts.filter(district => district.stateCode === step2Data.state);

  const handleLogin = async (data: Login) => {
    try {
      await login(data);

      // Redirect to home page after successful login
      // The auth hook will handle role-based redirects if needed
      setLocation('/');
    } catch (error) {
      // Error handling is done in the auth hook
      console.error('Login error:', error);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!formData.username.trim()) {
      toast({
        title: "Error",
        description: "Please enter your username",
        variant: "destructive",
      });
      return;
    }

    if (!formData.password.trim()) {
      toast({
        title: "Error",
        description: "Please enter your password",
        variant: "destructive",
      });
      return;
    }

    handleLogin(formData);
  };

  const handleInputChange = (field: keyof Login, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  // Registration mutation
  const registrationMutation = useMutation({
    mutationFn: async (data: { step1: RegistrationStep1; step2: RegistrationStep2 }) => {
      const response = await fetch('/api/users/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify(data),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Registration failed');
      }

      return await response.json();
    },
    onSuccess: (data: any) => {
      // Store JWT token if provided
      if (data.token) {
        localStorage.setItem('authToken', data.token);
      }

      toast({
        title: "Registration Successful",
        description: "Welcome! You have been logged in automatically.",
      });

      // Redirect to dashboard or home page
      setLocation('/');
    },
    onError: (error: any) => {
      toast({
        title: "Registration Failed",
        description: error.message || "Failed to create account. Please try again.",
        variant: "destructive",
      });
    }
  });

  // Registration form handlers
  const validateStep1 = (): boolean => {
    const { username, firstName, lastName, email, phone, password, confirmPassword } = step1Data;

    if (!username.trim() || username.length < 3) {
      toast({
        title: "Error",
        description: "Username must be at least 3 characters",
        variant: "destructive",
      });
      return false;
    }

    if (!firstName.trim() || firstName.length < 2) {
      toast({
        title: "Error",
        description: "First name must be at least 2 characters",
        variant: "destructive",
      });
      return false;
    }

    if (!lastName.trim() || lastName.length < 2) {
      toast({
        title: "Error",
        description: "Last name must be at least 2 characters",
        variant: "destructive",
      });
      return false;
    }

    if (!email.trim() || !email.includes('@')) {
      toast({
        title: "Error",
        description: "Please enter a valid email address",
        variant: "destructive",
      });
      return false;
    }

    if (!phone.trim() || phone.length < 10) {
      toast({
        title: "Error",
        description: "Please enter a valid phone number",
        variant: "destructive",
      });
      return false;
    }

    if (!password.trim() || password.length < 8) {
      toast({
        title: "Error",
        description: "Password must be at least 8 characters",
        variant: "destructive",
      });
      return false;
    }

    if (password !== confirmPassword) {
      toast({
        title: "Error",
        description: "Passwords don't match",
        variant: "destructive",
      });
      return false;
    }

    return true;
  };

  const handleNextStep = () => {
    if (validateStep1()) {
      setRegistrationStep(2);
    }
  };

  const handlePreviousStep = () => {
    setRegistrationStep(1);
  };

  const handleCreateAccount = () => {
    registrationMutation.mutate({
      step1: step1Data,
      step2: step2Data,
    });
  };

  const handleSwitchToLogin = () => {
    setIsRegistering(false);
    setRegistrationStep(1);
    // Reset form data
    setStep1Data({
      username: '',
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      password: '',
      confirmPassword: '',
      role: 'user',
    });
    setStep2Data({
      gender: '',
      dateOfBirth: '',
      country: '',
      state: '',
      district: '',
      education: '',
      profession: '',
      company: '',
    });
  };

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center" style={{ background: 'var(--primary-gradient)' }}>
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-6 col-lg-4">
            <div className="card glass-modal border-0 shadow-lg">
              <div className="card-header border-0 text-center bg-transparent">
                <h3 className="text-white fw-bold mb-0" data-testid="login-title">
                  <i className={`bi ${isRegistering ? 'bi-person-plus' : 'bi-shield-lock'} me-2`}></i>
                  {isRegistering ? 'Create Account' : 'AppHub Login'}
                </h3>
                {isRegistering && (
                  <p className="text-white-50 mt-2 mb-0">
                    Step {registrationStep} of 2
                  </p>
                )}
              </div>
              
              <div className="card-body p-4">
                {!isRegistering ? (
                  // Login Form
                  <form onSubmit={handleSubmit} data-testid="form-login">
                    <div className="mb-3">
                      <div className="form-floating">
                        <input
                          type="text"
                          className="form-control"
                          id="username"
                          placeholder="Username"
                          value={formData.username}
                          onChange={(e) => handleInputChange('username', e.target.value)}
                          disabled={loginMutation.isPending}
                          required
                          data-testid="input-username"
                        />
                        <label htmlFor="username" className="text-muted">
                          <i className="bi bi-person me-2"></i>Username
                        </label>
                      </div>
                    </div>

                    <div className="mb-4">
                      <div className="form-floating">
                        <input
                          type="password"
                          className="form-control"
                          id="password"
                          placeholder="Password"
                          value={formData.password}
                          onChange={(e) => handleInputChange('password', e.target.value)}
                          disabled={loginMutation.isPending}
                          required
                          data-testid="input-password"
                        />
                        <label htmlFor="password" className="text-muted">
                          <i className="bi bi-lock me-2"></i>Password
                        </label>
                      </div>
                    </div>

                    <button
                      type="submit"
                      className="btn btn-primary w-100 mb-3"
                      disabled={loginMutation.isPending}
                      data-testid="button-login"
                    >
                      {loginMutation.isPending ? (
                        <>
                          <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                          Signing in...
                        </>
                      ) : (
                        <>
                          <i className="bi bi-box-arrow-in-right me-2"></i>
                          Sign In
                        </>
                      )}
                    </button>

                    <div className="text-center">
                      <p className="text-white-50 mb-2">Don't have an account?</p>
                      <div className="d-grid gap-2">
                        <button
                          type="button"
                          className="btn btn-success"
                          onClick={() => setIsRegistering(true)}
                          data-testid="button-register"
                        >
                          <i className="bi bi-person-plus me-2"></i>
                          Create Account
                        </button>
                        <button
                          type="button"
                          className="btn btn-outline-light btn-sm"
                          onClick={() => setLocation('/')}
                          data-testid="button-back-home"
                        >
                          <i className="bi bi-house me-2"></i>
                          Back to Home
                        </button>
                      </div>
                    </div>
                  </form>
                ) : (
                  // Registration Form
                  <div data-testid="form-registration">
                    {/* Progress Bar */}
                    <div className="progress progress-custom mb-4">
                      <div
                        className="progress-bar progress-bar-custom"
                        role="progressbar"
                        style={{ width: `${registrationStep * 50}%` }}
                        data-testid="progress-registration"
                      />
                    </div>

                    {/* Step 1: Basic Information */}
                    {registrationStep === 1 && (
                      <div data-testid="step-registration-1">
                        <div className="row g-3">
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="reg-username"
                                placeholder="Username"
                                value={step1Data.username}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, username: e.target.value }))}
                                data-testid="input-reg-username"
                              />
                              <label htmlFor="reg-username" className="text-muted">Username</label>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="firstName"
                                placeholder="First Name"
                                value={step1Data.firstName}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, firstName: e.target.value }))}
                                data-testid="input-first-name"
                              />
                              <label htmlFor="firstName" className="text-muted">First Name</label>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="lastName"
                                placeholder="Last Name"
                                value={step1Data.lastName}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, lastName: e.target.value }))}
                                data-testid="input-last-name"
                              />
                              <label htmlFor="lastName" className="text-muted">Last Name</label>
                            </div>
                          </div>
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="email"
                                className="form-control"
                                id="reg-email"
                                placeholder="Email"
                                value={step1Data.email}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, email: e.target.value }))}
                                data-testid="input-reg-email"
                              />
                              <label htmlFor="reg-email" className="text-muted">Email Address</label>
                            </div>
                          </div>
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="tel"
                                className="form-control"
                                id="reg-phone"
                                placeholder="Phone"
                                value={step1Data.phone}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, phone: e.target.value }))}
                                data-testid="input-reg-phone"
                              />
                              <label htmlFor="reg-phone" className="text-muted">Phone Number</label>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="form-floating">
                              <input
                                type="password"
                                className="form-control"
                                id="reg-password"
                                placeholder="Password"
                                value={step1Data.password}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, password: e.target.value }))}
                                data-testid="input-reg-password"
                              />
                              <label htmlFor="reg-password" className="text-muted">Password</label>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="form-floating">
                              <input
                                type="password"
                                className="form-control"
                                id="confirmPassword"
                                placeholder="Confirm Password"
                                value={step1Data.confirmPassword}
                                onChange={(e) => setStep1Data(prev => ({ ...prev, confirmPassword: e.target.value }))}
                                data-testid="input-confirm-password"
                              />
                              <label htmlFor="confirmPassword" className="text-muted">Confirm</label>
                            </div>
                          </div>
                        </div>
                        <button
                          className="btn btn-primary w-100 mt-4"
                          onClick={handleNextStep}
                          data-testid="button-next-step"
                        >
                          Continue to Step 2
                        </button>
                      </div>
                    )}

                    {/* Step 2: Additional Details */}
                    {registrationStep === 2 && (
                      <div data-testid="step-registration-2">
                        <div className="row g-3">
                          <div className="col-6">
                            <div className="form-floating">
                              <select
                                className="form-select"
                                id="gender"
                                value={step2Data.gender}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, gender: e.target.value }))}
                                data-testid="select-gender"
                              >
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                              </select>
                              <label htmlFor="gender" className="text-muted">Gender</label>
                            </div>
                          </div>
                          <div className="col-6">
                            <div className="form-floating">
                              <input
                                type="date"
                                className="form-control"
                                id="dateOfBirth"
                                value={step2Data.dateOfBirth}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, dateOfBirth: e.target.value }))}
                                data-testid="input-date-of-birth"
                              />
                              <label htmlFor="dateOfBirth" className="text-muted">Date of Birth</label>
                            </div>
                          </div>
                          <div className="col-4">
                            <div className="form-floating">
                              <select
                                className="form-select"
                                id="country"
                                value={step2Data.country}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, country: e.target.value, state: '', district: '' }))}
                                data-testid="select-country"
                              >
                                <option value="">Country</option>
                                {countries.map(country => (
                                  <option key={country.code} value={country.code}>{country.name}</option>
                                ))}
                              </select>
                              <label htmlFor="country" className="text-muted">Country</label>
                            </div>
                          </div>
                          <div className="col-4">
                            <div className="form-floating">
                              <select
                                className="form-select"
                                id="state"
                                value={step2Data.state}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, state: e.target.value, district: '' }))}
                                disabled={!step2Data.country}
                                data-testid="select-state"
                              >
                                <option value="">State</option>
                                {filteredStates.map(state => (
                                  <option key={state.code} value={state.code}>{state.name}</option>
                                ))}
                              </select>
                              <label htmlFor="state" className="text-muted">State</label>
                            </div>
                          </div>
                          <div className="col-4">
                            <div className="form-floating">
                              <select
                                className="form-select"
                                id="district"
                                value={step2Data.district}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, district: e.target.value }))}
                                disabled={!step2Data.state}
                                data-testid="select-district"
                              >
                                <option value="">District</option>
                                {filteredDistricts.map(district => (
                                  <option key={district.code} value={district.code}>{district.name}</option>
                                ))}
                              </select>
                              <label htmlFor="district" className="text-muted">District</label>
                            </div>
                          </div>
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="education"
                                placeholder="Education"
                                value={step2Data.education}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, education: e.target.value }))}
                                data-testid="input-education"
                              />
                              <label htmlFor="education" className="text-muted">Education</label>
                            </div>
                          </div>
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="profession"
                                placeholder="Profession"
                                value={step2Data.profession}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, profession: e.target.value }))}
                                data-testid="input-profession"
                              />
                              <label htmlFor="profession" className="text-muted">Profession</label>
                            </div>
                          </div>
                          <div className="col-12">
                            <div className="form-floating">
                              <input
                                type="text"
                                className="form-control"
                                id="company"
                                placeholder="Company"
                                value={step2Data.company}
                                onChange={(e) => setStep2Data(prev => ({ ...prev, company: e.target.value }))}
                                data-testid="input-company"
                              />
                              <label htmlFor="company" className="text-muted">Company/Organization</label>
                            </div>
                          </div>
                        </div>
                        <div className="row g-2 mt-4">
                          <div className="col-6">
                            <button
                              className="btn btn-outline-light w-100"
                              onClick={handlePreviousStep}
                              data-testid="button-previous-step"
                            >
                              Back to Step 1
                            </button>
                          </div>
                          <div className="col-6">
                            <button
                              className="btn btn-success w-100"
                              onClick={handleCreateAccount}
                              disabled={registrationMutation.isPending}
                              data-testid="button-create-account"
                            >
                              {registrationMutation.isPending ? 'Creating...' : 'Create Account'}
                            </button>
                          </div>
                        </div>
                      </div>
                    )}

                    {/* Back to Login */}
                    <div className="text-center mt-4">
                      <p className="text-white-50 small">
                        Already have an account? {' '}
                        <a
                          href="#"
                          className="text-white"
                          onClick={(e) => {
                            e.preventDefault();
                            handleSwitchToLogin();
                          }}
                          data-testid="link-login"
                        >
                          Login here
                        </a>
                      </p>
                    </div>
                  </div>
                )}

                {/* Demo Users Section */}
                <div className="mt-4 pt-4 border-top border-white-50">
                  <h6 className="text-white text-center mb-3">Demo Users</h6>
                  <div className="row g-2 text-white-50 small">
                    <div className="col-6">
                      <p className="mb-1"><strong>Admin:</strong></p>
                      <p className="mb-0">admin / password</p>
                    </div>
                    <div className="col-6">
                      <p className="mb-1"><strong>Corporate:</strong></p>
                      <p className="mb-0">corporate / password</p>
                    </div>
                    <div className="col-6">
                      <p className="mb-1"><strong>Head Office:</strong></p>
                      <p className="mb-0">head_office / password</p>
                    </div>
                    <div className="col-6">
                      <p className="mb-1"><strong>Regional:</strong></p>
                      <p className="mb-0">regional / password</p>
                    </div>
                    <div className="col-6">
                      <p className="mb-1"><strong>Branch:</strong></p>
                      <p className="mb-0">branch / password</p>
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