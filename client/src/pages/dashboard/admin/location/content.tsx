import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { adminMenuItems, getActiveMenuItem } from "@/config/admin_menu";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Modal, ModalFooter, useModal } from "@/components/ui/modal";

interface ContinentData {
  id: number;
  continent: string;
  code: string;
  created_at: string;
}

function LocationContent() {
  const { toast } = useToast();
  const menuItems = getActiveMenuItem('/dashboard/admin/location/content');
  const continentModal = useModal();
  const deleteModal = useModal();
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [isEditing, setIsEditing] = useState(false);

  const [continentForm, setContinentForm] = useState({
    continent: '',
    code: ''
  });

  // Fetch continents
  const continentsQuery = useQuery({
    queryKey: ['/api/admin/continents'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/continents');
      return response.json();
    }
  });

  // Create continent mutation
  const createContinentMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await apiRequest('/api/admin/continents', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
      continentModal.close();
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create continent",
        variant: "destructive",
      });
    }
  });

  // Update continent mutation
  const updateContinentMutation = useMutation({
    mutationFn: async ({ id, ...data }: any) => {
      const response = await apiRequest(`/api/admin/continents/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
      continentModal.close();
      setIsEditing(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update continent",
        variant: "destructive",
      });
    }
  });

  // Delete continent mutation
  const deleteContinentMutation = useMutation({
    mutationFn: (id: number) => apiRequest(`/api/admin/continents/${id}`, { method: 'DELETE' }),
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
      deleteModal.close();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete continent",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setContinentForm({
      continent: '',
      code: ''
    });
    setSelectedItem(null);
    setIsEditing(false);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (isEditing && selectedItem) {
      updateContinentMutation.mutate({ id: selectedItem.id, ...continentForm });
    } else {
      createContinentMutation.mutate(continentForm);
    }
  };

  const handleEdit = (continent: ContinentData) => {
    setSelectedItem(continent);
    setContinentForm({
      continent: continent.continent,
      code: continent.code
    });
    setIsEditing(true);
    continentModal.open();
  };



  return (
    <DashboardLayout 
      title="Location Content Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-12">
          <div className="card">
            <div className="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 className="card-title mb-0">
                  <i className="bi bi-globe me-2"></i>
                  Continent Management
                </h5>
                <p className="text-muted mb-0">Manage continent data for location hierarchy</p>
              </div>
              <Button onClick={() => {
                resetForm();
                continentModal.open();
              }}>
                <i className="bi bi-plus-lg me-2"></i>
                Add Continent
              </Button>
            </div>
            <div className="card-body">
              {continentsQuery.isLoading ? (
                <div className="text-center py-4">
                  <div className="spinner-border" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : continentsQuery.error ? (
                <div className="alert alert-danger">
                  Error loading continents: {(continentsQuery.error as any).message}
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Continent</th>
                        <th>Code</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {continentsQuery.data?.map((continent: ContinentData) => (
                        <tr key={continent.id}>
                          <td>{continent.id}</td>
                          <td><strong>{continent.continent}</strong></td>
                          <td><code>{continent.code}</code></td>
                          <td>
                            {new Date(continent.created_at).toLocaleDateString()}
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <Button variant="outline" size="sm" onClick={() => handleEdit(continent)}>
                                <i className="bi bi-pencil"></i>
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="text-danger"
                                onClick={() => {
                                  setSelectedItem(continent);
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

              {continentsQuery.data && continentsQuery.data.length === 0 && (
                <div className="text-center py-5">
                  <div className="mb-3">
                    <i className="bi bi-globe display-1 text-muted"></i>
                  </div>
                  <h5 className="text-muted">No Continents Found</h5>
                  <p className="text-muted">No continents have been added yet.</p>
                  <Button onClick={() => {
                    resetForm();
                    continentModal.open();
                  }}>
                    <i className="bi bi-plus-lg me-2"></i>
                    Add First Continent
                  </Button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Continent Modal */}
      <Modal
        isOpen={continentModal.isOpen}
        onClose={continentModal.close}
        title={isEditing ? "Edit Continent" : "Add Continent"}
        size="md"
      >
        <form onSubmit={handleSubmit}>
          <div className="space-y-4">
            <div>
              <Label htmlFor="continent">Continent Name</Label>
              <Input
                id="continent"
                value={continentForm.continent}
                onChange={(e) => setContinentForm(prev => ({ ...prev, continent: e.target.value }))}
                required
                placeholder="e.g., Asia, Europe, Africa"
              />
            </div>
            <div>
              <Label htmlFor="code">Code</Label>
              <Input
                id="code"
                value={continentForm.code}
                onChange={(e) => setContinentForm(prev => ({ ...prev, code: e.target.value.toUpperCase() }))}
                required
                placeholder="e.g., AS, EU, AF"
                maxLength={3}
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={continentModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createContinentMutation.isPending || updateContinentMutation.isPending}>
              {isEditing ? "Update" : "Create"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* Delete Modal */}
      <Modal
        isOpen={deleteModal.isOpen}
        onClose={deleteModal.close}
        title="Delete Continent"
        size="sm"
      >
        <p>Are you sure you want to delete this continent? This action cannot be undone.</p>
        <ModalFooter>
          <Button variant="outline" onClick={deleteModal.close}>
            Cancel
          </Button>
          <Button 
            variant="destructive" 
            onClick={() => {
              if (selectedItem) {
                deleteContinentMutation.mutate(selectedItem.id);
              }
            }}
            disabled={deleteContinentMutation.isPending}
          >
            Delete
          </Button>
        </ModalFooter>
      </Modal>
    </DashboardLayout>
  );
}

export default LocationContent;
