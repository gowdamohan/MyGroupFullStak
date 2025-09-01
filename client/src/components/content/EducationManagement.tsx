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

interface Education {
  id: number;
  level: string;
  isActive: boolean;
  users: number;
}

interface EducationManagementProps {
  className?: string;
}

export default function EducationManagement({ className = "" }: EducationManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingEducation, setEditingEducation] = useState<Education | null>(null);
  const { toast } = useToast();

  const [educationForm, setEducationForm] = useState({
    level: '',
    isActive: true,
  });

  // Fetch education levels
  const educationQuery = useQuery({
    queryKey: ['education-list'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/education');
      return response.json();
    }
  });

  // Create education mutation
  const createEducationMutation = useMutation({
    mutationFn: async (educationData: any) => {
      const response = await apiRequest('/api/admin/education', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(educationData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Education level created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['education-list'] });
      setShowCreateModal(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create education level",
        variant: "destructive",
      });
    }
  });

  // Update education mutation
  const updateEducationMutation = useMutation({
    mutationFn: async ({ id, ...educationData }: any) => {
      const response = await apiRequest(`/api/admin/education/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(educationData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Education level updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['education-list'] });
      setEditingEducation(null);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update education level",
        variant: "destructive",
      });
    }
  });

  // Delete education mutation
  const deleteEducationMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await apiRequest(`/api/admin/education/${id}`, {
        method: 'DELETE',
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Education level deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['education-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete education level",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setEducationForm({
      level: '',
      isActive: true,
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (editingEducation) {
      updateEducationMutation.mutate({ id: editingEducation.id, ...educationForm });
    } else {
      createEducationMutation.mutate(educationForm);
    }
  };

  const handleEdit = (education: Education) => {
    setEditingEducation(education);
    setEducationForm({
      level: education.level,
      isActive: education.isActive,
    });
    setShowCreateModal(true);
  };

  const handleDelete = (education: Education) => {
    if (confirm(`Are you sure you want to delete "${education.level}"?`)) {
      deleteEducationMutation.mutate(education.id);
    }
  };

  const columns: Column<Education>[] = [
    {
      key: 'id',
      header: 'ID',
      sortable: true,
    },
    {
      key: 'level',
      header: 'Education Level',
      sortable: true,
    },
    {
      key: 'users',
      header: 'Users',
      sortable: true,
      render: (education) => education.users.toLocaleString(),
    },
    {
      key: 'isActive',
      header: 'Status',
      render: (education) => (
        <Badge variant={education.isActive ? 'default' : 'secondary'}>
          {education.isActive ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: 'Actions',
      render: (education) => (
        <div className="flex gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleEdit(education)}
          >
            Edit
          </Button>
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleDelete(education)}
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
          <h2 className="text-2xl font-bold">Education Management</h2>
          <p className="text-gray-600">Manage education levels and qualifications</p>
        </div>
        <Button onClick={() => setShowCreateModal(true)}>
          Add Education Level
        </Button>
      </div>

      <DataTable
        data={educationQuery.data || []}
        columns={columns}
        loading={educationQuery.isLoading}
        searchable
        searchPlaceholder="Search education levels..."
      />

      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>
              {editingEducation ? 'Edit Education Level' : 'Add New Education Level'}
            </DialogTitle>
            <DialogDescription>
              {editingEducation ? 'Update education level information' : 'Create a new education level entry'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Label htmlFor="level">Education Level</Label>
              <Input
                id="level"
                value={educationForm.level}
                onChange={(e) => setEducationForm({ ...educationForm, level: e.target.value })}
                placeholder="e.g., Bachelor's Degree"
                required
              />
            </div>

            <div className="flex items-center space-x-2">
              <Switch
                id="isActive"
                checked={educationForm.isActive}
                onCheckedChange={(checked) => setEducationForm({ ...educationForm, isActive: checked })}
              />
              <Label htmlFor="isActive">Active</Label>
            </div>

            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => {
                  setShowCreateModal(false);
                  setEditingEducation(null);
                  resetForm();
                }}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={createEducationMutation.isPending || updateEducationMutation.isPending}
              >
                {editingEducation ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
