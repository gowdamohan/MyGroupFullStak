import { useState } from "react";
import { useLocation } from "wouter";
import { useToast } from "@/hooks/use-toast";
import { useMutation } from "@tanstack/react-query";
import { apiRequest } from "@/lib/queryClient";
import type { Login } from "@shared/schema";

export default function LoginPage() {
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
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return await response.json();
    },
    onSuccess: (data: any) => {
      toast({
        title: "Login Successful",
        description: `Welcome back, ${data.user.firstName}!`,
      });
      
      // Redirect based on user role
      switch(data.user.role) {
        case 'admin':
          setLocation('/dashboard/admin');
          break;
        case 'corporate':
          setLocation('/dashboard/corporate');
          break;
        case 'head_office':
          setLocation('/dashboard/head-office');
          break;
        case 'regional':
          setLocation('/dashboard/regional');
          break;
        case 'branch':
          setLocation('/dashboard/branch');
          break;
        default:
          setLocation('/');
      }
    },
    onError: (error: any) => {
      toast({
        title: "Login Failed",
        description: error.message || "Invalid username or password",
        variant: "destructive",
      });
    }
  });

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

    loginMutation.mutate(formData);
  };

  const handleInputChange = (field: keyof Login, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center" style={{ background: 'var(--primary-gradient)' }}>
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-6 col-lg-4">
            <div className="card glass-modal border-0 shadow-lg">
              <div className="card-header border-0 text-center bg-transparent">
                <h3 className="text-white fw-bold mb-0" data-testid="login-title">
                  <i className="bi bi-shield-lock me-2"></i>
                  AppHub Login
                </h3>
              </div>
              
              <div className="card-body p-4">
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