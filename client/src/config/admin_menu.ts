export interface MenuItem {
  icon: string;
  label: string;
  path?: string;
  active?: boolean;
  subItems?: MenuItem[];
}

export const adminMenuItems: MenuItem[] = [
  {
    icon: 'bi-speedometer2',
    label: 'Dashboard',
    path: '/dashboard/admin'
  },
  {
    icon: 'bi-person',
    label: 'Profile',
    subItems: [
      {
        icon: 'bi-people',
        label: 'Group',
        path: '/dashboard/admin/profile/group'
      },
      {
        icon: 'bi-app',
        label: 'App Created',
        path: '/dashboard/admin/profile/app-created'
      },
      {
        icon: 'bi-key',
        label: 'Change Password',
        path: '/dashboard/admin/profile/change-password'
      }
    ]
  },
  {
    icon: 'bi-geo-alt',
    label: 'Location',
    subItems: [
      {
        icon: 'bi-file-text',
        label: 'Content',
        path: '/dashboard/admin/location/content'
      },
      {
        icon: 'bi-globe',
        label: 'Country',
        path: '/dashboard/admin/location/country'
      },
      {
        icon: 'bi-map',
        label: 'State',
        path: '/dashboard/admin/location/state'
      },
      {
        icon: 'bi-pin-map',
        label: 'District',
        path: '/dashboard/admin/location/district'
      }
    ]
  },
  {
    icon: 'bi-translate',
    label: 'Language',
    path: '/dashboard/admin/language'
  },
  {
    icon: 'bi-mortarboard',
    label: 'Education',
    path: '/dashboard/admin/education'
  },
  {
    icon: 'bi-briefcase',
    label: 'Profession',
    path: '/dashboard/admin/profession'
  },
  {
    icon: 'bi-gear',
    label: 'Admin Settings',
    path: '/dashboard/admin/admin-settings'
  },
  {
    icon: 'bi-box-arrow-in-right',
    label: 'Corporate Login',
    path: '/dashboard/admin/corporate-login'
  },
  {
    icon: 'bi-box-arrow-right',
    label: 'Logout'
    // No path - will be handled by click handler
  }
];

export const getActiveMenuItem = (currentPath: string): MenuItem[] => {
  return adminMenuItems.map(item => ({
    ...item,
    active: item.path === currentPath,
    subItems: item.subItems?.map(subItem => ({
      ...subItem,
      active: subItem.path === currentPath
    }))
  }));
};
