import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { apiRequest } from "@/lib/queryClient";
import { useToast } from "@/hooks/use-toast";

export default function AdminUsersPage() {
  const [selectedUser, setSelectedUser] = useState<any>(null);
  const [showModal, setShowModal] = useState(false);
  const { toast } = useToast();
  const queryClient = useQueryClient();

  // Fetch users
  const usersQuery = useQuery({
    queryKey: ['admin-users'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/users');
      return await response.json();
    }
  });

  // Delete user mutation
  const deleteUserMutation = useMutation({
    mutationFn: async (userId: number) => {
      const response = await apiRequest(`/api/admin/users/${userId}`, {
        method: 'DELETE'
      });
      return await response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "User deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['admin-users'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete user",
        variant: "destructive",
      });
    }
  });

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-people', label: 'User Management', path: '/dashboard/admin/users', active: true },
    { icon: 'bi-shield-check', label: 'Role Management', path: '/dashboard/admin/roles' },
    { icon: 'bi-building', label: 'Organization', path: '/dashboard/admin/organization' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content Management', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Categories' },
    { icon: 'bi-megaphone', label: 'Advertisements', path: '/dashboard/admin/ads' },
    { icon: 'bi-graph-up', label: 'Analytics & Reports', path: '/dashboard/admin/analytics' },
    { icon: 'bi-database', label: 'Database Management', path: '/dashboard/admin/database' },
    { icon: 'bi-shield-lock', label: 'Security', path: '/dashboard/admin/security' },
    { icon: 'bi-gear', label: 'System Settings', path: '/dashboard/admin/settings' },
    { icon: 'bi-box-arrow-in-right', label: 'Corporate Access', path: '/dashboard/admin/corporate-login' },
  ];

  const handleDeleteUser = (user: any) => {
    if (confirm(`Are you sure you want to delete user "${user.username}"?`)) {
      deleteUserMutation.mutate(user.id);
    }
  };

  const getRoleBadgeColor = (role: string) => {
    switch(role) {
      case 'admin': return 'danger';
      case 'corporate': return 'primary';
      case 'regional': return 'info';
      case 'branch': return 'success';
      default: return 'secondary';
    }
  };

  return (
    <DashboardLayout 
      title="User Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      {/* Header Actions */}
      <div className="row mb-4">
        <div className="col-12">
          <div className="card">
            <div className="card-body d-flex justify-content-between align-items-center">
              <h5 className="card-title mb-0">System Users</h5>
              <div className="d-flex gap-2">
                <button className="btn btn-outline-primary">
                  <i className="bi bi-download me-2"></i>Export
                </button>
                <button className="btn btn-primary">
                  <i className="bi bi-plus-lg me-2"></i>Add User
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Users Table */}
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <h6 className="card-title mb-0">All Users</h6>
            </div>
            <div className="card-body">
              {usersQuery.isLoading ? (
                <div className="text-center py-4">
                  <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : usersQuery.error ? (
                <div className="alert alert-danger">
                  Error loading users: {(usersQuery.error as any).message}
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {usersQuery.data?.map((user: any) => (
                        <tr key={user.id}>
                          <td>{user.id}</td>
                          <td>
                            <strong>{user.username}</strong>
                          </td>
                          <td>
                            {user.first_name} {user.last_name}
                          </td>
                          <td>{user.email}</td>
                          <td>
                            <span className={`badge bg-${getRoleBadgeColor(user.role_name || 'user')}`}>
                              {user.role_name || 'User'}
                            </span>
                          </td>
                          <td>
                            <span className={`badge ${user.is_active ? 'bg-success' : 'bg-danger'}`}>
                              {user.is_active ? 'Active' : 'Inactive'}
                            </span>
                          </td>
                          <td>
                            {user.last_login ? new Date(user.last_login).toLocaleDateString() : 'Never'}
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <button 
                                className="btn btn-outline-primary"
                                onClick={() => {
                                  setSelectedUser(user);
                                  setShowModal(true);
                                }}
                              >
                                <i className="bi bi-eye"></i>
                              </button>
                              <button className="btn btn-outline-warning">
                                <i className="bi bi-pencil"></i>
                              </button>
                              <button 
                                className="btn btn-outline-danger"
                                onClick={() => handleDeleteUser(user)}
                                disabled={deleteUserMutation.isPending}
                              >
                                <i className="bi bi-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* User Details Modal */}
      {showModal && selectedUser && (
        <div className="modal show d-block" tabIndex={-1}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">User Details</h5>
                <button 
                  type="button" 
                  className="btn-close" 
                  onClick={() => setShowModal(false)}
                ></button>
              </div>
              <div className="modal-body">
                <div className="row">
                  <div className="col-md-6">
                    <p><strong>Username:</strong> {selectedUser.username}</p>
                    <p><strong>Email:</strong> {selectedUser.email}</p>
                    <p><strong>Phone:</strong> {selectedUser.phone || 'N/A'}</p>
                    <p><strong>Role:</strong> 
                      <span className={`badge bg-${getRoleBadgeColor(selectedUser.role_name || 'user')} ms-2`}>
                        {selectedUser.role_name || 'User'}
                      </span>
                    </p>
                  </div>
                  <div className="col-md-6">
                    <p><strong>First Name:</strong> {selectedUser.first_name || 'N/A'}</p>
                    <p><strong>Last Name:</strong> {selectedUser.last_name || 'N/A'}</p>
                    <p><strong>Status:</strong> 
                      <span className={`badge ${selectedUser.is_active ? 'bg-success' : 'bg-danger'} ms-2`}>
                        {selectedUser.is_active ? 'Active' : 'Inactive'}
                      </span>
                    </p>
                    <p><strong>Created:</strong> {new Date(selectedUser.created_at).toLocaleDateString()}</p>
                  </div>
                </div>
              </div>
              <div className="modal-footer">
                <button 
                  type="button" 
                  className="btn btn-secondary" 
                  onClick={() => setShowModal(false)}
                >
                  Close
                </button>
                <button type="button" className="btn btn-primary">
                  Edit User
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}
