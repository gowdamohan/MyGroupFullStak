import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import MobileHeader from "@/components/mobile/MobileHeader";
import { COUNTRIES, getStatesByCountry, getDistrictsByState } from "@/data/locations";

interface MediaAgency {
  id: string;
  name: string;
  type: 'newspaper' | 'magazine' | 'tv' | 'radio' | 'online';
  logo: string;
  description: string;
  location: {
    country: string;
    state: string;
    district: string;
  };
  isActive: boolean;
}

interface MediaContent {
  id: string;
  title: string;
  description: string;
  type: 'article' | 'video' | 'audio' | 'magazine';
  agencyId: string;
  publishDate: string;
  thumbnail: string;
  url?: string;
  duration?: string;
}

const SAMPLE_AGENCIES: MediaAgency[] = [
  {
    id: '1',
    name: 'Times of India',
    type: 'newspaper',
    logo: '📰',
    description: 'Leading English daily newspaper',
    location: { country: 'IN', state: 'MH', district: 'MUM' },
    isActive: true
  },
  {
    id: '2',
    name: 'BBC News',
    type: 'online',
    logo: '🌐',
    description: 'International news and current affairs',
    location: { country: 'GB', state: '', district: '' },
    isActive: true
  },
  {
    id: '3',
    name: 'National Geographic',
    type: 'magazine',
    logo: '🌍',
    description: 'Science, exploration, and adventure magazine',
    location: { country: 'US', state: '', district: '' },
    isActive: true
  },
  {
    id: '4',
    name: 'Local News Karnataka',
    type: 'newspaper',
    logo: '📄',
    description: 'Regional news from Karnataka',
    location: { country: 'IN', state: 'KA', district: 'BLR' },
    isActive: true
  },
  {
    id: '5',
    name: 'Tech Weekly',
    type: 'magazine',
    logo: '💻',
    description: 'Technology and innovation magazine',
    location: { country: 'IN', state: 'KA', district: 'BLR' },
    isActive: true
  },
];

const SAMPLE_CONTENT: MediaContent[] = [
  {
    id: '1',
    title: 'Breaking: Economic Growth Reaches New Heights',
    description: 'Latest economic indicators show positive growth trends across multiple sectors.',
    type: 'article',
    agencyId: '1',
    publishDate: '2024-01-15',
    thumbnail: 'https://via.placeholder.com/300x200?text=Economic+News'
  },
  {
    id: '2',
    title: 'Climate Change Documentary',
    description: 'An in-depth look at climate change impacts around the world.',
    type: 'video',
    agencyId: '2',
    publishDate: '2024-01-14',
    thumbnail: 'https://via.placeholder.com/300x200?text=Climate+Documentary',
    duration: '45:30'
  },
  {
    id: '3',
    title: 'Wildlife Photography Special',
    description: 'Stunning wildlife photography from around the globe.',
    type: 'magazine',
    agencyId: '3',
    publishDate: '2024-01-10',
    thumbnail: 'https://via.placeholder.com/300x200?text=Wildlife+Photos'
  },
  {
    id: '4',
    title: 'Local Elections Update',
    description: 'Coverage of upcoming local elections in Karnataka.',
    type: 'article',
    agencyId: '4',
    publishDate: '2024-01-12',
    thumbnail: 'https://via.placeholder.com/300x200?text=Local+Elections'
  },
  {
    id: '5',
    title: 'AI Revolution in Healthcare',
    description: 'How artificial intelligence is transforming medical diagnosis and treatment.',
    type: 'article',
    agencyId: '5',
    publishDate: '2024-01-13',
    thumbnail: 'https://via.placeholder.com/300x200?text=AI+Healthcare'
  },
];

