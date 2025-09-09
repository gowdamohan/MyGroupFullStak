import { useState } from "react";
import { useLocation } from "wouter";
import { useToast } from "@/hooks/use-toast";
import { useMutation } from "@tanstack/react-query";
import { apiRequest } from "@/lib/queryClient";
import type { Login } from "@shared/schema";

export default function HeadOfficeLoginPage() {
  const [, setLocation] = useLocation();
  const [formData, setFormData] = useState<Login>({
    username: '',
    password: '',
  });
  const { toast } = useToast();

  const loginMutation = useMutation({
    mutationFn: async (data: Login) => {
      const response = await apiRequest('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Login failed');
      }

      return response.json();
    },
    onSuccess: (data: any) => {
      console.log('🔍 Head Office Login Success Data:', data);
      
      // Store JWT token if provided
      if (data.token) {
        localStorage.setItem('authToken', data.token);
      }

      toast({
        title: "Head Office Login Successful",
        description: `Welcome back, ${data.user.firstName || data.user.username}!`,
      });
      
      // Redirect to head office dashboard
      setLocation('/dashboard/head-office');
    },
    onError: (error: any) => {
      toast({
        title: "Head Office Login Failed",
        description: error.message || "Invalid head office credentials",
        variant: "destructive",
      });
    }
  });

  const handleInputChange = (field: keyof Login, value: string) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    loginMutation.mutate(formData);
  };

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center" style={{ background: 'linear-gradient(135deg, #2c3e50 0%, #3498db 100%)' }}>
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-6 col-lg-4">
            <div className="card glass-modal border-0 shadow-lg">
              <div className="card-header border-0 text-center bg-transparent">
                <h3 className="text-white fw-bold mb-0" data-testid="head-office-login-title">
                  <i className="bi bi-bank2 me-2 text-primary"></i>
                  Head Office Access
                </h3>
                <p className="text-white-50 mt-2 mb-0">Head Office User Login</p>
              </div>
              
              <div className="card-body p-4">
                <form onSubmit={handleSubmit} data-testid="form-head-office-login">
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
                        Head Office Sign In
                      </>
                    )}
                  </button>
                </form>

                <div className="text-center">
                  <button
                    className="btn btn-link text-white-50"
                    onClick={() => setLocation('/')}
                  >
                    <i className="bi bi-arrow-left me-2"></i>
                    Back to Home
                  </button>
                </div>

                {/* Demo Credentials */}
                <div className="mt-4 pt-4 border-top border-white-50">
                  <h6 className="text-white text-center mb-3">Demo Head Office User</h6>
                  <div className="text-white-50 small text-center">
                    <p className="mb-1"><strong>Username:</strong> head_office</p>
                    <p className="mb-0"><strong>Password:</strong> password</p>
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
