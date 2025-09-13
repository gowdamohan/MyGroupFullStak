import React, { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";

interface CorporateUser {
  id: number;
  name: string;
  mobile: string;
  email: string;
  username: string;
  created_at: string;
  is_active: boolean;
}

function AdminCorporateLogin() {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingUser, setEditingUser] = useState<CorporateUser | null>(null);
  const [showResetModal, setShowResetModal] = useState(false);
  const [resetUserId, setResetUserId] = useState<number | null>(null);
  const { toast } = useToast();

  const [corporateForm, setCorporateForm] = useState({
    name: '',
    mobile: '',
    email: '',
    username: '',
    password: ''
  });

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Create Category', path: '/dashboard/admin/categories' },
    { icon: 'bi-gear', label: 'Admin Settings', path: '/dashboard/admin/admin-settings' },
    { icon: 'bi-box-arrow-in-right', label: 'Corporate Login', path: '/dashboard/admin/corporate-login', active: true },
    { icon: 'bi-box-arrow-right', label: 'Logout' },
  ];

  // Fetch corporate users
  const corporatesQuery = useQuery({
    queryKey: ['/api/admin/corporate-users'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/corporate-users');
      return response.json();
    }
  });

  // Create corporate user mutation
  const createUserMutation = useMutation({
    mutationFn: async (userData: any) => {
      const response = await apiRequest('/api/admin/corporate-users', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(userData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Corporate user created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/corporate-users'] });
      setShowCreateModal(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create user",
        variant: "destructive",
      });
    }
  });

  // Update corporate user mutation
  const updateUserMutation = useMutation({
    mutationFn: async ({ id, ...userData }: any) => {
      const response = await apiRequest(`/api/admin/corporate-users/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(userData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Corporate user updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/corporate-users'] });
      setShowCreateModal(false);
      setEditingUser(null);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update user",
        variant: "destructive",
      });
    }
  });

  // Reset password mutation
  const resetPasswordMutation = useMutation({
    mutationFn: async (userId: number) => {
      const response = await apiRequest(`/api/admin/corporate-users/${userId}/reset-password`, {
        method: 'POST',
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Password reset successfully",
      });
      setShowResetModal(false);
      setResetUserId(null);
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to reset password",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setCorporateForm({
      name: '',
      mobile: '',
      email: '',
      username: '',
      password: ''
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (editingUser) {
      updateUserMutation.mutate({ id: editingUser.id, ...corporateForm });
    } else {
      createUserMutation.mutate(corporateForm);
    }
  };

  const handleEdit = (user: CorporateUser) => {
    setEditingUser(user);
    setCorporateForm({
      name: user.name,
      mobile: user.mobile,
      email: user.email,
      username: user.username,
      password: '' // Don't populate password for security
    });
    setShowCreateModal(true);
  };

  const handleResetPassword = (userId: number) => {
    setResetUserId(userId);
    setShowResetModal(true);
  };

  return (
    <DashboardLayout
      title="Corporate Login Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 className="card-title mb-0">
                  <i className="bi bi-building me-2"></i>
                  Corporate Users
                </h5>
                <p className="text-muted mb-0">Manage corporate login accounts</p>
              </div>
              <Button onClick={() => setShowCreateModal(true)}>
                <i className="bi bi-plus-lg me-2"></i>
                Add Corporate User
              </Button>
            </div>
            <div className="card-body">
              {corporatesQuery.isLoading ? (
                <div className="text-center py-4">
                  <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : corporatesQuery.error ? (
                <div className="alert alert-danger">
                  Error loading users: {(corporatesQuery.error as any).message}
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {corporatesQuery.data?.map((user: CorporateUser) => (
                        <tr key={user.id}>
                          <td>{user.id}</td>
                          <td>
                            <strong>{user.name}</strong>
                          </td>
                          <td>{user.mobile}</td>
                          <td>{user.email}</td>
                          <td>
                            <code>{user.username}</code>
                          </td>
                          <td>
                            <span className={`badge ${user.is_active ? 'bg-success' : 'bg-danger'}`}>
                              {user.is_active ? 'Active' : 'Inactive'}
                            </span>
                          </td>
                          <td>
                            {new Date(user.created_at).toLocaleDateString()}
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <Button
                                variant="outline"
                                size="sm"
                                onClick={() => handleEdit(user)}
                              >
                                <i className="bi bi-pencil"></i>
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                onClick={() => handleResetPassword(user.id)}
                                className="text-warning"
                              >
                                <i className="bi bi-key"></i>
                              </Button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}

              {corporatesQuery.data && corporatesQuery.data.length === 0 && (
                <div className="text-center py-5">
                  <div className="mb-3">
                    <i className="bi bi-building display-1 text-muted"></i>
                  </div>
                  <h5 className="text-muted">No Corporate Users</h5>
                  <p className="text-muted">No corporate users have been created yet.</p>
                  <Button onClick={() => setShowCreateModal(true)}>
                    <i className="bi bi-plus-lg me-2"></i>
                    Create First User
                  </Button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Create/Edit User Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>
              {editingUser ? 'Edit Corporate User' : 'Create Corporate User'}
            </DialogTitle>
            <DialogDescription>
              {editingUser ? 'Update corporate user information' : 'Create a new corporate login account'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Label htmlFor="name">Name</Label>
              <Input
                id="name"
                value={corporateForm.name}
                onChange={(e) => setCorporateForm({ ...corporateForm, name: e.target.value })}
                placeholder="Full Name"
                required
              />
            </div>

            <div>
              <Label htmlFor="mobile">Mobile Number</Label>
              <Input
                id="mobile"
                value={corporateForm.mobile}
                onChange={(e) => setCorporateForm({ ...corporateForm, mobile: e.target.value })}
                placeholder="+1234567890"
                required
              />
            </div>

            <div>
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                type="email"
                value={corporateForm.email}
                onChange={(e) => setCorporateForm({ ...corporateForm, email: e.target.value })}
                placeholder="user@company.com"
                required
              />
            </div>

            <div>
              <Label htmlFor="username">Username</Label>
              <Input
                id="username"
                value={corporateForm.username}
                onChange={(e) => setCorporateForm({ ...corporateForm, username: e.target.value })}
                placeholder="username"
                required
              />
            </div>

            <div>
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                type="password"
                value={corporateForm.password}
                onChange={(e) => setCorporateForm({ ...corporateForm, password: e.target.value })}
                placeholder="Password"
                required={!editingUser}
              />
              {editingUser && (
                <small className="text-muted">Leave blank to keep current password</small>
              )}
            </div>

            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => {
                  setShowCreateModal(false);
                  setEditingUser(null);
                  resetForm();
                }}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={createUserMutation.isPending || updateUserMutation.isPending}
              >
                {editingUser ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Reset Password Modal */}
      <Dialog open={showResetModal} onOpenChange={setShowResetModal}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Reset Password</DialogTitle>
            <DialogDescription>
              Are you sure you want to reset this user's password? A new temporary password will be generated.
            </DialogDescription>
          </DialogHeader>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => {
                setShowResetModal(false);
                setResetUserId(null);
              }}
            >
              Cancel
            </Button>
            <Button
              onClick={() => {
                if (resetUserId) {
                  resetPasswordMutation.mutate(resetUserId);
                }
              }}
              disabled={resetPasswordMutation.isPending}
            >
              {resetPasswordMutation.isPending ? 'Resetting...' : 'Reset Password'}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

export default AdminCorporateLogin;