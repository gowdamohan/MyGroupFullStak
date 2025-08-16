export interface AppItem {
  id: string;
  name: string;
  icon: string;
  category: 'default' | 'gaming' | 'social';
  description?: string;
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
