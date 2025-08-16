interface WelcomeSectionProps {
  onGetStarted: () => void;
}

export default function WelcomeSection({ onGetStarted }: WelcomeSectionProps) {
  return (
    <div className="welcome-section fade-in" data-testid="section-welcome">
      <h6 className="fw-bold text-dark mb-3">Welcome to AppHub!</h6>
      <p className="text-muted mb-4">
        Discover amazing apps, connect with communities, and track your digital journey.
      </p>
      <button 
        className="btn cta-button w-100" 
        onClick={onGetStarted}
        data-testid="button-get-started"
      >
        Get Started
      </button>
    </div>
  );
}
