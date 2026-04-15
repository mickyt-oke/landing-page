<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password | Foreigners Registration</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Nigeria Immigration Service">
    <meta name="description" content="User dashboard for managing foreigners registration for perssons affected by the middle-east crisis with the Nigeria Immigration Service">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    
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
<body class="dashboard-body">
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        {{-- <aside class="sidebar" id="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" onerror="this.src='{{ asset('assets/images/nis-logo.png') }}'">
                </div>
                <div class="sidebar-title">
                    <h4>Registration Portal</h4>
                </div>
            </div>

            <! -- sidebar mobile header -->
            <div class="sidebar-mobile-header">
                <div class="sidebar-logo">
                    <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" onerror="this.src='{{ asset('assets/images/nis-logo.png') }}'">
                </div>
                <button class="mobile-menu-toggle" id="mobileMenuToggleMobile" title="Toggle Menu">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    
                    <a href="{{ route('dashboard') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('applications.create') }}" class="nav-item">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Application</span>
                    </a>
                </div>

                <!-- <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    
                    <a href="#profile" class="nav-item">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    
                    <a href="#documents" class="nav-item">
                        <i class="fas fa-folder"></i>
                        <span>Documents</span>
                    </a>
                    
                    <a href="#settings" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div> -->

                <div class="nav-section">
                    <div class="nav-section-title">Support</div>
                    
                    <a href="#help" class="nav-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help Center</span>
                    </a>

                    <a href="#contact" class="nav-item" aria-label="Contact Support">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ optional(auth()->user())->name ?? 'User' }}</div>
                        <div class="user-role">{{ optional(auth()->user())->role ?? '' }}</div>
                    </div>
                </div>
            </div>
        </aside> --}}

<!-- MAIN CONTENT -->
        <main class="main-content" id="mainContent">
            <!-- Top Header -->
            {{-- <header class="top-header">
                <div style="display: flex; align-items: center;">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search applications...">
                    </div>
                </div>

                <div class="header-actions">
                    <button class="header-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge">2</span>
                    </button>
                    
                    <button class="header-btn" title="Messages">
                        <i class="fas fa-envelope"></i>
                    </button>                    
                    <div class="dropdown" style="position: relative;">
                        <button class="header-btn" title="Account" style="width: auto; padding: 0 1rem; gap: 0.5rem;">
                            <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                            <span style="font-weight: 600;">{{ optional(auth()->user())->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </button>
                        
                        <div class="dropdown-content" style="right: 0; top: 100%; margin-top: 0.5rem;">
                            <a href="#"><i class="fas fa-user"></i> My Profile</a>
                            <!-- logout form -->
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 0.5rem 2rem; background: none; border: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                    </div>
                </div>
            </header> --}}




<div class="dashboard-content" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 520px; width: 100%; margin: 40px auto; padding: 0 16px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">

                <div style="width:64px;height:64px;background:#e3f2fd;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                    <i class="fas fa-unlock-alt fa-2x" style="color:#003087;"></i>
                </div>

                <h2 style="color:#003087;font-size:1.4rem;margin-bottom:12px;">Reset Your Password</h2>
                <p class="text-muted mb-4" style="font-size:0.95rem;line-height:1.7;">
                    Enter your registered email address and we will send you a link to reset your password.
                </p>

                @if (session('status'))
                    <div class="alert alert-success py-2" role="alert" style="font-size:0.9rem;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2" role="alert" style="font-size:0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="text-start">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="Enter your registered email"
                               required autocomplete="email">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                </form>

                <hr class="my-4">

                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>

            </div>
        </div>
    </div>
</div>
       </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    <!-- Include modal functionality from main app -->
    <script>
        // Modal trigger functionality
        document.querySelectorAll('.modal-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalName = this.dataset.modal;
                if (modalName === 'register') {
                    // Redirect to index.php with register modal open
                    window.location.href = 'index.php?action=register';
                }
            });
        });

        // Modal close functionality
        document.querySelectorAll('.modal-close, .modal-close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    modal.classList.remove('active');
                });
            });
        });
    </script>
      <!-- Carousel Script -->
  <script src="{{ asset('assets/js/carousel.js') }}" defer></script>
  <!-- Main Application Script -->
  <script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>
