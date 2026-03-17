<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Nigeria Immigration Service">

    <title>User Dashboard | Foreigners Registration</title>
    <meta name="description" content="User dashboard for managing overstay clearance applications with Nigeria Immigration Service">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVJkEZSU3VwWiPqxigMAfjzD0/QW4K/ftSSLoDHtar4UlS3I1o2XQWARzbs86a1VQcREyXa40" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css" integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="assets/images/nis-logo-white.png" alt="NIS Logo" onerror="this.src='assets/images/nis-logo.png'">
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
                    
                    <a href="{{ route('web.applications.create') }}" class="nav-item">
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
        </aside>

<!-- MAIN CONTENT -->
        <main class="main-content" id="mainContent">
            <!-- Top Header -->
            <header class="top-header">
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
                            <a href="#profile"><i class="fas fa-user"></i> My Profile</a>
                            <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
                            <div style="border-top: 1px solid var(--gray-light); margin: 0.5rem 0;"></div>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-link" style="color: var(--accent); border:none; background:none; padding:0;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
