import { useState } from "react";

interface NavItem {
  id: string;
  icon: string;
  label: string;
  path: string;
}

const navigationItems: NavItem[] = [
  { id: 'home', icon: 'bi-house-fill', label: 'Home', path: '/' },
  { id: 'groups', icon: 'bi-people', label: 'Groups', path: '/groups' },
  { id: 'analytics', icon: 'bi-graph-up', label: 'Analytics', path: '/analytics' },
  { id: 'settings', icon: 'bi-gear', label: 'Settings', path: '/settings' },
];

interface BottomNavigationProps {
  onNavigate?: (path: string) => void;
  activeItem?: string;
}

export default function BottomNavigation({ 
  onNavigate, 
  activeItem = 'home' 
}: BottomNavigationProps) {
  const [active, setActive] = useState(activeItem);

  const handleNavClick = (item: NavItem) => {
    setActive(item.id);
    if (onNavigate) {
      onNavigate(item.path);
    } else {
      console.log(`Navigating to: ${item.path}`);
    }
  };

  return (
    <nav className="bottom-nav" data-testid="nav-bottom">
      <div className="row g-0">
        {navigationItems.map((item) => (
          <div key={item.id} className="col-3">
            <a 
              href="#" 
              className={`nav-item ${active === item.id ? 'active' : ''}`}
              onClick={(e) => {
                e.preventDefault();
                handleNavClick(item);
              }}
              data-testid={`nav-${item.id}`}
            >
              <i className={item.icon} />
              <span>{item.label}</span>
            </a>
          </div>
        ))}
      </div>
    </nav>
  );
}
