import React, { useState, useEffect } from "react";
import { useQuery } from "@tanstack/react-query";

interface Testimonial {
  id: number;
  group_id: number;
  title: string;
  content: string;
  image?: string;
  tag_line?: string;
  created_at?: string;
  updated_at?: string;
}

interface TestimonialsSectionProps {
  className?: string;
  autoPlay?: boolean;
  interval?: number;
}

// API function to fetch testimonials
const fetchTestimonials = async (): Promise<Testimonial[]> => {
  const response = await fetch('/api/testimonials');
  if (!response.ok) {
    throw new Error('Failed to fetch testimonials');
  }
  return response.json();
};

export default function TestimonialsSection({ 
  className = "", 
  autoPlay = true, 
  interval = 6000 
}: TestimonialsSectionProps) {
  const [currentSlide, setCurrentSlide] = useState(0);

  // Fetch testimonials
  const { data: testimonials, isLoading, error } = useQuery({
    queryKey: ['testimonials'],
    queryFn: fetchTestimonials,
  });

  // Auto-play functionality
  useEffect(() => {
    if (!autoPlay || !testimonials || testimonials.length <= 1) return;

    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % testimonials.length);
    }, interval);

    return () => clearInterval(timer);
  }, [autoPlay, interval, testimonials]);

  const nextSlide = () => {
    if (testimonials) {
      setCurrentSlide((prev) => (prev + 1) % testimonials.length);
    }
  };

  const prevSlide = () => {
    if (testimonials) {
      setCurrentSlide((prev) => (prev - 1 + testimonials.length) % testimonials.length);
    }
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };

  if (isLoading) {
    return (
      <div className={`testimonials-section ${className}`}>
        <div className="text-center p-4">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading testimonials...</span>
          </div>
        </div>
      </div>
    );
  }

  if (error || !testimonials || testimonials.length === 0) {
    return null; // Don't show anything if no testimonials
  }

  return (
    <div className={`testimonials-section ${className}`}>
      <div className="container">
        <h5 className="text-center mb-4 fw-bold text-primary">What Our Users Say</h5>
        
        <div className="testimonial-carousel position-relative">
          <div className="testimonial-container overflow-hidden rounded">
            <div 
              className="testimonial-slides d-flex transition-transform"
              style={{ 
                transform: `translateX(-${currentSlide * 100}%)`,
                transition: 'transform 0.5s ease-in-out'
              }}
            >
              {testimonials.map((testimonial, index) => (
                <div 
                  key={testimonial.id}
                  className="testimonial-slide flex-shrink-0 w-100 p-4"
                >
                  <div className="testimonial-card bg-white rounded-lg shadow-sm p-4 text-center">
                    <div className="testimonial-quote mb-3">
                      <i className="bi bi-quote text-primary" style={{ fontSize: '2rem' }}></i>
                    </div>
                    
                    {testimonial.image && (
                      <div className="testimonial-avatar mb-3">
                        <img
                          src={testimonial.image}
                          alt={testimonial.title}
                          className="rounded-circle mx-auto d-block"
                          style={{ width: '70px', height: '70px', objectFit: 'cover' }}
                          onError={(e) => {
                            (e.target as HTMLImageElement).src = `https://ui-avatars.com/api/?name=${encodeURIComponent(testimonial.title)}&background=007bff&color=fff&size=70`;
                          }}
                        />
                      </div>
                    )}
                    
                    <div className="testimonial-content mb-3">
                      <p className="text-muted mb-0" style={{ fontSize: '0.95rem', lineHeight: '1.6' }}>
                        "{testimonial.content}"
                      </p>
                    </div>
                    
                    <div className="testimonial-author">
                      <h6 className="mb-1 fw-bold text-dark">{testimonial.title}</h6>
                      {testimonial.tag_line && (
                        <small className="text-muted">{testimonial.tag_line}</small>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Navigation arrows */}
            {testimonials.length > 1 && (
              <>
                <button
                  className="testimonial-nav testimonial-nav-prev position-absolute top-50 start-0 translate-middle-y btn btn-primary btn-sm rounded-circle ms-2"
                  onClick={prevSlide}
                  style={{ zIndex: 10, width: '40px', height: '40px' }}
                >
                  <i className="bi bi-chevron-left"></i>
                </button>
                <button
                  className="testimonial-nav testimonial-nav-next position-absolute top-50 end-0 translate-middle-y btn btn-primary btn-sm rounded-circle me-2"
                  onClick={nextSlide}
                  style={{ zIndex: 10, width: '40px', height: '40px' }}
                >
                  <i className="bi bi-chevron-right"></i>
                </button>
              </>
            )}

            {/* Indicators */}
            {testimonials.length > 1 && (
              <div className="testimonial-indicators position-absolute bottom-0 start-50 translate-middle-x mb-3">
                <div className="d-flex gap-2">
                  {testimonials.map((_, index) => (
                    <button
                      key={index}
                      className={`testimonial-indicator btn btn-sm rounded-circle ${
                        index === currentSlide ? 'btn-primary' : 'btn-outline-primary'
                      }`}
                      style={{ width: '10px', height: '10px', padding: 0 }}
                      onClick={() => goToSlide(index)}
                    />
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <style jsx>{`
        .testimonial-nav {
          opacity: 0.8;
          transition: opacity 0.3s ease;
        }
        
        .testimonial-nav:hover {
          opacity: 1;
        }
        
        .testimonial-indicator {
          transition: all 0.3s ease;
        }
        
        .testimonial-card {
          border: 1px solid #e9ecef;
          min-height: 280px;
          display: flex;
          flex-direction: column;
          justify-content: center;
        }
        
        .testimonial-quote {
          opacity: 0.3;
        }
        
        @media (max-width: 768px) {
          .testimonial-card {
            min-height: 250px;
          }
          
          .testimonial-nav {
            width: 35px !important;
            height: 35px !important;
          }
        }
      `}</style>
    </div>
  );
}
