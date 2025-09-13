import React, { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { adminMenuItems, getActiveMenuItem } from "@/config/admin_menu";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Modal, ModalFooter, useModal } from "@/components/ui/modal";

interface AppCreatedData {
  id: number;
  name: string;
  apps_name: string;
  order_by: number;
  code: string | null;
  created_at: string;
}

function ProfileAppCreated() {
  const { toast } = useToast();
  const menuItems = getActiveMenuItem('/dashboard/admin/profile/app-created');
  const appModal = useModal();
  const deleteModal = useModal();
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [isEditing, setIsEditing] = useState(false);

  const [appForm, setAppForm] = useState({
    name: '',
    apps_name: 'My Apps',
    order_by: 1,
    code: ''
  });

  // Fetch app created
  const appsQuery = useQuery({
    queryKey: ['/api/admin/app-create'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/app-create');
      return response.json();
    }
  });

  // Create app mutation
  const createAppMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await apiRequest('/api/admin/app-create', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-create'] });
      appModal.close();
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create app",
        variant: "destructive",
      });
    }
  });

  // Update app mutation
  const updateAppMutation = useMutation({
    mutationFn: async ({ id, ...data }: any) => {
      const response = await apiRequest(`/api/admin/app-create/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-create'] });
      appModal.close();
      setIsEditing(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update app",
        variant: "destructive",
      });
    }
  });

  // Delete app mutation
  const deleteAppMutation = useMutation({
    mutationFn: (id: number) => apiRequest(`/api/admin/app-create/${id}`, { method: 'DELETE' }),
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-create'] });
      deleteModal.close();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete app",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setAppForm({
      name: '',
      apps_name: 'My Apps',
      order_by: 1,
      code: ''
    });
    setSelectedItem(null);
    setIsEditing(false);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (isEditing && selectedItem) {
      updateAppMutation.mutate({ id: selectedItem.id, ...appForm });
    } else {
      createAppMutation.mutate(appForm);
    }
  };

  const handleEdit = (app: AppCreatedData) => {
    setSelectedItem(app);
    setAppForm({
      name: app.name,
      apps_name: app.apps_name,
      order_by: app.order_by,
      code: app.code || ''
    });
    setIsEditing(true);
    appModal.open();
  };



  return (
    <DashboardLayout 
      title="App Created Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">
                <i className="bi bi-app me-2"></i>
                App Created Management
              </h5>
            </div>
            <div className="card-body">
              <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h6 className="mb-0">App List</h6>
                  <small className="text-muted">Manage your created apps</small>
                </div>
                <Button onClick={() => {
                  resetForm();
                  appModal.open();
                }}>
                  <i className="bi bi-plus-lg me-2"></i>
                  Add App
                </Button>
              </div>

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
                        <th>ID</th>
                        <th>App Name</th>
                        <th>Group Name</th>
                        <th>Order</th>
                        <th>Code</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {appsQuery.data?.map((app: AppCreatedData) => (
                        <tr key={app.id}>
                          <td>{app.id}</td>
                          <td><strong>{app.name}</strong></td>
                          <td>{app.apps_name}</td>
                          <td>{app.order_by}</td>
                          <td><code>{app.code}</code></td>
                          <td>
                            {new Date(app.created_at).toLocaleDateString()}
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <Button variant="outline" size="sm" onClick={() => handleEdit(app)}>
                                <i className="bi bi-pencil"></i>
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="text-danger"
                                onClick={() => {
                                  setSelectedItem(app);
                                  deleteModal.open();
                                }}
                              >
                                <i className="bi bi-trash"></i>
                              </Button>
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
                    <i className="bi bi-app display-1 text-muted"></i>
                  </div>
                  <h5 className="text-muted">No Apps Found</h5>
                  <p className="text-muted">No apps have been created yet.</p>
                  <Button onClick={() => {
                    resetForm();
                    appModal.open();
                  }}>
                    <i className="bi bi-plus-lg me-2"></i>
                    Create First App
                  </Button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* App Modal */}
      <Modal
        isOpen={appModal.isOpen}
        onClose={appModal.close}
        title={isEditing ? "Edit App" : "Add App"}
        size="md"
      >
        <form onSubmit={handleSubmit}>
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">App Name</Label>
              <Input
                id="name"
                value={appForm.name}
                onChange={(e) => setAppForm(prev => ({ ...prev, name: e.target.value }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="apps_name">Group Name</Label>
              <select
                id="apps_name"
                className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                value={appForm.apps_name}
                onChange={(e) => setAppForm(prev => ({ ...prev, apps_name: e.target.value }))}
                required
              >
                <option value="My Apps">My Apps</option>
                <option value="My Company">My Company</option>
                <option value="My Online Apps">My Online Apps</option>
                <option value="My Offline Apps">My Offline Apps</option>
              </select>
            </div>
            <div>
              <Label htmlFor="order_by">Order</Label>
              <Input
                id="order_by"
                type="number"
                value={appForm.order_by}
                onChange={(e) => setAppForm(prev => ({ ...prev, order_by: parseInt(e.target.value) }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="code">Code</Label>
              <Input
                id="code"
                value={appForm.code}
                onChange={(e) => setAppForm(prev => ({ ...prev, code: e.target.value }))}
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={appModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createAppMutation.isPending || updateAppMutation.isPending}>
              {isEditing ? "Update" : "Create"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* Delete Modal */}
      <Modal
        isOpen={deleteModal.isOpen}
        onClose={deleteModal.close}
        title="Delete App"
        size="sm"
      >
        <p>Are you sure you want to delete this app? This action cannot be undone.</p>
        <ModalFooter>
          <Button variant="outline" onClick={deleteModal.close}>
            Cancel
          </Button>
          <Button 
            variant="destructive" 
            onClick={() => {
              if (selectedItem) {
                deleteAppMutation.mutate(selectedItem.id);
              }
            }}
            disabled={deleteAppMutation.isPending}
          >
            Delete
          </Button>
        </ModalFooter>
      </Modal>
    </DashboardLayout>
  );
}

export default ProfileAppCreated;
