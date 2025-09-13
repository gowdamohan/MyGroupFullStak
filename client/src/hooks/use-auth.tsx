import React, { useState, useEffect, createContext, useContext, ReactNode } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { useLocation } from 'wouter';
import { apiRequest } from '@/lib/queryClient';
import { useToast } from '@/hooks/use-toast';

interface User {
  id: number;
  username: string;
  email: string;
  firstName?: string;
  lastName?: string;
  role: string;
  isAdmin?: boolean;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  isLoginLoading: boolean;
  login: (credentials: { username: string; password: string }) => Promise<any>;
  logout: () => void;
  token: string | null;
  getDashboardRoute: () => string;
}

// Helper function to get redirect path based on user role
function getRoleBasedRedirectPath(user: User): string {
  const role = user.role || user.userRole || 'user';

  switch (role.toLowerCase()) {
    case 'admin':
      return '/dashboard/admin';
    case 'corporate':
      return '/dashboard/corporate';
    case 'head_office':
    case 'head-office':
      return '/dashboard/head-office';
    case 'regional':
      return '/dashboard/regional';
    case 'branch':
      return '/dashboard/branch';
    default:
      return '/dashboard'; // Default dashboard
  }
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [token, setToken] = useState<string | null>(localStorage.getItem('authToken'));
  const [user, setUser] = useState<User | null>(null);
  const [, setLocation] = useLocation();
  const queryClient = useQueryClient();
  const { toast } = useToast();

  // Query to get current user info
  const { data: authData, isLoading, error } = useQuery({
    queryKey: ['auth', 'me'],
    queryFn: async () => {
      if (!token) return null;

      try {
        const response = await apiRequest('/api/auth/me');
        return await response.json();
      } catch (error) {
        console.log('Auth check failed:', error);
        // If token is invalid, clear it
        localStorage.removeItem('authToken');
        setToken(null);
        return null;
      }
    },
    enabled: !!token,
    retry: false,
    staleTime: 5 * 60 * 1000, // 5 minutes
    gcTime: 10 * 60 * 1000, // 10 minutes
  });

  // Update user state when auth data changes
  useEffect(() => {
    if (authData?.user) {
      setUser({
        ...authData.user,
        isAdmin: authData.isAdmin
      });
    } else {
      setUser(null);
    }
  }, [authData]);

  // Login mutation
  const loginMutation = useMutation({
    mutationFn: async (credentials: { username: string; password: string }) => {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify(credentials),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Login failed');
      }

      return await response.json();
    },
    onSuccess: (data) => {
      if (data.token) {
        localStorage.setItem('authToken', data.token);
        setToken(data.token);
      }

      if (data.user) {
        setUser(data.user);
      }

      // Invalidate auth queries to refetch user data
      queryClient.invalidateQueries({ queryKey: ['auth'] });

      toast({
        title: "Login Successful",
        description: `Welcome back, ${data.user?.firstName || data.user?.username}!`,
      });

      // Role-based redirection
      if (data.user) {
        const redirectPath = getRoleBasedRedirectPath(data.user);
        console.log(`🔄 Redirecting ${data.user.username} (${data.user.role || data.user.userRole}) to ${redirectPath}`);
        setLocation(redirectPath);
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

  // Logout function
  const logout = async () => {
    try {
      // Call logout endpoint to clear server-side session
      await apiRequest('/api/auth/logout', { method: 'POST' });
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Clear client-side data regardless of server response
      localStorage.removeItem('authToken');
      setToken(null);
      setUser(null);

      // Clear all cached queries
      queryClient.clear();

      toast({
        title: "Logged Out",
        description: "You have been successfully logged out.",
      });

      // Redirect to login page
      setLocation('/auth/login');
    }
  };

  const login = async (credentials: { username: string; password: string }) => {
    const result = await loginMutation.mutateAsync(credentials);
    return result;
  };

  // Function to get the appropriate dashboard route based on user role
  const getDashboardRoute = (): string => {
    if (!user) return '/';

    switch (user.role) {
      case 'admin':
        return '/dashboard/admin';
      case 'corporate':
        return '/dashboard/corporate';
      case 'regional':
        return '/dashboard/regional';
      case 'branch':
        return '/dashboard/branch';
      case 'head_office':
        return '/dashboard/head-office';
      default:
        return '/dashboard/admin'; // Default to admin dashboard for any user
    }
  };

  const value: AuthContextType = {
    user,
    isAuthenticated: !!user && !!token,
    isLoading: (!!token && isLoading) || loginMutation.isPending,
    isLoginLoading: loginMutation.isPending,
    login,
    logout,
    token,
    getDashboardRoute,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}

// Hook for checking if user has specific role
export function useRequireRole(requiredRole: string) {
  const { user, isAuthenticated } = useAuth();
  
  return {
    hasRole: isAuthenticated && user?.role === requiredRole,
    isAdmin: isAuthenticated && (user?.isAdmin || user?.role === 'admin'),
    user,
    isAuthenticated
  };
}

// Hook for admin-only access
export function useRequireAdmin() {
  return useRequireRole('admin');
}
