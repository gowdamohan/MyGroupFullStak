import { useLocation } from "wouter";

interface RoleOption {
  role: string;
  title: string;
  description: string;
  icon: string;
  color: string;
  path: string;
}

const roleOptions: RoleOption[] = [
  {
    role: 'admin',
    title: 'Administrator',
    description: 'Full system access and management',
    icon: 'bi-shield-lock-fill',
    color: 'warning',
    path: '/admin/login'
  },
  {
    role: 'corporate',
    title: 'Corporate',
    description: 'Corporate level management',
    icon: 'bi-building-fill',
    color: 'info',
    path: '/corporate/login'
  },
  {
    role: 'head_office',
    title: 'Head Office',
    description: 'Head office operations',
    icon: 'bi-bank2',
    color: 'primary',
    path: '/head-office/login'
  },
  {
    role: 'regional',
    title: 'Regional',
    description: 'Regional management access',
    icon: 'bi-geo-alt-fill',
    color: 'success',
    path: '/regional/login'
  },
  {
    role: 'branch',
    title: 'Branch',
    description: 'Branch level operations',
    icon: 'bi-diagram-3-fill',
    color: 'danger',
    path: '/branch/login'
  }
];

export default function RoleSelectionPage() {
  const [, setLocation] = useLocation();

  const handleRoleSelect = (path: string) => {
    setLocation(path);
  };

  return (
    <div className="min-vh-100 d-flex align-items-center justify-content-center" style={{ background: 'var(--primary-gradient)' }}>
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-lg-8">
            <div className="text-center mb-5">
              <h1 className="text-white fw-bold mb-3">
                <i className="bi bi-person-badge me-3"></i>
                Select Your Role
              </h1>
              <p className="text-white-50 lead">
                Choose your access level to continue to the appropriate login screen
              </p>
            </div>

            <div className="row g-4">
              {roleOptions.map((option) => (
                <div key={option.role} className="col-md-6 col-lg-4">
                  <div 
                    className="card glass-modal border-0 shadow-lg h-100 role-card"
                    style={{ cursor: 'pointer', transition: 'transform 0.2s ease' }}
                    onClick={() => handleRoleSelect(option.path)}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.transform = 'translateY(-5px)';
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.transform = 'translateY(0)';
                    }}
                  >
                    <div className="card-body text-center p-4">
                      <div className={`bg-${option.color} rounded-circle p-3 d-inline-flex mb-3`}>
                        <i className={`${option.icon} text-white fs-2`}></i>
                      </div>
                      <h5 className="text-white fw-bold mb-2">{option.title}</h5>
                      <p className="text-white-50 mb-3">{option.description}</p>
                      <button className={`btn btn-${option.color} w-100`}>
                        <i className="bi bi-box-arrow-in-right me-2"></i>
                        Login as {option.title}
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="text-center mt-5">
              <button
                className="btn btn-outline-light"
                onClick={() => setLocation('/')}
              >
                <i className="bi bi-arrow-left me-2"></i>
                Back to Home
              </button>
            </div>

            {/* General User Login */}
            <div className="text-center mt-4">
              <div className="card glass-modal border-0 shadow-lg">
                <div className="card-body p-4">
                  <h6 className="text-white mb-3">
                    <i className="bi bi-people me-2"></i>
                    General User Access
                  </h6>
                  <p className="text-white-50 mb-3">
                    For regular users and new registrations
                  </p>
                  <button
                    className="btn btn-light"
                    onClick={() => setLocation('/auth/login')}
                  >
                    <i className="bi bi-person me-2"></i>
                    User Login / Register
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
