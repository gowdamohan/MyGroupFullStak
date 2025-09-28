// Authentication service for handling login, registration, and OTP operations

export interface User {
  id: number;
  email: string;
  username: string;
  firstName: string;
  lastName: string;
  groupId: number;
  groups?: Array<{ id: number; name: string; description: string }>;
}

export interface LoginRequest {
  identity: string;
  password: string;
  remember?: boolean;
}

export interface LoginResponse {
  success: boolean;
  message: string;
  token?: string;
  user?: User;
  requiresRegistration?: boolean;
  redirectUrl?: string;
  userInfo?: any;
  error?: string;
}

export interface OTPRequest {
  emailId: string;
  group_id: number;
}

export interface OTPVerificationRequest {
  emailId: string;
  otp: string;
}

export interface OTPResponse {
  success: boolean;
  message: string;
  email?: string;
  otpId?: number;
  error?: string;
}

export interface RegistrationRequest {
  firstName: string;
  lastName: string;
  email: string;
  username: string;
  password: string;
  phone?: string;
  company?: string;
  groupId: number;
  otpId: number;
}

export interface RegistrationResponse {
  success: boolean;
  message: string;
  userId?: number;
  error?: string;
}

export interface ForgotPasswordRequest {
  identity: string;
}

export interface ResetPasswordRequest {
  username: string;
  newPassword: string;
  otpCode: string;
}

class AuthService {
  private baseURL = '/api/auth';

  // Login methods
  async login(data: LoginRequest): Promise<LoginResponse> {
    const response = await fetch(`${this.baseURL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Login failed');
    }
    
    return result;
  }

  async clientLogin(groupName: string, data: LoginRequest): Promise<LoginResponse> {
    const response = await fetch(`${this.baseURL}/client-login/${groupName}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Login failed');
    }
    
    return result;
  }

  // Registration methods
  async sendRegistrationOTP(data: OTPRequest): Promise<OTPResponse> {
    const response = await fetch(`${this.baseURL}/register-with-otp`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Failed to send OTP');
    }
    
    return result;
  }

  async verifyRegistrationOTP(data: OTPVerificationRequest): Promise<OTPResponse> {
    const response = await fetch(`${this.baseURL}/verify-registration-otp`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Failed to verify OTP');
    }
    
    return result;
  }

  async register(data: RegistrationRequest): Promise<RegistrationResponse> {
    const response = await fetch(`${this.baseURL}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Registration failed');
    }
    
    return result;
  }

  // Password recovery methods
  async forgotPassword(data: ForgotPasswordRequest): Promise<OTPResponse> {
    const response = await fetch(`${this.baseURL}/forgot-password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Failed to send password reset OTP');
    }
    
    return result;
  }

  async verifyForgotPasswordOTP(data: OTPVerificationRequest): Promise<OTPResponse> {
    const response = await fetch(`${this.baseURL}/verify-forgot-password-otp`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Failed to verify OTP');
    }
    
    return result;
  }

  async resetPassword(data: ResetPasswordRequest): Promise<{ success: boolean; message: string }> {
    const response = await fetch(`${this.baseURL}/reset-password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Failed to reset password');
    }
    
    return result;
  }

  // Session management
  async logout(): Promise<{ success: boolean; message: string }> {
    const response = await fetch(`${this.baseURL}/logout`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.error || 'Logout failed');
    }
    
    return result;
  }

  // Token management
  getToken(): string | null {
    return localStorage.getItem('authToken');
  }

  setToken(token: string): void {
    localStorage.setItem('authToken', token);
  }

  removeToken(): void {
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
  }

  getUser(): User | null {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  }

  setUser(user: User): void {
    localStorage.setItem('user', JSON.stringify(user));
  }

  isAuthenticated(): boolean {
    const token = this.getToken();
    const user = this.getUser();
    return !!(token && user);
  }

  // Utility methods for OTP
  validateOTP(otp: string, length: number = 6): boolean {
    return otp.length === length && /^\d+$/.test(otp);
  }

  validateEmail(email: string): boolean {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  validatePassword(password: string, minLength: number = 6): boolean {
    return password.length >= minLength;
  }

  // Format error messages
  formatErrorMessage(error: any): string {
    if (typeof error === 'string') {
      return error;
    }
    
    if (error?.message) {
      return error.message;
    }
    
    if (error?.error) {
      return error.error;
    }
    
    return 'An unexpected error occurred';
  }
}

export const authService = new AuthService();
export default authService;
