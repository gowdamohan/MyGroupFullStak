import type { AppItem } from "@/lib/types";

export const APPS_CONFIG: AppItem[] = [
  {
    id: 'mychat',
    name: 'MyChat',
    icon: 'bi-chat-dots',
    category: 'communication',
    description: 'WhatsApp-style messaging application',
    route: '/app/mychat',
    color: '#25D366'
  },
  {
    id: 'mygo',
    name: 'MyGo',
    icon: 'bi-geo-alt',
    category: 'productivity',
    description: 'Location and navigation services',
    route: '/app/mygo',
    color: '#4285F4'
  },
  {
    id: 'mydairy',
    name: 'MyDairy',
    icon: 'bi-journal-text',
    category: 'productivity',
    description: 'Personal diary and notes',
    route: '/app/mydairy',
    color: '#FF6B6B'
  },
  {
    id: 'myneedy',
    name: 'MyNeedy',
    icon: 'bi-heart-pulse',
    category: 'social',
    description: 'Community help and support',
    route: '/app/myneedy',
    color: '#E74C3C'
  },
  {
    id: 'myjoy',
    name: 'MyJoy',
    icon: 'bi-emoji-smile',
    category: 'entertainment',
    description: 'Entertainment and fun activities',
    route: '/app/myjoy',
    color: '#F39C12'
  },
  {
    id: 'mymedia',
    name: 'MyMedia',
    icon: 'bi-camera-video',
    category: 'media',
    description: 'News, magazines, and media content',
    route: '/app/mymedia',
    color: '#9B59B6'
  },
  {
    id: 'myunions',
    name: 'MyUnions',
    icon: 'bi-people-fill',
    category: 'social',
    description: 'Union management and member services',
    route: '/app/myunions',
    color: '#2ECC71'
  },
  {
    id: 'mytv',
    name: 'MyTV',
    icon: 'bi-tv',
    category: 'entertainment',
    description: 'Live TV and video streaming',
    route: '/app/mytv',
    color: '#3498DB'
  },
  {
    id: 'myfin',
    name: 'MyFin',
    icon: 'bi-currency-dollar',
    category: 'business',
    description: 'Financial management and banking',
    route: '/app/myfin',
    color: '#27AE60'
  },
  {
    id: 'myshop',
    name: 'MyShop',
    icon: 'bi-shop',
    category: 'business',
    description: 'Online shopping and marketplace',
    route: '/app/myshop',
    color: '#E67E22'
  },
  {
    id: 'myfriend',
    name: 'MyFriend',
    icon: 'bi-person-hearts',
    category: 'social',
    description: 'Social networking and friendships',
    route: '/app/myfriend',
    color: '#FF69B4'
  },
  {
    id: 'mybiz',
    name: 'MyBiz',
    icon: 'bi-briefcase',
    category: 'business',
    description: 'Business management and networking',
    route: '/app/mybiz',
    color: '#34495E'
  }
];

// Helper function to get app by ID
export const getAppById = (id: string): AppItem | undefined => {
  return APPS_CONFIG.find(app => app.id === id);
};

// Helper function to get apps by category
export const getAppsByCategory = (category: AppItem['category']): AppItem[] => {
  return APPS_CONFIG.filter(app => app.category === category);
};

// App categories for filtering
export const APP_CATEGORIES = [
  { id: 'communication', name: 'Communication', icon: 'bi-chat' },
  { id: 'entertainment', name: 'Entertainment', icon: 'bi-play-circle' },
  { id: 'productivity', name: 'Productivity', icon: 'bi-clipboard-check' },
  { id: 'social', name: 'Social', icon: 'bi-people' },
  { id: 'business', name: 'Business', icon: 'bi-briefcase' },
  { id: 'media', name: 'Media', icon: 'bi-camera-video' }
] as const;
