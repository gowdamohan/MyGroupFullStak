import { useState } from "react";
import { useQuery, useMutation } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { apiRequest, queryClient } from "@/lib/queryClient";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import ContinentManagement from "@/components/content/ContinentManagement";
import CountryManagement from "@/components/content/CountryManagement";
import StateManagement from "@/components/content/StateManagement";
import DistrictManagement from "@/components/content/DistrictManagement";

function AdminContent() {
  const [activeTab, setActiveTab] = useState('continent');
  const { toast } = useToast();



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
                className={`list-group-item list-group-item-action ${activeTab === 'continent' ? 'active' : ''}`}
                onClick={() => setActiveTab('continent')}
                data-testid="tab-continent"
              >
                <i className="bi bi-globe me-2"></i>Continent
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'country' ? 'active' : ''}`}
                onClick={() => setActiveTab('country')}
                data-testid="tab-country"
              >
                <i className="bi bi-flag me-2"></i>Country
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'state' ? 'active' : ''}`}
                onClick={() => setActiveTab('state')}
                data-testid="tab-state"
              >
                <i className="bi bi-map me-2"></i>State
              </button>
              <button
                className={`list-group-item list-group-item-action ${activeTab === 'district' ? 'active' : ''}`}
                onClick={() => setActiveTab('district')}
                data-testid="tab-district"
              >
                <i className="bi bi-geo-alt me-2"></i>District
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
          {/* Continent Management Tab */}
          {activeTab === 'continent' && (
            <div className="card">
              <div className="card-body">
                <ContinentManagement />
              </div>
            </div>
          )}

          {/* Country Management Tab */}
          {activeTab === 'country' && (
            <div className="card">
              <div className="card-body">
                <CountryManagement />
              </div>
            </div>
          )}

          {/* State Management Tab */}
          {activeTab === 'state' && (
            <div className="card">
              <div className="card-body">
                <StateManagement />
              </div>
            </div>
          )}

          {/* District Management Tab */}
          {activeTab === 'district' && (
            <div className="card">
              <div className="card-body">
                <DistrictManagement />
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