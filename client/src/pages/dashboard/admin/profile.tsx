import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { DataTable, Column, createImageRenderer, createDateRenderer } from "@/components/ui/data-table";
import { Modal, ModalFooter, ConfirmModal, useModal } from "@/components/ui/modal";
import { FileUpload } from "@/components/ui/file-upload";
import { ColorPicker } from "@/components/ui/color-picker";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

// Types for our data
interface GroupData {
  id: number;
  name: string;
  appsName: string;
  orderBy?: number;
  code?: string;
}

interface AppData {
  id: number;
  name: string;
  apps_name: string;
  icon?: string;
  logo?: string;
  name_image?: string;
  background_color?: string;
  banner?: string;
  url?: string;
}

interface AppAccountData {
  id: number;
  username: string;
  email: string;
  app_name: string;
  created_on: number;
  active: number;
}

function AdminProfile() {
  const [activeTab, setActiveTab] = useState('group');
  const { toast } = useToast();

  // Modal states
  const groupModal = useModal();
  const appModal = useModal();
  const accountModal = useModal();
  const deleteModal = useModal();
  const [selectedItem, setSelectedItem] = useState<any>(null);
  const [isEditing, setIsEditing] = useState(false);

  // Form states
  const [groupForm, setGroupForm] = useState({
    name: '',
    appsName: '',
    orderBy: 0,
    code: ''
  });

  const [appForm, setAppForm] = useState({
    groupData: { name: '', appsName: '', orderBy: 0, code: '' },
    detailsData: {
      icon: '',
      logo: '',
      nameImage: '',
      backgroundColor: '#ffffff',
      banner: '',
      url: ''
    }
  });

  const [accountForm, setAccountForm] = useState({
    username: '',
    password: '',
    appId: ''
  });

  const [passwordForm, setPasswordForm] = useState({
    oldPassword: '',
    newPassword: '',
    confirmPassword: ''
  });

  const [uploadedFiles, setUploadedFiles] = useState<Record<string, File>>({});

  // API Queries
  const groupsQuery = useQuery({
    queryKey: ['/api/admin/groups'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/groups');
      return response.json();
    }
  });

  const appsQuery = useQuery({
    queryKey: ['/api/admin/app-create'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/app-create');
      return response.json();
    }
  });

  const accountsQuery = useQuery({
    queryKey: ['/api/admin/app-accounts'],
    queryFn: async () => {
      const response = await apiRequest('/api/admin/app-accounts');
      return response.json();
    }
  });

  // Mutations
  const createGroupMutation = useMutation({
    mutationFn: (data: any) => apiRequest('/api/admin/groups', {
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'Content-Type': 'application/json' }
    }),
    onSuccess: () => {
      toast({ title: "Success", description: "Group created successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      groupModal.close();
      resetGroupForm();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to create group", variant: "destructive" });
    }
  });

  const updateGroupMutation = useMutation({
    mutationFn: ({ id, data }: { id: number; data: any }) => apiRequest(`/api/admin/groups/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
      headers: { 'Content-Type': 'application/json' }
    }),
    onSuccess: () => {
      toast({ title: "Success", description: "Group updated successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      groupModal.close();
      resetGroupForm();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to update group", variant: "destructive" });
    }
  });

  const deleteGroupMutation = useMutation({
    mutationFn: (id: number) => apiRequest(`/api/admin/groups/${id}`, { method: 'DELETE' }),
    onSuccess: () => {
      toast({ title: "Success", description: "Group deleted successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/groups'] });
      deleteModal.close();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to delete group", variant: "destructive" });
    }
  });

  const createAppMutation = useMutation({
    mutationFn: async (data: any) => {
      // First upload files if any
      const uploadedFileUrls: Record<string, string> = {};

      if (Object.keys(uploadedFiles).length > 0) {
        const formData = new FormData();
        Object.entries(uploadedFiles).forEach(([fieldName, file]) => {
          formData.append(fieldName, file);
        });

        const uploadResponse = await fetch('/api/admin/upload', {
          method: 'POST',
          body: formData,
          credentials: 'include'
        });

        if (!uploadResponse.ok) {
          throw new Error('Failed to upload files');
        }

        const uploadResult = await uploadResponse.json();
        uploadResult.files.forEach((file: any) => {
          uploadedFileUrls[file.fieldName] = file.url;
        });
      }

      // Update details data with uploaded file URLs
      const updatedDetailsData = {
        ...data.detailsData,
        icon: uploadedFileUrls.icon || data.detailsData.icon,
        logo: uploadedFileUrls.logo || data.detailsData.logo,
        nameImage: uploadedFileUrls.nameImage || data.detailsData.nameImage,
        banner: uploadedFileUrls.banner || data.detailsData.banner,
      };

      return apiRequest('/api/admin/app-create', {
        method: 'POST',
        body: JSON.stringify({
          groupData: data.groupData,
          detailsData: updatedDetailsData
        }),
        headers: { 'Content-Type': 'application/json' }
      }).then(res => res.json());
    },
    onSuccess: () => {
      toast({ title: "Success", description: "App created successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-create'] });
      appModal.close();
      resetAppForm();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to create app", variant: "destructive" });
    }
  });

  const createAccountMutation = useMutation({
    mutationFn: (data: any) => apiRequest('/api/admin/app-accounts', {
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'Content-Type': 'application/json' }
    }).then(res => res.json()),
    onSuccess: () => {
      toast({ title: "Success", description: "Account created successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-accounts'] });
      accountModal.close();
      resetAccountForm();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to create account", variant: "destructive" });
    }
  });

  const deleteAccountMutation = useMutation({
    mutationFn: (id: number) => apiRequest(`/api/admin/app-accounts/${id}`, { method: 'DELETE' }),
    onSuccess: () => {
      toast({ title: "Success", description: "Account deleted successfully" });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/app-accounts'] });
      deleteModal.close();
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to delete account", variant: "destructive" });
    }
  });

  const changePasswordMutation = useMutation({
    mutationFn: (data: any) => apiRequest('/api/admin/change-password', {
      method: 'POST',
      body: JSON.stringify(data),
      headers: { 'Content-Type': 'application/json' }
    }),
    onSuccess: () => {
      toast({ title: "Success", description: "Password changed successfully" });
      setPasswordForm({ oldPassword: '', newPassword: '', confirmPassword: '' });
    },
    onError: (error: any) => {
      toast({ title: "Error", description: error.message || "Failed to change password", variant: "destructive" });
    }
  });

  // Helper functions
  const resetGroupForm = () => {
    setGroupForm({ name: '', appsName: '', orderBy: 0, code: '' });
    setIsEditing(false);
    setSelectedItem(null);
  };

  const resetAppForm = () => {
    setAppForm({
      groupData: { name: '', appsName: '', orderBy: 0, code: '' },
      detailsData: { icon: '', logo: '', nameImage: '', backgroundColor: '#ffffff', banner: '', url: '' }
    });
    setUploadedFiles({});
    setIsEditing(false);
    setSelectedItem(null);
  };

  const resetAccountForm = () => {
    setAccountForm({ username: '', password: '', appId: '' });
    setIsEditing(false);
    setSelectedItem(null);
  };

  const handleFileSelect = (file: File | null, fieldName: string) => {
    if (file) {
      setUploadedFiles(prev => ({ ...prev, [fieldName]: file }));
    } else {
      setUploadedFiles(prev => {
        const newFiles = { ...prev };
        delete newFiles[fieldName];
        return newFiles;
      });
    }
  };

  const handlePasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (passwordForm.newPassword !== passwordForm.confirmPassword) {
      toast({ title: "Error", description: "New passwords don't match", variant: "destructive" });
      return;
    }
    if (passwordForm.newPassword.length < 8) {
      toast({ title: "Error", description: "Password must be at least 8 characters long", variant: "destructive" });
      return;
    }
    changePasswordMutation.mutate(passwordForm);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile', active: true },
    { icon: 'bi-people', label: 'User Management', path: '/dashboard/admin/users' },
    { icon: 'bi-building', label: 'Organizations', path: '/dashboard/admin/organizations' },
    { icon: 'bi-graph-up', label: 'Analytics', path: '/dashboard/admin/analytics' },
    { icon: 'bi-gear', label: 'System Settings', path: '/dashboard/admin/settings' },
  ];

  // Define table columns
  const groupColumns: Column<GroupData>[] = [
    { key: 'id', header: 'ID', width: '80px' },
    { key: 'name', header: 'Group Name' },
    { key: 'appsName', header: 'Apps Name' },
    { key: 'orderBy', header: 'Order', width: '100px' },
    { key: 'code', header: 'Code', width: '120px' }
  ];

  const appColumns: Column<AppData>[] = [
    { key: 'id', header: 'ID', width: '80px' },
    { key: 'name', header: 'App Name' },
    { key: 'apps_name', header: 'Group Name' },
    { key: 'icon', header: 'Icon', render: createImageRenderer('Icon'), width: '80px' },
    { key: 'logo', header: 'Logo', render: createImageRenderer('Logo'), width: '80px' },
    { key: 'background_color', header: 'Color', render: (value) => value ? (
      <div className="flex items-center gap-2">
        <div className="w-6 h-6 rounded border" style={{ backgroundColor: value }}></div>
        <span className="text-xs">{value}</span>
      </div>
    ) : '-', width: '120px' }
  ];

  const accountColumns: Column<AppAccountData>[] = [
    { key: 'id', header: 'ID', width: '80px' },
    { key: 'username', header: 'Username' },
    { key: 'email', header: 'Email' },
    { key: 'app_name', header: 'App Name' },
    { key: 'created_on', header: 'Created', render: createDateRenderer('date'), width: '120px' },
    { key: 'active', header: 'Status', render: (value) => (
      <span className={`px-2 py-1 text-xs rounded ${value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
        {value ? 'Active' : 'Inactive'}
      </span>
    ), width: '100px' }
  ];

  return (
    <DashboardLayout 
      title="Admin Profile Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-lg-3">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Profile Sections</h5>
            </div>
            <div className="list-group list-group-flush">
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'group' ? 'active' : ''}`}
                onClick={() => setActiveTab('group')}
                data-testid="tab-group"
              >
                <i className="bi bi-people me-2"></i>Group
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'app-create' ? 'active' : ''}`}
                onClick={() => setActiveTab('app-create')}
                data-testid="tab-app-create"
              >
                <i className="bi bi-plus-circle me-2"></i>App Create
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'app-account' ? 'active' : ''}`}
                onClick={() => setActiveTab('app-account')}
                data-testid="tab-app-account"
              >
                <i className="bi bi-person-plus me-2"></i>App Account
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'password' ? 'active' : ''}`}
                onClick={() => setActiveTab('password')}
                data-testid="tab-password"
              >
                <i className="bi bi-lock me-2"></i>Change Password
              </button>
            </div>
          </div>
        </div>

        <div className="col-lg-9">
          {/* Group Tab */}
          {activeTab === 'group' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Group Management</h5>
              </div>
              <div className="card-body">
                <DataTable
                  data={groupsQuery.data || []}
                  columns={groupColumns}
                  loading={groupsQuery.isLoading}
                  onAdd={() => {
                    resetGroupForm();
                    groupModal.open();
                  }}
                  onEdit={(row) => {
                    setGroupForm({
                      name: row.name || '',
                      appsName: row.appsName || '',
                      orderBy: row.orderBy || 0,
                      code: row.code || ''
                    });
                    setSelectedItem(row);
                    setIsEditing(true);
                    groupModal.open();
                  }}
                  onDelete={(row) => {
                    setSelectedItem(row);
                    deleteModal.open();
                  }}
                  addButtonText="Add Group"
                  emptyMessage="No groups found"
                />
              </div>
            </div>
          )}

          {/* App Create Tab */}
          {activeTab === 'app-create' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">App Create Management</h5>
              </div>
              <div className="card-body">
                <DataTable
                  data={appsQuery.data || []}
                  columns={appColumns}
                  loading={appsQuery.isLoading}
                  onAdd={() => {
                    resetAppForm();
                    appModal.open();
                  }}
                  onEdit={(row) => {
                    setAppForm({
                      groupData: {
                        name: row.name || '',
                        appsName: row.apps_name || '',
                        orderBy: 0,
                        code: ''
                      },
                      detailsData: {
                        icon: row.icon || '',
                        logo: row.logo || '',
                        nameImage: row.name_image || '',
                        backgroundColor: row.background_color || '#ffffff',
                        banner: row.banner || '',
                        url: row.url || ''
                      }
                    });
                    setSelectedItem(row);
                    setIsEditing(true);
                    appModal.open();
                  }}
                  addButtonText="Create App"
                  emptyMessage="No apps found"
                />
              </div>
            </div>
          )}

          {/* App Account Tab */}
          {activeTab === 'app-account' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">App Account Management</h5>
              </div>
              <div className="card-body">
                <DataTable
                  data={accountsQuery.data || []}
                  columns={accountColumns}
                  loading={accountsQuery.isLoading}
                  onAdd={() => {
                    resetAccountForm();
                    accountModal.open();
                  }}
                  onDelete={(row) => {
                    setSelectedItem(row);
                    deleteModal.open();
                  }}
                  addButtonText="Add Account"
                  emptyMessage="No app accounts found"
                />
              </div>
            </div>
          )}

          {/* Change Password Tab */}
          {activeTab === 'password' && (
            <div className="card">
              <div className="card-header">
                <h5 className="card-title mb-0">Change Password</h5>
              </div>
              <div className="card-body">
                <div className="row">
                  <div className="col-md-8">
                    <form onSubmit={handlePasswordSubmit} data-testid="form-change-password">
                      <div className="mb-3">
                        <label htmlFor="currentPassword" className="form-label">Current Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="currentPassword"
                          value={passwordForm.oldPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, oldPassword: e.target.value}))}
                          required
                          data-testid="input-current-password"
                        />
                      </div>
                      <div className="mb-3">
                        <label htmlFor="newPassword" className="form-label">New Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="newPassword"
                          value={passwordForm.newPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, newPassword: e.target.value}))}
                          required
                          minLength={8}
                          data-testid="input-new-password"
                        />
                        <div className="form-text">Password must be at least 8 characters long</div>
                      </div>
                      <div className="mb-4">
                        <label htmlFor="confirmPassword" className="form-label">Confirm New Password</label>
                        <input
                          type="password"
                          className="form-control"
                          id="confirmPassword"
                          value={passwordForm.confirmPassword}
                          onChange={(e) => setPasswordForm(prev => ({...prev, confirmPassword: e.target.value}))}
                          required
                          data-testid="input-confirm-password"
                        />
                      </div>
                      <button
                        type="submit"
                        className="btn btn-primary"
                        disabled={changePasswordMutation.isPending}
                        data-testid="button-change-password"
                      >
                        {changePasswordMutation.isPending ? (
                          <>
                            <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                            Changing...
                          </>
                        ) : (
                          <>
                            <i className="bi bi-key me-2"></i>
                            Change Password
                          </>
                        )}
                      </button>
                    </form>
                  </div>
                  <div className="col-md-4">
                    <div className="bg-light p-4 rounded">
                      <h6 className="mb-3">Password Security</h6>
                      <div className="mb-3">
                        <small className="text-muted">Last Changed:</small>
                        <br />
                        <strong data-testid="text-last-password-change">
                          Not available
                        </strong>
                      </div>
                      <div className="mb-3">
                        <small className="text-muted">Password Requirements:</small>
                        <ul className="small mt-2">
                          <li>Minimum 8 characters</li>
                          <li>Include uppercase and lowercase</li>
                          <li>Include numbers</li>
                          <li>Include special characters</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Group Modal */}
      <Modal
        isOpen={groupModal.isOpen}
        onClose={groupModal.close}
        title={isEditing ? "Edit Group" : "Add Group"}
        size="md"
      >
        <form onSubmit={(e) => {
          e.preventDefault();
          if (isEditing && selectedItem) {
            updateGroupMutation.mutate({ id: selectedItem.id, data: groupForm });
          } else {
            createGroupMutation.mutate(groupForm);
          }
        }}>
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">Group Name</Label>
              <Input
                id="name"
                value={groupForm.name}
                onChange={(e) => setGroupForm(prev => ({ ...prev, name: e.target.value }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="appsName">Apps Name</Label>
              <Input
                id="appsName"
                value={groupForm.appsName}
                onChange={(e) => setGroupForm(prev => ({ ...prev, appsName: e.target.value }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="orderBy">Order</Label>
              <Input
                id="orderBy"
                type="number"
                value={groupForm.orderBy}
                onChange={(e) => setGroupForm(prev => ({ ...prev, orderBy: parseInt(e.target.value) || 0 }))}
              />
            </div>
            <div>
              <Label htmlFor="code">Code</Label>
              <Input
                id="code"
                value={groupForm.code}
                onChange={(e) => setGroupForm(prev => ({ ...prev, code: e.target.value }))}
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={groupModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createGroupMutation.isPending || updateGroupMutation.isPending}>
              {createGroupMutation.isPending || updateGroupMutation.isPending ? "Saving..." : "Save"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* App Create Modal */}
      <Modal
        isOpen={appModal.isOpen}
        onClose={appModal.close}
        title={isEditing ? "Edit App" : "Create App"}
        size="lg"
      >
        <form onSubmit={(e) => {
          e.preventDefault();
          createAppMutation.mutate(appForm);
        }}>
          <div className="space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="appName">App Name</Label>
                <Input
                  id="appName"
                  value={appForm.groupData.name}
                  onChange={(e) => setAppForm(prev => ({
                    ...prev,
                    groupData: { ...prev.groupData, name: e.target.value }
                  }))}
                  required
                />
              </div>
              <div>
                <Label htmlFor="groupName">Group Name</Label>
                <select
                  id="groupName"
                  className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                  value={appForm.groupData.appsName}
                  onChange={(e) => setAppForm(prev => ({
                    ...prev,
                    groupData: { ...prev.groupData, appsName: e.target.value }
                  }))}
                  required
                >
                  <option value="">Select Group</option>
                  <option value="My Apps">My Apps</option>
                  <option value="My Company">My Company</option>
                  <option value="My Online Apps">My Online Apps</option>
                  <option value="My Offline Apps">My Offline Apps</option>
                </select>
              </div>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <FileUpload
                fieldName="icon"
                label="Icon"
                currentFile={appForm.detailsData.icon}
                onFileSelect={handleFileSelect}
              />
              <FileUpload
                fieldName="logo"
                label="Logo"
                currentFile={appForm.detailsData.logo}
                onFileSelect={handleFileSelect}
              />
            </div>

            <div className="grid grid-cols-2 gap-4">
              <FileUpload
                fieldName="nameImage"
                label="Name Image"
                currentFile={appForm.detailsData.nameImage}
                onFileSelect={handleFileSelect}
              />
              <ColorPicker
                label="Background Color"
                value={appForm.detailsData.backgroundColor}
                onChange={(color) => setAppForm(prev => ({
                  ...prev,
                  detailsData: { ...prev.detailsData, backgroundColor: color }
                }))}
              />
            </div>

            <FileUpload
              fieldName="banner"
              label="Banner"
              currentFile={appForm.detailsData.banner}
              onFileSelect={handleFileSelect}
            />

            <div>
              <Label htmlFor="url">URL</Label>
              <Input
                id="url"
                value={appForm.detailsData.url}
                onChange={(e) => setAppForm(prev => ({
                  ...prev,
                  detailsData: { ...prev.detailsData, url: e.target.value }
                }))}
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={appModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createAppMutation.isPending}>
              {createAppMutation.isPending ? "Creating..." : "Create App"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* App Account Modal */}
      <Modal
        isOpen={accountModal.isOpen}
        onClose={accountModal.close}
        title="Add App Account"
        size="md"
      >
        <form onSubmit={(e) => {
          e.preventDefault();
          createAccountMutation.mutate(accountForm);
        }}>
          <div className="space-y-4">
            <div>
              <Label htmlFor="appSelect">App Name</Label>
              <select
                id="appSelect"
                className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                value={accountForm.appId}
                onChange={(e) => setAccountForm(prev => ({ ...prev, appId: e.target.value }))}
                required
              >
                <option value="">Select App</option>
                {groupsQuery.data?.map((group: any) => (
                  <option key={group.id} value={group.id}>
                    {group.name}
                  </option>
                ))}
              </select>
            </div>
            <div>
              <Label htmlFor="username">Username</Label>
              <Input
                id="username"
                value={accountForm.username}
                onChange={(e) => setAccountForm(prev => ({ ...prev, username: e.target.value }))}
                required
              />
            </div>
            <div>
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                type="password"
                value={accountForm.password}
                onChange={(e) => setAccountForm(prev => ({ ...prev, password: e.target.value }))}
                required
              />
            </div>
          </div>
          <ModalFooter>
            <Button type="button" variant="outline" onClick={accountModal.close}>
              Cancel
            </Button>
            <Button type="submit" disabled={createAccountMutation.isPending}>
              {createAccountMutation.isPending ? "Creating..." : "Create Account"}
            </Button>
          </ModalFooter>
        </form>
      </Modal>

      {/* Delete Confirmation Modal */}
      <ConfirmModal
        isOpen={deleteModal.isOpen}
        onClose={deleteModal.close}
        onConfirm={() => {
          if (selectedItem) {
            if (activeTab === 'group') {
              deleteGroupMutation.mutate(selectedItem.id);
            } else if (activeTab === 'app-account') {
              deleteAccountMutation.mutate(selectedItem.id);
            }
          }
        }}
        title="Delete Item"
        message={`Are you sure you want to delete "${selectedItem?.name || selectedItem?.username}"? This action cannot be undone.`}
        loading={deleteGroupMutation.isPending || deleteAccountMutation.isPending}
      />
    </DashboardLayout>
  );
}

export default AdminProfile;