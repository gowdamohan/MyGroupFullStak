export interface MenuItem {
  icon: string;
  label: string;
  path?: string;
  active?: boolean;
  subItems?: MenuItem[];
}

export const regionalMenuItems: MenuItem[] = [
  {
    icon: 'bi-speedometer2',
    label: 'Dashboard',
    path: '/dashboard/regional'
  },
  {
    icon: 'bi-person',
    label: 'Profile',
    subItems: [
      {
        icon: 'bi-person-circle',
        label: 'My Profile',
        path: '/dashboard/regional/profile'
      },
      {
        icon: 'bi-key',
        label: 'Change Password',
        path: '/dashboard/regional/profile/change-password'
      }
    ]
  },
  {
    icon: 'bi-geo-alt',
    label: 'Regional',
    subItems: [
      {
        icon: 'bi-building',
        label: 'Local Branches',
        path: '/dashboard/regional/branches'
      },
      {
        icon: 'bi-people',
        label: 'Regional Staff',
        path: '/dashboard/regional/staff'
      },
      {
        icon: 'bi-bar-chart',
        label: 'Regional Reports',
        path: '/dashboard/regional/reports'
      },
      {
        icon: 'bi-calendar-event',
        label: 'Events',
        path: '/dashboard/regional/events'
      }
    ]
  },
  {
    icon: 'bi-clipboard-check',
    label: 'Operations',
    subItems: [
      {
        icon: 'bi-list-check',
        label: 'Tasks',
        path: '/dashboard/regional/tasks'
      },
      {
        icon: 'bi-file-earmark-check',
        label: 'Approvals',
        path: '/dashboard/regional/approvals'
      }
    ]
  },
  {
    icon: 'bi-gear',
    label: 'Settings',
    path: '/dashboard/regional/settings'
  },
  {
    icon: 'bi-box-arrow-right',
    label: 'Logout',
    path: '/logout'
  }
];

export const getActiveMenuItem = (currentPath: string): MenuItem[] => {
  return regionalMenuItems.map(item => ({
    ...item,
    active: item.path === currentPath,
    subItems: item.subItems?.map(subItem => ({
      ...subItem,
      active: subItem.path === currentPath
    }))
  }));
};
