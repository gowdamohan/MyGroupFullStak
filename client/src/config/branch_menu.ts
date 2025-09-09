export interface MenuItem {
  icon: string;
  label: string;
  path?: string;
  active?: boolean;
  subItems?: MenuItem[];
}

export const branchMenuItems: MenuItem[] = [
  {
    icon: 'bi-speedometer2',
    label: 'Dashboard',
    path: '/dashboard/branch'
  },
  {
    icon: 'bi-person',
    label: 'Profile',
    subItems: [
      {
        icon: 'bi-person-circle',
        label: 'My Profile',
        path: '/dashboard/branch/profile'
      },
      {
        icon: 'bi-key',
        label: 'Change Password',
        path: '/dashboard/branch/profile/change-password'
      }
    ]
  },
  {
    icon: 'bi-diagram-3',
    label: 'Branch',
    subItems: [
      {
        icon: 'bi-people',
        label: 'Branch Staff',
        path: '/dashboard/branch/staff'
      },
      {
        icon: 'bi-person-plus',
        label: 'Customers',
        path: '/dashboard/branch/customers'
      },
      {
        icon: 'bi-clipboard-data',
        label: 'Daily Reports',
        path: '/dashboard/branch/reports'
      },
      {
        icon: 'bi-cash-stack',
        label: 'Transactions',
        path: '/dashboard/branch/transactions'
      }
    ]
  },
  {
    icon: 'bi-calendar-check',
    label: 'Operations',
    subItems: [
      {
        icon: 'bi-clock',
        label: 'Schedule',
        path: '/dashboard/branch/schedule'
      },
      {
        icon: 'bi-list-task',
        label: 'Daily Tasks',
        path: '/dashboard/branch/tasks'
      },
      {
        icon: 'bi-exclamation-triangle',
        label: 'Issues',
        path: '/dashboard/branch/issues'
      }
    ]
  },
  {
    icon: 'bi-gear',
    label: 'Settings',
    path: '/dashboard/branch/settings'
  },
  {
    icon: 'bi-box-arrow-right',
    label: 'Logout',
    path: '/logout'
  }
];

export const getActiveMenuItem = (currentPath: string): MenuItem[] => {
  return branchMenuItems.map(item => ({
    ...item,
    active: item.path === currentPath,
    subItems: item.subItems?.map(subItem => ({
      ...subItem,
      active: subItem.path === currentPath
    }))
  }));
};
