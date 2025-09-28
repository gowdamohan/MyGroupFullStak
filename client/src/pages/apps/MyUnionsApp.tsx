import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useAuth } from "@/hooks/use-auth";
import MobileHeader from "@/components/mobile/MobileHeader";

interface Union {
  id: string;
  name: string;
  description: string;
  category: string;
  logo: string;
  establishedYear: number;
  memberCount: number;
  location: {
    country: string;
    state: string;
    district: string;
  };
  isActive: boolean;
  registrationFee: number;
  benefits: string[];
}

interface UnionMember {
  id: string;
  unionId: string;
  name: string;
  membershipId: string;
  joinDate: string;
  status: 'active' | 'inactive' | 'suspended';
  position?: string;
  avatar: string;
  phone?: string;
  email?: string;
}

interface UserMembership {
  unionId: string;
  membershipId: string;
  joinDate: string;
  status: 'active' | 'inactive' | 'suspended';
  position?: string;
}

const SAMPLE_UNIONS: Union[] = [
  {
    id: '1',
    name: 'Software Engineers Union',
    description: 'Professional union for software engineers and developers',
    category: 'Professional',
    logo: '💻',
    establishedYear: 2010,
    memberCount: 1250,
    location: { country: 'IN', state: 'KA', district: 'BLR' },
    isActive: true,
    registrationFee: 500,
    benefits: ['Legal Support', 'Career Guidance', 'Networking Events', 'Insurance Coverage']
  },
  {
    id: '2',
    name: 'Teachers Association',
    description: 'Union for educators and academic professionals',
    category: 'Education',
    logo: '📚',
    establishedYear: 1995,
    memberCount: 2100,
    location: { country: 'IN', state: 'MH', district: 'MUM' },
    isActive: true,
    registrationFee: 300,
    benefits: ['Professional Development', 'Legal Aid', 'Health Benefits', 'Retirement Planning']
  },
  {
    id: '3',
    name: 'Healthcare Workers Union',
    description: 'Union for doctors, nurses, and healthcare professionals',
    category: 'Healthcare',
    logo: '🏥',
    establishedYear: 2005,
    memberCount: 1800,
    location: { country: 'IN', state: 'TN', district: 'CHN' },
    isActive: true,
    registrationFee: 400,
    benefits: ['Medical Insurance', 'Legal Support', 'Training Programs', 'Emergency Fund']
  },
  {
    id: '4',
    name: 'Transport Workers Union',
    description: 'Union for drivers, conductors, and transport workers',
    category: 'Transport',
    logo: '🚌',
    establishedYear: 1980,
    memberCount: 3500,
    location: { country: 'IN', state: 'DL', district: 'ND' },
    isActive: true,
    registrationFee: 200,
    benefits: ['Accident Insurance', 'Legal Aid', 'Family Support', 'Skill Development']
  },
];

const SAMPLE_MEMBERS: UnionMember[] = [
  {
    id: '1',
    unionId: '1',
    name: 'Rajesh Kumar',
    membershipId: 'SEU001',
    joinDate: '2022-01-15',
    status: 'active',
    position: 'Senior Developer',
    avatar: '👨‍💻',
    phone: '+91-9876543210',
    email: 'rajesh@example.com'
  },
  {
    id: '2',
    unionId: '1',
    name: 'Priya Sharma',
    membershipId: 'SEU002',
    joinDate: '2022-03-20',
    status: 'active',
    position: 'Tech Lead',
    avatar: '👩‍💻',
    phone: '+91-9876543211',
    email: 'priya@example.com'
  },
  {
    id: '3',
    unionId: '2',
    name: 'Dr. Amit Patel',
    membershipId: 'TA001',
    joinDate: '2021-08-10',
    status: 'active',
    position: 'Professor',
    avatar: '👨‍🏫',
    phone: '+91-9876543212',
    email: 'amit@example.com'
  },
  {
    id: '4',
    unionId: '3',
    name: 'Nurse Mary',
    membershipId: 'HWU001',
    joinDate: '2023-02-05',
    status: 'active',
    position: 'Head Nurse',
    avatar: '👩‍⚕️',
    phone: '+91-9876543213',
    email: 'mary@example.com'
  },
];

