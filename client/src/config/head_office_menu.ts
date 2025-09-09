export interface MenuItem {
  icon: string;
  label: string;
  path?: string;
  active?: boolean;
  subItems?: MenuItem[];
}

export const headOfficeMenuItems: MenuItem[] = [
  {
    icon: 'bi-speedometer2',
    label: 'Dashboard',
    path: '/dashboard/head-office'
  },
  {
    icon: 'bi-person',
    label: 'Profile',
    subItems: [
      {
        icon: 'bi-person-circle',
        label: 'My Profile',
        path: '/dashboard/head-office/profile'
      },
      {
        icon: 'bi-key',
        label: 'Change Password',
        path: '/dashboard/head-office/profile/change-password'
      }
    ]
  },
  {
    icon: 'bi-bank2',
    label: 'Head Office',
    subItems: [
      {
        icon: 'bi-building',
        label: 'Branches',
        path: '/dashboard/head-office/branches'
      },
      {
        icon: 'bi-geo-alt',
        label: 'Regions',
        path: '/dashboard/head-office/regions'
      },
      {
        icon: 'bi-people',
        label: 'Staff Management',
        path: '/dashboard/head-office/staff'
      },
      {
        icon: 'bi-graph-up',
        label: 'Analytics',
        path: '/dashboard/head-office/analytics'
      }
    ]
  },
  {
    icon: 'bi-clipboard-data',
    label: 'Operations',
    subItems: [
      {
        icon: 'bi-file-earmark-text',
        label: 'Policies',
        path: '/dashboard/head-office/policies'
      },
      {
        icon: 'bi-calendar-check',
        label: 'Compliance',
        path: '/dashboard/head-office/compliance'
      }
    ]
  },
  {
    icon: 'bi-gear',
    label: 'Settings',
    path: '/dashboard/head-office/settings'
  },
  {
    icon: 'bi-box-arrow-right',
    label: 'Logout',
    path: '/logout'
  }
];

export const getActiveMenuItem = (currentPath: string): MenuItem[] => {
  return headOfficeMenuItems.map(item => ({
    ...item,
    active: item.path === currentPath,
    subItems: item.subItems?.map(subItem => ({
      ...subItem,
      active: subItem.path === currentPath
    }))
  }));
};
