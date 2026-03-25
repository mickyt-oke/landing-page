<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ | Foreigners Registration</title>
  <meta name="description" content="Frequently asked questions about the Registration of Foreign Nationals portal by the Nigeria Immigration Service.">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#004080">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/nis.png') }}">
</head>
<body>
  <header class="header">
    <div class="container">
      <nav class="nav">
        <div class="logo-container">
          <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center; gap: 1rem;">
            <img src="{{ asset('assets/images/nis-logo.jpg') }}" alt="NIS Logo" class="logo-img-mobile" width="110" height="38" loading="eager" decoding="async">
            <div class="logo-text">
              <span class="logo-text-main"></span>
            </div>
          </a>
        </div>

        {{-- <div class="nav-menu faq-nav-menu">
          <a href="{{ route('home') }}" class="faq-nav-link">Home</a>
          <a href="{{ route('home') }}#about" class="faq-nav-link">About</a>
          <a href="{{ route('faq') }}" class="faq-nav-link active">FAQ</a>
          <a href="#faq-support" class="faq-nav-link">Contact</a>
        </div> --}}

        <div class="faq-actions">
          <a href="mailto:nis.servicom@immigration.gov.ng" class="btn faq-support-btn">
            <i class="far fa-comment-alt"></i>
            <span>Contact Support</span>
          </a>
          @guest
            <a href="#" class="btn modal-trigger btn-login" data-modal="login"><i class="fas fa-sign-in-alt"></i>
              <span> Login
              </span>
            </a>
          @else
            <a href="{{ route('dashboard') }}" class="btn faq-support-btn"><i class="fas fa-tachometer-alt"></i>
              <span> Dashboard
              </span>
            </a>
            <form id="logout-form-faq" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-faq').submit();" class="btn faq-support-btn"><i class="fas fa-sign-out-alt"></i>
              <span> Logout
              </span>
            </a>
          @endguest
        </div>


  @include('partials.modals.login')
        </div>

        <div class="hamburger" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </nav>
    </div>
  </header>

  <div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('faq') }}">FAQ</a>
    <a href="mailto:nis.servicom@immigration.gov.ng">Contact Support</a>
    @guest
      <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt"></i> Login</a>
    @else
      <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <form id="logout-form-mobile-faq" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile-faq').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
    @endguest
  </div>
  <div class="overlay" id="overlay"></div>

  <main class="faq-page">
    <section class="faq-hero">
      <div class="container faq-hero-inner">
        <span class="faq-kicker">Support</span>
        <h1>Frequently Asked Questions</h1>
        <p>
          Everything you need to know about this platform. Can’t find the answer you’re looking for?
          Please <a href="mailto:nis.servicom@immigration.gov.ng">chat with our friendly team</a>.
        </p>
      </div>
    </section>

    <section class="faq-banner">
      <img src="{{ asset('assets/images/nis_officer3.jpg') }}" alt="Nigeria Immigration Service support" loading="lazy" decoding="async">
    </section>

    <section class="section">
      <div class="container faq-content-wrap">
        <div class="faq-content-header">
          <h2>Frequently Asked Questions (FAQs)</h2>
          <p class="faq-section-label">General Information</p>
          <p class="faq-section-copy">
            Find quick answers to common questions about this portal, its registration process, and available support channels.
            If you need further assistance, please contact our support team.
          </p>
        </div>

        <div class="faq-list">
          @foreach ($faqItems as $index => $item)
            <details class="faq-item" {{ $index === 0 ? 'open' : '' }}>
              <summary>
                <span>{{ $item['question'] }}</span>
                <i class="fas fa-chevron-down"></i>
              </summary>
              <div class="faq-answer">
                <p>{{ $item['answer'] }}</p>
              </div>
            </details>
          @endforeach
        </div>

        <div class="notice faq-notice" id="faq-support">
          <div class="notice-icon">
            <i class="fas fa-headset"></i>
          </div>
          <div class="notice-content">
            <h4>Need more help?</h4>
            <p>
              Contact support via <a href="mailto:nis.servicom@immigration.gov.ng">nis.servicom@immigration.gov.ng</a>
              or return to the <a href="{{ route('home') }}">landing page</a> to continue your application.
            </p>
          </div>
        </div>
      </div>
    </section>
  </main>

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
            <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
            <li><a href="{{ route('faq') }}"><i class="fas fa-chevron-right"></i> FAQ</a></li>
            <li><a href="mailto:nis.servicom@immigration.gov.ng"><i class="fas fa-chevron-right"></i> Contact Support</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Support</h4>
          <ul>
            <li><a href="{{ route('faq') }}"><i class="fas fa-chevron-right"></i> FAQ</a></li>
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

  <script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>
