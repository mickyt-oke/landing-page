<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta Tags for SEO -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page | Foreigners Registration</title>
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:;">
  <meta name="description" content="Apply for overstay clearance with the Nigeria Immigration Service. Regularize your status, avoid penalties, and stay compliant with immigration regulations. Fast, secure, and transparent application process.">
  <meta name="keywords" content="Nigeria Immigration Service, NIS, overstay clearance, visa regularization, immigration compliance, migrant portal, document upload, application process">
  <meta name="author" content="Nigeria Immigration Service">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#004080">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/nis.png') }}">

  <!-- Open Graph -->
  <meta property="og:title" content="Migrants Overstay Portal | Nigeria Immigration Service">
  <meta property="og:description" content="Apply now to avoid overstay penalties with the Nigeria Immigration Service. Submit your documents, avoid penalties, and stay compliant with immigration regulations.">
  <meta property="og:image" content="{{ asset('assets/images/nis.png') }}">
  <meta property="og:url" content="https://immigration.gov.ng">
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <nav class="nav">
        <div class="logo-container">
          <img src="{{ asset('assets/images/nis-logo.jpg') }}" alt="NIS Logo" class="logo-img-mobile" width="110" height="38" loading="eager" decoding="async" fetchpriority="high" onerror="this.style.display='none'">
          <div class="logo-text">
            <span class="logo-text-main"></span>
          </div>
        </div>

        <div class="nav-menu">
          <a href="{{ route('faq') }}" class="faq-nav-link">FAQ</a>
          <div class="dropdown">
            <button class="dropdown-btn" aria-haspopup="true" aria-expanded="false">
              Account
              <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="dropdown-content">
              @guest
                <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login</a>
                <a href="#" class="modal-trigger" data-modal="register"><i class="fas fa-user-plus" aria-hidden="true"></i> Register</a>
              @else
                <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt" aria-hidden="true"></i> Dashboard</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout</a>
              @endguest
            </div>
          </div>
        </div>

        <button class="hamburger" id="hamburger" aria-label="Toggle navigation menu" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </nav>
    </div>
  </header>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu" role="navigation" aria-label="Mobile menu">
    <a href="{{ route('faq') }}"><i class="fas fa-circle-question" aria-hidden="true"></i> FAQ</a>
    @guest
      <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login</a>
      <a href="#" class="modal-trigger" data-modal="register"><i class="fas fa-user-plus" aria-hidden="true"></i> Register</a>
    @else
      <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt" aria-hidden="true"></i> Dashboard</a>
      <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout</a>
    @endguest
  </div>
  <div class="overlay" id="overlay" role="presentation"></div>

  @if(session('success'))
    <div class="alert alert-success" role="alert" style="margin: 1rem; padding: .75rem 1rem; border: 1px solid #badbcc; background:#d1e7dd; color:#0a3622; border-radius:8px;">
      {{ session('success') }}
    </div>
  @endif

  @include('partials.modals.login')
  @include('partials.modals.register')
  @include('partials.modals.eligibility')
  @include('partials.modals.status')

  <main>
    <!-- HERO SECTION -->
    <section class="hero">
      <div class="hero-bg">
        <img src="{{ asset('assets/images/nis_officer2.jpg') }}" alt="NIS Officer" loading="eager" decoding="async" fetchpriority="high" onerror="this.style.display='none'">
      </div>
      <div class="hero-overlay"></div>

      <div class="container">
        <div class="hero-content">
          <h1>Registration of Foreign Nationals</h1>
          <p>Affected by the Middle-East Crisis</p>
          <div class="hero-buttons">
            <a href="#" class="btn btn-outline modal-trigger" data-modal="login">
              <i class="fas fa-user" aria-hidden="true"></i> Apply Now
            </a>
            <a href="#" class="btn btn-outline modal-trigger" data-modal="checkStatus">
              <i class="fas fa-search" aria-hidden="true"></i> Check Status
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- CAROUSEL SECTION -->
    <section class="section-sm">
      <div class="container">
        <div class="carousel-container">
          <div class="carousel">
            <div class="carousel-track" id="carouselTrack">
              <div class="carousel-slide">
                <div class="carousel-icon">
                  <i class="fas fa-shield-alt" aria-hidden="true"></i>
                </div>
                <div class="carousel-content">
                  <h3>Avoid Overstay Penalties</h3>
                  <p>Take advantage of this application window</p>
                </div>
              </div>
              <div class="carousel-slide">
                <div class="carousel-icon">
                  <i class="fas fa-file-upload" aria-hidden="true"></i>
                </div>
                <div class="carousel-content">
                  <h3>Upload Required Documents</h3>
                  <p>Securely apply online</p>
                </div>
              </div>
              <div class="carousel-slide">
                <div class="carousel-icon">
                  <i class="fas fa-clock" aria-hidden="true"></i>
                </div>
                <div class="carousel-content">
                  <h3>Fast Review Process</h3>
                  <p>Email Notification Updates</p>
                </div>
              </div>
            </div>

            <button class="carousel-btn prev" id="prevBtn" aria-label="Previous slide">
              <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button class="carousel-btn next" id="nextBtn" aria-label="Next slide">
              <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>

          <div class="carousel-dots" id="carouselDots" role="tablist" aria-label="Carousel navigation">
            <button class="dot active" role="tab" aria-label="Slide 1" aria-selected="true"></button>
            <button class="dot" role="tab" aria-label="Slide 2" aria-selected="false"></button>
            <button class="dot" role="tab" aria-label="Slide 3" aria-selected="false"></button>
          </div>
        </div>
      </div>
    </section>

    <!-- NIS OFFICERS GALLERY -->
    <section class="section">
      <div class="container">
        <h2 style="text-align: center; color: var(--secondary); margin-bottom: 1rem;">The Nations Foremost Border Agency</h2>
        <p style="text-align: center; color: var(--gray); max-width: 600px; margin: 0 auto 3rem;">Dedicated professionals ensuring border security and immigration compliance</p>

        <div class="gallery-grid">
          <div class="gallery-item">
            <img src="{{ asset('assets/images/nis_officer2.jpg') }}" alt="NIS Officer on duty" loading="lazy" decoding="async" onerror="this.style.display='none'">
          </div>
          <div class="gallery-item">
            <img src="{{ asset('assets/images/nis_officer3.jpg') }}" alt="NIS Officer at checkpoint" loading="lazy" decoding="async" onerror="this.style.display='none'">
          </div>
          <div class="gallery-item">
            <img src="{{ asset('assets/images/nis_officer4.jpg') }}" alt="NIS Officer in uniform" loading="lazy" decoding="async" onerror="this.style.display='none'">
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="section" style="background: var(--light);">
      <div class="container">
        <div class="about-grid">
          <div class="about-image">
            <img src="{{ asset('assets/images/nis_officer3.jpg') }}" alt="Immigration officers" loading="lazy" decoding="async" onerror="this.style.display='none'">
          </div>
          <div class="about-content">
            <h2>About the Portal</h2>
            <p class="mb-4">This Portal enables foreign nationals and travellers across the Middle-East document their stay so as to remain compliant with Immigration laws to avoid overstay penalties.</p>
            <ul class="about-features">
              <li>
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>Transparent online application process</span>
              </li>
              <li>
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>Secure document upload with encryption</span>
              </li>
              <li>
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>Expert guidance from NIS officials</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="section">
      <div class="container">
        <h2 style="text-align: center; color: var(--secondary); margin-bottom: 1rem;">Simple Steps</h2>
        <p style="text-align: center; color: var(--gray); max-width: 600px; margin: 0 auto 3rem;">Simple steps to complete your application</p>

        <div class="cards-grid">
          <div class="card">
            <div class="card-icon">
              <i class="fas fa-user-plus" aria-hidden="true"></i>
            </div>
            <h3>Register Account</h3>
            <p>Create a secure profile with your personal and travel info</p>
          </div>
          <div class="card">
            <div class="card-icon">
              <i class="fas fa-passport" aria-hidden="true"></i>
            </div>
            <h3>Upload &amp; Submit</h3>
            <p>Submit your travel documents for review</p>
          </div>
          <div class="card">
            <div class="card-icon">
              <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
            </div>
            <h3>Get Approval</h3>
            <p>Receive Acknowledgement of your Application</p>
          </div>
        </div>
      </div>
    </section>

    <!-- NOTICE SECTION -->
    <section class="section-sm">
      <div class="container">
        <div class="notice">
          <div class="notice-icon">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
          </div>
          <div class="notice-content">
            <h4>Eligibility Notice</h4>
            <p>Please check eligibility criteria <a href="#" data-modal="eligibility" class="modal-trigger">here</a></p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>About NIS</h4>
          <p style="color: rgba(255,255,255,0.8); line-height: 1.8;">Nigeria Immigration Service – protecting borders and facilitating migration with integrity and excellence.</p>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i> Immigration Policies</a></li>
            <li><a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i> Visa Information</a></li>
            <li><a href="{{ route('faq') }}"><i class="fas fa-chevron-right" aria-hidden="true"></i> FAQ</a></li>
            <li><a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i> Contact Support</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Support</h4>
          <ul>
            <li><a href="{{ route('faq') }}"><i class="fas fa-chevron-right" aria-hidden="true"></i> FAQ</a></li>
            <li><a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i> Privacy Policy</a></li>
            <li><a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i> Terms &amp; Conditions</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contact</h4>
          <ul class="contact-info">
            <li><i class="fas fa-envelope" aria-hidden="true"></i> nis.servicom@immigration.gov.ng</li>
            <li><i class="fas fa-phone" aria-hidden="true"></i> +234 800 123 4567</li>
            <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Abuja, Nigeria</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 Nigeria Immigration Service. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('assets/js/carousel.js') }}" defer></script>
  <script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>
