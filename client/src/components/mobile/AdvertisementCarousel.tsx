import React, { useState, useEffect } from "react";
import { useQuery } from "@tanstack/react-query";

interface MainAds {
  id: number;
  ads1?: string;
  ads2?: string;
  ads3?: string;
  ads1_url?: string;
  ads2_url?: string;
  ads3_url?: string;
  created_at?: string;
  updated_at?: string;
}

interface AdvertisementCarouselProps {
  className?: string;
  autoPlay?: boolean;
  interval?: number;
}

// API function to fetch main ads
const fetchMainAds = async (): Promise<MainAds[]> => {
  const response = await fetch('/api/advertisements');
  if (!response.ok) {
    throw new Error('Failed to fetch main ads');
  }
  return response.json();
};

export default function AdvertisementCarousel({ 
  className = "", 
  autoPlay = true, 
  interval = 5000 
}: AdvertisementCarouselProps) {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [showPopup, setShowPopup] = useState(false);
  const [selectedAd, setSelectedAd] = useState<MainAds | null>(null);

  // Fetch main ads
  const { data: mainAds, isLoading, error } = useQuery({
    queryKey: ['mainAds'],
    queryFn: fetchMainAds,
  });

  // Prepare carousel slides from main ads
  const slides = React.useMemo(() => {
    if (!mainAds || mainAds.length === 0) return [];

    const allSlides: Array<{ image: string; url?: string; title: string; ad: MainAds }> = [];

    mainAds.forEach(ad => {
      if (ad.ads1) {
        allSlides.push({
          image: ad.ads1,
          url: ad.ads1_url,
          title: 'Featured Advertisement 1',
          ad
        });
      }
      if (ad.ads2) {
        allSlides.push({
          image: ad.ads2,
          url: ad.ads2_url,
          title: 'Featured Advertisement 2',
          ad
        });
      }
      if (ad.ads3) {
        allSlides.push({
          image: ad.ads3,
          url: ad.ads3_url,
          title: 'Featured Advertisement 3',
          ad
        });
      }
    });

    return allSlides;
  }, [mainAds]);

  // Auto-play functionality
  useEffect(() => {
    if (!autoPlay || slides.length <= 1) return;

    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, interval);

    return () => clearInterval(timer);
  }, [autoPlay, interval, slides.length]);

  const handleSlideClick = (slide: any) => {
    if (slide.url) {
      if (slide.url.startsWith('http')) {
        window.open(slide.url, '_blank');
      } else {
        // Internal navigation
        window.location.href = slide.url;
      }
    }
  };

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };

  if (isLoading) {
    return (
      <div className={`advertisement-carousel ${className}`}>
        <div className="carousel-loading d-flex justify-content-center align-items-center" style={{ height: '200px' }}>
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading advertisements...</span>
          </div>
        </div>
      </div>
    );
  }

  if (error || !slides || slides.length === 0) {
    return (
      <div className={`advertisement-carousel ${className}`}>
        <div className="carousel-placeholder bg-light d-flex justify-content-center align-items-center" style={{ height: '200px' }}>
          <div className="text-center text-muted">
            <i className="bi bi-image fs-1 mb-2 d-block"></i>
            <p className="mb-0">No advertisements available</p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <>
      <div className={`advertisement-carousel position-relative ${className}`}>
        <div className="carousel-container overflow-hidden rounded">
          <div 
            className="carousel-slides d-flex transition-transform"
            style={{ 
              transform: `translateX(-${currentSlide * 100}%)`,
              transition: 'transform 0.5s ease-in-out'
            }}
          >
            {slides.map((slide, index) => (
              <div 
                key={index}
                className="carousel-slide flex-shrink-0 w-100 position-relative"
                style={{ cursor: slide.url ? 'pointer' : 'default' }}
                onClick={() => handleSlideClick(slide)}
              >
                <img
                  src={slide.image}
                  alt={slide.title}
                  className="w-100 h-100 object-fit-cover"
                  style={{ height: '200px' }}
                  onError={(e) => {
                    (e.target as HTMLImageElement).src = '/placeholder-ad.jpg';
                  }}
                />
                <div className="carousel-overlay position-absolute bottom-0 start-0 end-0 bg-gradient-dark p-3">
                  <h6 className="text-white mb-0">{slide.title}</h6>
                  {slide.url && (
                    <small className="text-white-50">
                      <i className="bi bi-box-arrow-up-right me-1"></i>
                      Click to learn more
                    </small>
                  )}
                </div>
              </div>
            ))}
          </div>

          {/* Navigation arrows */}
          {slides.length > 1 && (
            <>
              <button
                className="carousel-nav carousel-nav-prev position-absolute top-50 start-0 translate-middle-y btn btn-dark btn-sm rounded-circle ms-2"
                onClick={prevSlide}
                style={{ zIndex: 10 }}
              >
                <i className="bi bi-chevron-left"></i>
              </button>
              <button
                className="carousel-nav carousel-nav-next position-absolute top-50 end-0 translate-middle-y btn btn-dark btn-sm rounded-circle me-2"
                onClick={nextSlide}
                style={{ zIndex: 10 }}
              >
                <i className="bi bi-chevron-right"></i>
              </button>
            </>
          )}

          {/* Indicators */}
          {slides.length > 1 && (
            <div className="carousel-indicators position-absolute bottom-0 start-50 translate-middle-x mb-2">
              <div className="d-flex gap-1">
                {slides.map((_, index) => (
                  <button
                    key={index}
                    className={`carousel-indicator btn btn-sm rounded-circle ${
                      index === currentSlide ? 'btn-light' : 'btn-outline-light'
                    }`}
                    style={{ width: '8px', height: '8px', padding: 0 }}
                    onClick={() => goToSlide(index)}
                  />
                ))}
              </div>
            </div>
          )}
        </div>
      </div>



      <style jsx>{`
        .bg-gradient-dark {
          background: linear-gradient(transparent, rgba(0,0,0,0.7));
        }
        
        .carousel-nav {
          opacity: 0.7;
          transition: opacity 0.3s ease;
        }
        
        .carousel-nav:hover {
          opacity: 1;
        }
        
        .carousel-indicator {
          transition: all 0.3s ease;
        }
        
        .object-fit-cover {
          object-fit: cover;
        }
      `}</style>
    </>
  );
}
