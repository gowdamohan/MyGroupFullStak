import React, { useState, useEffect } from "react";
import { useToast } from "@/hooks/use-toast";
import { useAuth } from "@/hooks/use-auth";
import { COUNTRIES, getStatesByCountry, getDistrictsByState, EDUCATION_OPTIONS, PROFESSION_OPTIONS } from "@/data/locations";

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
  const [otpSent, setOtpSent] = useState(false);
  const [otpCode, setOtpCode] = useState("");
  const [userId, setUserId] = useState<string | null>(null);
  const [selectedCountry, setSelectedCountry] = useState(COUNTRIES[0]);
  const { toast } = useToast();
  const { login } = useAuth();

  // Registration form data
  const [formData, setFormData] = useState({
    // Step 1 - Basic Information
    fullName: '',
    mobileNumber: '',
    countryCode: '+91',
    password: '',
    confirmPassword: '',

    // Step 2 - Personal Details
    displayName: '',
    email: '',
    gender: 'M',
    maritalStatus: 'Single',
    dateOfBirth: {
      day: '',
      month: '',
      year: ''
    },

    // Step 2 - Location Details
    country: '',
    state: '',
    district: '',
    nationality: '',

    // Step 2 - Education & Profession
    education: '',
    educationOthers: '',
    profession: '',
    workOthers: '',

    // Additional fields
    alternateNumber: '',
  });

  // Get states and districts based on selection
  const availableStates = getStatesByCountry(formData.country);
  const availableDistricts = getDistrictsByState(formData.state);

  // OTP and registration functions
  const sendOTP = async () => {
    // Validate Step 1 fields
    if (!formData.fullName || !formData.mobileNumber || !formData.password || !formData.confirmPassword) {
      toast({
        title: "Error",
        description: "Please fill all required fields",
        variant: "destructive",
      });
      return;
    }

    if (formData.password !== formData.confirmPassword) {
      toast({
        title: "Error",
        description: "Passwords do not match",
        variant: "destructive",
      });
      return;
    }

    if (formData.password.length < 6) {
      toast({
        title: "Error",
        description: "Password must be at least 6 characters",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // First step registration - create user with basic info (matching PHP first_step_register_submit_popup)
      const response = await fetch('/api/auth/first-step-register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          first_name: formData.fullName,
          mobile_number: `${formData.countryCode}${formData.mobileNumber}`,
          password: formData.password
        })
      });

      const data = await response.json();
      if (data.success || data.userId) {
        setOtpSent(true);
        setUserId(data.userId || data.result);
        toast({
          title: "Registration Successful",
          description: "Use OTP 123456 to continue to profile completion.",
        });
      } else if (data.message === 'exits') {
        toast({
          title: "Error",
          description: "Mobile number already exists. Please use a different number.",
          variant: "destructive",
        });
      } else {
        toast({
          title: "Error",
          description: data.message || "Registration failed",
          variant: "destructive",
        });
      }
    } catch (error: any) {
      toast({
        title: "Error",
        description: "Registration failed. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const verifyOTPAndProceed = async () => {
    if (!otpCode) {
      toast({
        title: "Error",
        description: "Please enter the OTP code",
        variant: "destructive",
      });
      return;
    }

    // Demo OTP verification
    if (otpCode !== '123456') {
      toast({
        title: "Error",
        description: "Invalid OTP. Please use 123456 for demo.",
        variant: "destructive",
      });
      return;
    }

    if (formData.password !== formData.confirmPassword) {
      toast({
        title: "Error",
        description: "Passwords do not match",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // For demo purposes, accept 123456 as valid OTP
      if (otpCode === '123456') {
        setRegistrationStep(2);
        toast({
          title: "Success",
          description: "Mobile number verified successfully",
        });
      } else {
        throw new Error('Invalid OTP. Please use 123456 for demo.');
      }
    } catch (error: any) {
      toast({
        title: "Error",
        description: error.message || "Invalid OTP code",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

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
    const { phone, password, confirmPassword } = step1Data;

    if (!phone.trim() || phone.length < 10) {
      toast({
        title: "Error",
        description: "Please enter a valid mobile number",
        variant: "destructive",
      });
      return false;
    }

    if (!otpCode || otpCode.length !== 6) {
      toast({
        title: "Error",
        description: "Please enter the 6-digit OTP code",
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
    verifyOTPAndProceed();
  };

  const handlePreviousStep = () => {
    setRegistrationStep(1);
  };

  const handleCreateAccount = async () => {
    // Validate required Step 2 fields
    if (!formData.displayName || !formData.gender || !formData.country) {
      toast({
        title: "Error",
        description: "Please fill all required fields",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // Prepare data matching PHP user_update_register_submit_popup format
      const registrationData = {
        register_user_id: userId,
        register_username: `${formData.countryCode}${formData.mobileNumber}`,
        register_password: formData.password,
        display_name: formData.displayName,
        alter_number: formData.alternateNumber || '',
        email: formData.email || '',

        // Additional registration details matching PHP insert_user_registration_details
        country_code: formData.countryCode,
        gender: formData.gender,
        marital: formData.maritalStatus,
        from_date: formData.dateOfBirth.day,
        from_month: formData.dateOfBirth.month,
        from_year: formData.dateOfBirth.year,
        country: formData.country,
        state: formData.state,
        district: formData.district,
        nationality: formData.nationality,
        education: formData.education === 'education_others' ? formData.educationOthers : formData.education,
        profession: formData.profession === 'work_others' ? formData.workOthers : formData.profession,
        education_others: formData.education === 'education_others' ? formData.educationOthers : '',
        work_others: formData.profession === 'work_others' ? formData.workOthers : ''
      };

      // Call the update registration API (matching PHP user_update_register_submit_popup)
      const response = await fetch('/api/auth/update-registration', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(registrationData)
      });

      const data = await response.json();
      if (data.success || data.result === 1) {
        toast({
          title: "Success",
          description: "Registration completed successfully! You are now logged in.",
        });

        // Auto-login after successful registration (matching PHP behavior)
        await login({
          id: userId,
          username: `${formData.countryCode}${formData.mobileNumber}`,
          firstName: formData.fullName,
          email: formData.email || `${formData.mobileNumber}@demo.com`,
          phone: formData.mobileNumber,
          displayName: formData.displayName,
          isVerified: true
        }, 'demo-token');

        onRegistration();
      } else {
        toast({
          title: "Error",
          description: "Registration failed. Please try again.",
          variant: "destructive",
        });
      }
    } catch (error: any) {
      toast({
        title: "Error",
        description: "Registration failed. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const resetModal = () => {
    setRegistrationStep(1);
    setOtpSent(false);
    setOtpCode("");
    setFormData({
      // Step 1 - Basic Information
      fullName: '',
      mobileNumber: '',
      countryCode: '+91',
      password: '',
      confirmPassword: '',

      // Step 2 - Personal Details
      displayName: '',
      email: '',
      gender: 'M',
      maritalStatus: 'Single',
      dateOfBirth: {
        day: '',
        month: '',
        year: ''
      },

      // Step 2 - Location Details
      country: '',
      state: '',
      district: '',
      nationality: '',

      // Step 2 - Education & Profession
      education: '',
      educationOthers: '',
      profession: '',
      workOthers: '',

      // Additional fields
      alternateNumber: '',
    });
    setIsLoading(false);
  };

  const handleClose = () => {
    resetModal();
    onClose();
  };



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
        <div className="modal-content auth-modal border-0">
          <div className="modal-header border-0 text-center">
            <div className="w-100">
              <div className="auth-tabs mb-3">
                <button
                  className="auth-tab"
                  onClick={onSwitchToLogin}
                >
                  Login
                </button>
                <button
                  className={`auth-tab ${registrationStep === 1 ? 'active' : ''}`}
                  onClick={() => setRegistrationStep(1)}
                >
                  Register
                </button>
              </div>
            </div>
            <button
              type="button"
              className="btn-close btn-close-white"
              onClick={handleClose}
              aria-label="Close"
              data-testid="button-close-registration"
            />
          </div>
          <div className="modal-body p-4">
            {registrationStep === 2 && (
              <div className="progress progress-custom mb-4">
                <div
                  className="progress-bar progress-bar-custom"
                  role="progressbar"
                  style={{ width: `${registrationStep * 50}%` }}
                  data-testid="progress-registration"
                />
              </div>
            )}

            {registrationStep === 2 && (
              <p className="text-white text-center mb-4">
                Step <span data-testid="text-current-step">{registrationStep}</span> of 2
              </p>
            )}

            {/* Step 1: Basic Registration Form */}
            {registrationStep === 1 && (
              <div data-testid="step-registration-1">
                <div className="row g-3">
                  {/* Full Name */}
                  <div className="col-12">
                    <label className="form-label text-white">Full Name <span className="text-danger">*</span></label>
                    <input
                      type="text"
                      className="form-control auth-input"
                      placeholder="Full Name"
                      value={formData.fullName}
                      onChange={(e) => setFormData(prev => ({ ...prev, fullName: e.target.value }))}
                      required
                    />
                  </div>

                  {/* Mobile Number with Country Code */}
                  <div className="col-12">
                    <label className="form-label text-white">Mobile Number <span className="text-danger">*</span></label>
                    <div className="input-group">
                      <select
                        className="form-select auth-input"
                        style={{ maxWidth: '100px' }}
                        value={formData.countryCode}
                        onChange={(e) => setFormData(prev => ({ ...prev, countryCode: e.target.value }))}
                      >
                        <option value="+91">+91</option>
                        <option value="+1">+1</option>
                        <option value="+44">+44</option>
                        <option value="+86">+86</option>
                        <option value="+81">+81</option>
                      </select>
                      <input
                        type="tel"
                        className="form-control auth-input"
                        placeholder="Mobile Number"
                        value={formData.mobileNumber}
                        onChange={(e) => setFormData(prev => ({ ...prev, mobileNumber: e.target.value }))}
                        required
                      />
                    </div>
                  </div>

                  {/* Password */}
                  <div className="col-12">
                    <label className="form-label text-white">Password <span className="text-danger">*</span></label>
                    <input
                      type="password"
                      className="form-control auth-input"
                      placeholder="Password (minimum 6 characters)"
                      value={formData.password}
                      onChange={(e) => setFormData(prev => ({ ...prev, password: e.target.value }))}
                      minLength={6}
                      required
                    />
                  </div>

                  {/* Confirm Password */}
                  <div className="col-12">
                    <label className="form-label text-white">Confirm Password <span className="text-danger">*</span></label>
                    <input
                      type="password"
                      className="form-control auth-input"
                      placeholder="Confirm Password"
                      value={formData.confirmPassword}
                      onChange={(e) => setFormData(prev => ({ ...prev, confirmPassword: e.target.value }))}
                      required
                    />
                  </div>

                  {/* Send OTP Button */}
                  <div className="col-12 text-center">
                    <button
                      type="button"
                      className="btn auth-btn px-4"
                      onClick={sendOTP}
                      disabled={!formData.fullName || !formData.mobileNumber || !formData.password || !formData.confirmPassword || isLoading}
                      style={{ borderRadius: '25px', width: '120px' }}
                    >
                      {isLoading ? 'Sending...' : 'Register'}
                    </button>
                  </div>

                  {/* OTP Input */}
                  {otpSent && (
                    <>
                      <div className="col-12">
                        <label className="form-label text-white">Enter OTP <span className="text-danger">*</span></label>
                        <input
                          type="text"
                          className="form-control auth-input"
                          placeholder="Enter 6-digit OTP"
                          value={otpCode}
                          onChange={(e) => setOtpCode(e.target.value)}
                          maxLength={6}
                        />
                        <small className="text-white-50">
                          Use 123456 for demo. Didn't receive OTP? <button type="button" className="btn btn-link text-white p-0" onClick={sendOTP}>Resend</button>
                        </small>
                      </div>

                      {/* Verify OTP and Continue Button */}
                      <div className="col-12 text-center">
                        <button
                          type="button"
                          className="btn auth-btn px-4"
                          onClick={verifyOTPAndProceed}
                          disabled={!otpCode || isLoading}
                          style={{ borderRadius: '25px', width: '120px' }}
                        >
                          {isLoading ? 'Verifying...' : 'Continue'}
                        </button>
                      </div>
                    </>
                  )}
                </div>
              </div>
            )}

            {/* Step 2: Complete Profile Form */}
            {registrationStep === 2 && (
              <div data-testid="step-registration-2">
                <div className="row g-3">
                  {/* Display Name */}
                  <div className="col-12">
                    <label className="form-label text-white">Display Name (Nickname) <span className="text-danger">*</span></label>
                    <input
                      type="text"
                      className="form-control auth-input"
                      placeholder="Display Name"
                      value={formData.displayName}
                      onChange={(e) => setFormData(prev => ({ ...prev, displayName: e.target.value }))}
                      minLength={2}
                      maxLength={12}
                      required
                    />
                  </div>

                  {/* Email */}
                  <div className="col-12">
                    <label className="form-label text-white">Email-Id</label>
                    <input
                      type="email"
                      className="form-control auth-input"
                      placeholder="Email-Id"
                      value={formData.email}
                      onChange={(e) => setFormData(prev => ({ ...prev, email: e.target.value }))}
                    />
                  </div>

                  {/* Gender */}
                  <div className="col-12">
                    <label className="form-label text-white">Gender <span className="text-danger">*</span></label>
                    <div className="d-flex gap-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="gender"
                          id="male"
                          value="M"
                          checked={formData.gender === 'M'}
                          onChange={(e) => setFormData(prev => ({ ...prev, gender: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="male">Male</label>
                      </div>
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="gender"
                          id="female"
                          value="F"
                          checked={formData.gender === 'F'}
                          onChange={(e) => setFormData(prev => ({ ...prev, gender: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="female">Female</label>
                      </div>
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="gender"
                          id="transgender"
                          value="O"
                          checked={formData.gender === 'O'}
                          onChange={(e) => setFormData(prev => ({ ...prev, gender: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="transgender">Transgender</label>
                      </div>
                    </div>
                  </div>

                  {/* Marital Status */}
                  <div className="col-12">
                    <label className="form-label text-white">Marital Status <span className="text-danger">*</span></label>
                    <div className="d-flex gap-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="maritalStatus"
                          id="single"
                          value="Single"
                          checked={formData.maritalStatus === 'Single'}
                          onChange={(e) => setFormData(prev => ({ ...prev, maritalStatus: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="single">Single</label>
                      </div>
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="maritalStatus"
                          id="married"
                          value="Married"
                          checked={formData.maritalStatus === 'Married'}
                          onChange={(e) => setFormData(prev => ({ ...prev, maritalStatus: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="married">Married</label>
                      </div>
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="radio"
                          name="maritalStatus"
                          id="other"
                          value="Other"
                          checked={formData.maritalStatus === 'Other'}
                          onChange={(e) => setFormData(prev => ({ ...prev, maritalStatus: e.target.value }))}
                        />
                        <label className="form-check-label text-white" htmlFor="other">Other</label>
                      </div>
                    </div>
                  </div>

                  {/* Date of Birth */}
                  <div className="col-12">
                    <label className="form-label text-white">Date of Birth <span className="text-danger">*</span></label>
                    <div className="row g-2">
                      <div className="col-4">
                        <select
                          className="form-select auth-input"
                          value={formData.dateOfBirth.day}
                          onChange={(e) => setFormData(prev => ({
                            ...prev,
                            dateOfBirth: { ...prev.dateOfBirth, day: e.target.value }
                          }))}
                        >
                          <option value="">Date</option>
                          {Array.from({ length: 31 }, (_, i) => i + 1).map(day => (
                            <option key={day} value={day.toString().padStart(2, '0')}>
                              {day.toString().padStart(2, '0')}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="col-4">
                        <select
                          className="form-select auth-input"
                          value={formData.dateOfBirth.month}
                          onChange={(e) => setFormData(prev => ({
                            ...prev,
                            dateOfBirth: { ...prev.dateOfBirth, month: e.target.value }
                          }))}
                        >
                          <option value="">Month</option>
                          {[
                            { id: '01', name: 'January' }, { id: '02', name: 'February' }, { id: '03', name: 'March' },
                            { id: '04', name: 'April' }, { id: '05', name: 'May' }, { id: '06', name: 'June' },
                            { id: '07', name: 'July' }, { id: '08', name: 'August' }, { id: '09', name: 'September' },
                            { id: '10', name: 'October' }, { id: '11', name: 'November' }, { id: '12', name: 'December' }
                          ].map(month => (
                            <option key={month.id} value={month.id}>{month.name}</option>
                          ))}
                        </select>
                      </div>
                      <div className="col-4">
                        <select
                          className="form-select auth-input"
                          value={formData.dateOfBirth.year}
                          onChange={(e) => setFormData(prev => ({
                            ...prev,
                            dateOfBirth: { ...prev.dateOfBirth, year: e.target.value }
                          }))}
                        >
                          <option value="">Year</option>
                          {Array.from({ length: new Date().getFullYear() - 1900 + 1 }, (_, i) => new Date().getFullYear() - i).map(year => (
                            <option key={year} value={year}>{year}</option>
                          ))}
                        </select>
                      </div>
                    </div>
                  </div>

                  {/* Country */}
                  <div className="col-12">
                    <label className="form-label text-white">Country <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.country}
                      onChange={(e) => setFormData(prev => ({ ...prev, country: e.target.value, state: '', district: '' }))}
                      required
                    >
                      <option value="">Select Country</option>
                      <option value="1">India</option>
                      <option value="2">United States</option>
                      <option value="3">United Kingdom</option>
                      <option value="4">Canada</option>
                      <option value="5">Australia</option>
                    </select>
                  </div>

                  {/* State */}
                  <div className="col-12">
                    <label className="form-label text-white">State / Province <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.state}
                      onChange={(e) => setFormData(prev => ({ ...prev, state: e.target.value, district: '' }))}
                      required
                    >
                      <option value="">Select State / Province</option>
                      {formData.country === '1' && (
                        <>
                          <option value="1">Kerala</option>
                          <option value="2">Tamil Nadu</option>
                          <option value="3">Karnataka</option>
                          <option value="4">Maharashtra</option>
                          <option value="5">Delhi</option>
                        </>
                      )}
                    </select>
                  </div>

                  {/* District */}
                  <div className="col-12">
                    <label className="form-label text-white">District / City <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.district}
                      onChange={(e) => setFormData(prev => ({ ...prev, district: e.target.value }))}
                      required
                    >
                      <option value="">Select District</option>
                      {formData.state === '1' && (
                        <>
                          <option value="1">Thiruvananthapuram</option>
                          <option value="2">Kochi</option>
                          <option value="3">Kozhikode</option>
                          <option value="4">Thrissur</option>
                        </>
                      )}
                    </select>
                  </div>

                  {/* Nationality */}
                  <div className="col-12">
                    <label className="form-label text-white">Nationality <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.nationality}
                      onChange={(e) => setFormData(prev => ({ ...prev, nationality: e.target.value }))}
                      required
                    >
                      <option value="">Select Nationality</option>
                      <option value="Indian">Indian</option>
                      <option value="American">American</option>
                      <option value="British">British</option>
                      <option value="Canadian">Canadian</option>
                      <option value="Australian">Australian</option>
                    </select>
                  </div>

                  {/* Education */}
                  <div className="col-12">
                    <label className="form-label text-white">Education / Qualification <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.education}
                      onChange={(e) => setFormData(prev => ({ ...prev, education: e.target.value }))}
                      required
                    >
                      <option value="">Select Education / Qualification</option>
                      <option value="High School">High School</option>
                      <option value="Diploma">Diploma</option>
                      <option value="Bachelor's Degree">Bachelor's Degree</option>
                      <option value="Master's Degree">Master's Degree</option>
                      <option value="PhD">PhD</option>
                      <option value="education_others">Others</option>
                    </select>
                  </div>

                  {/* Education Others */}
                  {formData.education === 'education_others' && (
                    <div className="col-12">
                      <label className="form-label text-white">Others</label>
                      <input
                        type="text"
                        className="form-control auth-input"
                        placeholder="Specify your education"
                        value={formData.educationOthers}
                        onChange={(e) => setFormData(prev => ({ ...prev, educationOthers: e.target.value }))}
                      />
                    </div>
                  )}

                  {/* Profession */}
                  <div className="col-12">
                    <label className="form-label text-white">Work / Profession <span className="text-danger">*</span></label>
                    <select
                      className="form-select auth-input"
                      value={formData.profession}
                      onChange={(e) => setFormData(prev => ({ ...prev, profession: e.target.value }))}
                      required
                    >
                      <option value="">Select Work / Profession</option>
                      <option value="Student">Student</option>
                      <option value="Engineer">Engineer</option>
                      <option value="Doctor">Doctor</option>
                      <option value="Teacher">Teacher</option>
                      <option value="Business">Business</option>
                      <option value="Government Employee">Government Employee</option>
                      <option value="Private Employee">Private Employee</option>
                      <option value="work_others">Others</option>
                    </select>
                  </div>

                  {/* Work Others */}
                  {formData.profession === 'work_others' && (
                    <div className="col-12">
                      <label className="form-label text-white">Others</label>
                      <input
                        type="text"
                        className="form-control auth-input"
                        placeholder="Specify your profession"
                        value={formData.workOthers}
                        onChange={(e) => setFormData(prev => ({ ...prev, workOthers: e.target.value }))}
                      />
                    </div>
                  )}

                  {/* Submit Button */}
                  <div className="col-12 text-center mt-4">
                    <button
                      type="button"
                      className="btn auth-btn px-4 me-3"
                      onClick={handleCreateAccount}
                      disabled={isLoading || !formData.displayName || !formData.gender || !formData.country}
                      style={{ borderRadius: '25px', width: '120px' }}
                    >
                      {isLoading ? 'Submitting...' : 'Submit'}
                    </button>
                    <button
                      type="button"
                      className="btn btn-danger px-4"
                      onClick={onClose}
                      style={{ borderRadius: '25px', width: '120px' }}
                    >
                      Close
                    </button>
                  </div>
                </div>
              </div>
            )}

          </div>
        </div>
      </div>

      {/* Custom Styles */}
      <style>{`
        .auth-modal {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          border-radius: 20px;
          box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .auth-tabs {
          display: flex;
          background: rgba(255, 255, 255, 0.1);
          border-radius: 25px;
          padding: 4px;
          margin: 0 auto;
          width: fit-content;
        }

        .auth-tab {
          background: transparent;
          border: none;
          color: rgba(255, 255, 255, 0.7);
          padding: 8px 24px;
          border-radius: 20px;
          font-weight: 500;
          transition: all 0.3s ease;
          cursor: pointer;
        }

        .auth-tab.active {
          background: rgba(255, 255, 255, 0.2);
          color: white;
        }

        .auth-input {
          background: rgba(255, 255, 255, 0.1);
          border: 1px solid rgba(255, 255, 255, 0.2);
          color: white;
          border-radius: 12px;
        }

        .auth-input:focus {
          background: rgba(255, 255, 255, 0.15);
          border-color: rgba(255, 255, 255, 0.4);
          color: white;
          box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
        }

        .auth-input::placeholder {
          color: rgba(255, 255, 255, 0.6);
        }

        .auth-input option {
          background: #333;
          color: white;
        }

        .auth-btn {
          background: linear-gradient(45deg, #ff6b6b, #ee5a24);
          border: none;
          border-radius: 25px;
          color: white;
          font-weight: 600;
          padding: 12px;
          transition: all 0.3s ease;
        }

        .auth-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(238, 90, 36, 0.3);
        }

        .progress-custom {
          height: 6px;
          background: rgba(255, 255, 255, 0.1);
          border-radius: 3px;
        }

        .progress-bar-custom {
          background: linear-gradient(45deg, #ff6b6b, #ee5a24);
          border-radius: 3px;
        }

        .form-floating > label {
          color: rgba(255, 255, 255, 0.7);
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-select:focus ~ label,
        .form-floating > .form-select:not([value=""]) ~ label {
          color: rgba(255, 255, 255, 0.9);
        }
      `}</style>
    </div>
  );
}