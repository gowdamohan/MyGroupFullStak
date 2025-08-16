import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import { DataTable, type Column } from "@/components/ui/data-table";
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
import { Badge } from "@/components/ui/badge";
import type { Continent, ContinentInput } from "@shared/schema";

interface ContinentManagementProps {
  className?: string;
}

export default function ContinentManagement({ className = "" }: ContinentManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingContinent, setEditingContinent] = useState<Continent | null>(null);
  const { toast } = useToast();

  console.log('🌍 ContinentManagement component rendered');

  const [continentForm, setContinentForm] = useState({
    continent: '',
    code: '',
    order: 0,
    status: 1,
  });

  // Fetch continents
  const continentsQuery = useQuery({
    queryKey: ['continents-list'],
    queryFn: async () => {
      try {
        console.log('🌍 Making API call to /api/admin/continents');
        const response = await apiRequest('/api/admin/continents');
        const data = await response.json();
        console.log('🌍 Continents API Response:', data);
        console.log('🌍 Response type:', typeof data);
        console.log('🌍 Is array:', Array.isArray(data));
        if (Array.isArray(data) && data.length > 0) {
          console.log('🌍 First item:', data[0]);
          console.log('🌍 First item keys:', Object.keys(data[0]));
        }
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('🌍 Error fetching continents:', error);
        return [];
      }
    },
    retry: false,
  });

  // Create continent mutation
  const createContinentMutation = useMutation({
    mutationFn: async (data: ContinentInput) => {
      return apiRequest('/api/admin/continents', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent created successfully",
      });
      setShowCreateModal(false);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
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
    mutationFn: async ({ id, data }: { id: number; data: ContinentInput }) => {
      return apiRequest(`/api/admin/continents/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent updated successfully",
      });
      setEditingContinent(null);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
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
    mutationFn: async (id: number) => {
      return apiRequest(`/api/admin/continents/${id}`, {
        method: 'DELETE'
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Continent deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/continents'] });
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
      code: '',
      order: 0,
      status: 1,
    });
  };

  const handleCreate = () => {
    setEditingContinent(null);
    resetForm();
    setShowCreateModal(true);
  };

  const handleEdit = (continent: any) => {
    setEditingContinent(continent);
    setContinentForm({
      continent: continent.continent || '',
      code: continent.code || '',
      order: continent.order || 0,
      status: continent.status || 1,
    });
    setShowCreateModal(true);
  };

  const handleDelete = (continent: any) => {
    if (window.confirm(`Are you sure you want to delete "${continent.continent}"?`)) {
      deleteContinentMutation.mutate(continent.id);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!continentForm.continent.trim() || !continentForm.code.trim()) {
      toast({
        title: "Error",
        description: "Please fill in all required fields",
        variant: "destructive",
      });
      return;
    }

    if (editingContinent) {
      updateContinentMutation.mutate({
        id: editingContinent.id,
        data: continentForm
      });
    } else {
      createContinentMutation.mutate(continentForm);
    }
  };

  const columns: Column<any>[] = [
    {
      key: 'continent',
      header: 'Continent Name',
      sortable: true,
    },
    {
      key: 'code',
      header: 'Code',
      render: (value) => (
        <Badge variant="outline">{value}</Badge>
      ),
    },
    {
      key: 'order',
      header: 'Order',
    },
    {
      key: 'status',
      header: 'Status',
      render: (value) => (
        <Badge variant={value === 1 ? "default" : "secondary"}>
          {value === 1 ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
  ];

  return (
    <div className={`space-y-6 ${className}`}>
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold tracking-tight">Continent Management</h2>
          <p className="text-muted-foreground">
            Manage continents for location hierarchy
          </p>
        </div>
      </div>

      <DataTable
        data={(() => {
          const data = continentsQuery.data || [];
          console.log('🌍 DataTable data:', data);
          console.log('🌍 DataTable data length:', data.length);
          return data;
        })()}
        columns={columns}
        loading={continentsQuery.isLoading}
        onAdd={handleCreate}
        onEdit={handleEdit}
        onDelete={handleDelete}
        addButtonText="Add Continent"
        emptyMessage="No continents found"
      />

      {/* Create/Edit Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-[425px]">
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>
                {editingContinent ? 'Edit Continent' : 'Create New Continent'}
              </DialogTitle>
              <DialogDescription>
                {editingContinent 
                  ? 'Update the continent information below.'
                  : 'Add a new continent to the system.'
                }
              </DialogDescription>
            </DialogHeader>
            
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label htmlFor="continent">Continent Name *</Label>
                <Input
                  id="continent"
                  value={continentForm.continent}
                  onChange={(e) => setContinentForm(prev => ({ ...prev, continent: e.target.value }))}
                  placeholder="e.g., Asia, Europe, North America"
                  required
                />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="code">Continent Code *</Label>
                <Input
                  id="code"
                  value={continentForm.code}
                  onChange={(e) => setContinentForm(prev => ({ ...prev, code: e.target.value.toUpperCase() }))}
                  placeholder="e.g., AS, EU, NA"
                  maxLength={10}
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="order">Order</Label>
                  <Input
                    id="order"
                    type="number"
                    value={continentForm.order}
                    onChange={(e) => setContinentForm(prev => ({ ...prev, order: parseInt(e.target.value) || 0 }))}
                    placeholder="0"
                  />
                </div>

                <div className="grid gap-2">
                  <Label htmlFor="status">Status</Label>
                  <select
                    id="status"
                    className="form-select"
                    value={continentForm.status}
                    onChange={(e) => setContinentForm(prev => ({ ...prev, status: parseInt(e.target.value) }))}
                  >
                    <option value={1}>Active</option>
                    <option value={0}>Inactive</option>
                  </select>
                </div>
              </div>
            </div>
            
            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => setShowCreateModal(false)}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={createContinentMutation.isPending || updateContinentMutation.isPending}
              >
                {editingContinent ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
