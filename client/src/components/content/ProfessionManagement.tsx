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
import { Switch } from "@/components/ui/switch";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

interface Profession {
  id: number;
  name: string;
  category: string;
  isActive: boolean;
  users: number;
}

interface ProfessionManagementProps {
  className?: string;
}

const PROFESSION_CATEGORIES = [
  'Technology',
  'Healthcare',
  'Education',
  'Finance',
  'Marketing',
  'Sales',
  'Management',
  'Engineering',
  'Legal',
  'Creative',
  'Operations',
  'Human Resources',
  'Other'
];

export default function ProfessionManagement({ className = "" }: ProfessionManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingProfession, setEditingProfession] = useState<Profession | null>(null);
  const { toast } = useToast();

  const [professionForm, setProfessionForm] = useState({
    name: '',
    category: '',
    isActive: true,
  });

  // Fetch professions
  const professionsQuery = useQuery({
    queryKey: ['professions-list'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/professions');
      return response.json();
    }
  });

  // Create profession mutation
  const createProfessionMutation = useMutation({
    mutationFn: async (professionData: any) => {
      const response = await apiRequest('/api/admin/professions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(professionData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Profession created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['professions-list'] });
      setShowCreateModal(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create profession",
        variant: "destructive",
      });
    }
  });

  // Update profession mutation
  const updateProfessionMutation = useMutation({
    mutationFn: async ({ id, ...professionData }: any) => {
      const response = await apiRequest(`/api/admin/professions/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(professionData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Profession updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['professions-list'] });
      setEditingProfession(null);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update profession",
        variant: "destructive",
      });
    }
  });

  // Delete profession mutation
  const deleteProfessionMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await apiRequest(`/api/admin/professions/${id}`, {
        method: 'DELETE',
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Profession deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['professions-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete profession",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setProfessionForm({
      name: '',
      category: '',
      isActive: true,
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (editingProfession) {
      updateProfessionMutation.mutate({ id: editingProfession.id, ...professionForm });
    } else {
      createProfessionMutation.mutate(professionForm);
    }
  };

  const handleEdit = (profession: Profession) => {
    setEditingProfession(profession);
    setProfessionForm({
      name: profession.name,
      category: profession.category,
      isActive: profession.isActive,
    });
    setShowCreateModal(true);
  };

  const handleDelete = (profession: Profession) => {
    if (confirm(`Are you sure you want to delete "${profession.name}"?`)) {
      deleteProfessionMutation.mutate(profession.id);
    }
  };

  const columns: Column<Profession>[] = [
    {
      key: 'id',
      header: 'ID',
      sortable: true,
    },
    {
      key: 'name',
      header: 'Profession Name',
      sortable: true,
    },
    {
      key: 'category',
      header: 'Category',
      sortable: true,
      render: (profession) => (
        <Badge variant="outline">{profession.category}</Badge>
      ),
    },
    {
      key: 'users',
      header: 'Users',
      sortable: true,
      render: (profession) => (profession.users || 0).toLocaleString(),
    },
    {
      key: 'isActive',
      header: 'Status',
      render: (profession) => (
        <Badge variant={profession.isActive ? 'default' : 'secondary'}>
          {profession.isActive ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: 'Actions',
      render: (profession) => (
        <div className="flex gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleEdit(profession)}
          >
            Edit
          </Button>
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleDelete(profession)}
            className="text-red-600 hover:text-red-700"
          >
            Delete
          </Button>
        </div>
      ),
    },
  ];

  return (
    <div className={`space-y-6 ${className}`}>
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold">Profession Management</h2>
          <p className="text-gray-600">Manage professions and career categories</p>
        </div>
        <Button onClick={() => setShowCreateModal(true)}>
          Add Profession
        </Button>
      </div>

      <DataTable
        data={professionsQuery.data || []}
        columns={columns}
        loading={professionsQuery.isLoading}
        searchable
        searchPlaceholder="Search professions..."
      />

      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>
              {editingProfession ? 'Edit Profession' : 'Add New Profession'}
            </DialogTitle>
            <DialogDescription>
              {editingProfession ? 'Update profession information' : 'Create a new profession entry'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Label htmlFor="name">Profession Name</Label>
              <Input
                id="name"
                value={professionForm.name}
                onChange={(e) => setProfessionForm({ ...professionForm, name: e.target.value })}
                placeholder="e.g., Software Engineer"
                required
              />
            </div>

            <div>
              <Label htmlFor="category">Category</Label>
              <Select
                value={professionForm.category}
                onValueChange={(value) => setProfessionForm({ ...professionForm, category: value })}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Select a category" />
                </SelectTrigger>
                <SelectContent>
                  {PROFESSION_CATEGORIES.map((category) => (
                    <SelectItem key={category} value={category}>
                      {category}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="flex items-center space-x-2">
              <Switch
                id="isActive"
                checked={professionForm.isActive}
                onCheckedChange={(checked) => setProfessionForm({ ...professionForm, isActive: checked })}
              />
              <Label htmlFor="isActive">Active</Label>
            </div>

            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => {
                  setShowCreateModal(false);
                  setEditingProfession(null);
                  resetForm();
                }}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={createProfessionMutation.isPending || updateProfessionMutation.isPending}
              >
                {editingProfession ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
