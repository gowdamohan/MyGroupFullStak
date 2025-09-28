export interface Country {
  code: string;
  name: string;
  flag: string;
  phoneCode: string;
}

export interface State {
  code: string;
  name: string;
  countryCode: string;
}

export interface District {
  code: string;
  name: string;
  stateCode: string;
}

export const COUNTRIES: Country[] = [
  { code: 'IN', name: 'India', flag: '🇮🇳', phoneCode: '+91' },
  { code: 'US', name: 'United States', flag: '🇺🇸', phoneCode: '+1' },
  { code: 'GB', name: 'United Kingdom', flag: '🇬🇧', phoneCode: '+44' },
  { code: 'CA', name: 'Canada', flag: '🇨🇦', phoneCode: '+1' },
  { code: 'AU', name: 'Australia', flag: '🇦🇺', phoneCode: '+61' },
  { code: 'DE', name: 'Germany', flag: '🇩🇪', phoneCode: '+49' },
  { code: 'FR', name: 'France', flag: '🇫🇷', phoneCode: '+33' },
  { code: 'JP', name: 'Japan', flag: '🇯🇵', phoneCode: '+81' },
  { code: 'CN', name: 'China', flag: '🇨🇳', phoneCode: '+86' },
  { code: 'BR', name: 'Brazil', flag: '🇧🇷', phoneCode: '+55' },
];

export const INDIAN_STATES: State[] = [
  { code: 'AP', name: 'Andhra Pradesh', countryCode: 'IN' },
  { code: 'AR', name: 'Arunachal Pradesh', countryCode: 'IN' },
  { code: 'AS', name: 'Assam', countryCode: 'IN' },
  { code: 'BR', name: 'Bihar', countryCode: 'IN' },
  { code: 'CT', name: 'Chhattisgarh', countryCode: 'IN' },
  { code: 'GA', name: 'Goa', countryCode: 'IN' },
  { code: 'GJ', name: 'Gujarat', countryCode: 'IN' },
  { code: 'HR', name: 'Haryana', countryCode: 'IN' },
  { code: 'HP', name: 'Himachal Pradesh', countryCode: 'IN' },
  { code: 'JH', name: 'Jharkhand', countryCode: 'IN' },
  { code: 'KA', name: 'Karnataka', countryCode: 'IN' },
  { code: 'KL', name: 'Kerala', countryCode: 'IN' },
  { code: 'MP', name: 'Madhya Pradesh', countryCode: 'IN' },
  { code: 'MH', name: 'Maharashtra', countryCode: 'IN' },
  { code: 'MN', name: 'Manipur', countryCode: 'IN' },
  { code: 'ML', name: 'Meghalaya', countryCode: 'IN' },
  { code: 'MZ', name: 'Mizoram', countryCode: 'IN' },
  { code: 'NL', name: 'Nagaland', countryCode: 'IN' },
  { code: 'OR', name: 'Odisha', countryCode: 'IN' },
  { code: 'PB', name: 'Punjab', countryCode: 'IN' },
  { code: 'RJ', name: 'Rajasthan', countryCode: 'IN' },
  { code: 'SK', name: 'Sikkim', countryCode: 'IN' },
  { code: 'TN', name: 'Tamil Nadu', countryCode: 'IN' },
  { code: 'TG', name: 'Telangana', countryCode: 'IN' },
  { code: 'TR', name: 'Tripura', countryCode: 'IN' },
  { code: 'UP', name: 'Uttar Pradesh', countryCode: 'IN' },
  { code: 'UT', name: 'Uttarakhand', countryCode: 'IN' },
  { code: 'WB', name: 'West Bengal', countryCode: 'IN' },
  { code: 'AN', name: 'Andaman and Nicobar Islands', countryCode: 'IN' },
  { code: 'CH', name: 'Chandigarh', countryCode: 'IN' },
  { code: 'DN', name: 'Dadra and Nagar Haveli and Daman and Diu', countryCode: 'IN' },
  { code: 'DL', name: 'Delhi', countryCode: 'IN' },
  { code: 'JK', name: 'Jammu and Kashmir', countryCode: 'IN' },
  { code: 'LA', name: 'Ladakh', countryCode: 'IN' },
  { code: 'LD', name: 'Lakshadweep', countryCode: 'IN' },
  { code: 'PY', name: 'Puducherry', countryCode: 'IN' },
];

export const SAMPLE_DISTRICTS: District[] = [
  // Karnataka districts
  { code: 'BLR', name: 'Bangalore Urban', stateCode: 'KA' },
  { code: 'BLR_R', name: 'Bangalore Rural', stateCode: 'KA' },
  { code: 'MYS', name: 'Mysore', stateCode: 'KA' },
  { code: 'MNG', name: 'Mangalore', stateCode: 'KA' },
  { code: 'HUB', name: 'Hubli', stateCode: 'KA' },
  
  // Maharashtra districts
  { code: 'MUM', name: 'Mumbai', stateCode: 'MH' },
  { code: 'PUN', name: 'Pune', stateCode: 'MH' },
  { code: 'NGP', name: 'Nagpur', stateCode: 'MH' },
  { code: 'NSK', name: 'Nashik', stateCode: 'MH' },
  { code: 'AUR', name: 'Aurangabad', stateCode: 'MH' },
  
  // Tamil Nadu districts
  { code: 'CHN', name: 'Chennai', stateCode: 'TN' },
  { code: 'CBE', name: 'Coimbatore', stateCode: 'TN' },
  { code: 'MDU', name: 'Madurai', stateCode: 'TN' },
  { code: 'TCY', name: 'Trichy', stateCode: 'TN' },
  { code: 'SLM', name: 'Salem', stateCode: 'TN' },
  
  // Delhi districts
  { code: 'ND', name: 'New Delhi', stateCode: 'DL' },
  { code: 'CD', name: 'Central Delhi', stateCode: 'DL' },
  { code: 'SD', name: 'South Delhi', stateCode: 'DL' },
  { code: 'ED', name: 'East Delhi', stateCode: 'DL' },
  { code: 'WD', name: 'West Delhi', stateCode: 'DL' },
];

export const EDUCATION_OPTIONS = [
  'High School',
  'Higher Secondary',
  'Diploma',
  'Bachelor\'s Degree',
  'Master\'s Degree',
  'PhD',
  'Professional Certification',
  'Other'
];

export const PROFESSION_OPTIONS = [
  'Software Engineer',
  'Doctor',
  'Teacher',
  'Business Owner',
  'Government Employee',
  'Private Employee',
  'Student',
  'Farmer',
  'Lawyer',
  'Accountant',
  'Consultant',
  'Freelancer',
  'Retired',
  'Other'
];

// Helper functions
export const getStatesByCountry = (countryCode: string): State[] => {
  if (countryCode === 'IN') {
    return INDIAN_STATES;
  }
  // Add other countries' states as needed
  return [];
};

export const getDistrictsByState = (stateCode: string): District[] => {
  return SAMPLE_DISTRICTS.filter(district => district.stateCode === stateCode);
};

export const getCountryByCode = (code: string): Country | undefined => {
  return COUNTRIES.find(country => country.code === code);
};
