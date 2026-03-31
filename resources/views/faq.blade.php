<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ | Foreigners Registration</title>
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:;">
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
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <nav class="nav">
        <div class="logo-container">
          <a href="{{ route('home') }}" aria-label="Nigeria Immigration Service – Home" style="text-decoration:none;display:flex;align-items:center;gap:1rem;">
            <img src="{{ asset('assets/images/nis-logo.jpg') }}" alt="NIS Logo" class="logo-img-mobile"
                 width="110" height="38" loading="eager" decoding="async" onerror="this.style.display='none'">
            <div class="logo-text">
              <span class="logo-text-main"></span>
            </div>
          </a>
        </div>

        <div class="faq-actions">
          <a href="mailto:nis.servicom@immigration.gov.ng" class="btn faq-support-btn">
            <i class="far fa-comment-alt" aria-hidden="true"></i>
            <span>Contact Support</span>
          </a>
          @guest
            <a href="#" class="btn btn-primary faq-login-btn modal-trigger" data-modal="login">
              <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
              <span>Login</span>
            </a>
          @else
            <a href="{{ route('dashboard') }}" class="btn faq-support-btn">
              <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
              <span>Dashboard</span>
            </a>
            <form id="logout-form-faq" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            <a href="#" class="btn faq-support-btn"
               onclick="event.preventDefault(); document.getElementById('logout-form-faq').submit();">
              <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
              <span>Logout</span>
            </a>
          @endguest
        </div>

        <button class="hamburger" id="hamburger"
                aria-label="Toggle navigation menu" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </nav>
    </div>
  </header>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu" role="navigation" aria-label="Mobile menu">
    <a href="{{ route('home') }}"><i class="fas fa-home" aria-hidden="true"></i> Home</a>
    <a href="{{ route('faq') }}"><i class="fas fa-circle-question" aria-hidden="true"></i> FAQ</a>
    <a href="mailto:nis.servicom@immigration.gov.ng"><i class="fas fa-envelope" aria-hidden="true"></i> Contact Support</a>
    @guest
      <a href="#" class="modal-trigger" data-modal="login"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login</a>
      <a href="#" class="modal-trigger" data-modal="register"><i class="fas fa-user-plus" aria-hidden="true"></i> Register</a>
    @else
      <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt" aria-hidden="true"></i> Dashboard</a>
      <form id="logout-form-mobile-faq" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile-faq').submit();">
        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
      </a>
    @endguest
  </div>
  <div class="overlay" id="overlay" role="presentation"></div>

  <!-- MODALS (must be direct descendants of body-level, outside nav/header) -->
  @include('partials.modals.login')
  @include('partials.modals.register')

  <main class="faq-page">
    <!-- HERO -->
    <section class="faq-hero">
      <div class="container faq-hero-inner">
        <span class="faq-kicker">Support Centre</span>
        <h1>Frequently Asked Questions</h1>
        <p>
          Everything you need to know about this portal.
          Can't find what you're looking for?
          <a href="mailto:nis.servicom@immigration.gov.ng">Contact our support team</a>.
        </p>
      </div>
    </section>

    <!-- BANNER IMAGE -->
    <section class="faq-banner" aria-hidden="true">
      <img src="{{ asset('assets/images/nis_officer3.jpg') }}"
           alt="Nigeria Immigration Service officers" loading="lazy" decoding="async"
           onerror="this.style.display='none'">
    </section>

    <!-- FAQ CONTENT -->
    <section class="section">
      <div class="container faq-content-wrap">
        <div class="faq-content-header">
          <h2>Frequently Asked Questions</h2>
          <p class="faq-section-label">General Information</p>
          <p class="faq-section-copy">
            Quick answers to common questions about the portal, registration process, and support channels.
          </p>
        </div>

        <!-- TOOLBAR: search + expand/collapse -->
        <div class="faq-toolbar">
          <div class="faq-search-wrap">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" id="faqSearch"
                   placeholder="Search questions…"
                   aria-label="Search FAQ questions"
                   autocomplete="off">
          </div>
          <div class="faq-toolbar-actions">
            <button id="expandAllBtn" class="faq-tool-btn" type="button">
              <i class="fas fa-chevron-down" aria-hidden="true"></i> Expand all
            </button>
            <button id="collapseAllBtn" class="faq-tool-btn" type="button">
              <i class="fas fa-chevron-up" aria-hidden="true"></i> Collapse all
            </button>
          </div>
        </div>

        <!-- FAQ LIST -->
        <div class="faq-list" id="faqList">
          @foreach ($faqItems as $index => $item)
            <details class="faq-item" {{ $index === 0 ? 'open' : '' }}>
              <summary aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                <span class="faq-q-text">{{ $item['question'] }}</span>
                <span class="faq-chevron" aria-hidden="true">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="faq-answer-wrap">
                <div class="faq-answer">
                  <p>{{ $item['answer'] }}</p>
                </div>
              </div>
            </details>
          @endforeach

          <div id="faqNoResults" class="faq-no-results" style="display:none;" role="status">
            <i class="fas fa-search" aria-hidden="true"></i>
            <p>No questions match your search. Try different keywords.</p>
          </div>
        </div>

        <!-- SUPPORT NOTICE -->
        <div class="notice faq-notice" id="faq-support">
          <div class="notice-icon">
            <i class="fas fa-headset" aria-hidden="true"></i>
          </div>
          <div class="notice-content">
            <h4>Need more help?</h4>
            <p>
              Contact support via
              <a href="mailto:nis.servicom@immigration.gov.ng">nis.servicom@immigration.gov.ng</a>
              or return to the <a href="{{ route('home') }}">home page</a> to continue your application.
            </p>
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
          <p style="color:rgba(255,255,255,0.8);line-height:1.8;">Nigeria Immigration Service – protecting borders and facilitating migration with integrity and excellence.</p>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right" aria-hidden="true"></i> Home</a></li>
            <li><a href="{{ route('faq') }}"><i class="fas fa-chevron-right" aria-hidden="true"></i> FAQ</a></li>
            <li><a href="mailto:nis.servicom@immigration.gov.ng"><i class="fas fa-chevron-right" aria-hidden="true"></i> Contact Support</a></li>
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

  <script src="{{ asset('assets/js/app.js') }}" defer></script>

  <!-- FAQ Accordion & Search (page-specific) -->
  <script>
  (function () {
    'use strict';

    /* ── Animation helpers ─────────────────────────────────────── */
    function animateOpen(details) {
      var wrap = details.querySelector('.faq-answer-wrap');
      if (!wrap) { details.open = true; return; }
      details.open = true;
      wrap.style.gridTemplateRows = '0fr';
      wrap.style.opacity = '0';
      requestAnimationFrame(function () {
        wrap.style.transition = 'grid-template-rows 0.35s cubic-bezier(.4,0,.2,1), opacity 0.3s ease';
        wrap.style.gridTemplateRows = '1fr';
        wrap.style.opacity = '1';
        wrap.addEventListener('transitionend', function done(e) {
          if (e.propertyName !== 'grid-template-rows') return;
          wrap.removeEventListener('transitionend', done);
          wrap.style.cssText = '';
        });
      });
    }

    function animateClose(details) {
      var wrap = details.querySelector('.faq-answer-wrap');
      if (!wrap) { details.open = false; return; }
      wrap.style.gridTemplateRows = '1fr';
      wrap.style.opacity = '1';
      requestAnimationFrame(function () {
        wrap.style.transition = 'grid-template-rows 0.35s cubic-bezier(.4,0,.2,1), opacity 0.3s ease';
        wrap.style.gridTemplateRows = '0fr';
        wrap.style.opacity = '0';
        wrap.addEventListener('transitionend', function done(e) {
          if (e.propertyName !== 'grid-template-rows') return;
          wrap.removeEventListener('transitionend', done);
          details.open = false;
          wrap.style.cssText = '';
        });
      });
    }

    /* ── Attach click handlers ─────────────────────────────────── */
    document.querySelectorAll('.faq-item').forEach(function (details) {
      var summary = details.querySelector('summary');
      if (!summary) return;

      summary.setAttribute('aria-expanded', details.open ? 'true' : 'false');

      summary.addEventListener('click', function (e) {
        e.preventDefault();
        if (details.open) {
          animateClose(details);
          summary.setAttribute('aria-expanded', 'false');
        } else {
          animateOpen(details);
          summary.setAttribute('aria-expanded', 'true');
        }
      });
    });

    /* ── Expand / Collapse all ─────────────────────────────────── */
    var expandBtn    = document.getElementById('expandAllBtn');
    var collapseBtn  = document.getElementById('collapseAllBtn');

    if (expandBtn) {
      expandBtn.addEventListener('click', function () {
        document.querySelectorAll('.faq-item').forEach(function (details) {
          if (!details.open && details.style.display !== 'none') {
            var s = details.querySelector('summary');
            animateOpen(details);
            if (s) s.setAttribute('aria-expanded', 'true');
          }
        });
      });
    }

    if (collapseBtn) {
      collapseBtn.addEventListener('click', function () {
        document.querySelectorAll('.faq-item').forEach(function (details) {
          if (details.open) {
            var s = details.querySelector('summary');
            animateClose(details);
            if (s) s.setAttribute('aria-expanded', 'false');
          }
        });
      });
    }

    /* ── Client-side search / filter ──────────────────────────── */
    var searchInput  = document.getElementById('faqSearch');
    var noResults    = document.getElementById('faqNoResults');

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var visible = 0;

        document.querySelectorAll('.faq-item').forEach(function (details) {
          var question = (details.querySelector('.faq-q-text')  || {}).textContent || '';
          var answer   = (details.querySelector('.faq-answer')  || {}).textContent || '';
          var matches  = !q || question.toLowerCase().includes(q) || answer.toLowerCase().includes(q);

          details.style.display = matches ? '' : 'none';

          if (matches) {
            visible++;
            // Auto-expand items that match a search query
            if (q && !details.open) {
              var s = details.querySelector('summary');
              animateOpen(details);
              if (s) s.setAttribute('aria-expanded', 'true');
            }
          }
        });

        if (noResults) {
          noResults.style.display = visible === 0 ? 'block' : 'none';
        }
      });
    }

  }());
  </script>
</body>
</html>
