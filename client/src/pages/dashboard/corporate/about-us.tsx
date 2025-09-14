import React, { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { toast } from "sonner";
import { Plus, Edit, Trash2, Upload, Eye } from "lucide-react";

interface AboutUs {
  id: number;
  groupId: number;
  title: string;
  content: string;
  image: string;
  isActive: number;
  createdAt: string;
  updatedAt: string;
}

export default function CorporateAboutUs() {
  const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [selectedAboutUs, setSelectedAboutUs] = useState<AboutUs | null>(null);
  const [formData, setFormData] = useState({
    title: "",
    content: "",
    image: ""
  });
  const [imageFile, setImageFile] = useState<File | null>(null);

  const queryClient = useQueryClient();

  // Fetch about us entries
  const { data: aboutUsEntries = [], isLoading } = useQuery({
    queryKey: ['/api/corporate/about-us'],
    queryFn: async () => {
      const response = await fetch('/api/corporate/about-us', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch about us entries');
      return response.json();
    }
  });

  // Create about us mutation
  const createMutation = useMutation({
    mutationFn: async (data: any) => {
      let imageUrl = data.image;

      // Upload image if file is selected
      if (imageFile) {
        const formData = new FormData();
        formData.append('about', imageFile);

        const uploadResponse = await fetch('/api/corporate/upload', {
          method: 'POST',
          credentials: 'include',
          body: formData
        });

        if (!uploadResponse.ok) {
          throw new Error('Failed to upload image');
        }

        const uploadResult = await uploadResponse.json();
        imageUrl = uploadResult.files[0].url;
      }

      const response = await fetch('/api/corporate/about-us', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          title: data.title,
          content: data.content,
          image: imageUrl
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to create about us entry');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/about-us'] });
      setIsCreateDialogOpen(false);
      resetForm();
      toast.success("About us entry created successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Update about us mutation
  const updateMutation = useMutation({
    mutationFn: async (data: any) => {
      let imageUrl = data.image;

      // Upload image if file is selected
      if (imageFile) {
        const formData = new FormData();
        formData.append('about', imageFile);

        const uploadResponse = await fetch('/api/corporate/upload', {
          method: 'POST',
          credentials: 'include',
          body: formData
        });

        if (!uploadResponse.ok) {
          throw new Error('Failed to upload image');
        }

        const uploadResult = await uploadResponse.json();
        imageUrl = uploadResult.files[0].url;
      }

      const response = await fetch(`/api/corporate/about-us/${selectedAboutUs?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          title: data.title,
          content: data.content,
          image: imageUrl
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to update about us entry');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/about-us'] });
      setIsEditDialogOpen(false);
      resetForm();
      toast.success("About us entry updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Delete about us mutation
  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await fetch(`/api/corporate/about-us/${id}`, {
        method: 'DELETE',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to delete about us entry');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/about-us'] });
      toast.success("About us entry deleted successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Toggle status mutation
  const toggleStatusMutation = useMutation({
    mutationFn: async ({ id, isActive }: { id: number; isActive: number }) => {
      const response = await fetch(`/api/corporate/about-us/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ isActive: isActive ? 0 : 1 })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to toggle status');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/about-us'] });
      toast.success("Status updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  const resetForm = () => {
    setFormData({
      title: "",
      content: "",
      image: ""
    });
    setImageFile(null);
    setSelectedAboutUs(null);
  };

  const handleEdit = (aboutUs: AboutUs) => {
    setSelectedAboutUs(aboutUs);
    setFormData({
      title: aboutUs.title || "",
      content: aboutUs.content || "",
      image: aboutUs.image || ""
    });
    setIsEditDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedAboutUs) {
      updateMutation.mutate(formData);
    } else {
      createMutation.mutate(formData);
    }
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setImageFile(file);
      // Create preview URL
      const previewUrl = URL.createObjectURL(file);
      setFormData({ ...formData, image: previewUrl });
    }
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/corporate' },
    { icon: 'bi-building-gear', label: 'Head Office Login', path: '/dashboard/corporate/head-office-login' },
    { icon: 'bi-megaphone', label: 'Header Ads', path: '/dashboard/corporate/header-ads' },
    { icon: 'bi-window-stack', label: 'Popup Ads', path: '/dashboard/corporate/popup-ads' },
    { icon: 'bi-file-text', label: 'Terms & Conditions', path: '/dashboard/corporate/terms-conditions' },
    { icon: 'bi-info-circle', label: 'About Us', path: '/dashboard/corporate/about-us', active: true },
    { icon: 'bi-images', label: 'Gallery', path: '/dashboard/corporate/gallery' },
    { icon: 'bi-telephone', label: 'Contact Us', path: '/dashboard/corporate/contact-us' },
    { icon: 'bi-share', label: 'Social Links', path: '/dashboard/corporate/social-links' },
    { icon: 'bi-chat-dots', label: 'Feedback', path: '/dashboard/corporate/feedback' },
    { icon: 'bi-file-earmark-text', label: 'Applications', path: '/dashboard/corporate/applications' },
    { icon: 'bi-person-gear', label: 'Profile', path: '/dashboard/corporate/profile' },
  ];

  return (
    <DashboardLayout menuItems={menuItems}>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold">About Us Management</h1>
            <p className="text-muted-foreground">Manage your company's about us content</p>
          </div>
          <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
            <DialogTrigger asChild>
              <Button onClick={resetForm}>
                <Plus className="h-4 w-4 mr-2" />
                Add About Us Entry
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-2xl">
              <DialogHeader>
                <DialogTitle>Create About Us Entry</DialogTitle>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <Label htmlFor="title">Title</Label>
                  <Input
                    id="title"
                    value={formData.title}
                    onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="content">Content</Label>
                  <Textarea
                    id="content"
                    value={formData.content}
                    onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                    rows={6}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="image">Image</Label>
                  <Input
                    id="image"
                    type="file"
                    accept="image/*"
                    onChange={handleImageChange}
                  />
                  {formData.image && (
                    <div className="mt-2">
                      <img src={formData.image} alt="Preview" className="w-full h-48 object-cover rounded" />
                    </div>
                  )}
                </div>
                <div className="flex justify-end space-x-2">
                  <Button type="button" variant="outline" onClick={() => setIsCreateDialogOpen(false)}>
                    Cancel
                  </Button>
                  <Button type="submit" disabled={createMutation.isPending}>
                    {createMutation.isPending ? "Creating..." : "Create"}
                  </Button>
                </div>
              </form>
            </DialogContent>
          </Dialog>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>About Us Entries</CardTitle>
          </CardHeader>
          <CardContent>
            {isLoading ? (
              <div className="text-center py-4">Loading...</div>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Image</TableHead>
                    <TableHead>Title</TableHead>
                    <TableHead>Content</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {aboutUsEntries.map((entry: AboutUs) => (
                    <TableRow key={entry.id}>
                      <TableCell>
                        {entry.image ? (
                          <img src={entry.image} alt={entry.title} className="w-16 h-16 object-cover rounded" />
                        ) : (
                          <div className="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <Upload className="h-6 w-6 text-gray-400" />
                          </div>
                        )}
                      </TableCell>
                      <TableCell className="font-medium">{entry.title}</TableCell>
                      <TableCell>
                        <div className="max-w-xs truncate">
                          {entry.content}
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant={entry.isActive ? "default" : "secondary"}>
                          {entry.isActive ? "Active" : "Inactive"}
                        </Badge>
                      </TableCell>
                      <TableCell>{new Date(entry.createdAt).toLocaleDateString()}</TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleEdit(entry)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant={entry.isActive ? "secondary" : "default"}
                            onClick={() => toggleStatusMutation.mutate({ id: entry.id, isActive: entry.isActive })}
                          >
                            <Eye className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="destructive"
                            onClick={() => deleteMutation.mutate(entry.id)}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            )}
          </CardContent>
        </Card>

        {/* Edit Dialog */}
        <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Edit About Us Entry</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="edit-title">Title</Label>
                <Input
                  id="edit-title"
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-content">Content</Label>
                <Textarea
                  id="edit-content"
                  value={formData.content}
                  onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                  rows={6}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-image">Image</Label>
                <Input
                  id="edit-image"
                  type="file"
                  accept="image/*"
                  onChange={handleImageChange}
                />
                {formData.image && (
                  <div className="mt-2">
                    <img src={formData.image} alt="Preview" className="w-full h-48 object-cover rounded" />
                  </div>
                )}
              </div>
              <div className="flex justify-end space-x-2">
                <Button type="button" variant="outline" onClick={() => setIsEditDialogOpen(false)}>
                  Cancel
                </Button>
                <Button type="submit" disabled={updateMutation.isPending}>
                  {updateMutation.isPending ? "Updating..." : "Update"}
                </Button>
              </div>
            </form>
          </DialogContent>
        </Dialog>
      </div>
    </DashboardLayout>
  );
}
