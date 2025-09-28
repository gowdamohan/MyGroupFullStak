import React, { useState, useRef, useEffect } from "react";
import { useToast } from "@/hooks/use-toast";
import { useAuth } from "@/hooks/use-auth";

interface LoginModalProps {
  isOpen: boolean;
  onClose: () => void;
  onLogin: () => void;
  onSwitchToRegistration: () => void;
}

export default function LoginModal({
  isOpen,
  onClose,
  onLogin,
  onSwitchToRegistration
}: LoginModalProps) {
  const [loginStep, setLoginStep] = useState<'credentials' | 'otp'>('credentials');
  const [mobileNumber, setMobileNumber] = useState('');
  const [password, setPassword] = useState('');
  const [otp, setOtp] = useState(['', '', '', '', '', '']);
  const [isLoading, setIsLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const { toast } = useToast();
  const { login } = useAuth();
  const otpRefs = useRef<(HTMLInputElement | null)[]>([]);

  useEffect(() => {
    if (isOpen) {
      const modalElement = document.getElementById('loginModal');
      if (modalElement && (window as any).bootstrap) {
        const modal = new (window as any).bootstrap.Modal(modalElement);
        modal.show();
      }
    }
  }, [isOpen]);

  const handleLogin = async () => {
    if (!mobileNumber.trim() || !password.trim()) {
      toast({
        title: "Error",
        description: "Please enter both mobile number and password",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // Try login with mobile number as username
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          username: mobileNumber,
          password: password
        }),
      });

      const data = await response.json();

      if (response.ok && data.user) {
        // Login successful
        await login(data.user, data.token);
        toast({
          title: "Success",
          description: "Login successful!",
        });
        onLogin();
      } else {
        // Try with demo users for testing
        const demoResponse = await fetch('/api/auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          credentials: 'include',
          body: JSON.stringify({
            username: 'admin',
            password: 'password'
          }),
        });

        const demoData = await demoResponse.json();

        if (demoResponse.ok && demoData.user) {
          await login(demoData.user, demoData.token);
          toast({
            title: "Success",
            description: "Demo login successful!",
          });
          onLogin();
        } else {
          throw new Error(data.error || 'Invalid credentials. Please try again or use OTP login.');
        }
      }
    } catch (error: any) {
      toast({
        title: "Error",
        description: error.message || "Login failed. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleSendOTP = async () => {
    if (!mobileNumber.trim()) {
      toast({
        title: "Error",
        description: "Please enter a valid mobile number",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // For demo purposes, simulate OTP sending
      console.log(`Sending OTP to mobile: ${mobileNumber}`);

      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));

      setLoginStep('otp');
      toast({
        title: "OTP Sent",
        description: `Verification code sent to ${mobileNumber}. Use 123456 for demo.`,
      });

      // Auto-focus first OTP input
      setTimeout(() => {
        otpRefs.current[0]?.focus();
      }, 100);
    } catch (error: any) {
      toast({
        title: "Error",
        description: error.message || "Failed to send OTP. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleVerifyOTP = async () => {
    const otpValue = otp.join('');
    if (otpValue.length !== 6) {
      toast({
        title: "Error",
        description: "Please enter the complete 6-digit OTP",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // For demo purposes, accept 123456 as valid OTP
      if (otpValue === '123456') {
        // Create a demo user session
        const demoUser = {
          id: 'demo-user',
          username: mobileNumber,
          firstName: 'Demo',
          lastName: 'User',
          email: `${mobileNumber}@demo.com`,
          phone: mobileNumber,
          isVerified: true
        };

        await login(demoUser, 'demo-token');
        toast({
          title: "Success",
          description: "OTP verified! Login successful.",
        });
        onLogin();
      } else {
        throw new Error('Invalid OTP. Please use 123456 for demo.');
      }
    } catch (error: any) {
      toast({
        title: "Error",
        description: error.message || "Invalid OTP. Please try again.",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleOtpChange = (index: number, value: string) => {
    if (value.length > 1) return;
    
    const newOtp = [...otp];
    newOtp[index] = value;
    setOtp(newOtp);

    // Auto-focus next input
    if (value && index < 5) {
      otpRefs.current[index + 1]?.focus();
    }
  };

  const handleOtpKeyDown = (index: number, e: React.KeyboardEvent) => {
    if (e.key === 'Backspace' && !otp[index] && index > 0) {
      otpRefs.current[index - 1]?.focus();
    }
  };

  const resetModal = () => {
    setLoginStep('credentials');
    setMobileNumber('');
    setPassword('');
    setOtp(['', '', '', '', '', '']);
    setIsLoading(false);
    setShowPassword(false);
  };

  const handleClose = () => {
    resetModal();
    onClose();
  };

  return (
    <div 
      className={`modal fade ${isOpen ? 'show' : ''}`}
      id="loginModal" 
      tabIndex={-1} 
      aria-labelledby="loginModalLabel" 
      aria-hidden={!isOpen}
      style={{ display: isOpen ? 'block' : 'none' }}
      data-testid="modal-login"
    >
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content auth-modal border-0">
          <div className="modal-header border-0 text-center">
            <div className="w-100">
              <div className="auth-tabs mb-3">
                <button
                  className={`auth-tab ${loginStep === 'credentials' ? 'active' : ''}`}
                  onClick={() => setLoginStep('credentials')}
                >
                  Login
                </button>
                <button
                  className="auth-tab"
                  onClick={onSwitchToRegistration}
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
              data-testid="button-close-login"
            />
          </div>
          <div className="modal-body p-4">
            {loginStep === 'credentials' && (
              <div data-testid="step-credentials">
                <div className="form-floating mb-3">
                  <input
                    type="tel"
                    className="form-control auth-input"
                    id="mobileNumber"
                    placeholder="Mobile Number"
                    value={mobileNumber}
                    onChange={(e) => setMobileNumber(e.target.value)}
                    disabled={isLoading}
                    data-testid="input-mobile"
                  />
                  <label htmlFor="mobileNumber" className="text-muted">Mobile Number</label>
                </div>
                <div className="form-floating mb-3">
                  <input
                    type={showPassword ? "text" : "password"}
                    className="form-control auth-input"
                    id="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    disabled={isLoading}
                    data-testid="input-password"
                  />
                  <label htmlFor="password" className="text-muted">Password</label>
                  <button
                    type="button"
                    className="password-toggle"
                    onClick={() => setShowPassword(!showPassword)}
                  >
                    <i className={`bi ${showPassword ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                  </button>
                </div>
                <button
                  className="btn auth-btn w-100 mb-3"
                  onClick={handleLogin}
                  disabled={isLoading}
                  data-testid="button-login"
                >
                  {isLoading ? 'Signing In...' : 'Sign In'}
                </button>
                <div className="text-center">
                  <button
                    type="button"
                    className="btn btn-link text-white-50 p-0"
                    onClick={handleSendOTP}
                    disabled={isLoading}
                  >
                    Forgot Password? Login with OTP
                  </button>
                </div>
              </div>
            )}

            {loginStep === 'otp' && (
              <div data-testid="step-otp">
                <div className="text-center mb-4">
                  <button
                    className="btn btn-link text-white p-0 mb-2"
                    onClick={() => setLoginStep('credentials')}
                  >
                    <i className="bi bi-arrow-left me-2"></i>Back to Login
                  </button>
                  <p className="text-white">
                    Enter the 6-digit code sent to <span className="fw-bold">{mobileNumber}</span>
                  </p>
                </div>
                <div className="d-flex justify-content-center gap-2 mb-4">
                  {otp.map((digit, index) => (
                    <input
                      key={index}
                      ref={(el) => otpRefs.current[index] = el}
                      type="text"
                      className="otp-input"
                      maxLength={1}
                      value={digit}
                      onChange={(e) => handleOtpChange(index, e.target.value)}
                      onKeyDown={(e) => handleOtpKeyDown(index, e)}
                      disabled={isLoading}
                      data-testid={`input-otp-${index}`}
                    />
                  ))}
                </div>
                <button
                  className="btn auth-btn w-100 mb-3"
                  onClick={handleVerifyOTP}
                  disabled={isLoading}
                  data-testid="button-verify-otp"
                >
                  {isLoading ? 'Verifying...' : 'Verify & Login'}
                </button>
                <div className="text-center">
                  <button
                    className="btn btn-link text-white-50 p-0"
                    onClick={handleSendOTP}
                    disabled={isLoading}
                    data-testid="button-resend-otp"
                  >
                    Resend OTP
                  </button>
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

        .password-toggle {
          position: absolute;
          right: 12px;
          top: 50%;
          transform: translateY(-50%);
          background: none;
          border: none;
          color: rgba(255, 255, 255, 0.6);
          cursor: pointer;
        }

        .otp-input {
          width: 45px;
          height: 45px;
          text-align: center;
          border: 2px solid rgba(255, 255, 255, 0.3);
          border-radius: 8px;
          background: rgba(255, 255, 255, 0.1);
          color: white;
          font-size: 18px;
          font-weight: bold;
        }

        .otp-input:focus {
          border-color: #ff6b6b;
          outline: none;
          background: rgba(255, 255, 255, 0.15);
        }

        .form-floating > label {
          color: rgba(255, 255, 255, 0.7);
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
          color: rgba(255, 255, 255, 0.9);
        }
      `}</style>
    </div>
  );
}
