import React, { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useAuth } from "@/hooks/use-auth";
import MobileHeader from "@/components/mobile/MobileHeader";
import AppsGrid from "@/components/mobile/AppsGrid";
import AdvertisementCarousel from "@/components/mobile/AdvertisementCarousel";
import TestimonialsSection from "@/components/mobile/TestimonialsSection";
import WelcomeSection from "@/components/mobile/WelcomeSection";
import BottomNavigation from "@/components/mobile/BottomNavigation";
import LoginModal from "@/components/modals/LoginModal";
import RegistrationModal from "@/components/modals/RegistrationModal";
import UserProfileModal from "@/components/modals/UserProfileModal";
import type { AppItem } from "@/lib/types";

export default function Home() {
  const [, setLocation] = useLocation();
  const { isAuthenticated } = useAuth();
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [showRegistrationModal, setShowRegistrationModal] = useState(false);
  const [showProfileModal, setShowProfileModal] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");

  // Use searchQuery to avoid unused variable warning
  console.log('Current search query:', searchQuery);

  // Initialize demo users on component mount
  useEffect(() => {
    fetch('/api/init-demo-users')
      .then(res => res.json())
      .then(data => console.log('Demo users initialized:', data))
      .catch(err => console.error('Failed to initialize demo users:', err));
  }, []);

  // Auto-show login modal for non-authenticated users immediately
  useEffect(() => {
    if (!isAuthenticated) {
      const timer = setTimeout(() => {
        setShowLoginModal(true);
      }, 500); // Show modal after 500ms for better UX

      return () => clearTimeout(timer);
    }
  }, [isAuthenticated]);

  const handleLogin = () => {
    setShowLoginModal(false);
  };

  const handleRegistration = () => {
    setShowRegistrationModal(false);
  };

  const switchToRegistration = () => {
    setShowLoginModal(false);
    setTimeout(() => setShowRegistrationModal(true), 300);
  };

  const switchToLogin = () => {
    setShowRegistrationModal(false);
    setTimeout(() => setShowLoginModal(true), 300);
  };

  const handleAppSelect = (app: AppItem) => {
    console.log(`Selected app: ${app.name}`);
    if (app.route) {
      setLocation(app.route);
    }
  };

  const handleSearch = (query: string) => {
    setSearchQuery(query);
    console.log(`Searching for: ${query}`);
  };

  return (
    <div className="mobile-container">
      <MobileHeader
        onProfileClick={() => setShowProfileModal(true)}
        onSearch={handleSearch}
        onAppSelect={handleAppSelect}
      />

      <AppsGrid onAppClick={handleAppSelect} />
      
      <AdvertisementCarousel className="mb-4" />

      <TestimonialsSection className="mb-4" />

      {!isAuthenticated && (
        <WelcomeSection onGetStarted={() => setShowLoginModal(true)} />
      )}
      
      <div style={{ height: '80px' }} />
      
      <BottomNavigation />
      
      <LoginModal
        isOpen={showLoginModal}
        onClose={() => setShowLoginModal(false)}
        onLogin={handleLogin}
        onSwitchToRegistration={switchToRegistration}
      />
      
      <RegistrationModal
        isOpen={showRegistrationModal}
        onClose={() => setShowRegistrationModal(false)}
        onRegistration={handleRegistration}
        onSwitchToLogin={switchToLogin}
      />

      <UserProfileModal
        isOpen={showProfileModal}
        onClose={() => setShowProfileModal(false)}
      />
    </div>
  );
}
