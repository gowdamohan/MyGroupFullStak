import React, { useState, useEffect } from "react";
import { useToast } from "@/hooks/use-toast";
import type { RegistrationStep1, RegistrationStep2 } from "@shared/schema";

interface RegistrationModalProps {
  isOpen: boolean;
  onClose: () => void;
  onRegistration: () => void;
  onSwitchToLogin: () => void;
}

export default function RegistrationModal({ 
  isOpen, 
  onClose, 
  onRegistration, 
  onSwitchToLogin 
}: RegistrationModalProps) {
  const [registrationStep, setRegistrationStep] = useState(1);
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  // Step 1 data
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

  // Step 2 data
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

  // Location data
  const [countries] = useState([
    { code: 'us', name: 'United States' },
    { code: 'in', name: 'India' },
    { code: 'uk', name: 'United Kingdom' },
  ]);

  const [states, setStates] = useState([
    { code: 'ca', name: 'California', countryCode: 'us' },
    { code: 'ny', name: 'New York', countryCode: 'us' },
    { code: 'mh', name: 'Maharashtra', countryCode: 'in' },
    { code: 'dl', name: 'Delhi', countryCode: 'in' },
  ]);

  const [districts, setDistricts] = useState([
    { code: 'sf', name: 'San Francisco', stateCode: 'ca' },
    { code: 'la', name: 'Los Angeles', stateCode: 'ca' },
    { code: 'mumbai', name: 'Mumbai', stateCode: 'mh' },
    { code: 'pune', name: 'Pune', stateCode: 'mh' },
  ]);

  useEffect(() => {
    if (isOpen) {
      const modalElement = document.getElementById('registrationModal');
      if (modalElement && (window as any).bootstrap) {
        const modal = new (window as any).bootstrap.Modal(modalElement);
        modal.show();
      }
    }
  }, [isOpen]);

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

  const handleCreateAccount = async () => {
    setIsLoading(true);
    try {
      const response = await fetch('/api/users/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          step1: step1Data,
          step2: step2Data,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Registration failed');
      }

      // Store JWT token in localStorage
      if (data.token) {
        localStorage.setItem('authToken', data.token);
      }

      toast({
        title: "Success",
        description: "Account created successfully! You are now logged in.",
      });

      onRegistration();
    } catch (error: any) {
      toast({
        title: "Error",
        description: error.message || "Failed to create account. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const resetModal = () => {
    setRegistrationStep(1);
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
    setIsLoading(false);
  };

  const handleClose = () => {
    resetModal();
    onClose();
  };

  const filteredStates = states.filter(state => state.countryCode === step2Data.country);
  const filteredDistricts = districts.filter(district => district.stateCode === step2Data.state);

  return (
    <div 
      className={`modal fade ${isOpen ? 'show' : ''}`}
      id="registrationModal" 
      tabIndex={-1} 
      aria-labelledby="registrationModalLabel" 
      aria-hidden={!isOpen}
      style={{ display: isOpen ? 'block' : 'none' }}
      data-testid="modal-registration"
    >
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content glass-modal border-0">
          <div className="modal-header border-0">
            <h5 className="modal-title text-white fw-bold" id="registrationModalLabel">
              Create Account
            </h5>
            <button 
              type="button" 
              className="btn-close btn-close-white" 
              onClick={handleClose}
              aria-label="Close"
              data-testid="button-close-registration"
            />
          </div>
          <div className="modal-body p-4">
            
            {/* Progress Bar */}
            <div className="progress progress-custom mb-4">
              <div 
                className="progress-bar progress-bar-custom" 
                role="progressbar" 
                style={{ width: `${registrationStep * 50}%` }}
                data-testid="progress-registration"
              />
            </div>
            
            <p className="text-white text-center mb-4">
              Step <span data-testid="text-current-step">{registrationStep}</span> of 2
            </p>

            {/* Step 1: Basic Information */}
            {registrationStep === 1 && (
              <div data-testid="step-registration-1">
                <div className="row g-3">
                  <div className="col-12">
                    <div className="form-floating">
                      <input 
                        type="text" 
                        className="form-control" 
                        id="username" 
                        placeholder="Username"
                        value={step1Data.username}
                        onChange={(e) => setStep1Data(prev => ({ ...prev, username: e.target.value }))}
                        data-testid="input-username"
                      />
                      <label htmlFor="username" className="text-muted">Username</label>
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
                      <select 
                        className="form-select" 
                        id="role"
                        value={step1Data.role}
                        onChange={(e) => setStep1Data(prev => ({ ...prev, role: e.target.value as any }))}
                        data-testid="select-role"
                      >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="corporate">Corporate</option>
                        <option value="regional">Regional</option>
                        <option value="branch">Branch</option>
                      </select>
                      <label htmlFor="role" className="text-muted">Role</label>
                    </div>
                  </div>
                  <div className="col-12">
                    <div className="form-floating">
                      <input 
                        type="email" 
                        className="form-control" 
                        id="email" 
                        placeholder="Email"
                        value={step1Data.email}
                        onChange={(e) => setStep1Data(prev => ({ ...prev, email: e.target.value }))}
                        data-testid="input-email"
                      />
                      <label htmlFor="email" className="text-muted">Email Address</label>
                    </div>
                  </div>
                  <div className="col-12">
                    <div className="form-floating">
                      <input 
                        type="tel" 
                        className="form-control" 
                        id="regPhone" 
                        placeholder="Phone"
                        value={step1Data.phone}
                        onChange={(e) => setStep1Data(prev => ({ ...prev, phone: e.target.value }))}
                        data-testid="input-phone-reg"
                      />
                      <label htmlFor="regPhone" className="text-muted">Phone Number</label>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="form-floating">
                      <input 
                        type="password" 
                        className="form-control" 
                        id="password" 
                        placeholder="Password"
                        value={step1Data.password}
                        onChange={(e) => setStep1Data(prev => ({ ...prev, password: e.target.value }))}
                        data-testid="input-password"
                      />
                      <label htmlFor="password" className="text-muted">Password</label>
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
                  <div className="col-6">
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
                  <div className="col-6">
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
                      disabled={isLoading}
                      data-testid="button-create-account"
                    >
                      {isLoading ? 'Creating...' : 'Create Account'}
                    </button>
                  </div>
                </div>
              </div>
            )}

            <div className="text-center mt-4">
              <p className="text-white-50 small">
                Already have an account? {' '}
                <a 
                  href="#" 
                  className="text-white" 
                  onClick={(e) => {
                    e.preventDefault();
                    onSwitchToLogin();
                  }}
                  data-testid="link-login"
                >
                  Login here
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}