// Mock user memberships
const USER_MEMBERSHIPS: UserMembership[] = [
  {
    unionId: '1',
    membershipId: 'SEU001',
    joinDate: '2022-01-15',
    status: 'active',
    position: 'Senior Developer'
  }
];

export default function MyUnionsApp() {
  const [, setLocation] = useLocation();
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState<'unions' | 'my-cards' | 'members'>('unions');
  const [selectedUnion, setSelectedUnion] = useState<Union | null>(null);
  const [searchQuery, setSearchQuery] = useState("");

  const filteredUnions = SAMPLE_UNIONS.filter(union =>
    union.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    union.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
    union.category.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const userMemberships = USER_MEMBERSHIPS;
  const userUnions = SAMPLE_UNIONS.filter(union => 
    userMemberships.some(membership => membership.unionId === union.id)
  );

  const unionMembers = selectedUnion 
    ? SAMPLE_MEMBERS.filter(member => member.unionId === selectedUnion.id)
    : SAMPLE_MEMBERS;

  const handleUnionClick = (union: Union) => {
    setSelectedUnion(union);
    setActiveTab('members');
  };

  const handleJoinUnion = (union: Union) => {
    console.log(`Joining union: ${union.name}`);
    // In a real app, this would make an API call to join the union
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
      
      <div className="app-header bg-warning text-dark p-3 mb-3">
        <div className="d-flex align-items-center">
          <button 
            className="btn btn-outline-dark btn-sm me-3"
            onClick={handleBackToHome}
          >
            <i className="bi bi-arrow-left"></i>
          </button>
          <div>
            <h5 className="mb-0">
              <i className="bi bi-people-fill me-2"></i>
              MyUnions
            </h5>
            <small className="opacity-75">Union Management & Member Services</small>
          </div>
        </div>
      </div>

      {/* Navigation Tabs */}
      <div className="px-3 mb-3">
        <ul className="nav nav-pills nav-fill">
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'unions' ? 'active' : ''}`}
              onClick={() => setActiveTab('unions')}
            >
              <i className="bi bi-building me-1"></i>
              All Unions
            </button>
          </li>
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'my-cards' ? 'active' : ''}`}
              onClick={() => setActiveTab('my-cards')}
            >
              <i className="bi bi-credit-card me-1"></i>
              My Cards
            </button>
          </li>
          <li className="nav-item">
            <button 
              className={`nav-link ${activeTab === 'members' ? 'active' : ''}`}
              onClick={() => setActiveTab('members')}
            >
              <i className="bi bi-people me-1"></i>
              Members
            </button>
          </li>
        </ul>
      </div>

      {/* All Unions Tab */}
      {activeTab === 'unions' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">Registered Unions ({filteredUnions.length})</h6>
          </div>
          
          <div className="unions-list">
            {filteredUnions.map((union) => (
              <div 
                key={union.id}
                className="card mb-3 union-card"
                style={{ cursor: 'pointer' }}
              >
                <div className="card-body">
                  <div className="d-flex align-items-start">
                    <div 
                      className="union-logo me-3 d-flex align-items-center justify-content-center bg-light rounded"
                      style={{ width: '60px', height: '60px', fontSize: '2rem' }}
                    >
                      {union.logo}
                    </div>
                    <div className="flex-grow-1">
                      <div className="d-flex justify-content-between align-items-start">
                        <h6 className="card-title mb-1">{union.name}</h6>
                        <span className="badge bg-primary">{union.category}</span>
                      </div>
                      <p className="card-text small text-muted mb-2">{union.description}</p>
                      
                      <div className="union-stats mb-2">
                        <div className="row text-center">
                          <div className="col-4">
                            <small className="text-muted d-block">Members</small>
                            <strong>{union.memberCount.toLocaleString()}</strong>
                          </div>
                          <div className="col-4">
                            <small className="text-muted d-block">Since</small>
                            <strong>{union.establishedYear}</strong>
                          </div>
                          <div className="col-4">
                            <small className="text-muted d-block">Fee</small>
                            <strong>₹{union.registrationFee}</strong>
                          </div>
                        </div>
                      </div>
                      
                      <div className="d-flex justify-content-between align-items-center">
                        <button 
                          className="btn btn-outline-primary btn-sm"
                          onClick={() => handleUnionClick(union)}
                        >
                          View Members
                        </button>
                        {!userMemberships.some(m => m.unionId === union.id) ? (
                          <button 
                            className="btn btn-warning btn-sm"
                            onClick={() => handleJoinUnion(union)}
                          >
                            Join Union
                          </button>
                        ) : (
                          <span className="badge bg-success">Member</span>
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

      {/* My Cards Tab */}
      {activeTab === 'my-cards' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">My Union Member Cards</h6>
          </div>
          
          {userUnions.length === 0 ? (
            <div className="text-center py-5">
              <i className="bi bi-credit-card" style={{ fontSize: '3rem', color: '#6c757d' }}></i>
              <h6 className="mt-3 text-muted">No Union Memberships</h6>
              <p className="text-muted">Join a union to get your member card</p>
              <button 
                className="btn btn-warning"
                onClick={() => setActiveTab('unions')}
              >
                Browse Unions
              </button>
            </div>
          ) : (
            <div className="member-cards">
              {userUnions.map((union) => {
                const membership = userMemberships.find(m => m.unionId === union.id);
                return (
                  <div key={union.id} className="card mb-3 member-card">
                    <div className="card-header bg-warning text-dark">
                      <div className="d-flex justify-content-between align-items-center">
                        <h6 className="mb-0">{union.name}</h6>
                        <span className="badge bg-dark">{membership?.status?.toUpperCase()}</span>
                      </div>
                    </div>
                    <div className="card-body">
                      <div className="row">
                        <div className="col-8">
                          <div className="member-info">
                            <p className="mb-1"><strong>Member Name:</strong> {user?.firstName} {user?.lastName}</p>
                            <p className="mb-1"><strong>Member ID:</strong> {membership?.membershipId}</p>
                            <p className="mb-1"><strong>Position:</strong> {membership?.position || 'Member'}</p>
                            <p className="mb-1"><strong>Join Date:</strong> {new Date(membership?.joinDate || '').toLocaleDateString()}</p>
                          </div>
                        </div>
                        <div className="col-4 text-center">
                          <div 
                            className="union-logo-large d-flex align-items-center justify-content-center bg-light rounded mb-2"
                            style={{ width: '80px', height: '80px', fontSize: '2.5rem' }}
                          >
                            {union.logo}
                          </div>
                          <small className="text-muted">Valid Until: 2025</small>
                        </div>
                      </div>
                      
                      <div className="benefits mt-3">
                        <h6 className="small mb-2">Benefits:</h6>
                        <div className="d-flex flex-wrap gap-1">
                          {union.benefits.map((benefit, index) => (
                            <span key={index} className="badge bg-light text-dark">
                              {benefit}
                            </span>
                          ))}
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      )}

      {/* Members Tab */}
      {activeTab === 'members' && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">
              {selectedUnion ? `${selectedUnion.name} Members` : 'All Union Members'}
            </h6>
            {selectedUnion && (
              <button 
                className="btn btn-outline-secondary btn-sm"
                onClick={() => {
                  setSelectedUnion(null);
                  setActiveTab('unions');
                }}
              >
                <i className="bi bi-arrow-left me-1"></i>
                Back
              </button>
            )}
          </div>
          
          <div className="members-list">
            {unionMembers.map((member) => (
              <div key={member.id} className="card mb-2 member-item">
                <div className="card-body p-3">
                  <div className="d-flex align-items-center">
                    <div 
                      className="member-avatar me-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                      style={{ width: '50px', height: '50px', fontSize: '1.5rem' }}
                    >
                      {member.avatar}
                    </div>
                    <div className="flex-grow-1">
                      <div className="d-flex justify-content-between align-items-start">
                        <div>
                          <h6 className="mb-1">{member.name}</h6>
                          <p className="mb-1 small text-muted">{member.position}</p>
                          <small className="text-muted">ID: {member.membershipId}</small>
                        </div>
                        <div className="text-end">
                          <span className={`badge ${member.status === 'active' ? 'bg-success' : 
                                                  member.status === 'inactive' ? 'bg-secondary' : 'bg-danger'}`}>
                            {member.status.toUpperCase()}
                          </span>
                          <br />
                          <small className="text-muted">
                            Joined: {new Date(member.joinDate).toLocaleDateString()}
                          </small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
      
      <div style={{ height: '80px' }} />
    </div>
  );
}
