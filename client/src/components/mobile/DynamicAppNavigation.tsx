import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useQuery } from "@tanstack/react-query";

interface AppItem {
  id: number;
  name: string;
  apps_name: string;
  icon?: string;
  logo?: string;
  background_color?: string;
  url?: string;
  category_name?: string;
  category_display_name?: string;
  category_order?: number;
}

interface AppCategory {
  id: number;
  name: string;
  display_name: string;
  description?: string;
  icon?: string;
  order_by: number;
  is_active: number;
}

interface DynamicAppNavigationProps {
  onAppSelect?: (app: AppItem) => void;
  searchQuery?: string;
}

// API functions
const fetchAppCategories = async (): Promise<AppCategory[]> => {
  const response = await fetch('/api/app-categories');
  if (!response.ok) {
    throw new Error('Failed to fetch app categories');
  }
  return response.json();
};

const fetchAppsWithCategories = async (): Promise<AppItem[]> => {
  const response = await fetch('/api/apps/with-categories');
  if (!response.ok) {
    throw new Error('Failed to fetch apps with categories');
  }
  return response.json();
};

const fetchAppsByCategory = async (categoryName: string): Promise<AppItem[]> => {
  const response = await fetch(`/api/apps/by-category/${categoryName}`);
  if (!response.ok) {
    throw new Error('Failed to fetch apps by category');
  }
  return response.json();
};

const searchApps = async (query: string): Promise<AppItem[]> => {
  const response = await fetch(`/api/apps/search?q=${encodeURIComponent(query)}`);
  if (!response.ok) {
    throw new Error('Failed to search apps');
  }
  return response.json();
};

export default function DynamicAppNavigation({ onAppSelect, searchQuery }: DynamicAppNavigationProps) {
  const [, setLocation] = useLocation();
  const [selectedCategory, setSelectedCategory] = useState<string>('myapps');
  const [filteredApps, setFilteredApps] = useState<AppItem[]>([]);

  // Fetch app categories
  const { data: categories, isLoading: categoriesLoading } = useQuery({
    queryKey: ['appCategories'],
    queryFn: fetchAppCategories,
  });

  // Fetch all apps with categories
  const { data: allApps, isLoading: appsLoading } = useQuery({
    queryKey: ['appsWithCategories'],
    queryFn: fetchAppsWithCategories,
  });

  // Fetch apps by selected category
  const { data: categoryApps, isLoading: categoryAppsLoading } = useQuery({
    queryKey: ['appsByCategory', selectedCategory],
    queryFn: () => fetchAppsByCategory(selectedCategory),
    enabled: !!selectedCategory,
  });

  // Search apps when search query changes
  const { data: searchResults, isLoading: searchLoading } = useQuery({
    queryKey: ['searchApps', searchQuery],
    queryFn: () => searchApps(searchQuery!),
    enabled: !!searchQuery && searchQuery.length > 2,
  });

  // Update filtered apps based on search or category selection
  useEffect(() => {
    if (searchQuery && searchQuery.length > 2) {
      setFilteredApps(searchResults || []);
    } else if (categoryApps) {
      setFilteredApps(categoryApps);
    } else {
      setFilteredApps([]);
    }
  }, [searchQuery, searchResults, categoryApps]);

  const handleAppClick = (app: AppItem) => {
    if (onAppSelect) {
      onAppSelect(app);
    } else if (app.url) {
      setLocation(app.url);
    } else {
      // Default routing based on app name
      const route = `/app/${app.apps_name.toLowerCase().replace(/\s+/g, '')}`;
      setLocation(route);
    }
  };

  const handleCategoryChange = (categoryName: string) => {
    setSelectedCategory(categoryName);
  };

  if (categoriesLoading || appsLoading) {
    return (
      <div className="d-flex justify-content-center p-3">
        <div className="spinner-border spinner-border-sm" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
      </div>
    );
  }

  return (
    <div className="dynamic-app-navigation">
      {/* Category Tabs */}
      {!searchQuery && categories && categories.length > 0 && (
        <div className="category-tabs mb-3">
          <div className="d-flex gap-2 overflow-auto">
            {categories.map((category) => (
              <button
                key={category.id}
                className={`btn btn-sm ${
                  selectedCategory === category.name 
                    ? 'btn-primary' 
                    : 'btn-outline-primary'
                } flex-shrink-0`}
                onClick={() => handleCategoryChange(category.name)}
              >
                {category.icon && <i className={`${category.icon} me-1`}></i>}
                {category.display_name || category.name}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Apps Grid */}
      <div className="apps-grid">
        {searchQuery && searchQuery.length > 2 && (
          <div className="mb-2">
            <small className="text-muted">
              {searchLoading ? 'Searching...' : `Search results for "${searchQuery}"`}
            </small>
          </div>
        )}

        {(categoryAppsLoading || searchLoading) ? (
          <div className="d-flex justify-content-center p-3">
            <div className="spinner-border spinner-border-sm" role="status">
              <span className="visually-hidden">Loading apps...</span>
            </div>
          </div>
        ) : filteredApps.length > 0 ? (
          <div className="row g-2">
            {filteredApps.map((app) => (
              <div key={app.id} className="col-4 col-md-3 col-lg-2">
                <div
                  className="app-card text-center p-2 rounded border"
                  onClick={() => handleAppClick(app)}
                  style={{ 
                    cursor: 'pointer',
                    backgroundColor: app.background_color || '#f8f9fa',
                    transition: 'transform 0.2s, box-shadow 0.2s'
                  }}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.transform = 'translateY(-2px)';
                    e.currentTarget.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.transform = 'translateY(0)';
                    e.currentTarget.style.boxShadow = 'none';
                  }}
                >
                  <div className="app-icon mb-2">
                    {app.logo ? (
                      <img 
                        src={app.logo} 
                        alt={app.name}
                        className="img-fluid"
                        style={{ maxWidth: '40px', maxHeight: '40px' }}
                      />
                    ) : app.icon ? (
                      <i className={`${app.icon} fs-4`}></i>
                    ) : (
                      <i className="bi bi-app fs-4 text-muted"></i>
                    )}
                  </div>
                  <div className="app-name">
                    <small className="fw-medium">{app.name}</small>
                  </div>
                  {app.category_display_name && (
                    <div className="app-category">
                      <span className="badge bg-secondary" style={{ fontSize: '10px' }}>
                        {app.category_display_name}
                      </span>
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center p-4">
            <i className="bi bi-search fs-1 text-muted mb-2 d-block"></i>
            <p className="text-muted">
              {searchQuery 
                ? `No apps found for "${searchQuery}"` 
                : 'No apps available in this category'
              }
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
