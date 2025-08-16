import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";

function AdminAds() {
  const [activeTab, setActiveTab] = useState('all');
  const [showCreateModal, setShowCreateModal] = useState(false);
  const { toast } = useToast();

  const [adForm, setAdForm] = useState({
    title: '',
    description: '',
    imageUrl: '',
    targetUrl: '',
    category: '',
    budget: '',
    startDate: '',
    endDate: '',
    status: 'draft'
  });

  // Mock data - replace with real API calls
  const adsQuery = useQuery({
    queryKey: ['/api/admin/ads', activeTab],
    queryFn: () => Promise.resolve([
      {
        id: '1',
        title: 'Premium App Launch',
        description: 'Discover our new premium productivity app with advanced features',
        imageUrl: 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400',
        targetUrl: 'https://example.com/app1',
        category: 'Technology',
        budget: 5000,
        spent: 3200,
        impressions: 125400,
        clicks: 2340,
        conversions: 145,
        status: 'active',
        startDate: '2024-08-01',
        endDate: '2024-08-31',
        createdAt: '2024-07-25T10:00:00Z'
      },
      {
        id: '2',
        title: 'Business Solutions Suite',
        description: 'Complete business management solution for enterprises',
        imageUrl: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400',
        targetUrl: 'https://example.com/business',
        category: 'Business',
        budget: 8000,
        spent: 2100,
        impressions: 89200,
        clicks: 1560,
        conversions: 98,
        status: 'active',
        startDate: '2024-08-05',
        endDate: '2024-09-05',
        createdAt: '2024-07-30T14:30:00Z'
      },
      {
        id: '3',
        title: 'Educational Platform',
        description: 'Learn new skills with our comprehensive online courses',
        imageUrl: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400',
        targetUrl: 'https://example.com/education',
        category: 'Education',
        budget: 3000,
        spent: 3000,
        impressions: 67800,
        clicks: 890,
        conversions: 67,
        status: 'completed',
        startDate: '2024-07-01',
        endDate: '2024-07-31',
        createdAt: '2024-06-25T09:15:00Z'
      },
      {
        id: '4',
        title: 'Gaming Experience',
        description: 'Next-generation gaming platform with immersive experiences',
        imageUrl: 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400',
        targetUrl: 'https://example.com/gaming',
        category: 'Entertainment',
        budget: 12000,
        spent: 0,
        impressions: 0,
        clicks: 0,
        conversions: 0,
        status: 'draft',
        startDate: '2024-09-01',
        endDate: '2024-09-30',
        createdAt: '2024-08-10T16:45:00Z'
      }
    ])
  });

  const createAdMutation = useMutation({
    mutationFn: async (data: any) => {
      return apiRequest('/api/admin/ads', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Advertisement created successfully",
      });
      setShowCreateModal(false);
      setAdForm({
        title: '',
        description: '',
        imageUrl: '',
        targetUrl: '',
        category: '',
        budget: '',
        startDate: '',
        endDate: '',
        status: 'draft'
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/ads'] });
    }
  });

  const filteredAds = adsQuery.data?.filter(ad => {
    if (activeTab === 'all') return true;
    return ad.status === activeTab;
  }) || [];

  const getStatusBadge = (status: string) => {
    const statusClasses = {
      active: 'bg-success',
      draft: 'bg-secondary',
      paused: 'bg-warning',
      completed: 'bg-info',
      expired: 'bg-danger'
    };
    return statusClasses[status as keyof typeof statusClasses] || 'bg-secondary';
  };

  const handleCreateAd = (e: React.FormEvent) => {
    e.preventDefault();
    createAdMutation.mutate(adForm);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Categories', path: '/dashboard/admin/categories' },
    { icon: 'bi-megaphone', label: 'My Ads', path: '/dashboard/admin/ads', active: true },
    { icon: 'bi-building', label: 'Corporate Login', path: '/dashboard/admin/corporate-login' },
  ];

  return (
    <DashboardLayout 
      title="Advertisement Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      {/* Header with Stats */}
      <div className="row g-4 mb-4">
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-megaphone fs-1 text-primary mb-3"></i>
              <h5 className="card-title">Total Ads</h5>
              <h3 className="text-primary mb-0" data-testid="total-ads">
                {adsQuery.data?.length || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-play-circle fs-1 text-success mb-3"></i>
              <h5 className="card-title">Active</h5>
              <h3 className="text-success mb-0" data-testid="active-ads">
                {adsQuery.data?.filter(ad => ad.status === 'active').length || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-eye fs-1 text-info mb-3"></i>
              <h5 className="card-title">Total Impressions</h5>
              <h3 className="text-info mb-0" data-testid="total-impressions">
                {adsQuery.data?.reduce((sum, ad) => sum + ad.impressions, 0).toLocaleString() || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-currency-dollar fs-1 text-warning mb-3"></i>
              <h5 className="card-title">Total Spent</h5>
              <h3 className="text-warning mb-0" data-testid="total-spent">
                ${adsQuery.data?.reduce((sum, ad) => sum + ad.spent, 0).toLocaleString() || 0}
              </h3>
            </div>
          </div>
        </div>
      </div>

      {/* Action Bar */}
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 className="mb-0">My Advertisements</h4>
          <small className="text-muted">Manage your advertising campaigns</small>
        </div>
        <button 
          className="btn btn-primary"
          onClick={() => setShowCreateModal(true)}
          data-testid="button-create-ad"
        >
          <i className="bi bi-plus me-2"></i>Create New Ad
        </button>
      </div>

      {/* Filter Tabs */}
      <div className="card mb-4">
        <div className="card-body">
          <ul className="nav nav-pills">
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'all' ? 'active' : ''}`}
                onClick={() => setActiveTab('all')}
                data-testid="tab-all"
              >
                All Ads ({adsQuery.data?.length || 0})
              </button>
            </li>
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'active' ? 'active' : ''}`}
                onClick={() => setActiveTab('active')}
                data-testid="tab-active"
              >
                Active ({adsQuery.data?.filter(ad => ad.status === 'active').length || 0})
              </button>
            </li>
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'draft' ? 'active' : ''}`}
                onClick={() => setActiveTab('draft')}
                data-testid="tab-draft"
              >
                Draft ({adsQuery.data?.filter(ad => ad.status === 'draft').length || 0})
              </button>
            </li>
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'completed' ? 'active' : ''}`}
                onClick={() => setActiveTab('completed')}
                data-testid="tab-completed"
              >
                Completed ({adsQuery.data?.filter(ad => ad.status === 'completed').length || 0})
              </button>
            </li>
          </ul>
        </div>
      </div>

      {/* Ads Grid */}
      <div className="row g-4">
        {filteredAds.map((ad) => (
          <div key={ad.id} className="col-lg-6">
            <div className="card h-100">
              <div className="row g-0 h-100">
                <div className="col-md-4">
                  <img 
                    src={ad.imageUrl} 
                    className="img-fluid rounded-start h-100 object-fit-cover"
                    alt={ad.title}
                    style={{ minHeight: '200px' }}
                  />
                </div>
                <div className="col-md-8">
                  <div className="card-body d-flex flex-column h-100">
                    <div className="d-flex justify-content-between align-items-start mb-2">
                      <h5 className="card-title mb-0" data-testid={`ad-title-${ad.id}`}>
                        {ad.title}
                      </h5>
                      <span className={`badge ${getStatusBadge(ad.status)}`}>
                        {ad.status}
                      </span>
                    </div>
                    
                    <p className="card-text text-muted mb-3">{ad.description}</p>
                    
                    <div className="row g-2 mb-3 small">
                      <div className="col-6">
                        <div className="bg-light p-2 rounded text-center">
                          <div className="fw-bold text-primary">{ad.impressions.toLocaleString()}</div>
                          <div className="text-muted">Impressions</div>
                        </div>
                      </div>
                      <div className="col-6">
                        <div className="bg-light p-2 rounded text-center">
                          <div className="fw-bold text-success">{ad.clicks.toLocaleString()}</div>
                          <div className="text-muted">Clicks</div>
                        </div>
                      </div>
                    </div>

                    <div className="row g-2 mb-3 small">
                      <div className="col-6">
                        <div className="bg-light p-2 rounded text-center">
                          <div className="fw-bold text-info">{ad.conversions}</div>
                          <div className="text-muted">Conversions</div>
                        </div>
                      </div>
                      <div className="col-6">
                        <div className="bg-light p-2 rounded text-center">
                          <div className="fw-bold text-warning">${ad.spent.toLocaleString()}</div>
                          <div className="text-muted">Spent</div>
                        </div>
                      </div>
                    </div>

                    {/* Progress Bar */}
                    <div className="mb-3">
                      <div className="d-flex justify-content-between small mb-1">
                        <span>Budget Progress</span>
                        <span>${ad.spent.toLocaleString()} / ${ad.budget.toLocaleString()}</span>
                      </div>
                      <div className="progress" style={{ height: '6px' }}>
                        <div 
                          className="progress-bar" 
                          style={{ width: `${Math.min((ad.spent / ad.budget) * 100, 100)}%` }}
                        ></div>
                      </div>
                    </div>

                    <div className="mt-auto">
                      <div className="d-flex justify-content-between align-items-center">
                        <small className="text-muted">
                          {new Date(ad.startDate).toLocaleDateString()} - {new Date(ad.endDate).toLocaleDateString()}
                        </small>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-outline-primary">
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-outline-info">
                            <i className="bi bi-bar-chart"></i>
                          </button>
                          <button className="btn btn-outline-secondary">
                            <i className="bi bi-three-dots"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {filteredAds.length === 0 && (
        <div className="text-center py-5">
          <i className="bi bi-megaphone fs-1 text-muted mb-3"></i>
          <h4 className="text-muted">No advertisements found</h4>
          <p className="text-muted">Create your first advertisement to get started.</p>
          <button 
            className="btn btn-primary"
            onClick={() => setShowCreateModal(true)}
          >
            <i className="bi bi-plus me-2"></i>Create New Ad
          </button>
        </div>
      )}

      {/* Create Ad Modal */}
      {showCreateModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Create New Advertisement</h5>
                <button 
                  type="button" 
                  className="btn-close"
                  onClick={() => setShowCreateModal(false)}
                ></button>
              </div>
              <form onSubmit={handleCreateAd} data-testid="form-create-ad">
                <div className="modal-body">
                  <div className="row g-3">
                    <div className="col-md-6">
                      <label htmlFor="adTitle" className="form-label">Ad Title</label>
                      <input
                        type="text"
                        className="form-control"
                        id="adTitle"
                        value={adForm.title}
                        onChange={(e) => setAdForm(prev => ({...prev, title: e.target.value}))}
                        required
                        data-testid="input-ad-title"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adCategory" className="form-label">Category</label>
                      <select
                        className="form-select"
                        id="adCategory"
                        value={adForm.category}
                        onChange={(e) => setAdForm(prev => ({...prev, category: e.target.value}))}
                        required
                        data-testid="select-ad-category"
                      >
                        <option value="">Select Category</option>
                        <option value="Technology">Technology</option>
                        <option value="Business">Business</option>
                        <option value="Education">Education</option>
                        <option value="Entertainment">Entertainment</option>
                      </select>
                    </div>
                    <div className="col-12">
                      <label htmlFor="adDescription" className="form-label">Description</label>
                      <textarea
                        className="form-control"
                        id="adDescription"
                        rows={3}
                        value={adForm.description}
                        onChange={(e) => setAdForm(prev => ({...prev, description: e.target.value}))}
                        required
                        data-testid="textarea-ad-description"
                      ></textarea>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adImageUrl" className="form-label">Image URL</label>
                      <input
                        type="url"
                        className="form-control"
                        id="adImageUrl"
                        value={adForm.imageUrl}
                        onChange={(e) => setAdForm(prev => ({...prev, imageUrl: e.target.value}))}
                        data-testid="input-ad-image"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adTargetUrl" className="form-label">Target URL</label>
                      <input
                        type="url"
                        className="form-control"
                        id="adTargetUrl"
                        value={adForm.targetUrl}
                        onChange={(e) => setAdForm(prev => ({...prev, targetUrl: e.target.value}))}
                        required
                        data-testid="input-ad-target-url"
                      />
                    </div>
                    <div className="col-md-4">
                      <label htmlFor="adBudget" className="form-label">Budget ($)</label>
                      <input
                        type="number"
                        className="form-control"
                        id="adBudget"
                        min="0"
                        step="100"
                        value={adForm.budget}
                        onChange={(e) => setAdForm(prev => ({...prev, budget: e.target.value}))}
                        required
                        data-testid="input-ad-budget"
                      />
                    </div>
                    <div className="col-md-4">
                      <label htmlFor="adStartDate" className="form-label">Start Date</label>
                      <input
                        type="date"
                        className="form-control"
                        id="adStartDate"
                        value={adForm.startDate}
                        onChange={(e) => setAdForm(prev => ({...prev, startDate: e.target.value}))}
                        required
                        data-testid="input-ad-start-date"
                      />
                    </div>
                    <div className="col-md-4">
                      <label htmlFor="adEndDate" className="form-label">End Date</label>
                      <input
                        type="date"
                        className="form-control"
                        id="adEndDate"
                        value={adForm.endDate}
                        onChange={(e) => setAdForm(prev => ({...prev, endDate: e.target.value}))}
                        required
                        data-testid="input-ad-end-date"
                      />
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
                    disabled={createAdMutation.isPending}
                    data-testid="button-save-ad"
                  >
                    {createAdMutation.isPending ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-2"></span>
                        Creating...
                      </>
                    ) : (
                      <>
                        <i className="bi bi-check me-2"></i>
                        Create Advertisement
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

export default AdminAds;