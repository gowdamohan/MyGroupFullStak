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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { toast } from "sonner";
import { Plus, Edit, Trash2, Upload, Eye, ExternalLink } from "lucide-react";

interface CorporateAd {
  id: number;
  userId: number;
  adType: string;
  adPosition: string;
  adTitle: string;
  adImage: string;
  adUrl: string;
  adDescription: string;
  isActive: number;
  createdAt: string;
  updatedAt: string;
}

export default function CorporateHeaderAds() {
  const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [selectedAd, setSelectedAd] = useState<CorporateAd | null>(null);
  const [formData, setFormData] = useState({
    adPosition: "",
    adTitle: "",
    adImage: "",
    adUrl: "",
    adDescription: ""
  });
  const [imageFile, setImageFile] = useState<File | null>(null);

  const queryClient = useQueryClient();

  // Fetch header ads
  const { data: headerAds = [], isLoading } = useQuery({
    queryKey: ['/api/corporate/ads', 'header'],
    queryFn: async () => {
      const response = await fetch('/api/corporate/ads?adType=header', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch header ads');
      return response.json();
    }
  });

  // Create header ad mutation
  const createMutation = useMutation({
    mutationFn: async (data: any) => {
      let imageUrl = data.adImage;

      // Upload image if file is selected
      if (imageFile) {
        const formData = new FormData();
        formData.append('image', imageFile);

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

      const response = await fetch('/api/corporate/ads', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          adType: 'header',
          adPosition: data.adPosition,
          adTitle: data.adTitle,
          adImage: imageUrl,
          adUrl: data.adUrl,
          adDescription: data.adDescription
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to create header ad');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/ads', 'header'] });
      setIsCreateDialogOpen(false);
      resetForm();
      toast.success("Header ad created successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Update header ad mutation
  const updateMutation = useMutation({
    mutationFn: async (data: any) => {
      let imageUrl = data.adImage;

      // Upload image if file is selected
      if (imageFile) {
        const formData = new FormData();
        formData.append('image', imageFile);

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

      const response = await fetch(`/api/corporate/ads/${selectedAd?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          adPosition: data.adPosition,
          adTitle: data.adTitle,
          adImage: imageUrl,
          adUrl: data.adUrl,
          adDescription: data.adDescription
        })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to update header ad');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/ads', 'header'] });
      setIsEditDialogOpen(false);
      resetForm();
      toast.success("Header ad updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Delete header ad mutation
  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await fetch(`/api/corporate/ads/${id}`, {
        method: 'DELETE',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to delete header ad');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/ads', 'header'] });
      toast.success("Header ad deleted successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Toggle ad status mutation
  const toggleStatusMutation = useMutation({
    mutationFn: async ({ id, isActive }: { id: number; isActive: number }) => {
      const response = await fetch(`/api/corporate/ads/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ isActive: isActive ? 0 : 1 })
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to toggle ad status');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/ads', 'header'] });
      toast.success("Ad status updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  const resetForm = () => {
    setFormData({
      adPosition: "",
      adTitle: "",
      adImage: "",
      adUrl: "",
      adDescription: ""
    });
    setImageFile(null);
    setSelectedAd(null);
  };

  const handleEdit = (ad: CorporateAd) => {
    setSelectedAd(ad);
    setFormData({
      adPosition: ad.adPosition || "",
      adTitle: ad.adTitle || "",
      adImage: ad.adImage || "",
      adUrl: ad.adUrl || "",
      adDescription: ad.adDescription || ""
    });
    setIsEditDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedAd) {
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
      setFormData({ ...formData, adImage: previewUrl });
    }
  };

  const adPositions = [
    { value: "ads1", label: "Header Ads 1" },
    { value: "ads2", label: "Header Ads 2" },
    { value: "ads3", label: "Header Ads 3" },
    { value: "top_banner", label: "Top Banner" },
    { value: "navigation_banner", label: "Navigation Banner" }
  ];

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/corporate' },
    { icon: 'bi-building-gear', label: 'Head Office Login', path: '/dashboard/corporate/head-office-login' },
    { icon: 'bi-megaphone', label: 'Header Ads', path: '/dashboard/corporate/header-ads', active: true },
    { icon: 'bi-window-stack', label: 'Popup Ads', path: '/dashboard/corporate/popup-ads' },
    { icon: 'bi-badge-ad', label: 'Company Header Ads', path: '/dashboard/corporate/company-header-ads' },
    { icon: 'bi-layout-text-window', label: 'Main Page Ads', path: '/dashboard/corporate/main-page-ads' },
    { icon: 'bi-file-text', label: 'Terms & Conditions', path: '/dashboard/corporate/terms-conditions' },
    { icon: 'bi-info-circle', label: 'About Us', path: '/dashboard/corporate/about-us' },
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
            <h1 className="text-3xl font-bold">Header Ads Management</h1>
            <p className="text-muted-foreground">Manage header advertisements for your corporate website</p>
          </div>
          <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
            <DialogTrigger asChild>
              <Button onClick={resetForm}>
                <Plus className="h-4 w-4 mr-2" />
                Add Header Ad
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-md">
              <DialogHeader>
                <DialogTitle>Create Header Ad</DialogTitle>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <Label htmlFor="adPosition">Ad Position</Label>
                  <Select value={formData.adPosition} onValueChange={(value) => setFormData({ ...formData, adPosition: value })}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select ad position" />
                    </SelectTrigger>
                    <SelectContent>
                      {adPositions.map((position) => (
                        <SelectItem key={position.value} value={position.value}>
                          {position.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label htmlFor="adTitle">Ad Title</Label>
                  <Input
                    id="adTitle"
                    value={formData.adTitle}
                    onChange={(e) => setFormData({ ...formData, adTitle: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="adImage">Ad Image</Label>
                  <Input
                    id="adImage"
                    type="file"
                    accept="image/*"
                    onChange={handleImageChange}
                  />
                  {formData.adImage && (
                    <div className="mt-2">
                      <img src={formData.adImage} alt="Preview" className="w-full h-32 object-cover rounded" />
                    </div>
                  )}
                </div>
                <div>
                  <Label htmlFor="adUrl">Ad URL</Label>
                  <Input
                    id="adUrl"
                    type="url"
                    value={formData.adUrl}
                    onChange={(e) => setFormData({ ...formData, adUrl: e.target.value })}
                    placeholder="https://example.com"
                  />
                </div>
                <div>
                  <Label htmlFor="adDescription">Description</Label>
                  <Textarea
                    id="adDescription"
                    value={formData.adDescription}
                    onChange={(e) => setFormData({ ...formData, adDescription: e.target.value })}
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
            <CardTitle>Header Advertisements</CardTitle>
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
                    <TableHead>Position</TableHead>
                    <TableHead>URL</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {headerAds.map((ad: CorporateAd) => (
                    <TableRow key={ad.id}>
                      <TableCell>
                        {ad.adImage ? (
                          <img src={ad.adImage} alt={ad.adTitle} className="w-16 h-16 object-cover rounded" />
                        ) : (
                          <div className="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <Upload className="h-6 w-6 text-gray-400" />
                          </div>
                        )}
                      </TableCell>
                      <TableCell>{ad.adTitle}</TableCell>
                      <TableCell>
                        <Badge variant="outline">
                          {adPositions.find(p => p.value === ad.adPosition)?.label || ad.adPosition}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        {ad.adUrl ? (
                          <a href={ad.adUrl} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:underline">
                            <ExternalLink className="h-4 w-4 inline mr-1" />
                            Link
                          </a>
                        ) : (
                          <span className="text-gray-400">No URL</span>
                        )}
                      </TableCell>
                      <TableCell>
                        <Badge variant={ad.isActive ? "default" : "secondary"}>
                          {ad.isActive ? "Active" : "Inactive"}
                        </Badge>
                      </TableCell>
                      <TableCell>{new Date(ad.createdAt).toLocaleDateString()}</TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleEdit(ad)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant={ad.isActive ? "secondary" : "default"}
                            onClick={() => toggleStatusMutation.mutate({ id: ad.id, isActive: ad.isActive })}
                          >
                            <Eye className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="destructive"
                            onClick={() => deleteMutation.mutate(ad.id)}
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
          <DialogContent className="max-w-md">
            <DialogHeader>
              <DialogTitle>Edit Header Ad</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="edit-adPosition">Ad Position</Label>
                <Select value={formData.adPosition} onValueChange={(value) => setFormData({ ...formData, adPosition: value })}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select ad position" />
                  </SelectTrigger>
                  <SelectContent>
                    {adPositions.map((position) => (
                      <SelectItem key={position.value} value={position.value}>
                        {position.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div>
                <Label htmlFor="edit-adTitle">Ad Title</Label>
                <Input
                  id="edit-adTitle"
                  value={formData.adTitle}
                  onChange={(e) => setFormData({ ...formData, adTitle: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-adImage">Ad Image</Label>
                <Input
                  id="edit-adImage"
                  type="file"
                  accept="image/*"
                  onChange={handleImageChange}
                />
                {formData.adImage && (
                  <div className="mt-2">
                    <img src={formData.adImage} alt="Preview" className="w-full h-32 object-cover rounded" />
                  </div>
                )}
              </div>
              <div>
                <Label htmlFor="edit-adUrl">Ad URL</Label>
                <Input
                  id="edit-adUrl"
                  type="url"
                  value={formData.adUrl}
                  onChange={(e) => setFormData({ ...formData, adUrl: e.target.value })}
                  placeholder="https://example.com"
                />
              </div>
              <div>
                <Label htmlFor="edit-adDescription">Description</Label>
                <Textarea
                  id="edit-adDescription"
                  value={formData.adDescription}
                  onChange={(e) => setFormData({ ...formData, adDescription: e.target.value })}
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
      </div>
    </DashboardLayout>
  );
}
