import { useState, useRef, useEffect } from "react";
import { useToast } from "@/hooks/use-toast";
import type { LoginPhone, VerifyOtp } from "@shared/schema";

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
  const [loginStep, setLoginStep] = useState<'phone' | 'otp'>('phone');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [otp, setOtp] = useState(['', '', '', '', '', '']);
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();
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

  const handleSendOTP = async () => {
    if (!phoneNumber.trim()) {
      toast({
        title: "Error",
        description: "Please enter a valid phone number",
        variant: "destructive",
      });
      return;
    }

    setIsLoading(true);
    try {
      // TODO: Implement API call to send OTP
      console.log("Sending OTP to:", phoneNumber);
      
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      setLoginStep('otp');
      toast({
        title: "OTP Sent",
        description: `Verification code sent to ${phoneNumber}`,
      });
      
      // Auto-focus first OTP input
      setTimeout(() => {
        otpRefs.current[0]?.focus();
      }, 100);
    } catch (error) {
      toast({
        title: "Error",
        description: "Failed to send OTP. Please try again.",
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
      // TODO: Implement API call to verify OTP
      console.log("Verifying OTP:", otpValue, "for phone:", phoneNumber);
      
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      toast({
        title: "Success",
        description: "Login successful!",
      });
      
      onLogin();
    } catch (error) {
      toast({
        title: "Error",
        description: "Invalid OTP. Please try again.",
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
    setLoginStep('phone');
    setPhoneNumber('');
    setOtp(['', '', '', '', '', '']);
    setIsLoading(false);
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
        <div className="modal-content glass-modal border-0">
          <div className="modal-header border-0 text-center">
            <h5 className="modal-title w-100 text-white fw-bold" id="loginModalLabel">
              Welcome Back
            </h5>
            <button 
              type="button" 
              className="btn-close btn-close-white" 
              onClick={handleClose}
              aria-label="Close"
              data-testid="button-close-login"
            />
          </div>
          <div className="modal-body p-4">
            <div className="login-icon">
              <i className="bi bi-shield-lock text-white fs-2" />
            </div>

            {loginStep === 'phone' && (
              <div data-testid="step-phone">
                <div className="form-floating mb-3">
                  <input 
                    type="tel" 
                    className="form-control" 
                    id="phoneNumber" 
                    placeholder="Phone Number"
                    value={phoneNumber}
                    onChange={(e) => setPhoneNumber(e.target.value)}
                    disabled={isLoading}
                    data-testid="input-phone"
                  />
                  <label htmlFor="phoneNumber" className="text-muted">Phone Number</label>
                </div>
                <button 
                  className="btn btn-primary w-100 mb-3" 
                  onClick={handleSendOTP}
                  disabled={isLoading}
                  data-testid="button-send-otp"
                >
                  {isLoading ? 'Sending...' : 'Send OTP'}
                </button>
              </div>
            )}

            {loginStep === 'otp' && (
              <div data-testid="step-otp">
                <p className="text-white text-center mb-4">
                  Enter the 6-digit code sent to <span className="fw-bold">{phoneNumber}</span>
                </p>
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
                  className="btn btn-success w-100 mb-3" 
                  onClick={handleVerifyOTP}
                  disabled={isLoading}
                  data-testid="button-verify-otp"
                >
                  {isLoading ? 'Verifying...' : 'Verify & Login'}
                </button>
                <button 
                  className="btn btn-outline-light w-100" 
                  onClick={handleSendOTP}
                  disabled={isLoading}
                  data-testid="button-resend-otp"
                >
                  Resend OTP
                </button>
              </div>
            )}

            <div className="text-center mt-4">
              <p className="text-white-50 small">
                Don't have an account? {' '}
                <a 
                  href="#" 
                  className="text-white" 
                  onClick={(e) => {
                    e.preventDefault();
                    onSwitchToRegistration();
                  }}
                  data-testid="link-signup"
                >
                  Sign up here
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
