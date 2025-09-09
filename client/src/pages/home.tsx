import { useState, useEffect } from "react";
import { useLocation } from "wouter";
import { useAuth } from "@/hooks/use-auth";
import MobileHeader from "@/components/mobile/MobileHeader";
import AppsGrid from "@/components/mobile/AppsGrid";
import AdsCarousel from "@/components/mobile/AdsCarousel";
import WelcomeSection from "@/components/mobile/WelcomeSection";
import BottomNavigation from "@/components/mobile/BottomNavigation";
import LoginModal from "@/components/modals/LoginModal";
import RegistrationModal from "@/components/modals/RegistrationModal";

export default function Home() {
  const [, setLocation] = useLocation();
  const { isAuthenticated } = useAuth();
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [showRegistrationModal, setShowRegistrationModal] = useState(false);

  // Initialize demo users on component mount
  useEffect(() => {
    fetch('/api/init-demo-users')
      .then(res => res.json())
      .then(data => console.log('Demo users initialized:', data))
      .catch(err => console.error('Failed to initialize demo users:', err));
  }, []);

  // Auto-show login modal for non-authenticated users
  useEffect(() => {
    if (!isAuthenticated) {
      const timer = setTimeout(() => {
        setShowLoginModal(true);
      }, 2000);
      
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

  return (
    <div className="mobile-container">
      <MobileHeader 
        onProfileClick={() => console.log("Profile clicked")}
        onSearch={(query: string) => console.log("Search:", query)}
      />
      
      <AppsGrid />
      
      <AdsCarousel />
      
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
    </div>
  );
}
