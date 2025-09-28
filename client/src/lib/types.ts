export interface AppItem {
  id: string;
  name: string;
  icon: string;
  category: 'communication' | 'entertainment' | 'productivity' | 'social' | 'business' | 'media';
  description?: string;
  route?: string;
  color?: string;
}

export interface CarouselImage {
  src: string;
  alt: string;
  title?: string;
}

export interface LocationData {
  countries: Array<{ code: string; name: string }>;
  states: Array<{ code: string; name: string; countryCode: string }>;
  districts: Array<{ code: string; name: string; stateCode: string }>;
}
