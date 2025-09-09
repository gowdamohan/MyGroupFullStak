import { useLocation } from "wouter";

interface MobileHeaderProps {
  onProfileClick: () => void;
  onSearch: (query: string) => void;
}

export default function MobileHeader({ onProfileClick, onSearch }: MobileHeaderProps) {
  const [, setLocation] = useLocation();

  return (
    <header className="mobile-header">
      <div className="d-flex align-items-center justify-content-between mb-3">
        <h5 className="fw-bold text-primary mb-0" data-testid="app-logo">
          AppHub
        </h5>
        <div className="d-flex align-items-center gap-3">
          <button
            className="btn btn-outline-primary btn-sm"
            onClick={() => setLocation('/login')}
            data-testid="button-role-login"
          >
            <i className="bi bi-person-badge me-1"></i>
            Staff Login
          </button>
          <div className="position-relative">
            <i 
              className="bi bi-person-circle fs-4 text-muted" 
              style={{ cursor: 'pointer' }}
              onClick={onProfileClick}
              data-testid="button-profile"
            />
            <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style={{ fontSize: '10px' }}>
              3
              <span className="visually-hidden">unread messages</span>
            </span>
          </div>
        </div>
      </div>
      
      <div className="search-container">
        <input 
          type="text" 
          className="form-control search-input" 
          placeholder="Search apps..."
          onChange={(e) => onSearch(e.target.value)}
          data-testid="input-search"
        />
        <i className="bi bi-search search-icon" />
      </div>
    </header>
  );
}