export default function MyMediaApp() {
  const [, setLocation] = useLocation();
  const [activeTab, setActiveTab] = useState<'agencies' | 'content' | 'reader'>('agencies');
  const [selectedContent, setSelectedContent] = useState<MediaContent | null>(null);
  const [searchQuery, setSearchQuery] = useState("");
  
  // Location filters
  const [filters, setFilters] = useState({
    country: '',
    state: '',
    district: '',
    type: ''
  });

  const availableStates = getStatesByCountry(filters.country);
  const availableDistricts = getDistrictsByState(filters.state);

  // Filter agencies based on location and search
  const filteredAgencies = SAMPLE_AGENCIES.filter(agency => {
    const matchesSearch = agency.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         agency.description.toLowerCase().includes(searchQuery.toLowerCase());
    
    const matchesCountry = !filters.country || agency.location.country === filters.country;
    const matchesState = !filters.state || agency.location.state === filters.state;
    const matchesDistrict = !filters.district || agency.location.district === filters.district;
    const matchesType = !filters.type || agency.type === filters.type;

    return matchesSearch && matchesCountry && matchesState && matchesDistrict && matchesType;
  });

  // Filter content based on search and selected agencies
  const filteredContent = SAMPLE_CONTENT.filter(content => {
    const agency = SAMPLE_AGENCIES.find(a => a.id === content.agencyId);
    const isFromFilteredAgency = filteredAgencies.some(a => a.id === content.agencyId);
    
    const matchesSearch = content.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         content.description.toLowerCase().includes(searchQuery.toLowerCase());

    return matchesSearch && isFromFilteredAgency;
  });

  const handleAgencyClick = (agency: MediaAgency) => {
    console.log(`Selected agency: ${agency.name}`);
  };

  const handleContentClick = (content: MediaContent) => {
    setSelectedContent(content);
    setActiveTab('reader');
  };

  const handleBackToHome = () => {
    setLocation('/');
  };

  const clearFilters = () => {
    setFilters({
      country: '',
      state: '',
      district: '',
      type: ''
    });
  };

  return (
    <div className="mobile-container">
      <MobileHeader 
        onProfileClick={() => console.log("Profile clicked")}
        onSearch={setSearchQuery}
      />
      
      <div className="app-header bg-info text-white p-3 mb-3">
        <div className="d-flex align-items-center">
          <button 
            className="btn btn-outline-light btn-sm me-3"
            onClick={selectedContent ? () => setActiveTab('content') : handleBackToHome}
          >
            <i className="bi bi-arrow-left"></i>
          </button>
          <div>
            <h5 className="mb-0">
              <i className="bi bi-camera-video me-2"></i>
              MyMedia
            </h5>
            <small className="opacity-75">News, Magazines & Media Content</small>
          </div>
        </div>
      </div>

      {/* Navigation Tabs */}
      <div className="px-3 mb-3">
        <ul className="nav nav-pills nav-fill">
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'agencies' ? 'active' : ''}`}
              onClick={() => setActiveTab('agencies')}
            >
              <i className="bi bi-building me-1"></i>
              Agencies
            </button>
          </li>
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'content' ? 'active' : ''}`}
              onClick={() => setActiveTab('content')}
            >
              <i className="bi bi-newspaper me-1"></i>
              Content
            </button>
          </li>
          {selectedContent && (
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'reader' ? 'active' : ''}`}
                onClick={() => setActiveTab('reader')}
              >
                <i className="bi bi-book me-1"></i>
                Reader
              </button>
            </li>
          )}
        </ul>
      </div>

      {/* Location Filters */}
      {(activeTab === 'agencies' || activeTab === 'content') && (
        <div className="px-3 mb-3">
          <div className="card">
            <div className="card-header">
              <div className="d-flex justify-content-between align-items-center">
                <h6 className="mb-0">
                  <i className="bi bi-funnel me-2"></i>
                  Location Filter
                </h6>
                <button 
                  className="btn btn-outline-secondary btn-sm"
                  onClick={clearFilters}
                >
                  Clear
                </button>
              </div>
            </div>
            <div className="card-body">
              <div className="row g-2">
                <div className="col-6">
                  <select 
                    className="form-select form-select-sm"
                    value={filters.country}
                    onChange={(e) => setFilters(prev => ({ ...prev, country: e.target.value, state: '', district: '' }))}
                  >
                    <option value="">All Countries</option>
                    {COUNTRIES.map(country => (
                      <option key={country.code} value={country.code}>
                        {country.flag} {country.name}
                      </option>
                    ))}
                  </select>
                </div>
                <div className="col-6">
                  <select 
                    className="form-select form-select-sm"
                    value={filters.type}
                    onChange={(e) => setFilters(prev => ({ ...prev, type: e.target.value }))}
                  >
                    <option value="">All Types</option>
                    <option value="newspaper">Newspaper</option>
                    <option value="magazine">Magazine</option>
                    <option value="tv">TV</option>
                    <option value="radio">Radio</option>
                    <option value="online">Online</option>
                  </select>
                </div>
                {filters.country && (
                  <>
                    <div className="col-6">
                      <select 
                        className="form-select form-select-sm"
                        value={filters.state}
                        onChange={(e) => setFilters(prev => ({ ...prev, state: e.target.value, district: '' }))}
                        disabled={!availableStates.length}
                      >
                        <option value="">All States</option>
                        {availableStates.map(state => (
                          <option key={state.code} value={state.code}>
                            {state.name}
                          </option>
                        ))}
                      </select>
                    </div>
                    <div className="col-6">
                      <select 
                        className="form-select form-select-sm"
                        value={filters.district}
                        onChange={(e) => setFilters(prev => ({ ...prev, district: e.target.value }))}
                        disabled={!availableDistricts.length}
                      >
                        <option value="">All Districts</option>
                        {availableDistricts.map(district => (
                          <option key={district.code} value={district.code}>
                            {district.name}
                          </option>
                        ))}
                      </select>
                    </div>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Agencies Tab */}
      {activeTab === 'agencies' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">Media Agencies ({filteredAgencies.length})</h6>
          </div>
          
          <div className="agencies-list">
            {filteredAgencies.map((agency) => (
              <div 
                key={agency.id}
                className="card mb-3 agency-card"
                onClick={() => handleAgencyClick(agency)}
                style={{ cursor: 'pointer' }}
              >
                <div className="card-body">
                  <div className="d-flex align-items-start">
                    <div 
                      className="agency-logo me-3 d-flex align-items-center justify-content-center bg-light rounded"
                      style={{ width: '50px', height: '50px', fontSize: '1.5rem' }}
                    >
                      {agency.logo}
                    </div>
                    <div className="flex-grow-1">
                      <div className="d-flex justify-content-between align-items-start">
                        <h6 className="card-title mb-1">{agency.name}</h6>
                        <span className={`badge ${agency.type === 'newspaper' ? 'bg-primary' : 
                                                agency.type === 'magazine' ? 'bg-success' :
                                                agency.type === 'tv' ? 'bg-danger' :
                                                agency.type === 'radio' ? 'bg-warning' : 'bg-info'}`}>
                          {agency.type.toUpperCase()}
                        </span>
                      </div>
                      <p className="card-text small text-muted mb-2">{agency.description}</p>
                      <div className="d-flex justify-content-between align-items-center">
                        <small className="text-muted">
                          <i className="bi bi-geo-alt me-1"></i>
                          {COUNTRIES.find(c => c.code === agency.location.country)?.name || agency.location.country}
                        </small>
                        {agency.isActive && (
                          <span className="badge bg-success">Active</span>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Content Tab */}
      {activeTab === 'content' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">Latest Content ({filteredContent.length})</h6>
          </div>
          
          <div className="content-list">
            {filteredContent.map((content) => {
              const agency = SAMPLE_AGENCIES.find(a => a.id === content.agencyId);
              return (
                <div 
                  key={content.id}
                  className="card mb-3 content-card"
                  onClick={() => handleContentClick(content)}
                  style={{ cursor: 'pointer' }}
                >
                  <div className="row g-0">
                    <div className="col-4">
                      <img 
                        src={content.thumbnail} 
                        className="img-fluid rounded-start h-100" 
                        alt={content.title}
                        style={{ objectFit: 'cover' }}
                      />
                    </div>
                    <div className="col-8">
                      <div className="card-body">
                        <div className="d-flex justify-content-between align-items-start mb-2">
                          <span className={`badge ${content.type === 'article' ? 'bg-primary' : 
                                                  content.type === 'video' ? 'bg-danger' :
                                                  content.type === 'audio' ? 'bg-warning' : 'bg-success'}`}>
                            {content.type.toUpperCase()}
                          </span>
                          {content.duration && (
                            <small className="text-muted">
                              <i className="bi bi-clock me-1"></i>
                              {content.duration}
                            </small>
                          )}
                        </div>
                        <h6 className="card-title">{content.title}</h6>
                        <p className="card-text small text-muted">{content.description}</p>
                        <div className="d-flex justify-content-between align-items-center">
                          <small className="text-muted">
                            {agency?.name} • {new Date(content.publishDate).toLocaleDateString()}
                          </small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      )}

      {/* Reader Tab */}
      {activeTab === 'reader' && selectedContent && (
        <div className="px-3">
          <div className="content-reader">
            <div className="mb-3">
              <img 
                src={selectedContent.thumbnail} 
                className="img-fluid rounded w-100" 
                alt={selectedContent.title}
                style={{ maxHeight: '200px', objectFit: 'cover' }}
              />
            </div>
            
            <div className="content-header mb-3">
              <div className="d-flex justify-content-between align-items-start mb-2">
                <span className={`badge ${selectedContent.type === 'article' ? 'bg-primary' : 
                                        selectedContent.type === 'video' ? 'bg-danger' :
                                        selectedContent.type === 'audio' ? 'bg-warning' : 'bg-success'}`}>
                  {selectedContent.type.toUpperCase()}
                </span>
                {selectedContent.duration && (
                  <small className="text-muted">
                    <i className="bi bi-clock me-1"></i>
                    {selectedContent.duration}
                  </small>
                )}
              </div>
              <h4>{selectedContent.title}</h4>
              <p className="text-muted">{selectedContent.description}</p>
              
              <div className="d-flex justify-content-between align-items-center border-bottom pb-3">
                <small className="text-muted">
                  {SAMPLE_AGENCIES.find(a => a.id === selectedContent.agencyId)?.name} • 
                  {new Date(selectedContent.publishDate).toLocaleDateString()}
                </small>
                <div>
                  <button className="btn btn-outline-secondary btn-sm me-2">
                    <i className="bi bi-share"></i>
                  </button>
                  <button className="btn btn-outline-secondary btn-sm">
                    <i className="bi bi-bookmark"></i>
                  </button>
                </div>
              </div>
            </div>
            
            <div className="content-body">
              {selectedContent.type === 'video' ? (
                <div 
                  className="video-placeholder bg-dark text-white d-flex align-items-center justify-content-center mb-3"
                  style={{ height: '200px', borderRadius: '8px' }}
                >
                  <div className="text-center">
                    <i className="bi bi-play-circle" style={{ fontSize: '3rem' }}></i>
                    <p className="mt-2 mb-0">Video Player</p>
                  </div>
                </div>
              ) : selectedContent.type === 'audio' ? (
                <div 
                  className="audio-placeholder bg-secondary text-white d-flex align-items-center justify-content-center mb-3"
                  style={{ height: '100px', borderRadius: '8px' }}
                >
                  <div className="text-center">
                    <i className="bi bi-music-note" style={{ fontSize: '2rem' }}></i>
                    <p className="mt-2 mb-0">Audio Player</p>
                  </div>
                </div>
              ) : (
                <div className="article-content">
                  <p>This is where the full article content would be displayed. In a real application, this would contain the complete text of the article, properly formatted with paragraphs, images, and other media elements.</p>
                  <p>The content would be fetched from the media agency's API or database and rendered here for the user to read.</p>
                  <p>For magazines, this could include interactive elements, image galleries, and rich media content.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      )}
      
      <div style={{ height: '80px' }} />
    </div>
  );
}
