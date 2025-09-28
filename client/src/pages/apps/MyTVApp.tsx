import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import MobileHeader from "@/components/mobile/MobileHeader";

interface TVChannel {
  id: string;
  name: string;
  logo: string;
  category: string;
  isLive: boolean;
}

interface TVProgram {
  id: string;
  title: string;
  description: string;
  startTime: string;
  endTime: string;
  channelId: string;
  isLive: boolean;
  videoUrl?: string;
  thumbnail?: string;
}

const SAMPLE_CHANNELS: TVChannel[] = [
  { id: '1', name: 'News 24', logo: '📺', category: 'News', isLive: true },
  { id: '2', name: 'Sports Central', logo: '⚽', category: 'Sports', isLive: true },
  { id: '3', name: 'Movie Magic', logo: '🎬', category: 'Movies', isLive: false },
  { id: '4', name: 'Kids Zone', logo: '🧸', category: 'Kids', isLive: true },
  { id: '5', name: 'Music Plus', logo: '🎵', category: 'Music', isLive: true },
  { id: '6', name: 'Discovery World', logo: '🌍', category: 'Documentary', isLive: false },
];

const SAMPLE_PROGRAMS: TVProgram[] = [
  {
    id: '1',
    title: 'Breaking News',
    description: 'Latest news updates from around the world',
    startTime: '09:00',
    endTime: '10:00',
    channelId: '1',
    isLive: true,
    thumbnail: 'https://via.placeholder.com/300x200?text=Breaking+News'
  },
  {
    id: '2',
    title: 'Football Match',
    description: 'Live football match between Team A vs Team B',
    startTime: '15:00',
    endTime: '17:00',
    channelId: '2',
    isLive: true,
    thumbnail: 'https://via.placeholder.com/300x200?text=Football+Match'
  },
  {
    id: '3',
    title: 'Action Movie',
    description: 'Blockbuster action movie with amazing stunts',
    startTime: '20:00',
    endTime: '22:30',
    channelId: '3',
    isLive: false,
    thumbnail: 'https://via.placeholder.com/300x200?text=Action+Movie'
  },
  {
    id: '4',
    title: 'Cartoon Time',
    description: 'Fun cartoons for children',
    startTime: '16:00',
    endTime: '17:00',
    channelId: '4',
    isLive: true,
    thumbnail: 'https://via.placeholder.com/300x200?text=Cartoon+Time'
  },
];

