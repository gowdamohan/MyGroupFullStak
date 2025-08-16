import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";

function AdminCorporateLogin() {
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [selectedCorporate, setSelectedCorporate] = useState<any>(null);
  const { toast } = useToast();

  const [corporateForm, setCorporateForm] = useState({
    companyName: '',
    adminEmail: '',
    adminUsername: '',
    adminPassword: '',
    phoneNumber: '',
    address: '',
    country: '',
    state: '',
    industry: '',
    employees: '',
    website: ''
  });

  // Mock data - replace with real API calls
  const corporatesQuery = useQuery({
    queryKey: ['/api/admin/corporates'],
    queryFn: () => Promise.resolve([
      {
        id: '1',
        companyName: 'TechCorp Solutions',
        adminEmail: 'admin@techcorp.com',
        adminUsername: 'techcorp_admin',
        phoneNumber: '+1-555-0123',
        address: '123 Tech Street, Silicon Valley',
        country: 'United States',
        state: 'California',
        industry: 'Technology',
        employees: '500-1000',
        website: 'https://techcorp.com',
        status: 'active',
        regions: 5,
        branches: 45,
        totalUsers: 892,
        lastLogin: '2024-08-10T09:30:00Z',
        createdAt: '2024-01-15T10:00:00Z'
      },
      {
        id: '2',
        companyName: 'Global Manufacturing Inc',
        adminEmail: 'admin@globalmanuf.com',
        adminUsername: 'global_admin',
        phoneNumber: '+1-555-0456',
        address: '456 Industrial Ave, Detroit',
        country: 'United States',
        state: 'Michigan',
        industry: 'Manufacturing',
        employees: '1000+',
        website: 'https://globalmanuf.com',
        status: 'active',
        regions: 8,
        branches: 67,
        totalUsers: 1245,
        lastLogin: '2024-08-09T14:22:00Z',
        createdAt: '2024-02-01T11:30:00Z'
      },
      {
        id: '3',
        companyName: 'Education First Academy',
        adminEmail: 'admin@edufirst.edu',
        adminUsername: 'edu_admin',
        phoneNumber: '+1-555-0789',
        address: '789 Learning Blvd, Boston',
        country: 'United States',
        state: 'Massachusetts',
        industry: 'Education',
        employees: '100-500',
        website: 'https://edufirst.edu',
        status: 'pending',
        regions: 3,
        branches: 12,
        totalUsers: 234,
        lastLogin: null,
        createdAt: '2024-08-05T16:45:00Z'
      },
      {
        id: '4',
        companyName: 'HealthCare Plus',
        adminEmail: 'admin@healthplus.com',
        adminUsername: 'health_admin',
        phoneNumber: '+1-555-0321',
        address: '321 Medical Center Dr, Houston',
        country: 'United States',
        state: 'Texas',
        industry: 'Healthcare',
        employees: '500-1000',
        website: 'https://healthplus.com',
        status: 'suspended',
        regions: 4,
        branches: 28,
        totalUsers: 567,
        lastLogin: '2024-07-28T12:15:00Z',
        createdAt: '2024-03-10T13:20:00Z'
      }
    ])
  });

  const createCorporateMutation = useMutation({
    mutationFn: async (data: any) => {
      return apiRequest('/api/admin/corporates', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Corporate account created successfully",
      });
      setShowCreateModal(false);
      setCorporateForm({
        companyName: '',
        adminEmail: '',
        adminUsername: '',
        adminPassword: '',
        phoneNumber: '',
        address: '',
        country: '',
        state: '',
        industry: '',
        employees: '',
        website: ''
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/corporates'] });
    }
  });

  const updateStatusMutation = useMutation({
    mutationFn: async ({ id, status }: { id: string; status: string }) => {
      return apiRequest(`/api/admin/corporates/${id}/status`, {
        method: 'PATCH',
        body: JSON.stringify({ status }),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Corporate status updated successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/corporates'] });
    }
  });

  const getStatusBadge = (status: string) => {
    const statusClasses = {
      active: 'bg-success',
      pending: 'bg-warning',
      suspended: 'bg-danger',
      inactive: 'bg-secondary'
    };
    return statusClasses[status as keyof typeof statusClasses] || 'bg-secondary';
  };

  const getIndustryIcon = (industry: string) => {
    const industryIcons = {
      Technology: 'bi-laptop',
      Manufacturing: 'bi-gear',
      Education: 'bi-mortarboard',
      Healthcare: 'bi-heart-pulse',
      Finance: 'bi-bank',
      Retail: 'bi-shop'
    };
    return industryIcons[industry as keyof typeof industryIcons] || 'bi-building';
  };

  const handleCreateCorporate = (e: React.FormEvent) => {
    e.preventDefault();
    createCorporateMutation.mutate(corporateForm);
  };

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content' },
    { icon: 'bi-tags', label: 'Categories', path: '/dashboard/admin/categories' },
    { icon: 'bi-megaphone', label: 'My Ads', path: '/dashboard/admin/ads' },
    { icon: 'bi-building', label: 'Corporate Login', path: '/dashboard/admin/corporate-login', active: true },
  ];

  return (
    <DashboardLayout 
      title="Corporate Login Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      {/* Summary Cards */}
      <div className="row g-4 mb-4">
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-building fs-1 text-primary mb-3"></i>
              <h5 className="card-title">Total Corporates</h5>
              <h3 className="text-primary mb-0" data-testid="total-corporates">
                {corporatesQuery.data?.length || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-check-circle fs-1 text-success mb-3"></i>
              <h5 className="card-title">Active</h5>
              <h3 className="text-success mb-0" data-testid="active-corporates">
                {corporatesQuery.data?.filter(corp => corp.status === 'active').length || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-clock fs-1 text-warning mb-3"></i>
              <h5 className="card-title">Pending</h5>
              <h3 className="text-warning mb-0" data-testid="pending-corporates">
                {corporatesQuery.data?.filter(corp => corp.status === 'pending').length || 0}
              </h3>
            </div>
          </div>
        </div>
        <div className="col-md-3">
          <div className="card text-center">
            <div className="card-body">
              <i className="bi bi-people fs-1 text-info mb-3"></i>
              <h5 className="card-title">Total Users</h5>
              <h3 className="text-info mb-0" data-testid="total-corporate-users">
                {corporatesQuery.data?.reduce((sum, corp) => sum + corp.totalUsers, 0).toLocaleString() || 0}
              </h3>
            </div>
          </div>
        </div>
      </div>

      {/* Action Bar */}
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 className="mb-0">Corporate Accounts</h4>
          <small className="text-muted">Manage corporate login accounts and permissions</small>
        </div>
        <button 
          className="btn btn-primary"
          onClick={() => setShowCreateModal(true)}
          data-testid="button-create-corporate"
        >
          <i className="bi bi-plus me-2"></i>Create Corporate Account
        </button>
      </div>

      {/* Corporates Table */}
      <div className="card">
        <div className="card-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead>
                <tr>
                  <th>Company</th>
                  <th>Industry</th>
                  <th>Admin</th>
                  <th>Regions/Branches</th>
                  <th>Users</th>
                  <th>Status</th>
                  <th>Last Login</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {corporatesQuery.data?.map((corporate) => (
                  <tr key={corporate.id}>
                    <td>
                      <div className="d-flex align-items-center">
                        <div className={`bg-light rounded-circle p-2 me-3`}>
                          <i className={`${getIndustryIcon(corporate.industry)} text-primary`}></i>
                        </div>
                        <div>
                          <h6 className="mb-0" data-testid={`company-name-${corporate.id}`}>
                            {corporate.companyName}
                          </h6>
                          <small className="text-muted">{corporate.country}, {corporate.state}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span className="badge bg-info">{corporate.industry}</span>
                    </td>
                    <td>
                      <div>
                        <div className="fw-bold">{corporate.adminUsername}</div>
                        <small className="text-muted">{corporate.adminEmail}</small>
                      </div>
                    </td>
                    <td>
                      <div className="text-center">
                        <div><strong>{corporate.regions}</strong> regions</div>
                        <div><strong>{corporate.branches}</strong> branches</div>
                      </div>
                    </td>
                    <td>
                      <div className="text-center">
                        <h6 className="mb-0 text-primary">{corporate.totalUsers.toLocaleString()}</h6>
                        <small className="text-muted">total users</small>
                      </div>
                    </td>
                    <td>
                      <span className={`badge ${getStatusBadge(corporate.status)}`}>
                        {corporate.status}
                      </span>
                    </td>
                    <td>
                      {corporate.lastLogin ? (
                        <small>{new Date(corporate.lastLogin).toLocaleDateString()}</small>
                      ) : (
                        <small className="text-muted">Never</small>
                      )}
                    </td>
                    <td>
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
                            <a className="dropdown-item" href="#" onClick={() => setSelectedCorporate(corporate)}>
                              <i className="bi bi-eye me-2"></i>View Details
                            </a>
                          </li>
                          <li>
                            <a className="dropdown-item" href="#">
                              <i className="bi bi-pencil me-2"></i>Edit
                            </a>
                          </li>
                          <li><hr className="dropdown-divider" /></li>
                          {corporate.status === 'active' && (
                            <li>
                              <a 
                                className="dropdown-item text-warning" 
                                href="#"
                                onClick={() => updateStatusMutation.mutate({ id: corporate.id, status: 'suspended' })}
                              >
                                <i className="bi bi-pause-circle me-2"></i>Suspend
                              </a>
                            </li>
                          )}
                          {corporate.status === 'suspended' && (
                            <li>
                              <a 
                                className="dropdown-item text-success" 
                                href="#"
                                onClick={() => updateStatusMutation.mutate({ id: corporate.id, status: 'active' })}
                              >
                                <i className="bi bi-play-circle me-2"></i>Activate
                              </a>
                            </li>
                          )}
                          {corporate.status === 'pending' && (
                            <li>
                              <a 
                                className="dropdown-item text-success" 
                                href="#"
                                onClick={() => updateStatusMutation.mutate({ id: corporate.id, status: 'active' })}
                              >
                                <i className="bi bi-check-circle me-2"></i>Approve
                              </a>
                            </li>
                          )}
                        </ul>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Create Corporate Modal */}
      {showCreateModal && (
        <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-xl">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Create Corporate Account</h5>
                <button 
                  type="button" 
                  className="btn-close"
                  onClick={() => setShowCreateModal(false)}
                ></button>
              </div>
              <form onSubmit={handleCreateCorporate} data-testid="form-create-corporate">
                <div className="modal-body">
                  <div className="row g-3">
                    <div className="col-12">
                      <h6 className="text-primary mb-3">Company Information</h6>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="companyName" className="form-label">Company Name</label>
                      <input
                        type="text"
                        className="form-control"
                        id="companyName"
                        value={corporateForm.companyName}
                        onChange={(e) => setCorporateForm(prev => ({...prev, companyName: e.target.value}))}
                        required
                        data-testid="input-company-name"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="industry" className="form-label">Industry</label>
                      <select
                        className="form-select"
                        id="industry"
                        value={corporateForm.industry}
                        onChange={(e) => setCorporateForm(prev => ({...prev, industry: e.target.value}))}
                        required
                        data-testid="select-industry"
                      >
                        <option value="">Select Industry</option>
                        <option value="Technology">Technology</option>
                        <option value="Manufacturing">Manufacturing</option>
                        <option value="Education">Education</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Finance">Finance</option>
                        <option value="Retail">Retail</option>
                      </select>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="employees" className="form-label">Number of Employees</label>
                      <select
                        className="form-select"
                        id="employees"
                        value={corporateForm.employees}
                        onChange={(e) => setCorporateForm(prev => ({...prev, employees: e.target.value}))}
                        required
                        data-testid="select-employees"
                      >
                        <option value="">Select Range</option>
                        <option value="1-50">1-50</option>
                        <option value="51-100">51-100</option>
                        <option value="101-500">101-500</option>
                        <option value="501-1000">501-1000</option>
                        <option value="1000+">1000+</option>
                      </select>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="website" className="form-label">Website</label>
                      <input
                        type="url"
                        className="form-control"
                        id="website"
                        value={corporateForm.website}
                        onChange={(e) => setCorporateForm(prev => ({...prev, website: e.target.value}))}
                        data-testid="input-website"
                      />
                    </div>
                    
                    <div className="col-12 mt-4">
                      <h6 className="text-primary mb-3">Admin Account</h6>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adminUsername" className="form-label">Admin Username</label>
                      <input
                        type="text"
                        className="form-control"
                        id="adminUsername"
                        value={corporateForm.adminUsername}
                        onChange={(e) => setCorporateForm(prev => ({...prev, adminUsername: e.target.value}))}
                        required
                        data-testid="input-admin-username"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adminEmail" className="form-label">Admin Email</label>
                      <input
                        type="email"
                        className="form-control"
                        id="adminEmail"
                        value={corporateForm.adminEmail}
                        onChange={(e) => setCorporateForm(prev => ({...prev, adminEmail: e.target.value}))}
                        required
                        data-testid="input-admin-email"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="adminPassword" className="form-label">Admin Password</label>
                      <input
                        type="password"
                        className="form-control"
                        id="adminPassword"
                        minLength={8}
                        value={corporateForm.adminPassword}
                        onChange={(e) => setCorporateForm(prev => ({...prev, adminPassword: e.target.value}))}
                        required
                        data-testid="input-admin-password"
                      />
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="phoneNumber" className="form-label">Phone Number</label>
                      <input
                        type="tel"
                        className="form-control"
                        id="phoneNumber"
                        value={corporateForm.phoneNumber}
                        onChange={(e) => setCorporateForm(prev => ({...prev, phoneNumber: e.target.value}))}
                        required
                        data-testid="input-phone"
                      />
                    </div>

                    <div className="col-12 mt-4">
                      <h6 className="text-primary mb-3">Location</h6>
                    </div>
                    <div className="col-12">
                      <label htmlFor="address" className="form-label">Address</label>
                      <textarea
                        className="form-control"
                        id="address"
                        rows={2}
                        value={corporateForm.address}
                        onChange={(e) => setCorporateForm(prev => ({...prev, address: e.target.value}))}
                        required
                        data-testid="textarea-address"
                      ></textarea>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="country" className="form-label">Country</label>
                      <select
                        className="form-select"
                        id="country"
                        value={corporateForm.country}
                        onChange={(e) => setCorporateForm(prev => ({...prev, country: e.target.value}))}
                        required
                        data-testid="select-country"
                      >
                        <option value="">Select Country</option>
                        <option value="United States">United States</option>
                        <option value="Canada">Canada</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="India">India</option>
                      </select>
                    </div>
                    <div className="col-md-6">
                      <label htmlFor="state" className="form-label">State/Province</label>
                      <input
                        type="text"
                        className="form-control"
                        id="state"
                        value={corporateForm.state}
                        onChange={(e) => setCorporateForm(prev => ({...prev, state: e.target.value}))}
                        required
                        data-testid="input-state"
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
                    disabled={createCorporateMutation.isPending}
                    data-testid="button-save-corporate"
                  >
                    {createCorporateMutation.isPending ? (
                      <>
                        <span className="spinner-border spinner-border-sm me-2"></span>
                        Creating...
                      </>
                    ) : (
                      <>
                        <i className="bi bi-check me-2"></i>
                        Create Corporate Account
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

export default AdminCorporateLogin;