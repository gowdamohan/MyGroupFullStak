import React from "react";
import { useLocation } from "wouter";
import type { AppItem } from "@/lib/types";
import { APPS_CONFIG } from "@/config/apps";

interface AppsGridProps {
  apps?: AppItem[];
  onAppClick?: (app: AppItem) => void;
  showAll?: boolean;
}

export default function AppsGrid({ apps = APPS_CONFIG, onAppClick, showAll = false }: AppsGridProps) {
  const [, setLocation] = useLocation();

  const displayApps = showAll ? apps : apps.slice(0, 12);

  const handleAppClick = (app: AppItem) => {
    if (onAppClick) {
      onAppClick(app);
    } else if (app.route) {
      setLocation(app.route);
    } else {
      console.log(`Opening app: ${app.name}`);
    }
  };

  return (
    <div className="apps-grid">
      <div className="row g-3">
        {displayApps.map((app) => (
          <div key={app.id} className="col-4">
            <div
              className={`app-card ${app.category}`}
              onClick={() => handleAppClick(app)}
              data-testid={`card-app-${app.id}`}
              style={{
                background: app.color ? `linear-gradient(135deg, ${app.color}20, ${app.color}40)` : undefined,
                borderColor: app.color || undefined
              }}
            >
              <i
                className={`${app.icon} app-icon`}
                style={{ color: app.color || undefined }}
              />
              <p className="app-title">{app.name}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
