import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import { DataTable, type Column } from "@/components/ui/data-table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Badge } from "@/components/ui/badge";
import type { State, StateInput, Country } from "@shared/schema";

interface StateManagementProps {
  className?: string;
}

export default function StateManagement({ className = "" }: StateManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingState, setEditingState] = useState<State | null>(null);
  const { toast } = useToast();

  const [stateForm, setStateForm] = useState<StateInput>({
    countryId: 0,
    state: '',
    order: 0,
    status: 1,
    code: '',
  });

  // Fetch states
  const statesQuery = useQuery({
    queryKey: ['states-list'],
    queryFn: async () => {
      try {
        const response = await apiRequest('/api/admin/states');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error fetching states:', error);
        return [];
      }
    },
    retry: false,
  });

  // Fetch countries for dropdown
  const countriesQuery = useQuery({
    queryKey: ['countries-dropdown'],
    queryFn: async () => {
      try {
        const response = await apiRequest('/api/admin/countries');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error fetching countries:', error);
        return [];
      }
    },
    retry: false,
  });

  // Create state mutation
  const createStateMutation = useMutation({
    mutationFn: async (data: StateInput) => {
      return apiRequest('/api/admin/states', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "State created successfully",
      });
      setShowCreateModal(false);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/states'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create state",
        variant: "destructive",
      });
    }
  });

  // Update state mutation
  const updateStateMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: StateInput }) => {
      return apiRequest(`/api/admin/states/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "State updated successfully",
      });
      setEditingState(null);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/states'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update state",
        variant: "destructive",
      });
    }
  });

  // Delete state mutation
  const deleteStateMutation = useMutation({
    mutationFn: async (id: number) => {
      return apiRequest(`/api/admin/states/${id}`, {
        method: 'DELETE'
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "State deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/states'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete state",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setStateForm({
      countryId: 0,
      state: '',
      order: 0,
      status: 1,
      code: '',
    });
  };

  const handleCreate = () => {
    setEditingState(null);
    resetForm();
    setShowCreateModal(true);
  };

  const handleEdit = (state: State) => {
    setEditingState(state);
    setStateForm({
      countryId: state.countryId,
      state: state.state,
      order: state.order || 0,
      status: state.status || 1,
      code: state.code,
    });
    setShowCreateModal(true);
  };

  const handleDelete = (state: State) => {
    if (window.confirm(`Are you sure you want to delete "${state.state}"?`)) {
      deleteStateMutation.mutate(state.id);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!stateForm.state.trim() || !stateForm.code.trim() || stateForm.countryId === 0) {
      toast({
        title: "Error",
        description: "Please fill in all required fields",
        variant: "destructive",
      });
      return;
    }

    if (editingState) {
      updateStateMutation.mutate({
        id: editingState.id,
        data: stateForm
      });
    } else {
      createStateMutation.mutate(stateForm);
    }
  };

  const columns: Column<any>[] = [
    {
      key: 'state',
      header: 'State/Province',
      sortable: true,
    },
    {
      key: 'country_name',
      header: 'Country',
      render: (value) => (
        <Badge variant="secondary">{value}</Badge>
      ),
    },
    {
      key: 'continent_name',
      header: 'Continent',
      render: (value) => (
        <Badge variant="outline">{value}</Badge>
      ),
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
          <h2 className="text-2xl font-bold tracking-tight">State Management</h2>
          <p className="text-muted-foreground">
            Manage states/provinces with country associations
          </p>
        </div>
      </div>

      <DataTable
        data={statesQuery.data || []}
        columns={columns}
        loading={statesQuery.isLoading}
        onAdd={handleCreate}
        onEdit={handleEdit}
        onDelete={handleDelete}
        addButtonText="Add State"
        emptyMessage="No states found"
      />

      {/* Create/Edit Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-[500px]">
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>
                {editingState ? 'Edit State' : 'Create New State'}
              </DialogTitle>
              <DialogDescription>
                {editingState 
                  ? 'Update the state information below.'
                  : 'Add a new state/province to the system.'
                }
              </DialogDescription>
            </DialogHeader>
            
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label htmlFor="country">Country *</Label>
                <Select
                  value={stateForm.countryId.toString()}
                  onValueChange={(value) => setStateForm(prev => ({ ...prev, countryId: parseInt(value) }))}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Select country" />
                  </SelectTrigger>
                  <SelectContent>
                    {countriesQuery.data?.map((country: any) => (
                      <SelectItem key={country.id} value={country.id.toString()}>
                        {country.country}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              
              <div className="grid gap-2">
                <Label htmlFor="state">State/Province Name *</Label>
                <Input
                  id="state"
                  value={stateForm.state}
                  onChange={(e) => setStateForm(prev => ({ ...prev, state: e.target.value }))}
                  placeholder="e.g., California, Maharashtra"
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="code">State Code *</Label>
                  <Input
                    id="code"
                    value={stateForm.code}
                    onChange={(e) => setStateForm(prev => ({ ...prev, code: e.target.value.toUpperCase() }))}
                    placeholder="e.g., CA, MH"
                    maxLength={10}
                    required
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="order">Order</Label>
                  <Input
                    id="order"
                    type="number"
                    value={stateForm.order}
                    onChange={(e) => setStateForm(prev => ({ ...prev, order: parseInt(e.target.value) || 0 }))}
                    placeholder="0"
                  />
                </div>
              </div>

              <div className="grid gap-2">
                <Label htmlFor="status">Status</Label>
                <Select
                  value={stateForm.status.toString()}
                  onValueChange={(value) => setStateForm(prev => ({ ...prev, status: parseInt(value) }))}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="1">Active</SelectItem>
                    <SelectItem value="0">Inactive</SelectItem>
                  </SelectContent>
                </Select>
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
                disabled={createStateMutation.isPending || updateStateMutation.isPending}
              >
                {editingState ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
