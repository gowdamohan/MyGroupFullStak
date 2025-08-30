  import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";

function AdminCategories() {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [editingCategory, setEditingCategory] = useState<any>(null);
  const { toast } = useToast();

  const [categoryForm, setCategoryForm] = useState({
    name: '',
    description: '',
    icon: '',
    color: '#007bff',
    parentId: '',
    isActive: true
  });

  // Mock data - replace with real API calls
  const categoriesQuery = useQuery({
    queryKey: ['/api/admin/categories'],
    queryFn: () => Promise.resolve([
      {
        id: '1',
        name: 'Technology',
        description: 'Tech-related applications and services',
        icon: 'bi-laptop',
        color: '#007bff',
        parentId: null,
        isActive: true,
        appsCount: 45,
        subcategories: [
          { id: '11', name: 'Mobile Apps', appsCount: 28 },
          { id: '12', name: 'Web Development', appsCount: 17 }
        ]
      },
      {
        id: '2',
        name: 'Business',
        description: 'Business and enterprise solutions',
        icon: 'bi-briefcase',
        color: '#28a745',
        parentId: null,
        isActive: true,
        appsCount: 32,
        subcategories: [
          { id: '21', name: 'CRM', appsCount: 12 },
          { id: '22', name: 'Project Management', appsCount: 20 }
        ]
      },
      {
        id: '3',
        name: 'Education',
        description: 'Educational tools and platforms',
        icon: 'bi-mortarboard',
        color: '#ffc107',
        parentId: null,
        isActive: true,
        appsCount: 28,
        subcategories: [
          { id: '31', name: 'Online Courses', appsCount: 18 },
          { id: '32', name: 'Learning Management', appsCount: 10 }
        ]
      },
      {
        id: '4',
        name: 'Entertainment',
        description: 'Games, media, and entertainment',
        icon: 'bi-controller',
        color: '#dc3545',
        parentId: null,
        isActive: true,
        appsCount: 67,
        subcategories: [
          { id: '41', name: 'Games', appsCount: 45 },
          { id: '42', name: 'Streaming', appsCount: 22 }
        ]
      },
      {
        id: '5',
        name: 'Health & Fitness',
        description: 'Health, fitness, and wellness apps',
        icon: 'bi-heart-pulse',
        color: '#20c997',
        parentId: null,
        isActive: false,
        appsCount: 19,
        subcategories: [
          { id: '51', name: 'Fitness Tracking', appsCount: 12 },
          { id: '52', name: 'Mental Health', appsCount: 7 }
        ]
      }
    ])
  });

  const createCategoryMutation = useMutation({
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
        description: "Category created successfully",
      });
      setShowCreateModal(false);
      setCategoryForm({
        name: '',
        description: '',
        icon: '',
        color: '#007bff',
        parentId: '',
        isActive: true
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/categories'] });
    },
    onError: (error: any) => {
      toast({
        title: "Error",
        description: error.message || "Failed to create category",
        variant: "destructive",
      });
    }
  });

  const toggleCategoryMutation = useMutation({
    mutationFn: async ({ id, isActive }: { id: string; isActive: boolean }) => {
      return apiRequest(`/api/admin/categories/${id}/toggle`, {
        method: 'PATCH',
        body: JSON.stringify({ isActive }),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Category status updated",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/categories'] });
    }
  });

  const handleCreateCategory = (e: React.FormEvent) => {
    e.preventDefault();
    createCategoryMutation.mutate(categoryForm);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Categories', path: '/dashboard/admin/categories', active: true },
    { icon: 'bi-megaphone', label: 'My Ads', path: '/dashboard/admin/ads' },
    { icon: 'bi-building', label: 'Corporate Login', path: '/dashboard/admin/corporate-login' },
  ];

  return (
    <DashboardLayout 
      title="Category Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h4 className="mb-0">Create & Manage Categories</h4>
        <button 
          className="btn btn-primary"
          onClick={() => setShowCreateModal(true)}
          data-testid="button-create-category"
        >
          <i className="bi bi-plus me-2"></i>Create New Category
        </button>
      </div>

      {/* Categories Grid */}
      <div className="row g-4">
        {categoriesQuery.data?.map((category) => (
          <div key={category.id} className="col-lg-6 col-xl-4">
            <div className="card h-100">
              <div className="card-body">
                <div className="d-flex justify-content-between align-items-start mb-3">
                  <div className="d-flex align-items-center">
                    <div 
                      className="rounded-circle p-3 me-3"
                      style={{ backgroundColor: `${category.color}20`, color: category.color }}
                    >
                      <i className={`${category.icon} fs-4`}></i>
                    </div>
                    <div>
                      <h5 className="mb-1" data-testid={`category-name-${category.id}`}>
                        {category.name}
                      </h5>
                      <span className={`badge ${category.isActive ? 'bg-success' : 'bg-secondary'}`}>
                        {category.isActive ? 'Active' : 'Inactive'}
                      </span>
                    </div>
                  </div>
                  <div className="dropdown">
                    <button 
                      className="btn btn-outline-secondary btn-sm"
                      type="button"
                      data-bs-toggle="dropdown"
                    >
                      <i className="bi bi-three-dots"></i>
                    </button>
                    <ul className="dropdown-menu">
                      <li>
                        <a className="dropdown-item" href="#" onClick={() => setEditingCategory(category)}>
                          <i className="bi bi-pencil me-2"></i>Edit
                        </a>
                      </li>
                      <li>
                        <a 
                          className="dropdown-item" 
                          href="#"
                          onClick={() => toggleCategoryMutation.mutate({ 
                            id: category.id, 
                            isActive: !category.isActive 
                          })}
                        >
                          <i className={`bi bi-${category.isActive ? 'eye-slash' : 'eye'} me-2`}></i>
                          {category.isActive ? 'Deactivate' : 'Activate'}
                        </a>
                      </li>
                      <li><hr className="dropdown-divider" /></li>
                      <li>
                        <a className="dropdown-item text-danger" href="#">
                          <i className="bi bi-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>

                <p className="text-muted mb-3">{category.description}</p>

                <div className="row g-3 mb-3">
                  <div className="col-6">
                    <div className="text-center p-2 bg-light rounded">
                      <h5 className="mb-0 text-primary" data-testid={`apps-count-${category.id}`}>
                        {category.appsCount}
                      </h5>
                      <small className="text-muted">Total Apps</small>
                    </div>
                  </div>
                  <div className="col-6">
                    <div className="text-center p-2 bg-light rounded">
                      <h5 className="mb-0 text-info">
                        {category.subcategories.length}
                      </h5>
                      <small className="text-muted">Subcategories</small>
                    </div>
                  </div>
                </div>

                {category.subcategories.length > 0 && (
                  <div>
                    <h6 className="mb-2">Subcategories:</h6>
                    <div className="d-flex flex-wrap gap-1">
                      {category.subcategories.map((sub) => (
                        <span key={sub.id} className="badge bg-light text-dark">
                          {sub.name} ({sub.appsCount})
                        </span>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Create Category Modal */}
      {showCreateModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Create New Category</h5>
                <button 
                  type="button" 
                  className="btn-close"
                  onClick={() => setShowCreateModal(false)}
                ></button>
              </div>
              <form onSubmit={handleCreateCategory} data-testid="form-create-category">
                <div className="modal-body">
                  <div className="row g-3">
                    <div className="col-md-6">
                      <label htmlFor="categoryName" className="form-label">Category Name</label>
                      <input
                        type="text"
                        className="form-control"
                        id="categoryName"
                        value={categoryForm.name}
                        onChange={(e) => setCategoryForm(prev => ({...prev, name: e.target.value}))}
                        required
                        data-testid="input-category-name"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="categoryIcon" className="form-label">Icon Class</label>
                      <input
                        type="text"
                        className="form-control"
                        id="categoryIcon"
                        placeholder="bi-laptop"
                        value={categoryForm.icon}
                        onChange={(e) => setCategoryForm(prev => ({...prev, icon: e.target.value}))}
                        data-testid="input-category-icon"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="categoryColor" className="form-label">Color</label>
                      <input
                        type="color"
                        className="form-control form-control-color"
                        id="categoryColor"
                        value={categoryForm.color}
                        onChange={(e) => setCategoryForm(prev => ({...prev, color: e.target.value}))}
                        data-testid="input-category-color"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="categoryParent" className="form-label">Parent Category</label>
                      <select
                        className="form-select"
                        id="categoryParent"
                        value={categoryForm.parentId}
                        onChange={(e) => setCategoryForm(prev => ({...prev, parentId: e.target.value}))}
                        data-testid="select-parent-category"
                      >
                        <option value="">No Parent (Main Category)</option>
                        {categoriesQuery.data?.filter(cat => cat.parentId === null).map(cat => (
                          <option key={cat.id} value={cat.id}>{cat.name}</option>
                        ))}
                      </select>
                    </div>
                    <div className="col-12">
                      <label htmlFor="categoryDescription" className="form-label">Description</label>
                      <textarea
                        className="form-control"
                        id="categoryDescription"
                        rows={3}
                        value={categoryForm.description}
                        onChange={(e) => setCategoryForm(prev => ({...prev, description: e.target.value}))}
                        data-testid="textarea-category-description"
                      ></textarea>
                    </div>
                    <div className="col-12">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          id="categoryActive"
                          checked={categoryForm.isActive}
                          onChange={(e) => setCategoryForm(prev => ({...prev, isActive: e.target.checked}))}
                          data-testid="checkbox-category-active"
                        />
                        <label className="form-check-label" htmlFor="categoryActive">
                          Active (visible to users)
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="modal-footer">
                  <button 
                    type="button" 
                    className="btn btn-secondary"
                    onClick={() => setShowCreateModal(false)}
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    className="btn btn-primary"
                    disabled={createCategoryMutation.isPending}
                    data-testid="button-save-category"
                  >
                    {createCategoryMutation.isPending ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-2"></span>
                        Creating...
                      </>
                    ) : (
                      <>
                        <i className="bi bi-check me-2"></i>
                        Create Category
                      </>
                    )}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}

export default AdminCategories;