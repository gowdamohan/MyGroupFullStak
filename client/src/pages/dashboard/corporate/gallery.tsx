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
import { Plus, Edit, Trash2, Upload, Eye, Images } from "lucide-react";

interface Gallery {
  id: number;
  groupId: number;
  galleryName: string;
  description: string;
  isActive: number;
  createdAt: string;
  updatedAt: string;
}

interface GalleryImage {
  id: number;
  galleryId: number;
  groupId: number;
  imageName: string;
  imageDescription: string;
  uploadedAt: string;
}

export default function CorporateGallery() {
  const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isImageDialogOpen, setIsImageDialogOpen] = useState(false);
  const [selectedGallery, setSelectedGallery] = useState<Gallery | null>(null);
  const [formData, setFormData] = useState({
    galleryName: "",
    description: ""
  });
  const [imageFiles, setImageFiles] = useState<File[]>([]);
  const [imageDescriptions, setImageDescriptions] = useState<string[]>([]);

  const queryClient = useQueryClient();

  // Fetch galleries
  const { data: galleries = [], isLoading } = useQuery({
    queryKey: ['/api/corporate/galleries'],
    queryFn: async () => {
      const response = await fetch('/api/corporate/galleries', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch galleries');
      return response.json();
    }
  });

  // Fetch gallery images
  const { data: galleryImages = [] } = useQuery({
    queryKey: ['/api/corporate/galleries', selectedGallery?.id, 'images'],
    queryFn: async () => {
      if (!selectedGallery) return [];
      const response = await fetch(`/api/corporate/galleries/${selectedGallery.id}/images`, {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch gallery images');
      return response.json();
    },
    enabled: !!selectedGallery
  });

  // Create gallery mutation
  const createMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await fetch('/api/corporate/galleries', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          galleryName: data.galleryName,
          description: data.description
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to create gallery');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/galleries'] });
      setIsCreateDialogOpen(false);
      resetForm();
      toast.success("Gallery created successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Update gallery mutation
  const updateMutation = useMutation({
    mutationFn: async (data: any) => {
      const response = await fetch(`/api/corporate/galleries/${selectedGallery?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          galleryName: data.galleryName,
          description: data.description
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to update gallery');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/galleries'] });
      setIsEditDialogOpen(false);
      resetForm();
      toast.success("Gallery updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Delete gallery mutation
  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await fetch(`/api/corporate/galleries/${id}`, {
        method: 'DELETE',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to delete gallery');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/galleries'] });
      toast.success("Gallery deleted successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Add images mutation
  const addImagesMutation = useMutation({
    mutationFn: async () => {
      if (!selectedGallery || imageFiles.length === 0) return;

      const uploadedImages = [];

      // Upload each image
      for (let i = 0; i < imageFiles.length; i++) {
        const file = imageFiles[i];
        const formData = new FormData();
        formData.append('gallery', file);

        const uploadResponse = await fetch('/api/corporate/upload', {
          method: 'POST',
          credentials: 'include',
          body: formData
        });

        if (!uploadResponse.ok) {
          throw new Error(`Failed to upload image: ${file.name}`);
        }

        const uploadResult = await uploadResponse.json();
        uploadedImages.push({
          imageName: uploadResult.files[0].url,
          imageDescription: imageDescriptions[i] || ''
        });
      }

      // Add images to gallery
      const response = await fetch(`/api/corporate/galleries/${selectedGallery.id}/images`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ images: uploadedImages })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to add images to gallery');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/galleries', selectedGallery?.id, 'images'] });
      setIsImageDialogOpen(false);
      setImageFiles([]);
      setImageDescriptions([]);
      toast.success("Images added successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  const resetForm = () => {
    setFormData({
      galleryName: "",
      description: ""
    });
    setSelectedGallery(null);
  };

  const handleEdit = (gallery: Gallery) => {
    setSelectedGallery(gallery);
    setFormData({
      galleryName: gallery.galleryName || "",
      description: gallery.description || ""
    });
    setIsEditDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedGallery) {
      updateMutation.mutate(formData);
    } else {
      createMutation.mutate(formData);
    }
  };

  const handleImageFilesChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.target.files || []);
    setImageFiles(files);
    setImageDescriptions(new Array(files.length).fill(''));
  };

  const handleImageDescriptionChange = (index: number, description: string) => {
    const newDescriptions = [...imageDescriptions];
    newDescriptions[index] = description;
    setImageDescriptions(newDescriptions);
  };

  const openImageDialog = (gallery: Gallery) => {
    setSelectedGallery(gallery);
    setIsImageDialogOpen(true);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/corporate' },
    { icon: 'bi-building-gear', label: 'Head Office Login', path: '/dashboard/corporate/head-office-login' },
    { icon: 'bi-megaphone', label: 'Header Ads', path: '/dashboard/corporate/header-ads' },
    { icon: 'bi-window-stack', label: 'Popup Ads', path: '/dashboard/corporate/popup-ads' },
    { icon: 'bi-file-text', label: 'Terms & Conditions', path: '/dashboard/corporate/terms-conditions' },
    { icon: 'bi-info-circle', label: 'About Us', path: '/dashboard/corporate/about-us' },
    { icon: 'bi-images', label: 'Gallery', path: '/dashboard/corporate/gallery', active: true },
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
            <h1 className="text-3xl font-bold">Gallery Management</h1>
            <p className="text-muted-foreground">Manage your company's image galleries</p>
          </div>
          <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
            <DialogTrigger asChild>
              <Button onClick={resetForm}>
                <Plus className="h-4 w-4 mr-2" />
                Create Gallery
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-md">
              <DialogHeader>
                <DialogTitle>Create Gallery</DialogTitle>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <Label htmlFor="galleryName">Gallery Name</Label>
                  <Input
                    id="galleryName"
                    value={formData.galleryName}
                    onChange={(e) => setFormData({ ...formData, galleryName: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="description">Description</Label>
                  <Textarea
                    id="description"
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    rows={3}
                  />
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
            <CardTitle>Galleries</CardTitle>
          </CardHeader>
          <CardContent>
            {isLoading ? (
              <div className="text-center py-4">Loading...</div>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Gallery Name</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {galleries.map((gallery: Gallery) => (
                    <TableRow key={gallery.id}>
                      <TableCell className="font-medium">{gallery.galleryName}</TableCell>
                      <TableCell>
                        <div className="max-w-xs truncate">
                          {gallery.description}
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant={gallery.isActive ? "default" : "secondary"}>
                          {gallery.isActive ? "Active" : "Inactive"}
                        </Badge>
                      </TableCell>
                      <TableCell>{new Date(gallery.createdAt).toLocaleDateString()}</TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => openImageDialog(gallery)}
                          >
                            <Images className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleEdit(gallery)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="destructive"
                            onClick={() => deleteMutation.mutate(gallery.id)}
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

        {/* Edit Gallery Dialog */}
        <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
          <DialogContent className="max-w-md">
            <DialogHeader>
              <DialogTitle>Edit Gallery</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="edit-galleryName">Gallery Name</Label>
                <Input
                  id="edit-galleryName"
                  value={formData.galleryName}
                  onChange={(e) => setFormData({ ...formData, galleryName: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-description">Description</Label>
                <Textarea
                  id="edit-description"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  rows={3}
                />
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

        {/* Add Images Dialog */}
        <Dialog open={isImageDialogOpen} onOpenChange={setIsImageDialogOpen}>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Manage Gallery Images - {selectedGallery?.galleryName}</DialogTitle>
            </DialogHeader>
            <div className="space-y-4">
              <div>
                <Label htmlFor="images">Select Images</Label>
                <Input
                  id="images"
                  type="file"
                  accept="image/*"
                  multiple
                  onChange={handleImageFilesChange}
                />
              </div>
              
              {imageFiles.length > 0 && (
                <div className="space-y-4">
                  <h4 className="font-medium">Image Descriptions</h4>
                  {imageFiles.map((file, index) => (
                    <div key={index} className="flex items-center space-x-4">
                      <div className="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                        <img 
                          src={URL.createObjectURL(file)} 
                          alt={file.name} 
                          className="w-full h-full object-cover rounded"
                        />
                      </div>
                      <div className="flex-1">
                        <Input
                          placeholder={`Description for ${file.name}`}
                          value={imageDescriptions[index] || ''}
                          onChange={(e) => handleImageDescriptionChange(index, e.target.value)}
                        />
                      </div>
                    </div>
                  ))}
                </div>
              )}

              {galleryImages.length > 0 && (
                <div className="space-y-4">
                  <h4 className="font-medium">Existing Images</h4>
                  <div className="grid grid-cols-3 gap-4">
                    {galleryImages.map((image: GalleryImage) => (
                      <div key={image.id} className="space-y-2">
                        <img 
                          src={image.imageName} 
                          alt={image.imageDescription} 
                          className="w-full h-24 object-cover rounded"
                        />
                        <p className="text-sm text-gray-600 truncate">{image.imageDescription}</p>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              <div className="flex justify-end space-x-2">
                <Button type="button" variant="outline" onClick={() => setIsImageDialogOpen(false)}>
                  Close
                </Button>
                {imageFiles.length > 0 && (
                  <Button onClick={() => addImagesMutation.mutate()} disabled={addImagesMutation.isPending}>
                    {addImagesMutation.isPending ? "Adding..." : "Add Images"}
                  </Button>
                )}
              </div>
            </div>
          </DialogContent>
        </Dialog>
      </div>
    </DashboardLayout>
  );
}
