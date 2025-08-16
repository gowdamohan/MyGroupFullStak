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
import ReactCountryFlag from "react-country-flag";
import type { CountryInput } from "@shared/schema";

interface CountryManagementProps {
  className?: string;
}

export default function CountryManagement({ className = "" }: CountryManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingCountry, setEditingCountry] = useState<any | null>(null);
  const { toast } = useToast();

  const [countryForm, setCountryForm] = useState<CountryInput>({
    continentId: 0,
    country: '',
    order: 0,
    status: 1,
    code: '',
    currency: '',
    countryFlag: '',
    phoneCode: '',
    nationality: '',
  });

  // Fetch countries
  const countriesQuery = useQuery({
    queryKey: ['countries-list'],
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

  // Fetch continents for dropdown
  const continentsQuery = useQuery({
    queryKey: ['continents-dropdown'],
    queryFn: async () => {
      try {
        const response = await apiRequest('/api/admin/continents');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error fetching continents:', error);
        return [];
      }
    },
    retry: false,
  });

  // Create country mutation
  const createCountryMutation = useMutation({
    mutationFn: async (data: CountryInput) => {
      return apiRequest('/api/admin/countries', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Country created successfully",
      });
      setShowCreateModal(false);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/countries'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create country",
        variant: "destructive",
      });
    }
  });

  // Update country mutation
  const updateCountryMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: CountryInput }) => {
      return apiRequest(`/api/admin/countries/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Country updated successfully",
      });
      setEditingCountry(null);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['/api/admin/countries'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update country",
        variant: "destructive",
      });
    }
  });

  // Delete country mutation
  const deleteCountryMutation = useMutation({
    mutationFn: async (id: number) => {
      return apiRequest(`/api/admin/countries/${id}`, {
        method: 'DELETE'
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Country deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/countries'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete country",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setCountryForm({
      continentId: 0,
      country: '',
      order: 0,
      status: 1,
      code: '',
      currency: '',
      countryFlag: '',
      phoneCode: '',
      nationality: '',
    });
  };

  const handleCreate = () => {
    setEditingCountry(null);
    resetForm();
    setShowCreateModal(true);
  };

  const handleEdit = (country: any) => {
    setEditingCountry(country);
    setCountryForm({
      continentId: country.continentId || 0,
      country: country.country || '',
      order: country.order || 0,
      status: country.status || 1,
      code: country.code || '',
      currency: country.currency || '',
      countryFlag: country.countryFlag || '',
      phoneCode: country.phoneCode || '',
      nationality: country.nationality || '',
    });
    setShowCreateModal(true);
  };

  const handleDelete = (country: any) => {
    if (window.confirm(`Are you sure you want to delete "${country.country}"?`)) {
      deleteCountryMutation.mutate(country.id);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!countryForm.country.trim() || !countryForm.code.trim() || countryForm.continentId === 0) {
      toast({
        title: "Error",
        description: "Please fill in all required fields",
        variant: "destructive",
      });
      return;
    }

    if (editingCountry) {
      updateCountryMutation.mutate({
        id: editingCountry.id,
        data: countryForm
      });
    } else {
      createCountryMutation.mutate(countryForm);
    }
  };

  const columns: Column<any>[] = [
    {
      key: 'country',
      header: 'Country',
      sortable: true,
      render: (value, row) => (
        <div className="flex items-center gap-2">
          {row.country_flag && (
            <ReactCountryFlag
              countryCode={row.country_flag}
              svg
              style={{ width: '20px', height: '15px' }}
            />
          )}
          <span>{value}</span>
        </div>
      ),
    },
    {
      key: 'continent_name',
      header: 'Continent',
      render: (value) => (
        <Badge variant="secondary">{value}</Badge>
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
      key: 'currency',
      header: 'Currency',
    },
    {
      key: 'phone_code',
      header: 'Phone Code',
      render: (value) => value ? `+${value}` : '-',
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
          <h2 className="text-2xl font-bold tracking-tight">Country Management</h2>
          <p className="text-muted-foreground">
            Manage countries with continent associations and details
          </p>
        </div>
      </div>

      <DataTable
        data={countriesQuery.data || []}
        columns={columns}
        loading={countriesQuery.isLoading}
        onAdd={handleCreate}
        onEdit={handleEdit}
        onDelete={handleDelete}
        addButtonText="Add Country"
        emptyMessage="No countries found"
      />

      {/* Create/Edit Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-[600px] max-h-[80vh] overflow-y-auto">
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>
                {editingCountry ? 'Edit Country' : 'Create New Country'}
              </DialogTitle>
              <DialogDescription>
                {editingCountry 
                  ? 'Update the country information below.'
                  : 'Add a new country to the system.'
                }
              </DialogDescription>
            </DialogHeader>
            
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="continent">Continent *</Label>
                  <Select
                    value={countryForm.continentId.toString()}
                    onValueChange={(value) => setCountryForm(prev => ({ ...prev, continentId: parseInt(value) }))}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Select continent" />
                    </SelectTrigger>
                    <SelectContent>
                      {continentsQuery.data?.map((continent: any) => (
                        <SelectItem key={continent.id} value={continent.id.toString()}>
                          {continent.continent}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="country">Country Name *</Label>
                  <Input
                    id="country"
                    value={countryForm.country}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, country: e.target.value }))}
                    placeholder="e.g., United States"
                    required
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="code">Country Code *</Label>
                  <Input
                    id="code"
                    value={countryForm.code}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, code: e.target.value.toUpperCase() }))}
                    placeholder="e.g., US, IN, UK"
                    maxLength={10}
                    required
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="countryFlag">Flag Code</Label>
                  <Input
                    id="countryFlag"
                    value={countryForm.countryFlag}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, countryFlag: e.target.value.toUpperCase() }))}
                    placeholder="e.g., US, IN, GB"
                    maxLength={2}
                  />
                </div>
              </div>

              <div className="grid grid-cols-3 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="currency">Currency</Label>
                  <Input
                    id="currency"
                    value={countryForm.currency}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, currency: e.target.value }))}
                    placeholder="e.g., USD, INR"
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="phoneCode">Phone Code</Label>
                  <Input
                    id="phoneCode"
                    value={countryForm.phoneCode}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, phoneCode: e.target.value }))}
                    placeholder="e.g., 1, 91"
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="order">Order</Label>
                  <Input
                    id="order"
                    type="number"
                    value={countryForm.order}
                    onChange={(e) => setCountryForm(prev => ({ ...prev, order: parseInt(e.target.value) || 0 }))}
                    placeholder="0"
                  />
                </div>
              </div>

              <div className="grid gap-2">
                <Label htmlFor="nationality">Nationality</Label>
                <Input
                  id="nationality"
                  value={countryForm.nationality}
                  onChange={(e) => setCountryForm(prev => ({ ...prev, nationality: e.target.value }))}
                  placeholder="e.g., American, Indian"
                />
              </div>

              <div className="grid gap-2">
                <Label htmlFor="status">Status</Label>
                <Select
                  value={countryForm.status.toString()}
                  onValueChange={(value) => setCountryForm(prev => ({ ...prev, status: parseInt(value) }))}
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
                disabled={createCountryMutation.isPending || updateCountryMutation.isPending}
              >
                {editingCountry ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
