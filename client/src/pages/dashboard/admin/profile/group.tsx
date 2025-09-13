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

interface GroupData {
  id: number;
  name: string;
  appsName: string;
  order_by: number;
  code: string | null;
  created_at: string;
}

function ProfileGroup() {
  const { toast } = useToast();
  const menuItems = getActiveMenuItem('/dashboard/admin/profile/group');
  const groupModal = useModal();
  const deleteModal = useModal();
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [isEditing, setIsEditing] = useState(false);

  const [groupForm, setGroupForm] = useState({
    name: '',
    appsName: 'My Apps',
    order_by: 1,
    code: ''
  });

  // Fetch groups
  const groupsQuery = useQuery({
    queryKey: ['/api/admin/groups'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/groups');
      return response.json();
    }
  });

  // Create group mutation
  const createGroupMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await apiRequest('/api/admin/groups', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Group created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      groupModal.close();
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create group",
        variant: "destructive",
      });
    }
  });

  // Update group mutation
  const updateGroupMutation = useMutation({
    mutationFn: async ({ id, ...data }: any) => {
      const response = await apiRequest(`/api/admin/groups/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Group updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      groupModal.close();
      setIsEditing(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update group",
        variant: "destructive",
      });
    }
  });

  // Delete group mutation
  const deleteGroupMutation = useMutation({
    mutationFn: (id: number) => apiRequest(`/api/admin/groups/${id}`, { method: 'DELETE' }),
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Group deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      deleteModal.close();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete group",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setGroupForm({
      name: '',
      appsName: 'My Apps',
      order_by: 1,
      code: ''
    });
    setSelectedItem(null);
    setIsEditing(false);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (isEditing && selectedItem) {
      updateGroupMutation.mutate({ id: selectedItem.id, ...groupForm });
    } else {
      createGroupMutation.mutate(groupForm);
    }
  };

  const handleEdit = (group: GroupData) => {
    setSelectedItem(group);
    setGroupForm({
      name: group.name,
      appsName: group.appsName,
      order_by: group.order_by,
      code: group.code || ''
    });
    setIsEditing(true);
    groupModal.open();
  };



  return (
    <DashboardLayout 
      title="Group Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">
                <i className="bi bi-people me-2"></i>
                Group Management
              </h5>
            </div>
            <div className="card-body">
              <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h6 className="mb-0">Group List</h6>
                  <small className="text-muted">Manage your groups</small>
                </div>
                <Button onClick={() => {
                  resetForm();
                  groupModal.open();
                }}>
                  <i className="bi bi-plus-lg me-2"></i>
                  Add Group
                </Button>
              </div>

              {groupsQuery.isLoading ? (
                <div className="text-center py-4">
                  <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : groupsQuery.error ? (
                <div className="alert alert-danger">
                  Error loading groups: {(groupsQuery.error as any).message}
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Apps Name</th>
                        <th>Order</th>
                        <th>Code</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {groupsQuery.data?.map((group: GroupData) => (
                        <tr key={group.id}>
                          <td>{group.id}</td>
                          <td><strong>{group.name}</strong></td>
                          <td>{group.appsName}</td>
                          <td>{group.order_by}</td>
                          <td><code>{group.code}</code></td>
                          <td>
                            {new Date(group.created_at).toLocaleDateString()}
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <Button variant="outline" size="sm" onClick={() => handleEdit(group)}>
                                <i className="bi bi-pencil"></i>
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="text-danger"
                                onClick={() => {
                                  setSelectedItem(group);
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

              {groupsQuery.data && groupsQuery.data.length === 0 && (
                <div className="text-center py-5">
                  <div className="mb-3">
                    <i className="bi bi-people display-1 text-muted"></i>
                  </div>
                  <h5 className="text-muted">No Groups Found</h5>
                  <p className="text-muted">No groups have been created yet.</p>
                  <Button onClick={() => {
                    resetForm();
                    groupModal.open();
                  }}>
                    <i className="bi bi-plus-lg me-2"></i>
                    Create First Group
                  </Button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Group Modal */}
      <Modal
        isOpen={groupModal.isOpen}
        onClose={groupModal.close}
        title={isEditing ? "Edit Group" : "Add Group"}
        size="md"
      >
        <form onSubmit={handleSubmit}>
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">Group Name</Label>
              <Input
                id="name"
                value={groupForm.name}
                onChange={(e) => setGroupForm(prev => ({ ...prev, name: e.target.value }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="appsName">Apps Name</Label>
              <select
                id="appsName"
                className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                value={groupForm.appsName}
                onChange={(e) => setGroupForm(prev => ({ ...prev, appsName: e.target.value }))}
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
                value={groupForm.order_by}
                onChange={(e) => setGroupForm(prev => ({ ...prev, order_by: parseInt(e.target.value) }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="code">Code</Label>
              <Input
                id="code"
                value={groupForm.code}
                onChange={(e) => setGroupForm(prev => ({ ...prev, code: e.target.value }))}
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={groupModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createGroupMutation.isPending || updateGroupMutation.isPending}>
              {isEditing ? "Update" : "Create"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* Delete Modal */}
      <Modal
        isOpen={deleteModal.isOpen}
        onClose={deleteModal.close}
        title="Delete Group"
        size="sm"
      >
        <p>Are you sure you want to delete this group? This action cannot be undone.</p>
        <ModalFooter>
          <Button variant="outline" onClick={deleteModal.close}>
            Cancel
          </Button>
          <Button 
            variant="destructive" 
            onClick={() => {
              if (selectedItem) {
                deleteGroupMutation.mutate(selectedItem.id);
              }
            }}
            disabled={deleteGroupMutation.isPending}
          >
            Delete
          </Button>
        </ModalFooter>
      </Modal>
    </DashboardLayout>
  );
}

export default ProfileGroup;
