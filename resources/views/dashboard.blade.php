@include('partials.header')

@php
    /** @var \App\Models\User|null $currentUser */
    $currentUser = auth()->user();
    $firstName = filled($currentUser?->name) ? explode(' ', trim($currentUser->name))[0] : 'there';


@endphp

<!-- Dashboard Content -->
<div class="dashboard-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h2>Welcome back, {{ $firstName }}!</h2>
            <p>Manage your application and track status</p>
        </div>

        <div class="welcome-action">
            <a href="{{ route('applications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Application
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    {{-- <div class="stats-grid">
        @foreach ($statCards as $card)
            <div class="stat-card {{ $card['cardClass'] }}">
                <div class="stat-icon {{ $card['iconClass'] }}">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>

                <div class="stat-value" id="{{ $card['key'] }}">{{ $card['value'] }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
        @endforeach
    </div> --}}

    <!-- Recent Applications -->
    <div class="content-card" id="applications">
        <div class="card-header">
            <h3 class="card-title">My Application</h3>

            <div class="card-actions">
                <button class="btn btn-outline btn-sm" type="button" aria-label="Filter applications">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a class="btn btn-primary btn-sm" href="{{ route('applications.create') }}">
                    <i class="fas fa-plus"></i>
                    New
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- <!-- Filter Tabs -->
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
            </div> --}}

            <!-- Applications Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Ref ID</th>
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
                                <td class="font-mono">{{ $app->ack_ref_number ?: '-' }}</td>
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
                                        <button
                                            class="action-btn ack-preview"
                                            data-app-id="{{ $app->id }}"
                                            data-ack-ref="{{ $app->ack_ref_number }}"
                                            data-submitted="{{ $app->submitted_at?->format('d M Y H:i') ?? '' }}"
                                            data-name="{{ $app->full_name }}"
                                            data-passport="{{ $app->passport_number }}"
                                            data-nationality="{{ $app->nationality }}"
                                            data-visa="{{ $app->visa_category }}"
                                            data-arrival="{{ $app->arrival_date?->format('d M Y') }}"
                                            data-status="{{ $app->status }}"
                                            title="View Acknowledgement"
                                            type="button"
                                        >
                                            <i class="fas fa-receipt"></i>
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

    <!-- Acknowledgement Preview Modal -->
    <div class="modal-overlay" id="ackModal" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Application Acknowledgement</h3>
                <button class="modal-close" onclick="closeAckModal()" type="button" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="ackContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
    function closeAckModal() {
        document.getElementById('ackModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Ack preview
        document.querySelectorAll('.ack-preview').forEach(btn => {
            btn.addEventListener('click', function() {
                const data = this.dataset;
                const content = generateAckContent(data);
                document.getElementById('ackContent').innerHTML = content;
                document.getElementById('ackModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });

        // Close modal on overlay click
        document.getElementById('ackModal').addEventListener('click', function(e) {
            if (e.target === this) closeAckModal();
        });
    });

    function generateAckContent(data) {
        const statusClass = data.status === 'approved' ? 'success' : data.status === 'rejected' ? 'danger' : 'warning';
        return `
            <style>
                @media print { .no-print { display: none !important; } }
                .nis-header { background: linear-gradient(135deg, #003087, #0056b3); color: white; padding: 1rem; text-align: center; border-radius: 8px 8px 0 0; }
                .ref-badge { background: #28a745; color: white; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: bold; font-size: 1.3em; display: inline-block; }
                .ack-details td:first-child { font-weight: bold; width: 40%; }
            </style>
            <div class="no-print text-center mb-3">
                <button onclick="window.print()" class="btn btn-success">Print Acknowledgement</button>
            </div>
            <div class="nis-header">
                <h2>Nigeria Immigration Service</h2>
                <p>Foreigners Registration Portal</p>
            </div>
            <div style="padding: 2rem; border: 1px solid #ddd; background: #fafafa;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h1 style="color: #003087;">APPLICATION ACKNOWLEDGEMENT</h1>
                    <div class="ref-badge">Ref: \${data.ackRef || 'N/A'}</div>
                    <p>Date: \${data.submitted || 'N/A'}</p>
                </div>
                <table style="width: 100%; margin-bottom: 1rem;">
                    <tr><td><strong>Full Name:</strong></td><td>\${data.name}</td></tr>
                    <tr><td><strong>Passport:</strong></td><td>\${data.passport}</td></tr>
                    <tr><td><strong>Nationality:</strong></td><td>\${data.nationality}</td></tr>
                    <tr><td><strong>Visa:</strong></td><td>\${data.visa}</td></tr>
                    <tr><td><strong>Arrival:</strong></td><td>\${data.arrival}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-\${statusClass}">\${data.status?.toUpperCase()}</span></td></tr>
                </table>
                <div style="padding: 1rem; background: white; border-left: 4px solid #003087;">
                    <h5>Next Steps</h5>
                    <ul>
                        <li>Application received and \${data.status === 'approved' ? 'approved' : 'under review'}</li>
                        <li>Track using reference number</li>
                        <li>Processing: 5-10 days</li>
                    </ul>
                </div>
            </div>
        `;
    }
    </script>

@include('partials.footer')
