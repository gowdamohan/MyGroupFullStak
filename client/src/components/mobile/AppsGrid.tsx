import type { AppItem } from "@/lib/types";

const defaultApps: AppItem[] = [
  { id: '1', name: 'Developer', icon: 'bi-journal-code', category: 'default' },
  { id: '2', name: 'Gaming', icon: 'bi-controller', category: 'gaming' },
  { id: '3', name: 'Social', icon: 'bi-people', category: 'social' },
  { id: '4', name: 'Photo', icon: 'bi-camera', category: 'default' },
  { id: '5', name: 'Music', icon: 'bi-music-note', category: 'gaming' },
  { id: '6', name: 'Shopping', icon: 'bi-cart', category: 'social' },
];

interface AppsGridProps {
  apps?: AppItem[];
  onAppClick?: (app: AppItem) => void;
}

export default function AppsGrid({ apps = defaultApps, onAppClick }: AppsGridProps) {
  const handleAppClick = (app: AppItem) => {
    if (onAppClick) {
      onAppClick(app);
    } else {
      console.log(`Opening app: ${app.name}`);
    }
  };

  return (
    <div className="apps-grid">
      <div className="row g-3">
        {apps.map((app) => (
          <div key={app.id} className="col-4">
            <div 
              className={`app-card ${app.category}`}
              onClick={() => handleAppClick(app)}
              data-testid={`card-app-${app.id}`}
            >
              <i className={`${app.icon} app-icon`} />
              <p className="app-title">{app.name}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
