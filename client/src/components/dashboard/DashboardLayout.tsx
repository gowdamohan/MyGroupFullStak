import { useState } from "react";
import { useLocation } from "wouter";

interface MenuItem {
  icon: string;
  label: string;
  path: string;
  active?: boolean;
}

interface DashboardLayoutProps {
  title: string;
  userRole: string;
  menuItems: MenuItem[];
  children: React.ReactNode;
}

export default function DashboardLayout({ title, userRole, menuItems, children }: DashboardLayoutProps) {
  const [, setLocation] = useLocation();
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false);

  const handleLogout = () => {
    // TODO: Implement actual logout logic
    setLocation('/auth/login');
  };

  const getRoleColor = (role: string) => {
    switch(role) {
      case 'admin': return 'danger';
      case 'corporate': return 'primary';
      case 'regional': return 'info';
      case 'branch': return 'success';
      default: return 'secondary';
    }
  };

  const getRoleIcon = (role: string) => {
    switch(role) {
      case 'admin': return 'bi-shield-check';
      case 'corporate': return 'bi-building';
      case 'regional': return 'bi-diagram-3';
      case 'branch': return 'bi-shop';
      default: return 'bi-person';
    }
  };

  return (
    <div className="d-flex" style={{ minHeight: '100vh' }}>
      {/* Sidebar */}
      <div className={`bg-dark text-white ${sidebarCollapsed ? 'sidebar-collapsed' : 'sidebar-expanded'}`} style={{ 
        width: sidebarCollapsed ? '80px' : '280px',
        transition: 'width 0.3s ease'
      }}>
        {/* Brand */}
        <div className="p-3 border-bottom border-secondary">
          <div className="d-flex align-items-center">
            <div className={`bg-${getRoleColor(userRole)} rounded-circle p-2 me-3`}>
              <i className={`${getRoleIcon(userRole)} text-white`}></i>
            </div>
            {!sidebarCollapsed && (
              <div>
                <h6 className="mb-0">AppHub</h6>
                <small className="text-muted text-capitalize">{userRole} Panel</small>
              </div>
            )}
          </div>
        </div>

        {/* Navigation */}
        <nav className="flex-grow-1">
          <ul className="nav nav-pills flex-column p-3">
            {menuItems.map((item, index) => (
              <li key={index} className="nav-item mb-1">
                <a
                  href="#"
                  className={`nav-link d-flex align-items-center text-white ${item.active ? 'active' : ''}`}
                  onClick={(e) => {
                    e.preventDefault();
                    setLocation(item.path);
                  }}
                  data-testid={`nav-${item.label.toLowerCase().replace(/\s+/g, '-')}`}
                >
                  <i className={`${item.icon} me-3`} style={{ minWidth: '20px' }}></i>
                  {!sidebarCollapsed && <span>{item.label}</span>}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        {/* User Section */}
        <div className="p-3 border-top border-secondary">
          <div className="dropdown">
            <button
              className="btn btn-outline-light w-100 d-flex align-items-center"
              type="button"
              data-bs-toggle="dropdown"
              data-testid="button-user-menu"
            >
              <i className="bi bi-person-circle me-2"></i>
              {!sidebarCollapsed && (
                <>
                  <span className="flex-grow-1 text-start">User Menu</span>
                  <i className="bi bi-chevron-up"></i>
                </>
              )}
            </button>
            <ul className="dropdown-menu dropdown-menu-dark w-100">
              <li><a className="dropdown-item" href="#"><i className="bi bi-person me-2"></i>Profile</a></li>
              <li><a className="dropdown-item" href="#"><i className="bi bi-gear me-2"></i>Settings</a></li>
              <li><hr className="dropdown-divider" /></li>
              <li>
                <a 
                  className="dropdown-item text-danger" 
                  href="#"
                  onClick={(e) => {
                    e.preventDefault();
                    handleLogout();
                  }}
                  data-testid="button-logout"
                >
                  <i className="bi bi-box-arrow-right me-2"></i>Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex-grow-1 bg-light">
        {/* Top Bar */}
        <header className="bg-white shadow-sm border-bottom p-3">
          <div className="d-flex justify-content-between align-items-center">
            <div className="d-flex align-items-center">
              <button
                className="btn btn-outline-secondary me-3"
                onClick={() => setSidebarCollapsed(!sidebarCollapsed)}
                data-testid="button-toggle-sidebar"
              >
                <i className="bi bi-list"></i>
              </button>
              <h4 className="mb-0">{title}</h4>
            </div>
            <div className="d-flex align-items-center">
              <span className={`badge bg-${getRoleColor(userRole)} me-3 text-capitalize`}>
                {userRole}
              </span>
              <button className="btn btn-outline-primary">
                <i className="bi bi-bell"></i>
                <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style={{ fontSize: '10px' }}>
                  3
                </span>
              </button>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="p-4">
          {children}
        </main>
      </div>
    </div>
  );
}