export default function MyTVApp() {
  const [, setLocation] = useLocation();
  const [selectedChannel, setSelectedChannel] = useState<TVChannel | null>(null);
  const [selectedProgram, setSelectedProgram] = useState<TVProgram | null>(null);
  const [activeTab, setActiveTab] = useState<'channels' | 'programs' | 'player'>('channels');
  const [searchQuery, setSearchQuery] = useState("");

  const filteredChannels = SAMPLE_CHANNELS.filter(channel =>
    channel.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    channel.category.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const filteredPrograms = selectedChannel 
    ? SAMPLE_PROGRAMS.filter(program => program.channelId === selectedChannel.id)
    : SAMPLE_PROGRAMS.filter(program =>
        program.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
        program.description.toLowerCase().includes(searchQuery.toLowerCase())
      );

  const handleChannelSelect = (channel: TVChannel) => {
    setSelectedChannel(channel);
    setActiveTab('programs');
  };

  const handleProgramSelect = (program: TVProgram) => {
    setSelectedProgram(program);
    setActiveTab('player');
  };

  const handleBackToHome = () => {
    setLocation('/');
  };

  return (
    <div className="mobile-container">
      <MobileHeader 
        onProfileClick={() => console.log("Profile clicked")}
        onSearch={setSearchQuery}
      />
      
      <div className="app-header bg-primary text-white p-3 mb-3">
        <div className="d-flex align-items-center">
          <button 
            className="btn btn-outline-light btn-sm me-3"
            onClick={handleBackToHome}
          >
            <i className="bi bi-arrow-left"></i>
          </button>
          <div>
            <h5 className="mb-0">
              <i className="bi bi-tv me-2"></i>
              MyTV
            </h5>
            <small className="opacity-75">Live TV & Video Streaming</small>
          </div>
        </div>
      </div>

      {/* Navigation Tabs */}
      <div className="px-3 mb-3">
        <ul className="nav nav-pills nav-fill">
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'channels' ? 'active' : ''}`}
              onClick={() => setActiveTab('channels')}
            >
              <i className="bi bi-tv me-1"></i>
              Channels
            </button>
          </li>
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'programs' ? 'active' : ''}`}
              onClick={() => setActiveTab('programs')}
            >
              <i className="bi bi-list-ul me-1"></i>
              Programs
            </button>
          </li>
          {selectedProgram && (
            <li className="nav-item">
              <button 
                className={`nav-link ${activeTab === 'player' ? 'active' : ''}`}
                onClick={() => setActiveTab('player')}
              >
                <i className="bi bi-play-circle me-1"></i>
                Player
              </button>
            </li>
          )}
        </ul>
      </div>

      {/* Channels Tab */}
      {activeTab === 'channels' && (
        <div className="px-3">
          <h6 className="mb-3">TV Channels</h6>
          <div className="row g-3">
            {filteredChannels.map((channel) => (
              <div key={channel.id} className="col-6">
                <div 
                  className="card h-100 channel-card"
                  onClick={() => handleChannelSelect(channel)}
                  style={{ cursor: 'pointer' }}
                >
                  <div className="card-body text-center">
                    <div className="channel-logo mb-2" style={{ fontSize: '2rem' }}>
                      {channel.logo}
                    </div>
                    <h6 className="card-title">{channel.name}</h6>
                    <p className="card-text small text-muted">{channel.category}</p>
                    {channel.isLive && (
                      <span className="badge bg-danger">
                        <i className="bi bi-broadcast me-1"></i>
                        LIVE
                      </span>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Programs Tab */}
      {activeTab === 'programs' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">
              {selectedChannel ? `${selectedChannel.name} Programs` : 'All Programs'}
            </h6>
            {selectedChannel && (
              <button 
                className="btn btn-outline-secondary btn-sm"
                onClick={() => {
                  setSelectedChannel(null);
                  setActiveTab('channels');
                }}
              >
                <i className="bi bi-arrow-left me-1"></i>
                Back
              </button>
            )}
          </div>
          
          <div className="programs-list">
            {filteredPrograms.map((program) => (
              <div 
                key={program.id} 
                className="card mb-3 program-card"
                onClick={() => handleProgramSelect(program)}
                style={{ cursor: 'pointer' }}
              >
                <div className="row g-0">
                  <div className="col-4">
                    <img 
                      src={program.thumbnail} 
                      className="img-fluid rounded-start h-100" 
                      alt={program.title}
                      style={{ objectFit: 'cover' }}
                    />
                  </div>
                  <div className="col-8">
                    <div className="card-body">
                      <h6 className="card-title">{program.title}</h6>
                      <p className="card-text small text-muted">{program.description}</p>
                      <div className="d-flex justify-content-between align-items-center">
                        <small className="text-muted">
                          {program.startTime} - {program.endTime}
                        </small>
                        {program.isLive ? (
                          <span className="badge bg-danger">LIVE</span>
                        ) : (
                          <span className="badge bg-secondary">RECORDED</span>
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

      {/* Video Player Tab */}
      {activeTab === 'player' && selectedProgram && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">Now Playing</h6>
            <button 
              className="btn btn-outline-secondary btn-sm"
              onClick={() => setActiveTab('programs')}
            >
              <i className="bi bi-arrow-left me-1"></i>
              Back
            </button>
          </div>
          
          <div className="video-player-container mb-3">
            <div 
              className="video-placeholder bg-dark text-white d-flex align-items-center justify-content-center"
              style={{ height: '200px', borderRadius: '8px' }}
            >
              <div className="text-center">
                <i className="bi bi-play-circle" style={{ fontSize: '3rem' }}></i>
                <p className="mt-2 mb-0">Video Player</p>
                <small className="text-muted">
                  {selectedProgram.isLive ? 'Live Stream' : 'Recorded Video'}
                </small>
              </div>
            </div>
          </div>
          
          <div className="program-info">
            <h5>{selectedProgram.title}</h5>
            <p className="text-muted">{selectedProgram.description}</p>
            <div className="d-flex justify-content-between align-items-center">
              <span className="text-muted">
                {selectedProgram.startTime} - {selectedProgram.endTime}
              </span>
              {selectedProgram.isLive ? (
                <span className="badge bg-danger">
                  <i className="bi bi-broadcast me-1"></i>
                  LIVE
                </span>
              ) : (
                <span className="badge bg-secondary">RECORDED</span>
              )}
            </div>
          </div>
          
          <div className="player-controls mt-4">
            <div className="d-flex justify-content-center gap-3">
              <button className="btn btn-outline-primary">
                <i className="bi bi-skip-backward"></i>
              </button>
              <button className="btn btn-primary">
                <i className="bi bi-play-fill"></i>
              </button>
              <button className="btn btn-outline-primary">
                <i className="bi bi-skip-forward"></i>
              </button>
              <button className="btn btn-outline-secondary">
                <i className="bi bi-volume-up"></i>
              </button>
              <button className="btn btn-outline-secondary">
                <i className="bi bi-fullscreen"></i>
              </button>
            </div>
          </div>
        </div>
      )}
      
      <div style={{ height: '80px' }} />
    </div>
  );
}
