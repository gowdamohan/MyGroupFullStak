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

interface Language {
  id: number;
  name: string;
  code: string;
  isActive: boolean;
  speakers: string;
}

interface LanguageManagementProps {
  className?: string;
}

export default function LanguageManagement({ className = "" }: LanguageManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingLanguage, setEditingLanguage] = useState<Language | null>(null);
  const { toast } = useToast();

  const [languageForm, setLanguageForm] = useState({
    name: '',
    code: '',
    isActive: true,
    speakers: '',
  });

  // Fetch languages
  const languagesQuery = useQuery({
    queryKey: ['languages-list'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/languages');
      return response.json();
    }
  });

  // Create language mutation
  const createLanguageMutation = useMutation({
    mutationFn: async (languageData: any) => {
      const response = await apiRequest('/api/admin/languages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(languageData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Language created successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['languages-list'] });
      setShowCreateModal(false);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create language",
        variant: "destructive",
      });
    }
  });

  // Update language mutation
  const updateLanguageMutation = useMutation({
    mutationFn: async ({ id, ...languageData }: any) => {
      const response = await apiRequest(`/api/admin/languages/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(languageData),
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Language updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['languages-list'] });
      setEditingLanguage(null);
      resetForm();
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update language",
        variant: "destructive",
      });
    }
  });

  // Delete language mutation
  const deleteLanguageMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await apiRequest(`/api/admin/languages/${id}`, {
        method: 'DELETE',
      });
      return response.json();
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Language deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['languages-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete language",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setLanguageForm({
      name: '',
      code: '',
      isActive: true,
      speakers: '',
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (editingLanguage) {
      updateLanguageMutation.mutate({ id: editingLanguage.id, ...languageForm });
    } else {
      createLanguageMutation.mutate(languageForm);
    }
  };

  const handleEdit = (language: Language) => {
    setEditingLanguage(language);
    setLanguageForm({
      name: language.name,
      code: language.code,
      isActive: language.isActive,
      speakers: language.speakers,
    });
    setShowCreateModal(true);
  };

  const handleDelete = (language: Language) => {
    if (confirm(`Are you sure you want to delete "${language.name}"?`)) {
      deleteLanguageMutation.mutate(language.id);
    }
  };

  const columns: Column<Language>[] = [
    {
      key: 'id',
      header: 'ID',
      sortable: true,
    },
    {
      key: 'name',
      header: 'Language Name',
      sortable: true,
    },
    {
      key: 'code',
      header: 'Code',
      sortable: true,
    },
    {
      key: 'speakers',
      header: 'Speakers',
      sortable: true,
    },
    {
      key: 'isActive',
      header: 'Status',
      render: (language) => (
        <Badge variant={language.isActive ? 'default' : 'secondary'}>
          {language.isActive ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: 'Actions',
      render: (language) => (
        <div className="flex gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleEdit(language)}
          >
            Edit
          </Button>
          <Button
            variant="outline"
            size="sm"
            onClick={() => handleDelete(language)}
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
          <h2 className="text-2xl font-bold">Language Management</h2>
          <p className="text-gray-600">Manage system languages and their settings</p>
        </div>
        <Button onClick={() => setShowCreateModal(true)}>
          Add Language
        </Button>
      </div>

      <DataTable
        data={languagesQuery.data || []}
        columns={columns}
        loading={languagesQuery.isLoading}
        searchable
        searchPlaceholder="Search languages..."
      />

      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>
              {editingLanguage ? 'Edit Language' : 'Add New Language'}
            </DialogTitle>
            <DialogDescription>
              {editingLanguage ? 'Update language information' : 'Create a new language entry'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Label htmlFor="name">Language Name</Label>
              <Input
                id="name"
                value={languageForm.name}
                onChange={(e) => setLanguageForm({ ...languageForm, name: e.target.value })}
                placeholder="e.g., English"
                required
              />
            </div>

            <div>
              <Label htmlFor="code">Language Code</Label>
              <Input
                id="code"
                value={languageForm.code}
                onChange={(e) => setLanguageForm({ ...languageForm, code: e.target.value })}
                placeholder="e.g., en"
                required
              />
            </div>

            <div>
              <Label htmlFor="speakers">Number of Speakers</Label>
              <Input
                id="speakers"
                value={languageForm.speakers}
                onChange={(e) => setLanguageForm({ ...languageForm, speakers: e.target.value })}
                placeholder="e.g., 1.5B"
              />
            </div>

            <div className="flex items-center space-x-2">
              <Switch
                id="isActive"
                checked={languageForm.isActive}
                onCheckedChange={(checked) => setLanguageForm({ ...languageForm, isActive: checked })}
              />
              <Label htmlFor="isActive">Active</Label>
            </div>

            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => {
                  setShowCreateModal(false);
                  setEditingLanguage(null);
                  resetForm();
                }}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={createLanguageMutation.isPending || updateLanguageMutation.isPending}
              >
                {editingLanguage ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
