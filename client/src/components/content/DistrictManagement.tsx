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
import type { District, DistrictInput, State } from "@shared/schema";

interface DistrictManagementProps {
  className?: string;
}

export default function DistrictManagement({ className = "" }: DistrictManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingDistrict, setEditingDistrict] = useState<District | null>(null);
  const [selectedCountryId, setSelectedCountryId] = useState<number>(0);
  const { toast } = useToast();

  const [districtForm, setDistrictForm] = useState<DistrictInput>({
    stateId: 0,
    district: '',
    order: 0,
    status: 1,
    code: '',
  });

  // Fetch districts
  const districtsQuery = useQuery({
    queryKey: ['districts-list'],
    queryFn: async () => {
      try {
        const response = await apiRequest('/api/admin/districts');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error fetching districts:', error);
        return [];
      }
    },
    retry: false,
  });

  // Fetch countries for dropdown
  const countriesQuery = useQuery({
    queryKey: ['countries-dropdown-districts'],
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

  // Fetch states based on selected country
  const statesQuery = useQuery({
    queryKey: ['states-by-country', selectedCountryId],
    queryFn: async () => {
      if (!selectedCountryId) return [];
      try {
        const response = await apiRequest(`/api/admin/states/by-country/${selectedCountryId}`);
        const data = await response.json();
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error fetching states:', error);
        return [];
      }
    },
    enabled: selectedCountryId > 0,
    retry: false,
  });

  // Create district mutation
  const createDistrictMutation = useMutation({
    mutationFn: async (data: DistrictInput) => {
      return apiRequest('/api/admin/districts', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "District created successfully",
      });
      setShowCreateModal(false);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/districts'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create district",
        variant: "destructive",
      });
    }
  });

  // Update district mutation
  const updateDistrictMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: DistrictInput }) => {
      return apiRequest(`/api/admin/districts/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "District updated successfully",
      });
      setEditingDistrict(null);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/districts'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update district",
        variant: "destructive",
      });
    }
  });

  // Delete district mutation
  const deleteDistrictMutation = useMutation({
    mutationFn: async (id: number) => {
      return apiRequest(`/api/admin/districts/${id}`, {
        method: 'DELETE'
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "District deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/districts'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete district",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setDistrictForm({
      stateId: 0,
      district: '',
      order: 0,
      status: 1,
      code: '',
    });
    setSelectedCountryId(0);
  };

  const handleCreate = () => {
    setEditingDistrict(null);
    resetForm();
    setShowCreateModal(true);
  };

  const handleEdit = (district: District) => {
    setEditingDistrict(district);
    setDistrictForm({
      stateId: district.stateId || 0,
      district: district.district || '',
      order: district.order || 0,
      status: district.status || 1,
      code: district.code || '',
    });
    // We need to get the country from the state relationship
    setSelectedCountryId(0); // Will be set when we load the state data
    setShowCreateModal(true);
  };

  const handleDelete = (district: District) => {
    if (window.confirm(`Are you sure you want to delete "${district.district}"?`)) {
      deleteDistrictMutation.mutate(district.id);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!districtForm.district.trim() || !districtForm.code.trim() ||
        districtForm.stateId === 0) {
      toast({
        title: "Error",
        description: "Please fill in all required fields",
        variant: "destructive",
      });
      return;
    }

    if (editingDistrict) {
      updateDistrictMutation.mutate({
        id: editingDistrict.id,
        data: districtForm
      });
    } else {
      createDistrictMutation.mutate(districtForm);
    }
  };

  // Update country selection and reset state
  const handleCountryChange = (countryId: string) => {
    const id = parseInt(countryId);
    setSelectedCountryId(id);
    setDistrictForm(prev => ({
      ...prev,
      stateId: 0 // Reset state when country changes
    }));
  };

  const columns: Column<any>[] = [
    {
      key: 'district',
      header: 'District',
      sortable: true,
    },
    {
      key: 'state_name',
      header: 'State',
      render: (value) => (
        <Badge variant="secondary">{value}</Badge>
      ),
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
          <h2 className="text-2xl font-bold tracking-tight">District Management</h2>
          <p className="text-muted-foreground">
            Manage districts with state and country associations
          </p>
        </div>
      </div>

      <DataTable
        data={districtsQuery.data || []}
        columns={columns}
        loading={districtsQuery.isLoading}
        onAdd={handleCreate}
        onEdit={handleEdit}
        onDelete={handleDelete}
        addButtonText="Add District"
        emptyMessage="No districts found"
      />

      {/* Create/Edit Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-[500px]">
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>
                {editingDistrict ? 'Edit District' : 'Create New District'}
              </DialogTitle>
              <DialogDescription>
                {editingDistrict 
                  ? 'Update the district information below.'
                  : 'Add a new district to the system.'
                }
              </DialogDescription>
            </DialogHeader>
            
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label htmlFor="country">Country *</Label>
                <Select
                  value={selectedCountryId.toString()}
                  onValueChange={handleCountryChange}
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
                <Label htmlFor="state">State *</Label>
                <Select
                  value={districtForm.stateId.toString()}
                  onValueChange={(value) => setDistrictForm(prev => ({ ...prev, stateId: parseInt(value) }))}
                  disabled={!selectedCountryId}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Select state" />
                  </SelectTrigger>
                  <SelectContent>
                    {statesQuery.data?.map((state: State) => (
                      <SelectItem key={state.id} value={state.id.toString()}>
                        {state.state}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              
              <div className="grid gap-2">
                <Label htmlFor="district">District Name *</Label>
                <Input
                  id="district"
                  value={districtForm.district}
                  onChange={(e) => setDistrictForm(prev => ({ ...prev, district: e.target.value }))}
                  placeholder="e.g., Los Angeles, Mumbai"
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="code">District Code *</Label>
                  <Input
                    id="code"
                    value={districtForm.code}
                    onChange={(e) => setDistrictForm(prev => ({ ...prev, code: e.target.value.toUpperCase() }))}
                    placeholder="e.g., LA, MUM"
                    maxLength={10}
                    required
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="order">Order</Label>
                  <Input
                    id="order"
                    type="number"
                    value={districtForm.order}
                    onChange={(e) => setDistrictForm(prev => ({ ...prev, order: parseInt(e.target.value) || 0 }))}
                    placeholder="0"
                  />
                </div>
              </div>

              <div className="grid gap-2">
                <Label htmlFor="status">Status</Label>
                <Select
                  value={districtForm.status.toString()}
                  onValueChange={(value) => setDistrictForm(prev => ({ ...prev, status: parseInt(value) }))}
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
                disabled={createDistrictMutation.isPending || updateDistrictMutation.isPending}
              >
                {editingDistrict ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
