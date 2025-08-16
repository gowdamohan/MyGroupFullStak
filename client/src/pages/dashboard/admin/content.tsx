import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";

function AdminContent() {
  const [activeTab, setActiveTab] = useState('location');
  const [selectedCountry, setSelectedCountry] = useState('');
  const [selectedState, setSelectedState] = useState('');
  const { toast } = useToast();

  // Mock data - replace with real API calls
  const locationsQuery = useQuery({
    queryKey: ['/api/admin/locations'],
    queryFn: () => Promise.resolve({
      countries: [
        { id: '1', name: 'United States', code: 'US', states: 50, districts: 3142 },
        { id: '2', name: 'India', code: 'IN', states: 28, districts: 766 },
        { id: '3', name: 'United Kingdom', code: 'UK', states: 4, districts: 48 },
        { id: '4', name: 'Canada', code: 'CA', states: 13, districts: 293 },
      ],
      states: [
        { id: '1', name: 'California', countryId: '1', districts: 58 },
        { id: '2', name: 'Texas', countryId: '1', districts: 254 },
        { id: '3', name: 'Maharashtra', countryId: '2', districts: 36 },
        { id: '4', name: 'Karnataka', countryId: '2', districts: 31 },
      ],
      districts: [
        { id: '1', name: 'Los Angeles', stateId: '1' },
        { id: '2', name: 'San Francisco', stateId: '1' },
        { id: '3', name: 'Mumbai', stateId: '3' },
        { id: '4', name: 'Pune', stateId: '3' },
      ]
    })
  });

  const languagesQuery = useQuery({
    queryKey: ['/api/admin/languages'],
    queryFn: () => Promise.resolve([
      { id: '1', name: 'English', code: 'en', isActive: true, speakers: '1.5B' },
      { id: '2', name: 'Spanish', code: 'es', isActive: true, speakers: '500M' },
      { id: '3', name: 'Hindi', code: 'hi', isActive: true, speakers: '600M' },
      { id: '4', name: 'Chinese', code: 'zh', isActive: false, speakers: '1.4B' },
      { id: '5', name: 'French', code: 'fr', isActive: true, speakers: '280M' },
    ])
  });

  const educationQuery = useQuery({
    queryKey: ['/api/admin/education'],
    queryFn: () => Promise.resolve([
      { id: '1', level: 'High School', isActive: true, users: 2450 },
      { id: '2', level: 'Bachelor\'s Degree', isActive: true, users: 4230 },
      { id: '3', level: 'Master\'s Degree', isActive: true, users: 2100 },
      { id: '4', level: 'PhD/Doctorate', isActive: true, users: 890 },
      { id: '5', level: 'Professional Certificate', isActive: true, users: 1560 },
    ])
  });

  const professionsQuery = useQuery({
    queryKey: ['/api/admin/professions'],
    queryFn: () => Promise.resolve([
      { id: '1', name: 'Software Engineer', category: 'Technology', isActive: true, users: 1850 },
      { id: '2', name: 'Marketing Manager', category: 'Marketing', isActive: true, users: 920 },
      { id: '3', name: 'Data Scientist', category: 'Technology', isActive: true, users: 650 },
      { id: '4', name: 'Product Manager', category: 'Management', isActive: true, users: 480 },
      { id: '5', name: 'Sales Representative', category: 'Sales', isActive: true, users: 1200 },
    ])
  });

  const addLocationMutation = useMutation({
    mutationFn: async (data: any) => {
      return apiRequest('/api/admin/locations', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
      });
    },
    onSuccess: () => {
      toast({
        title: "Success",
        description: "Location added successfully",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/admin/locations'] });
    }
  });

  const menuItems = [
    { icon: 'bi-speedometer2', label: 'Dashboard', path: '/dashboard/admin' },
    { icon: 'bi-person', label: 'Profile', path: '/dashboard/admin/profile' },
    { icon: 'bi-file-text', label: 'Content', path: '/dashboard/admin/content', active: true },
    { icon: 'bi-tags', label: 'Categories', path: '/dashboard/admin/categories' },
    { icon: 'bi-megaphone', label: 'My Ads', path: '/dashboard/admin/ads' },
    { icon: 'bi-building', label: 'Corporate Login', path: '/dashboard/admin/corporate-login' },
  ];

  return (
    <DashboardLayout 
      title="Content Management" 
      userRole="admin"
      menuItems={menuItems}
    >
      <div className="row">
        <div className="col-lg-3">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Content Sections</h5>
            </div>
            <div className="list-group list-group-flush">
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'location' ? 'active' : ''}`}
                onClick={() => setActiveTab('location')}
                data-testid="tab-location"
              >
                <i className="bi bi-geo-alt me-2"></i>Location Management
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'language' ? 'active' : ''}`}
                onClick={() => setActiveTab('language')}
                data-testid="tab-language"
              >
                <i className="bi bi-translate me-2"></i>Language Management
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'education' ? 'active' : ''}`}
                onClick={() => setActiveTab('education')}
                data-testid="tab-education"
              >
                <i className="bi bi-mortarboard me-2"></i>Education Levels
              </button>
              <button 
                className={`list-group-item list-group-item-action ${activeTab === 'profession' ? 'active' : ''}`}
                onClick={() => setActiveTab('profession')}
                data-testid="tab-profession"
              >
                <i className="bi bi-briefcase me-2"></i>Professions
              </button>
            </div>
          </div>
        </div>

        <div className="col-lg-9">
          {/* Location Management Tab */}
          {activeTab === 'location' && (
            <div className="card">
              <div className="card-header d-flex justify-content-between align-items-center">
                <h5 className="card-title mb-0">Location Management</h5>
                <button className="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                  <i className="bi bi-plus me-1"></i>Add Location
                </button>
              </div>
              <div className="card-body">
                <div className="row g-4">
                  {/* Countries */}
                  <div className="col-md-4">
                    <h6 className="mb-3">Countries ({locationsQuery.data?.countries.length})</h6>
                    <div className="list-group">
                      {locationsQuery.data?.countries.map((country) => (
                        <div key={country.id} className="list-group-item">
                          <div className="d-flex justify-content-between align-items-center">
                            <div>
                              <h6 className="mb-1">{country.name}</h6>
                              <small className="text-muted">{country.states} states, {country.districts} districts</small>
                            </div>
                            <span className="badge bg-primary">{country.code}</span>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* States */}
                  <div className="col-md-4">
                    <h6 className="mb-3">States/Provinces ({locationsQuery.data?.states.length})</h6>
                    <div className="list-group">
                      {locationsQuery.data?.states.map((state) => (
                        <div key={state.id} className="list-group-item">
                          <div className="d-flex justify-content-between align-items-center">
                            <div>
                              <h6 className="mb-1">{state.name}</h6>
                              <small className="text-muted">{state.districts} districts</small>
                            </div>
                            <button className="btn btn-sm btn-outline-primary">
                              <i className="bi bi-pencil"></i>
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Districts */}
                  <div className="col-md-4">
                    <h6 className="mb-3">Districts ({locationsQuery.data?.districts.length})</h6>
                    <div className="list-group">
                      {locationsQuery.data?.districts.map((district) => (
                        <div key={district.id} className="list-group-item">
                          <div className="d-flex justify-content-between align-items-center">
                            <h6 className="mb-0">{district.name}</h6>
                            <button className="btn btn-sm btn-outline-primary">
                              <i className="bi bi-pencil"></i>
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Language Management Tab */}
          {activeTab === 'language' && (
            <div className="card">
              <div className="card-header d-flex justify-content-between align-items-center">
                <h5 className="card-title mb-0">Language Management</h5>
                <button className="btn btn-primary btn-sm">
                  <i className="bi bi-plus me-1"></i>Add Language
                </button>
              </div>
              <div className="card-body">
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>Language</th>
                        <th>Code</th>
                        <th>Native Speakers</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {languagesQuery.data?.map((language) => (
                        <tr key={language.id}>
                          <td>
                            <strong>{language.name}</strong>
                          </td>
                          <td>
                            <code>{language.code}</code>
                          </td>
                          <td>{language.speakers}</td>
                          <td>
                            <span className={`badge ${language.isActive ? 'bg-success' : 'bg-secondary'}`}>
                              {language.isActive ? 'Active' : 'Inactive'}
                            </span>
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <button className="btn btn-outline-primary">
                                <i className="bi bi-pencil"></i>
                              </button>
                              <button className="btn btn-outline-danger">
                                <i className="bi bi-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          )}

          {/* Education Levels Tab */}
          {activeTab === 'education' && (
            <div className="card">
              <div className="card-header d-flex justify-content-between align-items-center">
                <h5 className="card-title mb-0">Education Levels</h5>
                <button className="btn btn-primary btn-sm">
                  <i className="bi bi-plus me-1"></i>Add Education Level
                </button>
              </div>
              <div className="card-body">
                <div className="row g-3">
                  {educationQuery.data?.map((education) => (
                    <div key={education.id} className="col-md-6">
                      <div className="card h-100">
                        <div className="card-body">
                          <div className="d-flex justify-content-between align-items-start mb-3">
                            <h6 className="card-title mb-0">{education.level}</h6>
                            <span className={`badge ${education.isActive ? 'bg-success' : 'bg-secondary'}`}>
                              {education.isActive ? 'Active' : 'Inactive'}
                            </span>
                          </div>
                          <div className="d-flex justify-content-between align-items-center">
                            <div>
                              <small className="text-muted">Users with this level:</small>
                              <h5 className="mb-0 text-primary">{education.users.toLocaleString()}</h5>
                            </div>
                            <div className="btn-group btn-group-sm">
                              <button className="btn btn-outline-primary">
                                <i className="bi bi-pencil"></i>
                              </button>
                              <button className="btn btn-outline-danger">
                                <i className="bi bi-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* Professions Tab */}
          {activeTab === 'profession' && (
            <div className="card">
              <div className="card-header d-flex justify-content-between align-items-center">
                <h5 className="card-title mb-0">Profession Management</h5>
                <button className="btn btn-primary btn-sm">
                  <i className="bi bi-plus me-1"></i>Add Profession
                </button>
              </div>
              <div className="card-body">
                <div className="table-responsive">
                  <table className="table table-hover">
                    <thead>
                      <tr>
                        <th>Profession</th>
                        <th>Category</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {professionsQuery.data?.map((profession) => (
                        <tr key={profession.id}>
                          <td>
                            <div className="d-flex align-items-center">
                              <i className="bi bi-briefcase me-2 text-muted"></i>
                              <strong>{profession.name}</strong>
                            </div>
                          </td>
                          <td>
                            <span className="badge bg-info">{profession.category}</span>
                          </td>
                          <td>
                            <strong>{profession.users.toLocaleString()}</strong>
                          </td>
                          <td>
                            <span className={`badge ${profession.isActive ? 'bg-success' : 'bg-secondary'}`}>
                              {profession.isActive ? 'Active' : 'Inactive'}
                            </span>
                          </td>
                          <td>
                            <div className="btn-group btn-group-sm">
                              <button className="btn btn-outline-primary">
                                <i className="bi bi-pencil"></i>
                              </button>
                              <button className="btn btn-outline-danger">
                                <i className="bi bi-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}

export default AdminContent;