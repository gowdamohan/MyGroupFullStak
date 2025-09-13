import React, { useEffect } from "react";
import type { CarouselImage } from "@/lib/types";

const defaultImages: CarouselImage[] = [
  {
    src: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=300",
    alt: "Tech Conference",
    title: "Tech Conference"
  },
  {
    src: "https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=300",
    alt: "Mobile Apps",
    title: "Mobile Apps"
  },
  {
    src: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=300",
    alt: "Creative Workspace",
    title: "Creative Workspace"
  }
];

interface AdsCarouselProps {
  images?: CarouselImage[];
  autoRotateInterval?: number;
}

export default function AdsCarousel({ 
  images = defaultImages, 
  autoRotateInterval = 3000 
}: AdsCarouselProps) {
  
  useEffect(() => {
    // Initialize Bootstrap carousel with auto-rotation
    const carouselElement = document.querySelector('#adsCarousel');
    if (carouselElement && (window as any).bootstrap) {
      new (window as any).bootstrap.Carousel(carouselElement, {
        interval: autoRotateInterval,
        ride: 'carousel'
      });
    }
  }, [autoRotateInterval]);

  return (
    <div className="ads-carousel">
      <div 
        id="adsCarousel" 
        className="carousel slide" 
        data-bs-ride="carousel" 
        data-bs-interval={autoRotateInterval}
        data-testid="carousel-ads"
      >
        <div className="carousel-inner">
          {images.map((image, index) => (
            <div 
              key={index}
              className={`carousel-item ${index === 0 ? 'active' : ''}`}
            >
              <img 
                src={image.src} 
                className="d-block w-100" 
                alt={image.alt}
                data-testid={`img-carousel-${index}`}
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
