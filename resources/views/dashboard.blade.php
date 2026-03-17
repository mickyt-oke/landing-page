@include('partials.header')

@php
    /** @var \App\Models\User|null $currentUser */
    $currentUser = auth()->user();
    $firstName = filled($currentUser?->name) ? explode(' ', trim($currentUser->name))[0] : 'there';

    $stats = $stats ?? [];
    $applications = $applications ?? collect();
    $totalApplications = (int) data_get($stats, 'total_applications', 0);
    $pendingCount = (int) data_get($stats, 'pending', 0);
    $approvedCount = (int) data_get($stats, 'approved', 0);
    $rejectedCount = (int) data_get($stats, 'rejected', 0);

    $statCards = [
        [
            'key' => 'totalApplications',
            'cardClass' => '',
            'iconClass' => 'primary',
            'icon' => 'fa-file-alt',
            'value' => $totalApplications,
            'label' => 'Total Applications',
        ],
        [
            'key' => 'pendingApplications',
            'cardClass' => 'warning',
            'iconClass' => 'warning',
            'icon' => 'fa-clock',
            'value' => $pendingCount,
            'label' => 'Pending Review',
        ],
        [
            'key' => 'approvedApplications',
            'cardClass' => 'success',
            'iconClass' => 'primary',
            'icon' => 'fa-check-circle',
            'value' => $approvedCount,
            'label' => 'Approved',
        ],
        [
            'key' => 'rejectedApplications',
            'cardClass' => 'danger',
            'iconClass' => 'danger',
            'icon' => 'fa-times-circle',
            'value' => $rejectedCount,
            'label' => 'Rejected',
        ],
    ];

    $filterTabs = [
        ['filter' => 'all', 'label' => 'All', 'count' => $totalApplications, 'active' => true],
        ['filter' => 'pending', 'label' => 'Pending', 'count' => $pendingCount, 'active' => false],
        ['filter' => 'approved', 'label' => 'Approved', 'count' => $approvedCount, 'active' => false],
        ['filter' => 'rejected', 'label' => 'Rejected', 'count' => $rejectedCount, 'active' => false],
    ];

    $resourceCards = [
        [
            'icon' => 'fa-book',
            'colorVar' => 'var(--primary)',
            'title' => 'Application Guide',
            'description' => 'Learn how to apply for overstay clearance',
            'linkText' => 'Read More',
            'href' => '#',
            'linkColorVar' => 'var(--primary)',
            'linkIcon' => 'fa-arrow-right',
        ],
        [
            'icon' => 'fa-file-pdf',
            'colorVar' => 'var(--secondary)',
            'title' => 'Required Documents',
            'description' => 'Checklist of documents you need to prepare',
            'linkText' => 'Download PDF',
            'href' => '#',
            'linkColorVar' => 'var(--secondary)',
            'linkIcon' => 'fa-download',
        ],
        [
            'icon' => 'fa-question-circle',
            'colorVar' => 'var(--warning)',
            'title' => 'FAQs',
            'description' => 'Frequently asked questions about the process',
            'linkText' => 'View FAQs',
            'href' => '#',
            'linkColorVar' => 'var(--warning)',
            'linkIcon' => 'fa-arrow-right',
        ],
    ];
@endphp

<!-- Dashboard Content -->
<div class="dashboard-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h2>Welcome back, {{ $firstName }}!</h2>
            <p>Manage your application and track status.</p>
        </div>

        <div class="welcome-action">
            <a href="{{ route('create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Application
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        @foreach ($statCards as $card)
            <div class="stat-card {{ $card['cardClass'] }}">
                <div class="stat-icon {{ $card['iconClass'] }}">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>

                <div class="stat-value" id="{{ $card['key'] }}">{{ $card['value'] }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Recent Applications -->
    <div class="content-card" id="applications">
        <div class="card-header">
            <h3 class="card-title">My Applications</h3>

            <div class="card-actions">
                <button class="btn btn-outline btn-sm" type="button" aria-label="Filter applications">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a class="btn btn-primary btn-sm" href="{{ route('create') }}">
                    <i class="fas fa-plus"></i>
                    New
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Tabs -->
            <div class="filter-tabs" role="tablist" aria-label="Application filters">
                @foreach ($filterTabs as $tab)
                    <button
                        class="filter-tab {{ $tab['active'] ? 'active' : '' }}"
                        data-filter="{{ $tab['filter'] }}"
                        type="button"
                        role="tab"
                        aria-selected="{{ $tab['active'] ? 'true' : 'false' }}"
                    >
                        {{ $tab['label'] }} <span class="count">{{ $tab['count'] }}</span>
                    </button>
                @endforeach
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
                        @forelse ($applications as $app)
                            <tr>
                                <td>
                                    <div class="font-semibold text-dark">{{ $app->id }}</div>
                                </td>
                                <td>{{ $app->visa_category }}</td>
                                <td>{{ optional($app->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $app->status }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button
                                            class="action-btn view"
                                            data-action="view"
                                            data-application-id="{{ $app->id }}"
                                            title="View Details"
                                            type="button"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if ($app->status === 'pending')
                                            <a class="action-btn edit" title="Edit Application" href="{{ route('applications.show', $app->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    No applications found.
                                </td>
                            </tr>
                        @endforelse
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
            <div class="resource-grid">
                @foreach ($resourceCards as $resource)
                    <div class="resource-card">
                        <i class="fas {{ $resource['icon'] }} resource-icon" style="color: {{ $resource['colorVar'] }}"></i>
                        <h4 class="resource-title">{{ $resource['title'] }}</h4>
                        <p class="resource-desc">{{ $resource['description'] }}</p>

                        <a class="resource-link" href="{{ $resource['href'] }}" style="color: {{ $resource['linkColorVar'] }}; font-weight: 600;">
                            {{ $resource['linkText'] }} <i class="fas {{ $resource['linkIcon'] }}"></i>
                        </a>
                    </div>
                @endforeach
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
                <button class="modal-close" type="button" aria-label="Close modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-user"></i>
                        Applicant Information
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
                        <i class="fas fa-plane"></i>
                        Travel Information
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
                        <i class="fas fa-folder-open"></i>
                        Uploaded Documents
                    </h4>

                    <div class="document-list">
                        <!-- NOTE: Document items are currently static placeholders. Consider rendering real uploaded docs from the backend. -->
                        <div class="document-item">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <div class="document-name">Passport Data Page</div>
                                <div class="document-meta">PDF • 2.4 MB</div>
                            </div>
                            <button class="document-action" type="button">
                                <i class="fas fa-download"></i>
                                Download
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
                            <button class="document-action" type="button">
                                <i class="fas fa-download"></i>
                                Download
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
                            <button class="document-action" type="button">
                                <i class="fas fa-download"></i>
                                Download
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
                            <button class="document-action" type="button">
                                <i class="fas fa-download"></i>
                                Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('partials.footer')
