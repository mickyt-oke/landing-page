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
  
  <!-- Font Awesome for icons -->
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
  <!-- apple-touch-icon -->
  <link rel="apple-touch-icon" href="{{ asset('assets/images/nis.png') }}">

  <!-- Open Graph Meta Tags for social media sharing -->
  <meta property="og:title" content="Migrants Overstay Portal | Nigeria Immigration Service">
  <meta property="og:description" content="Apply now to avoid overstay penalties with the Nigeria Immigration Service. Submit your documents, avoid penalties, and stay compliant with immigration regulations. Fast, secure, and transparent application process.">
  <meta property="og:image" content="{{ asset('assets/images/nis.png') }}">
  <meta property="og:url" content="https://immigration.gov.ng">  
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <nav class="nav">
        <div class="logo-container">
          <img src="{{ asset('assets/images/nis-logo.jpg') }}" alt="NIS Logo" class="logo-img-mobile" width="110" height="38" loading="eager" decoding="async" fetchpriority="high" onerror="this.src='https://via.placeholder.com/40?text=NIS'">
          <div class="logo-text">
            <span class="logo-text-main"></span>
          </div>
        </div>

        <div class="nav-menu">
          <div class="dropdown">
            <button class="dropdown-btn">
              Account
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-content">
              <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt"></i> Login</a>
              <!-- <a href="#" class="modal-trigger" data-modal="register"><i class="fas fa-user-plus"></i> Register</a> -->
            </div>
          </div>
        </div>

        <div class="hamburger" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </nav>
    </div>
  </header>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt"></i> Login</a>
    <!-- <a href="#" class="modal-trigger" data-modal="register"><i class="fas fa-user-plus"></i> Register</a> -->
  </div>
  <div class="overlay" id="overlay"></div>

  <!-- LOGIN MODAL -->
  <div class="modal" id="loginModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close login modal">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        <h2>Welcome Back</h2>
        <p>Login to your Account</p>
      </div>
      <!-- PHP error and success message handling -->
      @if (session('error'))
        <div class="alert alert-error">
          {{ session('error') }}
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <div class="modal-body">
        <form id="dashboard" method="POST">
              @csrf
          <div class="form-group">
            <label for="loginEmail">Email Address</label>
            <div class="input-wrapper">
              <i class="fas fa-envelope"></i>
              <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
            </div>
          </div>

          <div class="form-group">
            <label for="loginPassword">Password</label>
            <div class="input-wrapper">
              
              <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
              <button type="button" class="password-toggle-btn" data-input="loginPassword" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="modal-btn">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>

          <div class="modal-footer">
            Don't have an account? <a href="#" class="modal-switch-trigger" data-from="login" data-to="register">Register here</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- REGISTER MODAL -->
  <div class="modal" id="registerModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close registration modal">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        <h4>Create New Account</h4>
        <p></p>
      </div>

      <div class="modal-body mb-0 mx-0">
        <div class="container" style="display: block;">
          <form id="register" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="Surname">Surname</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" name="sname" placeholder="Enter surname" required>
              </div>
            </div>

            <div class="form-group">
              <label for="FirstName">Given Names</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" name="fname" placeholder="Firstname Othernames" required>
              </div>
            </div>

            <div class="form-group">
              <label for="Passport">Passport Number</label>
              <div class="input-wrapper">
                <i class="fas fa-passport"></i>
                <input type="text" name="pptno" placeholder="Enter passport number" required>
              </div>
            </div>

            <div class="form-group">
              <label for="PptType">Type of Passport</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar"></i>
                <select name="ppttype" required>
                  <option value="" disabled selected>Select passport type</option>
                  <option value="ordinary">Ordinary/Standard</option>
                  <option value="diplomatic">Diplomatic</option>
                  <option value="service">Service/Official</option>
                  <option value="UN_Official">UN Laissez Passez</option>
                </select>
              </div>
            <!-- nationality list passed from json file in controller -->
             <div class="form-group">
              <label for="Nationality">Nationality</label>
              <div class="input-wrapper">
                <i class="fas fa-globe"></i>
                <select name="nationality" required>
                  <option value="" disabled selected>Select Nationality</option>
                  @foreach ($countries as $country)
                    <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="Email">Email Address</label>
              <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter email" required>
              </div>
            </div>

            <div class="form-group">
              <label for="Password1">Password</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="Password1" name="password1" placeholder="Create password" required>
                <button type="button" class="password-toggle-btn" data-input="Password1" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label for="Password2">Confirm Password</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="Password2" name="password_confirmation" placeholder="Confirm password" required>
                <button type="button" class="password-toggle-btn" data-input="regPassword2" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <div style="display: flex; gap: 1rem;">
            <button type="submit" class="modal-btn">Submit</button>
          </div>
           <div class="modal-footer">
            Already have an account? <a href="#" class="modal-switch-trigger" data-from="register" data-to="login">Login here</a>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ELIGIBILITY MODAL -->
  <div class="modal" id="eligibilityModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close eligibility modal">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        <h2>Eligibility Criteria</h2>
        <p>Ensure you meet the requirements before applying</p>
      </div>

      <div class="modal-body">
        <ul class="eligibility-list">
          <li>
            <i class="fas fa-check"></i>
            <span>Foreign nationals currently in Nigeria affected by the Middle-East crisis</span>
          </li>
          <li>
            <i class="fas fa-check"></i>
            <span>Must have valid travel documents (passport, visa)</span>
          </li>
          <li>
            <i class="fas fa-check"></i>
            <span>Must not have a history of immigration violations</span>
          </li>
          <li>
            <i class="fas fa-check"></i>
            <span>Must provide accurate and complete information</span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- CHECK STATUS MODAL with passport number or reference number generated after application submission -->
  <div class="modal" id="checkStatusModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close check status modal">
        <i class="fas fa-times"></i>
      </button>

      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        <h2>Check Application Status</h2>
        <p>Enter your passport number or reference number to check the status of your application</p>
      </div>

      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="passportNumber">Passport Number</label>
            <input type="text" id="passportNumber" class="form-control" placeholder="Enter your passport number">
          </div>
          <div class="form-group">
            <label for="referenceNumber">Reference Number</label>
            <input type="text" id="referenceNumber" class="form-control" placeholder="Enter your reference number">
          </div>
          <button type="submit" class="btn btn-primary">Check Status</button>
        </form>
      </div>
    </div>
  </div>

  <main>
    <!-- HERO SECTION -->
    <section class="hero">
      <div class="hero-bg">
        <img src="{{ asset('assets/images/nis_officer2.jpg') }}" alt="NIS Officer" loading="eager" decoding="async" fetchpriority="high" onerror="this.src='https://images.unsplash.com/photo-1578575436955-ef29da568c6c?w=1600'">
      </div>
      <div class="hero-overlay"></div>
      
      <div class="container">
        <div class="hero-content">
          <h1>Registration of Foreign Nationals</h1>
          <p>Affected by the Middle-East Crisis</p>
          <div class="hero-buttons">
            <a href="#" class="btn btn-outline modal-trigger" data-modal="login">
              <i class="fa-user fa-solid"></i> Apply Now
            </a>
            <a href="#" data-modal="checkStatusModal" class="btn btn-outline modal-trigger">
              <i class="fas fa-search"></i> Check Status
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
                  <i class="fas fa-shield-alt"></i>
                </div>
                <div class="carousel-content">
                  <h3>Avoid Overstay Penalties</h3>
                  <p>Take advantage of this application window</p>
                </div>
              </div>
              <div class="carousel-slide">
                <div class="carousel-icon">
                  <i class="fas fa-file-upload"></i>
                </div>
                <div class="carousel-content">
                  <h3>Upload Required Documents</h3>
                  <p>Securely apply online</p>
                </div>
              </div>
              <div class="carousel-slide">
                <div class="carousel-icon">
                  <i class="fas fa-clock"></i>
                </div>
                <div class="carousel-content">
                  <h3>Fast Review Process</h3>
                  <p>Email Notification Updates</p>
                </div>
              </div>
            </div>
            
            <button class="carousel-btn prev" id="prevBtn">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-btn next" id="nextBtn">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          
          <div class="carousel-dots" id="carouselDots">
            <button class="dot active"></button>
            <button class="dot"></button>
            <button class="dot"></button>
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
            <img src="{{ asset('assets/images/nis_officer2.jpg') }}" alt="NIS Officer 2" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1587502537104-aac9f540c691?w=800'">
          </div>
          <div class="gallery-item">
            <img src="{{ asset('assets/images/nis_officer3.jpg') }}" alt="NIS Officer 3" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1578575436955-ef29da568c6c?w=800'">
          </div>
          <div class="gallery-item">
            <img src="{{ asset('assets/images/nis_officer4.jpg') }}" alt="NIS Officer 4" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1587502537104-aac9f540c691?w=800'">
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="section" style="background: var(--light);">
      <div class="container">
        <div class="about-grid">
          <div class="about-image">
            <img src="{{ asset('assets/images/nis_officer3.jpg') }}" alt="Immigration officers" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1587502537104-aac9f540c691?w=800'">
          </div>
          <div class="about-content">
            <h2>About the Portal</h2>
            <p class="mb-4">This Portal enables foreign nationals and travellers across the Middle-East document their stay so as to remain compliant with Immigration laws to avoid overstay penalties.</p>
            <ul class="about-features">
              <li>
                <i class="fas fa-check-circle"></i>
                <span>Transparent online application process</span>
              </li>
              <li>
                <i class="fas fa-check-circle"></i>
                <span>Secure document upload with encryption</span>
              </li>
              <li>
                <i class="fas fa-check-circle"></i>
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
              <i class="fas fa-user-plus"></i>
            </div>
            <h3>Register Account</h3>
            <p>Create a secure profile with your personal and travel info</p>
          </div>
          <div class="card">
            <div class="card-icon">
              <i class="fas fa-passport"></i>
            </div>
            <h3>Upload &amp; Submit</h3>
            <p>Submit your travel documents for review</p>
          </div>
          <div class="card">
            <div class="card-icon">
              <i class="fas fa-cloud-upload-alt"></i>
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
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="notice-content">
            <h4>Eligibility Notice</h4>
            <p>Please check eligibility criteria <a href="#" data-modal="eligibilityModal" class="modal-trigger">here</a></p>
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
            <li><a href="#"><i class="fas fa-chevron-right"></i> Immigration Policies</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Visa Information</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Contact Support</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Support</h4>
          <ul>
            <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
            <li><a href="#"><i class="fas fa-chevron-right"></i> Terms & Conditions</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contact</h4>
          <ul class="contact-info">
            <li><i class="fas fa-envelope"></i> nis.servicom@immigration.gov.ng</li>
            <li><i class="fas fa-phone"></i> +234 800 123 4567</li>
            <li><i class="fas fa-map-marker-alt"></i> Abuja, Nigeria</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2026 Nigeria Immigration Service. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Carousel Script -->
  <script src="{{ asset('assets/js/carousel.js') }}" defer></script>
  <!-- Main Application Script -->
  <script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>