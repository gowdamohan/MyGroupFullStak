import React, { useState } from "react";
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
      const data = await response.json();
      console.log('🔍 Languages data received:', data);
      return data;
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
      setShowCreateModal(false);
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
    console.log('🔍 Editing language:', language);

    if (!language) {
      console.error('❌ Language object is undefined');
      toast({
        title: "Error",
        description: "Invalid language data",
        variant: "destructive",
      });
      return;
    }

    setEditingLanguage(language);
    setLanguageForm({
      name: language.name || '',
      code: language.code || '',
      isActive: language.isActive !== undefined ? language.isActive : true,
      speakers: language.speakers || '',
    });
    setShowCreateModal(true);
  };

  const handleDelete = (language: Language) => {
    if (!language) {
      console.error('❌ Language object is undefined');
      return;
    }

    if (confirm(`Are you sure you want to delete "${language.name}"?`)) {
      deleteLanguageMutation.mutate(language.id);
    }
  };

  const handleCloseModal = () => {
    setShowCreateModal(false);
    setEditingLanguage(null);
    resetForm();
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
      render: (value, language) => (
        <Badge variant={language.isActive ? 'default' : 'secondary'}>
          {language.isActive ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    {
      key: 'actions',
      header: 'Actions',
      render: (value, language) => {
        console.log('🔍 Rendering actions for language:', language);
        return (
          <div className="flex gap-2">
            <Button
              variant="outline"
              size="sm"
              onClick={() => {
                console.log('🔍 Edit button clicked for language:', language);
                handleEdit(language);
              }}
            >
              Edit
            </Button>
            <Button
              variant="outline"
              size="sm"
              onClick={() => {
                console.log('🔍 Delete button clicked for language:', language);
                handleDelete(language);
              }}
              className="text-red-600 hover:text-red-700"
            >
              Delete
            </Button>
          </div>
        );
      },
    },
  ];

  return (
    <div className={`space-y-6 ${className}`}>
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold">Language Management</h2>
          <p className="text-gray-600">Manage system languages and their settings</p>
        </div>
        <Button onClick={() => {
          setEditingLanguage(null);
          resetForm();
          setShowCreateModal(true);
        }}>
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

      {/* Debug info */}
      {process.env.NODE_ENV === 'development' && (
        <div className="mt-4 p-4 bg-gray-100 rounded">
          <h3 className="font-bold">Debug Info:</h3>
          <p>Languages count: {languagesQuery.data?.length || 0}</p>
          <pre className="text-xs mt-2 overflow-auto max-h-32">
            {JSON.stringify(languagesQuery.data, null, 2)}
          </pre>
        </div>
      )}

      <Dialog open={showCreateModal} onOpenChange={handleCloseModal}>
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
