<?php
/**
 * Admin Dashboard - Migrants Overstay Portal
 * Nigeria Immigration Service
 * For Immigration Officers to review and process applications
 */
session_start();

// Check if user is logged in and is an admin (placeholder for actual auth)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../index.php');
//     exit;
// }

// Placeholder admin user data
$currentUser = [
    'name' => 'Officer Ahmed',
    'email' => 'officer.ahmed@nis.gov.ng',
    'role' => 'Immigration Officer',
    'initials' => 'OA'
];

// Placeholder statistics
$stats = [
    'total_applications' => 156,
    'pending_review' => 23,
    'under_review' => 12,
    'approved' => 98,
    'rejected' => 23
];

// Rejection reasons for dropdown
$rejectionReasons = [
    'incomplete_documents' => 'Incomplete Documents',
    'invalid_passport' => 'Invalid/Expired Passport',
    'overstay_duration' => 'Excessive Overstay Duration',
    'security_concerns' => 'Security Concerns',
    'false_information' => 'False Information Provided',
    'other' => 'Other Reason'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | NIS Overstay Portal</title>
    <meta name="description" content="Admin dashboard for processing migrant overstay applications">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap files -->
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
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
                    <img src="../assets/images/nis-logo-white.png" alt="NIS Logo" onerror="this.src='../assets/images/nis-logo.png'">
                </div>
                <div class="sidebar-title">
                    Admin Portal
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Overview</div>
                    
                    <a href="dashboard.php" class="nav-item active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- <a href="#analytics" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a> -->
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Management</div> 
                    
                    <a href="#applicationsSection" class="nav-item" data-filter="all">
                        <i class="fas fa-list"></i>
                        <span>All Applications</span>
                        <span class="nav-badge"><?php echo $stats['total_applications']; ?></span>
                    </a>

                    <a href="#users" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                    
                    <!-- <a href="#pending" class="nav-item" data-filter="pending">
                        <i class="fas fa-clock"></i>
                        <span>Pending Review</span>
                        <span class="nav-badge" style="background: var(--warning);"></span>
                    </a>
                    
                    <a href="#under-review" class="nav-item" data-filter="under-review">
                        <i class="fas fa-search"></i>
                        <span>Under Review</span>
                        <span class="nav-badge" style="background: var(--info);"></span>
                    </a>
                    
                    <a href="#approved" class="nav-item" data-filter="approved">
                        <i class="fas fa-check-circle"></i>
                        <span>Approved</span>
                        <span class="nav-badge" style="background: var(--primary);"></span>
                    </a>
                    
                    <a href="#rejected" class="nav-item" data-filter="rejected">
                        <i class="fas fa-times-circle"></i>
                        <span>Rejected</span>
                        <span class="nav-badge" style="background: var(--accent);"></span>
                    </a> -->
                </div>
            </nav>
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
                        <input type="text" placeholder="Search applications, users...">
                    </div>
                </div>

                <div class="header-actions">
                    <button class="header-btn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge">5</span>
                    </button>
                    
                    <button class="header-btn" title="Messages">
                        <i class="fas fa-envelope"></i>
                    </button>
                    
                    <button class="header-btn" title="System Alerts" style="color: var(--warning);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </button>
                    
                    <div class="dropdown" style="position: relative;">
                        <button class="header-btn" title="Account" style="width: auto; padding: 0 1rem; gap: 0.5rem;">
                            <i class="fas fa-user-shield" style="font-size: 1.5rem; color: var(--primary);"></i>
                            <span style="font-weight: 600;"><?php echo $currentUser['name']; ?></span>
                            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </button>
                        
                        <div class="dropdown-content" style="right: 0; top: 100%; margin-top: 0.5rem;">
                            <a href="#profile"><i class="fas fa-user"></i> My Profile</a>
                            <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
                            <div style="border-top: 1px solid var(--gray-light); margin: 0.5rem 0;"></div>
                            <a href="../index.php" style="color: var(--accent);"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Application Management</h1>
                    <p class="page-subtitle">Review and process applications</p>
                </div>

                <!-- Quick Stats Row -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 2rem;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Applications Received</h3>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; align-items: center; gap: 1.5rem;">
                                <div style="width: 60px; height: 60px; background: rgba(30, 132, 73, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-alt" style="font-size: 1.5rem; color: var(--primary);"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1.25rem; font-weight: 600;"><?php echo $stats['total_applications']; ?></div>
                                    <div style="font-size: 0.9rem; color: var(--gray);">Total applications received</div>
                                </div>
                            </div>
                            </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Approval Rate</h3>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; align-items: center; gap: 1.5rem;">
                                <div style="position: relative; width: 100px; height: 100px;">
                                    <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#ecf0f1" stroke-width="3"/>
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#1E8449" stroke-width="3" stroke-dasharray="81, 100"/>
                                    </svg>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                                        81%
                                    </div>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; padding: 0.75rem; background: rgba(30, 132, 73, 0.1); border-radius: 8px;">
                                        <span style="font-size: 0.9rem; color: var(--dark);"><i class="fas fa-check" style="color: var(--primary); margin-right: 0.5rem;"></i>Approved</span>
                                        <span style="font-size: 0.9rem; font-weight: 600; color: var(--primary);">98</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(192, 57, 43, 0.1); border-radius: 8px;">
                                        <span style="font-size: 0.9rem; color: var(--dark);"><i class="fas fa-times" style="color: var(--accent); margin-right: 0.5rem;"></i>Rejected</span>
                                        <span style="font-size: 0.9rem; font-weight: 600; color: var(--accent);">23</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Charts Overview -->
                <div style="display: grid; grid-template-columns: repeat(2, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                    <div class="chart-container">
                        <h3 class="chart-title">Applications by Country (Bar)</h3>
                        <canvas id="applicationsChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="chart-title">Top Nationalities Share (Pie)</h3>
                        <canvas id="nationalitiesPieChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="chart-title">Country Application Distribution (Histogram)</h3>
                        <canvas id="countryHistogramChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="chart-title">Submission Trend (Line)</h3>
                        <canvas id="submissionLineChart"></canvas>
                    </div>
                </div>

                <!-- <div style="display: flex; gap: 1.5rem; margin-top: 2rem;">
                    <div class="content-card" style="flex: 1;">
                        <div class="card-header">
                            <h3 class="card-title">Top Nationalities</h3>
                        </div>
                        <div class="card-body">
                            <ul class="top-list">
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <img src="../assets/images/flags/india.png" alt="India Flag" class="item-avatar">
                                            <span>India</span>
                                        </div>
                                        <span class="item-value">45 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <img src="../assets/images/flags/pakistan.png" alt="Pakistan Flag" class="item-avatar">
                                            <span>Pakistan</span>
                                        </div>
                                        <span class="item-value">30 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <img src="../assets/images/flags/philippines.png" alt="Philippines Flag" class="item-avatar">
                                            <span>Philippines</span>
                                        </div>
                                        <span class="item-value">25 Applications</span>
                                    </div>
                                </li>   
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <img src="../assets/images/flags/ghana.png" alt="Ghana Flag" class="item-avatar">
                                            <span>Ghana</span>
                                        </div>
                                        <span class="item-value">20 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <img src="../assets/images/flags/sudan.png" alt="Sudan Flag" class="item-avatar">
                                            <span>Sudan</span>
                                        </div>
                                        <span class="item-value">15 Applications</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="content-card" style="flex: 1;">
                        <div class="card-header">
                            <h3 class="card-title">Top Visa Categories</h3>
                        </div>
                        <div class="card-body">
                            <ul class="top-list">
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <i class="fas fa-briefcase item-avatar" style="color: var(--primary);"></i>
                                            <span>Business Visa</span>
                                        </div>
                                        <span class="item-value">60 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <i class="fas fa-plane item-avatar" style="color: var(--info);"></i>
                                            <span>Tourist Visa</span>
                                        </div>
                                        <span class="item-value">50 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <i class="fas fa-graduation-cap item-avatar" style="color: var(--accent);"></i>
                                            <span>Student Visa</span>
                                        </div>
                                        <span class="item-value">30 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <i class="fas fa-users item-avatar" style="color: var(--warning);"></i>
                                            <span>Family Visa</span>
                                        </div>
                                        <span class="item-value">10 Applications</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-item">
                                        <div class="item-info">
                                            <i class="fas fa-briefcase item-avatar" style="color: var(--primary);"></i>
                                            <span>Work Visa</span>
                                        </div>
                                        <span class="item-value">5 Applications</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-value" id="totalApplications"><?php echo $stats['total_applications']; ?></div>
                        <div class="stat-label">Total Applications</div>
                    </div>

                    <div class="stat-card warning">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value" id="pendingApplications"><?php echo $stats['pending_review']; ?></div>
                        <div class="stat-label">Pending Review</div>
                    </div>

                    <div class="stat-card info">
                        <div class="stat-icon info">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="stat-value" id="underReviewApplications"><?php echo $stats['under_review']; ?></div>
                        <div class="stat-label">Under Review</div>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon primary">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value" id="approvedApplications"><?php echo $stats['approved']; ?></div>
                        <div class="stat-label">Approved</div>
                    </div>
                </div>

                <!-- Applications Management -->
                <div class="section content-card" id="applicationsSection">
                    <div class="card-header">
                        <h3 class="card-title">Applications Queue</h3>
                        <div class="card-actions">
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-filter"></i> Advanced Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">
                                All <span class="count"><?php echo $stats['total_applications']; ?></span>
                            </button>
                            <button class="filter-tab" data-filter="pending">
                                Pending <span class="count"><?php echo $stats['pending_review']; ?></span>
                            </button>
                            <button class="filter-tab" data-filter="under-review">
                                Under Review <span class="count"><?php echo $stats['under_review']; ?></span>
                            </button>
                            <button class="filter-tab" data-filter="approved">
                                Approved <span class="count"><?php echo $stats['approved']; ?></span>
                            </button>
                            <button class="filter-tab" data-filter="rejected">
                                Rejected <span class="count"><?php echo $stats['rejected']; ?></span>
                            </button>
                        </div>

                        <!-- Applications Table -->
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Passport</th>
                                        <th>Nationality</th>
                                        <th>Visa Category</th>
                                        <th>Arrival Date</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicationsTableBody">
                                    <!-- Populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
            </div>
        </main>
    </div>

    <!-- Application Details Modal -->
    <div class="modal-overlay" id="applicationModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Application Details</h3>
                <button class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-user"></i> Applicant Information
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value" id="modalApplicantName">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Passport Number</div>
                            <div class="detail-value" id="modalPassportNumber">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Nationality</div>
                            <div class="detail-value" id="modalNationality">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Visa Category</div>
                            <div class="detail-value" id="modalVisaCategory">-</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-plane"></i> Travel Information
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Arrival Date</div>
                            <div class="detail-value" id="modalArrivalDate">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Submitted Date</div>
                            <div class="detail-value" id="modalSubmittedDate">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Current Status</div>
                            <div class="detail-value">
                                <span class="status-badge" id="modalStatus">-</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Application ID</div>
                            <div class="detail-value" id="modalApplicationId">-</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-folder-open"></i> Uploaded Documents
                    </h4>
                    <div class="document-list">
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Passport Data Page</div>
                                <div class="document-meta">PDF • 2.4 MB • Uploaded on Mar 10, 2024</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Entry Visa</div>
                                <div class="document-meta">PDF • 1.8 MB • Uploaded on Mar 10, 2024</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Entry Stamp</div>
                                <div class="document-meta">JPEG • 856 KB • Uploaded on Mar 10, 2024</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Return Ticket</div>
                                <div class="document-meta">PDF • 1.2 MB • Uploaded on Mar 10, 2024</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                </div>

                <div class="detail-section" id="reviewerNotesSection" style="display: none;">
                    <h4 class="detail-section-title">
                        <i class="fas fa-sticky-note"></i> Reviewer Notes
                    </h4>
                    <div class="detail-item" style="background: #fff8e1; border-left: 4px solid var(--warning);">
                        <div class="detail-value" id="modalReviewerNotes" style="font-style: italic; color: var(--dark);">
                            -
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="applicationModalFooter">
                <button class="btn btn-outline modal-close-btn">Close</button>
                <button class="btn btn-success" id="modalApproveBtn" onclick="Dashboard.openApprovalModalFromDetails()">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="btn btn-danger" id="modalRejectBtn" onclick="Dashboard.openRejectionModalFromDetails()">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal-overlay" id="approvalModal">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">Approve Application</h3>
                <button class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: rgba(30, 132, 73, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-check" style="font-size: 2.5rem; color: var(--primary);"></i>
                    </div>
                    <h4 style="color: var(--dark); margin-bottom: 0.5rem;">Confirm Approval</h4>
                    <p style="color: var(--gray);">You are about to approve the application for <strong id="approvalApplicantName">-</strong></p>
                </div>

                <form id="approvalForm">
                    <input type="hidden" id="approvalApplicationId">
                    
                    <div class="form-group">
                        <label class="form-label">Approval Comments (Optional)</label>
                        <textarea class="form-control" id="approvalComments" placeholder="Enter any comments or notes about this approval..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Clearance Valid Until</label>
                        <input type="date" class="form-control" id="clearanceValidUntil" required>
                    </div>

                    <div style="background: #e8f5e9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--primary); font-weight: 600; margin-bottom: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Important
                        </div>
                        <p style="font-size: 0.9rem; color: var(--dark); margin: 0;">
                            Once approved, the applicant will be notified via email and can download their clearance certificate.
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline modal-close-btn">Cancel</button>
                <button type="submit" form="approvalForm" class="btn btn-success">
                    <i class="fas fa-check"></i> Confirm Approval
                </button>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal-overlay" id="rejectionModal">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">Reject Application</h3>
                <button class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: rgba(192, 57, 43, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-times" style="font-size: 2.5rem; color: var(--accent);"></i>
                    </div>
                    <h4 style="color: var(--dark); margin-bottom: 0.5rem;">Confirm Rejection</h4>
                    <p style="color: var(--gray);">You are about to reject the application for <strong id="rejectionApplicantName">-</strong></p>
                </div>

                <form id="rejectionForm">
                    <input type="hidden" id="rejectionApplicationId">
                    
                    <div class="form-group">
                        <label class="form-label">Rejection Reason <span style="color: var(--accent);">*</span></label>
                        <select class="form-control" id="rejectionReason" required>
                            <option value="">Select a reason...</option>
                            <?php foreach ($rejectionReasons as $key => $label): ?>
                            <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Detailed Comments <span style="color: var(--accent);">*</span></label>
                        <textarea class="form-control" id="rejectionComments" rows="4" placeholder="Provide detailed explanation for the rejection..." required></textarea>
                    </div>

                    <div style="background: #ffebee; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--accent); font-weight: 600; margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i> Important
                        </div>
                        <p style="font-size: 0.9rem; color: var(--dark); margin: 0;">
                            The applicant will be notified of the rejection and may reapply after addressing the issues.
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline modal-close-btn">Cancel</button>
                <button type="submit" form="rejectionForm" class="btn btn-danger">
                    <i class="fas fa-times"></i> Confirm Rejection
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    
    <script>
        // Admin-specific extensions to Dashboard
        window.Dashboard.openApprovalModalFromDetails = function() {
            const modal = document.getElementById('applicationModal');
            const applicationId = modal.dataset.currentApplicationId;
            const application = state.applications.find(app => app.id === applicationId);
            
            if (application) {
                closeAllModals();
                setTimeout(() => openApprovalModal(application), 300);
            }
        };

        window.Dashboard.openRejectionModalFromDetails = function() {
            const modal = document.getElementById('applicationModal');
            const applicationId = modal.dataset.currentApplicationId;
            const application = state.applications.find(app => app.id === applicationId);
            
            if (application) {
                closeAllModals();
                setTimeout(() => openRejectionModal(application), 300);
            }
        };

        // Set default clearance date to 30 days from now
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('clearanceValidUntil');
            if (dateInput) {
                const future = new Date();
                future.setDate(future.getDate() + 30);
                dateInput.value = future.toISOString().split('T')[0];
                dateInput.min = new Date().toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
