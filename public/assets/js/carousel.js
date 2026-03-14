// FILE: landing-page/assets/js/carousel.js

/**
 * Migrants Overstay Portal – Carousel (vanilla JS, touch support, auto-play)
 * No inline scripts, deferred execution.
 */
(function() {
  'use strict';

  // Wait for DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dots = document.querySelectorAll('.dot');
    
    // Exit if required elements are missing
    if (!track || !prevBtn || !nextBtn || dots.length === 0) return;

    const slides = track.children;
    const totalSlides = slides.length;
    if (totalSlides === 0) return;

    let currentIndex = 0;
    let autoTimer = null;
    let touchStartX = 0;
    let touchEndX = 0;

    // Minimum swipe distance (px)
    const SWIPE_THRESHOLD = 50;

    // Update carousel position and active dot
    function updateCarousel(index) {
      // Loop around
      if (index < 0) index = totalSlides - 1;
      if (index >= totalSlides) index = 0;

      track.style.transform = `translateX(-${index * 100}%)`;
      
      // Update dot indicators
      dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
      });

      currentIndex = index;
    }

    // Next slide
    function nextSlide() {
      updateCarousel(currentIndex + 1);
    }

    // Previous slide
    function prevSlide() {
      updateCarousel(currentIndex - 1);
    }

    // Auto-play management
    function startAutoPlay() {
      if (autoTimer) clearInterval(autoTimer);
      autoTimer = setInterval(nextSlide, 5000); // 5 seconds
    }

    function resetAutoPlay() {
      if (autoTimer) {
        clearInterval(autoTimer);
        startAutoPlay();
      }
    }

    // --- Event Listeners ---
    prevBtn.addEventListener('click', function() {
      prevSlide();
      resetAutoPlay();
    });

    nextBtn.addEventListener('click', function() {
      nextSlide();
      resetAutoPlay();
    });

    // Dot navigation
    dots.forEach((dot, index) => {
      dot.addEventListener('click', function(e) {
        updateCarousel(index);
        resetAutoPlay();
      });
    });

    // --- Touch events for mobile swipe ---
    track.addEventListener('touchstart', function(e) {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', function(e) {
      touchEndX = e.changedTouches[0].screenX;
      const diff = touchEndX - touchStartX;

      if (Math.abs(diff) > SWIPE_THRESHOLD) {
        if (diff > 0) {
          prevSlide(); // swipe right -> previous
        } else {
          nextSlide(); // swipe left -> next
        }
        resetAutoPlay();
      }
    }, { passive: true });

    // Pause auto-play when user hovers over carousel (optional)
    const container = document.querySelector('.carousel-container');
    if (container) {
      container.addEventListener('mouseenter', function() {
        if (autoTimer) clearInterval(autoTimer);
      });
      container.addEventListener('mouseleave', function() {
        startAutoPlay();
      });
    }

    // Initialize
    updateCarousel(0);
    startAutoPlay();
  });
})();