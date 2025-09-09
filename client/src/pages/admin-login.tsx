import { useState } from "react";
import { useLocation } from "wouter";
import { useToast } from "@/hooks/use-toast";
import { useMutation } from "@tanstack/react-query";
import { apiRequest } from "@/lib/queryClient";
import type { AdminLogin } from "@shared/schema";

export default function AdminLoginPage() {
  const [, setLocation] = useLocation();
  const [formData, setFormData] = useState<AdminLogin>({
    username: '',
    password: '',
  });
  const { toast } = useToast();

  const loginMutation = useMutation({
    mutationFn: async (data: AdminLogin) => {
      const response = await apiRequest('/api/auth/admin/login', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      
      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Login failed');
      }
      
      return await response.json();
    },
    onSuccess: (data: any) => {
      // Store JWT token if provided
      if (data.token) {
        localStorage.setItem('authToken', data.token);
      }

      toast({
        title: "Admin Login Successful",
        description: `Welcome back, ${data.user.firstName || data.user.username}!`,
      });

      // Redirect to admin dashboard
      setLocation('/dashboard/admin');
    },
    onError: (error: any) => {
      toast({
        title: "Admin Login Failed",
        description: error.message || "Invalid admin credentials",
        variant: "destructive",
      });
    }
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!formData.username.trim()) {
      toast({
        title: "Error",
        description: "Please enter your admin username",
        variant: "destructive",
      });
      return;
    }

    if (!formData.password.trim()) {
      toast({
        title: "Error", 
        description: "Please enter your admin password",
        variant: "destructive",
      });
      return;
    }

    loginMutation.mutate(formData);
  };

  const handleInputChange = (field: keyof AdminLogin, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center" style={{ background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}>
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-6 col-lg-4">
            <div className="card glass-modal border-0 shadow-lg">
              <div className="card-header border-0 text-center bg-transparent">
                <h3 className="text-white fw-bold mb-0" data-testid="admin-login-title">
                  <i className="bi bi-shield-lock-fill me-2 text-warning"></i>
                  Admin Access
                </h3>
                <p className="text-white-50 mt-2 mb-0">Secure Administrator Login</p>
              </div>
              
              <div className="card-body p-4">
                <form onSubmit={handleSubmit} data-testid="form-admin-login">
                  <div className="mb-3">
                    <div className="form-floating">
                      <input
                        type="text"
                        className="form-control"
                        id="admin-username"
                        placeholder="Admin Username"
                        value={formData.username}
                        onChange={(e) => handleInputChange('username', e.target.value)}
                        disabled={loginMutation.isPending}
                        required
                        data-testid="input-admin-username"
                        autoComplete="username"
                      />
                      <label htmlFor="admin-username" className="text-muted">
                        <i className="bi bi-person-badge me-2"></i>Admin Username
                      </label>
                    </div>
                  </div>

                  <div className="mb-4">
                    <div className="form-floating">
                      <input
                        type="password"
                        className="form-control"
                        id="admin-password"
                        placeholder="Admin Password"
                        value={formData.password}
                        onChange={(e) => handleInputChange('password', e.target.value)}
                        disabled={loginMutation.isPending}
                        required
                        data-testid="input-admin-password"
                        autoComplete="current-password"
                      />
                      <label htmlFor="admin-password" className="text-muted">
                        <i className="bi bi-key me-2"></i>Admin Password
                      </label>
                    </div>
                  </div>

                  <button
                    type="submit"
                    className="btn btn-warning w-100 mb-3 text-dark fw-bold"
                    disabled={loginMutation.isPending}
                    data-testid="button-admin-login"
                  >
                    {loginMutation.isPending ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Authenticating...
                      </>
                    ) : (
                      <>
                        <i className="bi bi-shield-check me-2"></i>
                        Admin Sign In
                      </>
                    )}
                  </button>

                  <div className="text-center">
                    <button
                      type="button"
                      className="btn btn-outline-light"
                      onClick={() => setLocation('/')}
                      data-testid="button-back-home"
                    >
                      <i className="bi bi-house me-2"></i>
                      Back to Home
                    </button>
                  </div>
                </form>

                {/* Security Notice */}
                <div className="mt-4 pt-4 border-top border-white-50">
                  <div className="alert alert-warning bg-warning bg-opacity-25 border-warning text-white small">
                    <i className="bi bi-exclamation-triangle me-2"></i>
                    <strong>Security Notice:</strong> This is a restricted admin area. All access attempts are logged and monitored.
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
