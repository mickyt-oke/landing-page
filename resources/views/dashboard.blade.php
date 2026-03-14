@include('partials.header')

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h2>Welcome back, {{ explode(' ', $currentUser->name)[0] }}!</h2>
                        <p>Manage your overstay clearance applications and track their status.</p>
                    </div>
                    <div class="welcome-action">
                        <a href="{{ route('create-new') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Application
                        </a>
                        <a href="#applications" class="btn" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="fas fa-list"></i> View All
                        </a>
                    </div>
                </div>

                <!-- Quick Actionss
                <div class="quick-actions">
                    <a href="#" class="quick-action-card modal-trigger" data-modal="register">
                        <div class="quick-action-icon green">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="quick-action-content">
                            <h4>Apply for Clearance</h4>
                            <p>Submit a new overstay clearance application</p>
                        </div>
                    </a>
                    
                    <a href="#status" class="quick-action-card">
                        <div class="quick-action-icon blue">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="quick-action-content">
                            <h4>Check Status</h4>
                            <p>Track your application status</p>
                        </div>
                    </a>
                    
                    <a href="#documents" class="quick-action-card">
                        <div class="quick-action-icon orange">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="quick-action-content">
                            <h4>Upload Documents</h4>
                            <p>Manage your uploaded documents</p>
                        </div>
                    </a>
                </div> -->

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-value" id="totalApplications">{{ $stats['total_applications'] }}</div>
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-change positive">
                        </div>
                    </div>

                    <div class="stat-card warning">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value" id="pendingApplications">{{ $stats['pending'] }}</div>
                        <div class="stat-label">Pending Review</div>
                        <div class="stat-change">
                        </div>
                    </div>

                    <div class="stat-card success">
                        <div class="stat-icon primary">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value" id="approvedApplications">{{ $stats['approved'] }}</div>
                        <div class="stat-label">Approved</div>
                        <div class="stat-change positive">
                        </div>
                    </div>

                    <div class="stat-card danger">
                        <div class="stat-icon danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-value" id="rejectedApplications">{{ $stats['rejected'] }}</div>
                        <div class="stat-label">Rejected</div>
                        <div class="stat-change negative">
                        </div>
                    </div>
                </div>

                <!-- Recent Applications -->
                <div class="content-card" id="applications">
                    <div class="card-header">
                        <h3 class="card-title">My Applications</h3>
                        <div class="card-actions">
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-primary btn-sm modal-trigger" data-modal="register">
                                <i class="fas fa-plus"></i> New
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">
                                All <span class="count">{{ $stats['total_applications'] }}</span>
                            </button>
                            <button class="filter-tab" data-filter="pending">
                                Pending <span class="count">{{ $stats['pending'] }}</span>
                            </button>
                            <button class="filter-tab" data-filter="approved">
                                Approved <span class="count">{{ $stats['approved'] }}</span>
                            </button>
                            <button class="filter-tab" data-filter="rejected">
                                Rejected <span class="count">{{ $stats['rejected'] }}</span>
                            </button>
                        </div>

                        <!-- Applications Table -->
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Application ID</th>
                                        <th>Type</th>
                                        <th>Submitted Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicationsTableBody">
                                    @foreach ($applications as $app)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600; color: var(--dark);">{{ $app->id }}</div>
                                        </td>
                                        <td>{{ $app->visa_category }}</td>
                                        <td>{{ $app->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="status-badge {{ $app->status }}">
                                                {{ ucfirst($app->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                <button class="action-btn view" data-action="view" data-application-id="{{ $app->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if ($app->status === 'pending')
                                                <button class="action-btn edit" title="Edit Application">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Help & Resources -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Help & Resources</h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                            <div style="padding: 1.5rem; background: var(--light); border-radius: 12px;">
                                <i class="fas fa-book" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                                <h4 style="margin-bottom: 0.5rem;">Application Guide</h4>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Learn how to apply for overstay clearance</p>
                                <a href="#" style="color: var(--primary); font-weight: 600;">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light); border-radius: 12px;">
                                <i class="fas fa-file-pdf" style="font-size: 2rem; color: var(--secondary); margin-bottom: 1rem;"></i>
                                <h4 style="margin-bottom: 0.5rem;">Required Documents</h4>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Checklist of documents you need to prepare</p>
                                <a href="#" style="color: var(--secondary); font-weight: 600;">Download PDF <i class="fas fa-download"></i></a>
                            </div>
                            
                            <div style="padding: 1.5rem; background: var(--light); border-radius: 12px;">
                                <i class="fas fa-question-circle" style="font-size: 2rem; color: var(--warning); margin-bottom: 1rem;"></i>
                                <h4 style="margin-bottom: 0.5rem;">FAQs</h4>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Frequently asked questions about the process</p>
                                <a href="#" style="color: var(--warning); font-weight: 600;">View FAQs <i class="fas fa-arrow-right"></i></a>
                            </div>
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
                                <div class="document-meta">PDF • 2.4 MB</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Entry Visa</div>
                                <div class="document-meta">PDF • 1.8 MB</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Entry Stamp</div>
                                <div class="document-meta">JPEG • 856 KB</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Return Ticket</div>
                                <div class="document-meta">PDF • 1.2 MB</div>
                            </div>
                            <button class="document-action">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
@include('partials.footer')
