import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { authService, User, LoginRequest, LoginResponse } from '../services/authService';

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: (data: LoginRequest) => Promise<LoginResponse>;
  clientLogin: (groupName: string, data: LoginRequest) => Promise<LoginResponse>;
  logout: () => Promise<void>;
  refreshUser: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  // Initialize authentication state on mount
  useEffect(() => {
    const initializeAuth = () => {
      try {
        const token = authService.getToken();
        const savedUser = authService.getUser();
        
        if (token && savedUser) {
          setUser(savedUser);
        }
      } catch (error) {
        console.error('Error initializing auth:', error);
        // Clear invalid data
        authService.removeToken();
      } finally {
        setIsLoading(false);
      }
    };

    initializeAuth();
  }, []);

  const login = async (data: LoginRequest): Promise<LoginResponse> => {
    try {
      const response = await authService.login(data);
      
      if (response.success && response.token && response.user) {
        authService.setToken(response.token);
        authService.setUser(response.user);
        setUser(response.user);
      }
      
      return response;
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  };

  const clientLogin = async (groupName: string, data: LoginRequest): Promise<LoginResponse> => {
    try {
      const response = await authService.clientLogin(groupName, data);
      
      if (response.success && response.token && response.user) {
        authService.setToken(response.token);
        authService.setUser(response.user);
        setUser(response.user);
      }
      
      return response;
    } catch (error) {
      console.error('Client login error:', error);
      throw error;
    }
  };

  const logout = async (): Promise<void> => {
    try {
      await authService.logout();
    } catch (error) {
      console.error('Logout error:', error);
      // Continue with local logout even if server logout fails
    } finally {
      authService.removeToken();
      setUser(null);
    }
  };

  const refreshUser = (): void => {
    const savedUser = authService.getUser();
    setUser(savedUser);
  };

  const value: AuthContextType = {
    user,
    isAuthenticated: !!user,
    isLoading,
    login,
    clientLogin,
    logout,
    refreshUser,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

// Higher-order component for protected routes
interface ProtectedRouteProps {
  children: ReactNode;
  fallback?: ReactNode;
  redirectTo?: string;
}

export const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ 
  children, 
  fallback = <div>Please log in to access this page.</div>,
  redirectTo = '/login'
}) => {
  const { isAuthenticated, isLoading } = useAuth();

  if (isLoading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ height: '100vh' }}>
        <div className="spinner-border" role="status">
          <span className="sr-only">Loading...</span>
        </div>
      </div>
    );
  }

  if (!isAuthenticated) {
    // In a real app, you might want to use a router redirect here
    // For now, we'll show the fallback
    return <>{fallback}</>;
  }

  return <>{children}</>;
};

// Hook for checking specific permissions
export const usePermissions = () => {
  const { user } = useAuth();

  const hasGroup = (groupName: string): boolean => {
    if (!user?.groups) return false;
    return user.groups.some(group => group.name === groupName);
  };

  const hasGroupId = (groupId: number): boolean => {
    if (!user?.groups) return false;
    return user.groups.some(group => group.id === groupId);
  };

  const isAdmin = (): boolean => {
    return hasGroup('admin') || hasGroup('administrators');
  };

  const isMember = (): boolean => {
    return hasGroup('members') || hasGroup('users');
  };

  return {
    hasGroup,
    hasGroupId,
    isAdmin,
    isMember,
    userGroups: user?.groups || [],
  };
};

// Custom hook for authentication actions
export const useAuthActions = () => {
  const { login, clientLogin, logout } = useAuth();

  const handleLogin = async (data: LoginRequest, onSuccess?: () => void, onError?: (error: string) => void) => {
    try {
      const response = await login(data);
      if (response.success) {
        onSuccess?.();
      } else {
        onError?.(response.error || 'Login failed');
      }
      return response;
    } catch (error) {
      const errorMessage = authService.formatErrorMessage(error);
      onError?.(errorMessage);
      throw error;
    }
  };

  const handleClientLogin = async (
    groupName: string, 
    data: LoginRequest, 
    onSuccess?: () => void, 
    onError?: (error: string) => void
  ) => {
    try {
      const response = await clientLogin(groupName, data);
      if (response.success) {
        onSuccess?.();
      } else if (response.requiresRegistration) {
        // Handle registration redirect
        return response;
      } else {
        onError?.(response.error || 'Login failed');
      }
      return response;
    } catch (error) {
      const errorMessage = authService.formatErrorMessage(error);
      onError?.(errorMessage);
      throw error;
    }
  };

  const handleLogout = async (onSuccess?: () => void, onError?: (error: string) => void) => {
    try {
      await logout();
      onSuccess?.();
    } catch (error) {
      const errorMessage = authService.formatErrorMessage(error);
      onError?.(errorMessage);
    }
  };

  return {
    handleLogin,
    handleClientLogin,
    handleLogout,
  };
};

export default AuthContext;
