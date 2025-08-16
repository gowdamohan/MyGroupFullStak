import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
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
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { ChevronDown, ChevronRight, Plus, Edit, Trash2 } from "lucide-react";

interface CategoryApp {
  id: number;
  name: string;
  apps_name: string;
  order_by: number;
  code: string | null;
}

interface CategoryGroup {
  category: string;
  apps: CategoryApp[];
}

interface CategoriesManagementProps {
  className?: string;
}

export default function CategoriesManagement({ className = "" }: CategoriesManagementProps) {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingApp, setEditingApp] = useState<CategoryApp | null>(null);
  const [expandedCategories, setExpandedCategories] = useState<Set<string>>(new Set(['My Apps']));
  const { toast } = useToast();

  const [appForm, setAppForm] = useState({
    name: '',
    apps_name: 'My Apps',
    order_by: 0,
    code: '',
  });

  // Fetch categories and apps
  const categoriesQuery = useQuery({
    queryKey: ['categories-list'],
    queryFn: async () => {
      try {
        const response = await apiRequest('/api/admin/categories');
        const data = await response.json();
        console.log('📁 Categories API Response:', data);
        
        // Group apps by category
        const grouped: { [key: string]: CategoryApp[] } = {};
        data.forEach((app: CategoryApp) => {
          if (!grouped[app.apps_name]) {
            grouped[app.apps_name] = [];
          }
          grouped[app.apps_name].push(app);
        });

        // Sort apps within each category by order_by
        Object.keys(grouped).forEach(category => {
          grouped[category].sort((a, b) => (a.order_by || 0) - (b.order_by || 0));
        });

        return grouped;
      } catch (error) {
        console.error('Error fetching categories:', error);
        return {};
      }
    },
    retry: false,
  });

  // Create app mutation
  const createAppMutation = useMutation({
    mutationFn: async (data: any) => {
      return apiRequest('/api/admin/categories', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App created successfully",
      });
      setShowCreateModal(false);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['categories-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create app",
        variant: "destructive",
      });
    }
  });

  // Update app mutation
  const updateAppMutation = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: any }) => {
      return apiRequest(`/api/admin/categories/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App updated successfully",
      });
      setEditingApp(null);
      resetForm();
      queryClient.invalidateQueries({ queryKey: ['categories-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to update app",
        variant: "destructive",
      });
    }
  });

  // Delete app mutation
  const deleteAppMutation = useMutation({
    mutationFn: async (id: number) => {
      return apiRequest(`/api/admin/categories/${id}`, {
        method: 'DELETE'
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "App deleted successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['categories-list'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to delete app",
        variant: "destructive",
      });
    }
  });

  const resetForm = () => {
    setAppForm({
      name: '',
      apps_name: 'My Apps',
      order_by: 0,
      code: '',
    });
  };

  const handleCreate = () => {
    setEditingApp(null);
    resetForm();
    setShowCreateModal(true);
  };

  const handleEdit = (app: CategoryApp) => {
    setEditingApp(app);
    setAppForm({
      name: app.name || '',
      apps_name: app.apps_name || 'My Apps',
      order_by: app.order_by || 0,
      code: app.code || '',
    });
    setShowCreateModal(true);
  };

  const handleDelete = (app: CategoryApp) => {
    if (window.confirm(`Are you sure you want to delete "${app.name}"?`)) {
      deleteAppMutation.mutate(app.id);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!appForm.name.trim() || !appForm.apps_name.trim()) {
      toast({
        title: "Error",
        description: "Please fill in all required fields",
        variant: "destructive",
      });
      return;
    }

    if (editingApp) {
      updateAppMutation.mutate({
        id: editingApp.id,
        data: appForm
      });
    } else {
      createAppMutation.mutate(appForm);
    }
  };

  const toggleCategory = (category: string) => {
    const newExpanded = new Set(expandedCategories);
    if (newExpanded.has(category)) {
      newExpanded.delete(category);
    } else {
      newExpanded.add(category);
    }
    setExpandedCategories(newExpanded);
  };

  const categoriesData = categoriesQuery.data || {};

  return (
    <div className={`space-y-6 ${className}`}>
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold tracking-tight">Categories Management</h2>
          <p className="text-muted-foreground">
            Manage application categories and their sub-applications
          </p>
        </div>
        <Button onClick={handleCreate}>
          <Plus className="h-4 w-4 mr-2" />
          Add App
        </Button>
      </div>

      {/* Categories Tree */}
      <div className="space-y-4">
        {Object.entries(categoriesData).map(([category, apps]) => (
          <Card key={category} className="w-full">
            <CardHeader 
              className="cursor-pointer hover:bg-gray-50 transition-colors"
              onClick={() => toggleCategory(category)}
            >
              <CardTitle className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                  {expandedCategories.has(category) ? (
                    <ChevronDown className="h-4 w-4" />
                  ) : (
                    <ChevronRight className="h-4 w-4" />
                  )}
                  <span>{category}</span>
                  <Badge variant="secondary">{apps.length} apps</Badge>
                </div>
              </CardTitle>
            </CardHeader>
            
            {expandedCategories.has(category) && (
              <CardContent className="pt-0">
                <div className="space-y-2">
                  {apps.map((app) => (
                    <div 
                      key={app.id}
                      className="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                      <div className="flex items-center gap-3">
                        <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div>
                          <span className="font-medium">{app.name}</span>
                          <div className="text-sm text-gray-500">
                            Order: {app.order_by} {app.code && `• Code: ${app.code}`}
                          </div>
                        </div>
                      </div>
                      
                      <div className="flex items-center gap-2">
                        <Button
                          variant="ghost"
                          size="sm"
                          onClick={() => handleEdit(app)}
                        >
                          <Edit className="h-4 w-4" />
                        </Button>
                        <Button
                          variant="ghost"
                          size="sm"
                          onClick={() => handleDelete(app)}
                          className="text-red-600 hover:text-red-700"
                        >
                          <Trash2 className="h-4 w-4" />
                        </Button>
                      </div>
                    </div>
                  ))}
                  
                  {apps.length === 0 && (
                    <div className="text-center py-8 text-gray-500">
                      No apps in this category
                    </div>
                  )}
                </div>
              </CardContent>
            )}
          </Card>
        ))}
        
        {Object.keys(categoriesData).length === 0 && !categoriesQuery.isLoading && (
          <Card>
            <CardContent className="text-center py-8">
              <p className="text-gray-500">No categories found</p>
            </CardContent>
          </Card>
        )}
      </div>

      {/* Create/Edit Modal */}
      <Dialog open={showCreateModal} onOpenChange={setShowCreateModal}>
        <DialogContent className="sm:max-w-[425px]">
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>
                {editingApp ? 'Edit App' : 'Create New App'}
              </DialogTitle>
              <DialogDescription>
                {editingApp 
                  ? 'Update the app information below.'
                  : 'Add a new app to a category.'
                }
              </DialogDescription>
            </DialogHeader>
            
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label htmlFor="name">App Name *</Label>
                <Input
                  id="name"
                  value={appForm.name}
                  onChange={(e) => setAppForm(prev => ({ ...prev, name: e.target.value }))}
                  placeholder="e.g., Mychat, Mydiary"
                  required
                />
              </div>
              
              <div className="grid gap-2">
                <Label htmlFor="apps_name">Category *</Label>
                <Input
                  id="apps_name"
                  value={appForm.apps_name}
                  onChange={(e) => setAppForm(prev => ({ ...prev, apps_name: e.target.value }))}
                  placeholder="e.g., My Apps"
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label htmlFor="order_by">Order</Label>
                  <Input
                    id="order_by"
                    type="number"
                    value={appForm.order_by}
                    onChange={(e) => setAppForm(prev => ({ ...prev, order_by: parseInt(e.target.value) || 0 }))}
                    placeholder="0"
                  />
                </div>
                
                <div className="grid gap-2">
                  <Label htmlFor="code">Code</Label>
                  <Input
                    id="code"
                    value={appForm.code}
                    onChange={(e) => setAppForm(prev => ({ ...prev, code: e.target.value }))}
                    placeholder="Optional code"
                  />
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
                disabled={createAppMutation.isPending || updateAppMutation.isPending}
              >
                {editingApp ? 'Update' : 'Create'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
