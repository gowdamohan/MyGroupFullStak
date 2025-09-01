import { useState } from "react";
import { useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { adminMenuItems, getActiveMenuItem } from "@/config/admin_menu";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

function ProfileChangePassword() {
  const { toast } = useToast();
  const menuItems = getActiveMenuItem('/dashboard/admin/profile/change-password');

  const [passwordForm, setPasswordForm] = useState({
    oldPassword: '',
    newPassword: '',
    confirmPassword: ''
  });

  // Change password mutation
  const changePasswordMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await apiRequest('/api/admin/change-password', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Password changed successfully",
      });
      setPasswordForm({
        oldPassword: '',
        newPassword: '',
        confirmPassword: ''
      });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to change password",
        variant: "destructive",
      });
    }
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (passwordForm.newPassword !== passwordForm.confirmPassword) {
      toast({
        title: "Error",
        description: "New password and confirm password do not match",
        variant: "destructive",
      });
      return;
    }

    if (passwordForm.newPassword.length < 6) {
      toast({
        title: "Error",
        description: "New password must be at least 6 characters long",
        variant: "destructive",
      });
      return;
    }

    changePasswordMutation.mutate({
      oldPassword: passwordForm.oldPassword,
      newPassword: passwordForm.newPassword
    });
  };

  return (
    <DashboardLayout 
      title="Change Password" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row justify-content-center">
        <div className="col-md-6">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">
                <i className="bi bi-key me-2"></i>
                Change Password
              </h5>
              <p className="text-muted mb-0">Update your account password</p>
            </div>
            <div className="card-body">
              <form onSubmit={handleSubmit}>
                <div className="mb-3">
                  <Label htmlFor="oldPassword">Current Password</Label>
                  <Input
                    id="oldPassword"
                    type="password"
                    value={passwordForm.oldPassword}
                    onChange={(e) => setPasswordForm(prev => ({ ...prev, oldPassword: e.target.value }))}
                    required
                    placeholder="Enter your current password"
                  />
                </div>

                <div className="mb-3">
                  <Label htmlFor="newPassword">New Password</Label>
                  <Input
                    id="newPassword"
                    type="password"
                    value={passwordForm.newPassword}
                    onChange={(e) => setPasswordForm(prev => ({ ...prev, newPassword: e.target.value }))}
                    required
                    minLength={6}
                    placeholder="Enter your new password"
                  />
                  <small className="text-muted">Password must be at least 6 characters long</small>
                </div>

                <div className="mb-4">
                  <Label htmlFor="confirmPassword">Confirm New Password</Label>
                  <Input
                    id="confirmPassword"
                    type="password"
                    value={passwordForm.confirmPassword}
                    onChange={(e) => setPasswordForm(prev => ({ ...prev, confirmPassword: e.target.value }))}
                    required
                    minLength={6}
                    placeholder="Confirm your new password"
                  />
                </div>

                <div className="d-flex gap-2">
                  <Button 
                    type="submit" 
                    disabled={changePasswordMutation.isPending}
                    className="flex-1"
                  >
                    {changePasswordMutation.isPending ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                        Changing Password...
                      </>
                    ) : (
                      <>
                        <i className="bi bi-check-lg me-2"></i>
                        Change Password
                      </>
                    )}
                  </Button>
                  
                  <Button 
                    type="button" 
                    variant="outline"
                    onClick={() => setPasswordForm({
                      oldPassword: '',
                      newPassword: '',
                      confirmPassword: ''
                    })}
                  >
                    <i className="bi bi-arrow-clockwise me-2"></i>
                    Reset
                  </Button>
                </div>
              </form>

              <div className="mt-4 p-3 bg-light rounded">
                <h6 className="mb-2">
                  <i className="bi bi-info-circle me-2"></i>
                  Password Requirements
                </h6>
                <ul className="mb-0 small text-muted">
                  <li>At least 6 characters long</li>
                  <li>Must be different from your current password</li>
                  <li>Should contain a mix of letters and numbers for better security</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}

export default ProfileChangePassword;
