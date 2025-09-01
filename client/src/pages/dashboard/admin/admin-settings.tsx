import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

function AdminSettings() {
  const [passwords, setPasswords] = useState<Record<number, string>>({});
  const { toast } = useToast();

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Create Category', path: '/dashboard/admin/categories' },
    { icon: 'bi-gear', label: 'Admin Settings', path: '/dashboard/admin/admin-settings', active: true },
    { icon: 'bi-box-arrow-in-right', label: 'Corporate Login', path: '/dashboard/admin/corporate-login' },
    { icon: 'bi-box-arrow-right', label: 'Logout', path: '/logout' },
  ];

  // Fetch 'My Apps' list with account status
  const appsQuery = useQuery({
    queryKey: ['/api/admin/my-apps'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/groups');
      const data = await response.json();
      // Filter only 'My Apps' group and check if accounts exist
      const myApps = data.filter((group: any) => group.appsName === 'My Apps');

      // Check which apps already have accounts created
      const appsWithAccountStatus = await Promise.all(
        myApps.map(async (app: any) => {
          try {
            const accountResponse = await apiRequest(`/api/admin/app-accounts/check/${app.id}`);
            const accountData = await accountResponse.json();
            return {
              ...app,
              hasAccount: accountData.exists || false
            };
          } catch (error) {
            return {
              ...app,
              hasAccount: false
            };
          }
        })
      );

      return appsWithAccountStatus;
    }
  });

  // Create password mutation
  const createPasswordMutation = useMutation({
    mutationFn: async ({ appId, password }: { appId: number; password: string }) => {
      const response = await apiRequest('/api/admin/app-accounts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          appId,
          username: appsQuery.data?.find((app: any) => app.id === appId)?.name || '',
          password
        }),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Password created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/my-apps'] });
      setPasswords({});
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create password",
        variant: "destructive",
      });
    }
  });

  // Reset password mutation
  const resetPasswordMutation = useMutation({
    mutationFn: async (appId: number) => {
      const response = await apiRequest(`/api/admin/app-accounts/${appId}/reset-password`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: 'mygroup123' }),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Password reset to 'mygroup123' successfully",
      });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to reset password",
        variant: "destructive",
      });
    }
  });

  const handleCreatePassword = (appId: number) => {
    const app = appsQuery.data?.find((app: any) => app.id === appId);

    // Check if account already exists
    if (app?.hasAccount) {
      toast({
        title: "Error",
        description: "Account already exists for this app. Use reset to change password.",
        variant: "destructive",
      });
      return;
    }

    const password = passwords[appId];
    if (!password) {
      toast({
        title: "Error",
        description: "Please enter a password",
        variant: "destructive",
      });
      return;
    }
    createPasswordMutation.mutate({ appId, password });
  };

  const handleResetPassword = (appId: number) => {
    if (confirm("Are you sure you want to reset the password to 'mygroup123'?")) {
      resetPasswordMutation.mutate(appId);
    }
  };

  return (
    <DashboardLayout 
      title="Admin Settings" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">
                <i className="bi bi-gear me-2"></i>
                App Account Management - My Apps
              </h5>
              <p className="text-muted mb-0">Manage passwords for My Apps applications</p>
            </div>
            <div className="card-body">
              {appsQuery.isLoading ? (
                <div className="text-center py-4">
                  <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : appsQuery.error ? (
                <div className="alert alert-danger">
                  Error loading apps: {(appsQuery.error as any).message}
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>App Name</th>
                        <th>Username</th>
                        <th>Account Status</th>
                        <th>Password</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {appsQuery.data?.map((app: any) => (
                        <tr key={app.id}>
                          <td>
                            <div className="d-flex align-items-center">
                              <div className="bg-primary rounded-circle p-2 me-3">
                                <i className="bi bi-app text-white"></i>
                              </div>
                              <strong>{app.name}</strong>
                            </div>
                          </td>
                          <td>
                            <code>{app.name}</code>
                          </td>
                          <td>
                            <span className={`badge ${app.hasAccount ? 'bg-success' : 'bg-warning'}`}>
                              {app.hasAccount ? 'Account Created' : 'No Account'}
                            </span>
                          </td>
                          <td>
                            <Input
                              type="password"
                              placeholder={app.hasAccount ? "Use reset to change" : "Enter password"}
                              value={passwords[app.id] || ''}
                              onChange={(e) => setPasswords(prev => ({
                                ...prev,
                                [app.id]: e.target.value
                              }))}
                              className="form-control-sm"
                              disabled={app.hasAccount}
                            />
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <Button
                                size="sm"
                                onClick={() => handleCreatePassword(app.id)}
                                disabled={createPasswordMutation.isPending || app.hasAccount}
                                variant={app.hasAccount ? "secondary" : "default"}
                              >
                                {createPasswordMutation.isPending ? (
                                  <>
                                    <span className="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Creating...
                                  </>
                                ) : app.hasAccount ? (
                                  <>
                                    <i className="bi bi-check-circle me-1"></i>
                                    Created
                                  </>
                                ) : (
                                  <>
                                    <i className="bi bi-plus-circle me-1"></i>
                                    Create
                                  </>
                                )}
                              </Button>

                              <Button
                                variant="outline"
                                size="sm"
                                onClick={() => handleResetPassword(app.id)}
                                disabled={resetPasswordMutation.isPending}
                                className="text-warning"
                              >
                                {resetPasswordMutation.isPending ? (
                                  <>
                                    <span className="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Resetting...
                                  </>
                                ) : (
                                  <>
                                    <i className="bi bi-arrow-clockwise me-1"></i>
                                    Reset
                                  </>
                                )}
                              </Button>
                            </div>
                            <div className="mt-1">
                              <small className="text-muted">
                                Reset sets to 'mygroup123'
                              </small>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}

              {appsQuery.data && appsQuery.data.length === 0 && (
                <div className="text-center py-5">
                  <div className="mb-3">
                    <i className="bi bi-app-indicator display-1 text-muted"></i>
                  </div>
                  <h5 className="text-muted">No My Apps Found</h5>
                  <p className="text-muted">No applications found in the 'My Apps' category.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}

export default AdminSettings;
