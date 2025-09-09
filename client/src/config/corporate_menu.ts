export interface MenuItem {
  icon: string;
  label: string;
  path?: string;
  active?: boolean;
  subItems?: MenuItem[];
}

export const corporateMenuItems: MenuItem[] = [
  {
    icon: 'bi-speedometer2',
    label: 'Dashboard',
    path: '/dashboard/corporate'
  },
  {
    icon: 'bi-person',
    label: 'Profile',
    subItems: [
      {
        icon: 'bi-person-circle',
        label: 'My Profile',
        path: '/dashboard/corporate/profile'
      },
      {
        icon: 'bi-key',
        label: 'Change Password',
        path: '/dashboard/corporate/profile/change-password'
      }
    ]
  },
  {
    icon: 'bi-building',
    label: 'Corporate',
    subItems: [
      {
        icon: 'bi-people',
        label: 'Employees',
        path: '/dashboard/corporate/employees'
      },
      {
        icon: 'bi-diagram-3',
        label: 'Departments',
        path: '/dashboard/corporate/departments'
      },
      {
        icon: 'bi-bar-chart',
        label: 'Reports',
        path: '/dashboard/corporate/reports'
      }
    ]
  },
  {
    icon: 'bi-gear',
    label: 'Settings',
    path: '/dashboard/corporate/settings'
  },
  {
    icon: 'bi-box-arrow-right',
    label: 'Logout',
    path: '/logout'
  }
];

export const getActiveMenuItem = (currentPath: string): MenuItem[] => {
  return corporateMenuItems.map(item => ({
    ...item,
    active: item.path === currentPath,
    subItems: item.subItems?.map(subItem => ({
      ...subItem,
      active: subItem.path === currentPath
    }))
  }));
};
