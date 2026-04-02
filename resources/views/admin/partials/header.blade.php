<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Nigeria Immigration Service</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin dashboard for reviewing and processing foreigners registration applications">

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
</head>
<body class="dashboard-body">
<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Admin navigation">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Collapse sidebar">
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>

        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/images/nis-logo.jpg') }}" alt="NIS Logo" data-img-fallback>
            </div>
            <div class="sidebar-title">Admin Portal</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Overview</div>

                @if(optional($currentUser)->hasAnyRole(['admin', 'superadmin']))
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                    <span>Admin Dashboard</span>
                </a>
                @endif

                <a href="{{ route('admin.reviewer.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.reviewer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span>Reviewer Dashboard</span>
                    @if(($stats['pending'] ?? 0) + ($stats['under_review'] ?? 0) > 0)
                    <span class="nav-badge">{{ ($stats['pending'] ?? 0) + ($stats['under_review'] ?? 0) }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                @if(optional($currentUser)->hasAnyRole(['admin', 'superadmin']))
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item" id="navAllApps">
                    <i class="fas fa-list" aria-hidden="true"></i>
                    <span>All Applications</span>
                    <span class="nav-badge">{{ $stats['total_applications'] ?? 0 }}</span>
                </a>
                @endif
                @if(optional($currentUser)->hasAnyRole(['superadmin']))
                <a href="{{ route('admin.users.index') }}" class="nav-item">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    <span>User Management</span>
                </a>
                @endif
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0; padding:0;">
                    @csrf
                    <button type="submit"
                            class="nav-item"
                            style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.8);padding:0.875rem 1.5rem;display:flex;align-items:center;gap:1rem;font-size:inherit;">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(optional($currentUser)->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ optional($currentUser)->name ?? 'Admin' }}</div>
                    <div class="user-role">{{ optional($currentUser)->role ?? 'Administrator' }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content" id="mainContent">
        <header class="top-header">
            <div style="display:flex;align-items:center;gap:1rem;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
                <div class="header-search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="search" id="tableSearch"
                           placeholder="Search name, passport, nationality..."
                           aria-label="Search applications">
                </div>
            </div>

            <div class="header-actions">
                <div class="dropdown" style="position:relative;">
                    <button class="header-btn"
                            style="width:auto;padding:0 1rem;gap:0.5rem;"
                            aria-haspopup="true" aria-expanded="false"
                            id="adminAccountDropdownBtn"
                            title="Account menu">
                        <i class="fas fa-user-shield" style="font-size:1.4rem;color:var(--primary);" aria-hidden="true"></i>
                        <span style="font-weight:600;">{{ optional($currentUser)->name ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down" style="font-size:0.8rem;" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-content" style="right:0;top:100%;margin-top:0.5rem;min-width:200px;">
                        <div style="padding:0.75rem 1.25rem;border-bottom:1px solid var(--gray-light);font-size:0.8rem;color:var(--gray);">
                            Signed in as <strong>{{ optional($currentUser)->role ?? 'admin' }}</strong>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit"
                                    style="width:100%;text-align:left;padding:0.75rem 1.25rem;background:none;border:none;cursor:pointer;color:var(--accent);display:flex;align-items:center;gap:0.5rem;font-size:0.9rem;">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTENT -->
        <div class="dashboard-content">
