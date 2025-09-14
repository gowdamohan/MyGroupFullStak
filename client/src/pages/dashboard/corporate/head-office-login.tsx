import React, { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { toast } from "sonner";
import { Plus, Edit, Trash2, Key, Eye } from "lucide-react";

interface FranchiseHolder {
  id: number;
  userId: number;
  username: string;
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  country: number;
  state: number;
  district: number;
  countryName: string;
  stateName: string;
  districtName: string;
  createdAt: string;
}

interface Country {
  id: number;
  country: string;
}

interface State {
  id: number;
  state: string;
  countryId: number;
}

interface District {
  id: number;
  district: string;
  stateId: number;
}

export default function CorporateHeadOfficeLogin() {
  const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [selectedFranchiseHolder, setSelectedFranchiseHolder] = useState<FranchiseHolder | null>(null);
  const [formData, setFormData] = useState({
    name: "",
    mobile: "",
    email: "",
    username: "",
    country: "",
    state: "",
    district: ""
  });

  const queryClient = useQueryClient();

  // Fetch franchise holders
  const { data: franchiseHolders = [], isLoading } = useQuery({
    queryKey: ['/api/corporate/franchise-holders'],
    queryFn: async () => {
      const response = await fetch('/api/corporate/franchise-holders', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch franchise holders');
      return response.json();
    }
  });

  // Fetch countries
  const { data: countries = [] } = useQuery({
    queryKey: ['/api/countries'],
    queryFn: async () => {
      const response = await fetch('/api/countries', {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch countries');
      return response.json();
    }
  });

  // Fetch states based on selected country
  const { data: states = [] } = useQuery({
    queryKey: ['/api/states', formData.country],
    queryFn: async () => {
      if (!formData.country) return [];
      const response = await fetch(`/api/states?countryId=${formData.country}`, {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch states');
      return response.json();
    },
    enabled: !!formData.country
  });

  // Fetch districts based on selected state
  const { data: districts = [] } = useQuery({
    queryKey: ['/api/districts', formData.state],
    queryFn: async () => {
      if (!formData.state) return [];
      const response = await fetch(`/api/districts?stateId=${formData.state}`, {
        credentials: 'include'
      });
      if (!response.ok) throw new Error('Failed to fetch districts');
      return response.json();
    },
    enabled: !!formData.state
  });

  // Create franchise holder mutation
  const createMutation = useMutation({
    mutationFn: async (data: any) => {
      // First create the user
      const userResponse = await fetch('/api/corporate/users', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          name: data.name,
          mobile: data.mobile,
          email: data.email,
          username: data.username
        })
      });

      if (!userResponse.ok) {
        const error = await userResponse.json();
        throw new Error(error.error || 'Failed to create user');
      }

      const newUser = await userResponse.json();

      // Then create the franchise holder
      const franchiseResponse = await fetch('/api/corporate/franchise-holders', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          userId: newUser.id,
          country: data.country ? parseInt(data.country) : null,
          state: data.state ? parseInt(data.state) : null,
          district: data.district ? parseInt(data.district) : null
        })
      });

      if (!franchiseResponse.ok) {
        const error = await franchiseResponse.json();
        throw new Error(error.error || 'Failed to create franchise holder');
      }

      return franchiseResponse.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/franchise-holders'] });
      setIsCreateDialogOpen(false);
      resetForm();
      toast.success("Head office login created successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Update franchise holder mutation
  const updateMutation = useMutation({
    mutationFn: async (data: any) => {
      // Update user details
      const userResponse = await fetch(`/api/corporate/users/${selectedFranchiseHolder?.userId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          name: data.name,
          mobile: data.mobile,
          email: data.email,
          username: data.username
        })
      });

      if (!userResponse.ok) {
        const error = await userResponse.json();
        throw new Error(error.error || 'Failed to update user');
      }

      // Update franchise holder details
      const franchiseResponse = await fetch(`/api/corporate/franchise-holders/${selectedFranchiseHolder?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          country: data.country ? parseInt(data.country) : null,
          state: data.state ? parseInt(data.state) : null,
          district: data.district ? parseInt(data.district) : null
        })
      });

      if (!franchiseResponse.ok) {
        const error = await franchiseResponse.json();
        throw new Error(error.error || 'Failed to update franchise holder');
      }

      return franchiseResponse.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/franchise-holders'] });
      setIsEditDialogOpen(false);
      resetForm();
      toast.success("Head office login updated successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Delete franchise holder mutation
  const deleteMutation = useMutation({
    mutationFn: async (id: number) => {
      const response = await fetch(`/api/corporate/franchise-holders/${id}`, {
        method: 'DELETE',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to delete franchise holder');
      }

      return response.json();
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/corporate/franchise-holders'] });
      toast.success("Head office login deleted successfully");
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  // Reset password mutation
  const resetPasswordMutation = useMutation({
    mutationFn: async (userId: number) => {
      const response = await fetch(`/api/corporate/users/${userId}/reset-password`, {
        method: 'POST',
        credentials: 'include'
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Failed to reset password');
      }

      return response.json();
    },
    onSuccess: (data) => {
      toast.success(`Password reset successfully. New password: ${data.newPassword}`);
    },
    onError: (error: Error) => {
      toast.error(error.message);
    }
  });

  const resetForm = () => {
    setFormData({
      name: "",
      mobile: "",
      email: "",
      username: "",
      country: "",
      state: "",
      district: ""
    });
    setSelectedFranchiseHolder(null);
  };

  const handleEdit = (franchiseHolder: FranchiseHolder) => {
    setSelectedFranchiseHolder(franchiseHolder);
    setFormData({
      name: franchiseHolder.firstName || "",
      mobile: franchiseHolder.phone || "",
      email: franchiseHolder.email || "",
      username: franchiseHolder.username || "",
      country: franchiseHolder.country?.toString() || "",
      state: franchiseHolder.state?.toString() || "",
      district: franchiseHolder.district?.toString() || ""
    });
    setIsEditDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedFranchiseHolder) {
      updateMutation.mutate(formData);
    } else {
      createMutation.mutate(formData);
    }
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/corporate' },
    { icon: 'bi-building-gear', label: 'Head Office Login', path: '/dashboard/corporate/head-office-login', active: true },
    { icon: 'bi-megaphone', label: 'Header Ads', path: '/dashboard/corporate/header-ads' },
    { icon: 'bi-window-stack', label: 'Popup Ads', path: '/dashboard/corporate/popup-ads' },
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
            <h1 className="text-3xl font-bold">Head Office Login Management</h1>
            <p className="text-muted-foreground">Manage head office login accounts by country, state, and district</p>
          </div>
          <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
            <DialogTrigger asChild>
              <Button onClick={resetForm}>
                <Plus className="h-4 w-4 mr-2" />
                Add Head Office Login
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-md">
              <DialogHeader>
                <DialogTitle>Create Head Office Login</DialogTitle>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <Label htmlFor="name">Name</Label>
                  <Input
                    id="name"
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="mobile">Mobile</Label>
                  <Input
                    id="mobile"
                    value={formData.mobile}
                    onChange={(e) => setFormData({ ...formData, mobile: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="username">Username</Label>
                  <Input
                    id="username"
                    value={formData.username}
                    onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="country">Country</Label>
                  <Select value={formData.country} onValueChange={(value) => setFormData({ ...formData, country: value, state: "", district: "" })}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select country" />
                    </SelectTrigger>
                    <SelectContent>
                      {countries.map((country: Country) => (
                        <SelectItem key={country.id} value={country.id.toString()}>
                          {country.country}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                {formData.country && (
                  <div>
                    <Label htmlFor="state">State</Label>
                    <Select value={formData.state} onValueChange={(value) => setFormData({ ...formData, state: value, district: "" })}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select state" />
                      </SelectTrigger>
                      <SelectContent>
                        {states.map((state: State) => (
                          <SelectItem key={state.id} value={state.id.toString()}>
                            {state.state}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
                {formData.state && (
                  <div>
                    <Label htmlFor="district">District</Label>
                    <Select value={formData.district} onValueChange={(value) => setFormData({ ...formData, district: value })}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select district" />
                      </SelectTrigger>
                      <SelectContent>
                        {districts.map((district: District) => (
                          <SelectItem key={district.id} value={district.id.toString()}>
                            {district.district}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
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
            <CardTitle>Head Office Logins</CardTitle>
          </CardHeader>
          <CardContent>
            {isLoading ? (
              <div className="text-center py-4">Loading...</div>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Username</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Phone</TableHead>
                    <TableHead>Location</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {franchiseHolders.map((holder: FranchiseHolder) => (
                    <TableRow key={holder.id}>
                      <TableCell>{holder.firstName} {holder.lastName}</TableCell>
                      <TableCell>{holder.username}</TableCell>
                      <TableCell>{holder.email}</TableCell>
                      <TableCell>{holder.phone}</TableCell>
                      <TableCell>
                        <div className="space-y-1">
                          {holder.countryName && <Badge variant="outline">{holder.countryName}</Badge>}
                          {holder.stateName && <Badge variant="outline">{holder.stateName}</Badge>}
                          {holder.districtName && <Badge variant="outline">{holder.districtName}</Badge>}
                        </div>
                      </TableCell>
                      <TableCell>{new Date(holder.createdAt).toLocaleDateString()}</TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleEdit(holder)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => resetPasswordMutation.mutate(holder.userId)}
                          >
                            <Key className="h-4 w-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="destructive"
                            onClick={() => deleteMutation.mutate(holder.id)}
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
              <DialogTitle>Edit Head Office Login</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="edit-name">Name</Label>
                <Input
                  id="edit-name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-mobile">Mobile</Label>
                <Input
                  id="edit-mobile"
                  value={formData.mobile}
                  onChange={(e) => setFormData({ ...formData, mobile: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-email">Email</Label>
                <Input
                  id="edit-email"
                  type="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-username">Username</Label>
                <Input
                  id="edit-username"
                  value={formData.username}
                  onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                  required
                />
              </div>
              <div>
                <Label htmlFor="edit-country">Country</Label>
                <Select value={formData.country} onValueChange={(value) => setFormData({ ...formData, country: value, state: "", district: "" })}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select country" />
                  </SelectTrigger>
                  <SelectContent>
                    {countries.map((country: Country) => (
                      <SelectItem key={country.id} value={country.id.toString()}>
                        {country.country}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              {formData.country && (
                <div>
                  <Label htmlFor="edit-state">State</Label>
                  <Select value={formData.state} onValueChange={(value) => setFormData({ ...formData, state: value, district: "" })}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select state" />
                    </SelectTrigger>
                    <SelectContent>
                      {states.map((state: State) => (
                        <SelectItem key={state.id} value={state.id.toString()}>
                          {state.state}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              )}
              {formData.state && (
                <div>
                  <Label htmlFor="edit-district">District</Label>
                  <Select value={formData.district} onValueChange={(value) => setFormData({ ...formData, district: value })}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select district" />
                    </SelectTrigger>
                    <SelectContent>
                      {districts.map((district: District) => (
                        <SelectItem key={district.id} value={district.id.toString()}>
                          {district.district}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              )}
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
