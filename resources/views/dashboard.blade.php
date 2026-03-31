@include('partials.header')

@php
    /** @var \App\Models\User|null $currentUser */
    $currentUser = auth()->user();
    $firstName   = filled($currentUser?->name)
        ? explode(' ', trim($currentUser->name))[0]
        : 'there';

    $statusMap = [
        'pending'      => ['label' => 'Pending',      'class' => 'pending'],
        'under_review' => ['label' => 'Under Review',  'class' => 'under-review'],
        'approved'     => ['label' => 'Approved',      'class' => 'approved'],
        'rejected'     => ['label' => 'Rejected',      'class' => 'rejected'],
    ];
@endphp

<div class="dashboard-content">

    {{-- ── Welcome ─────────────────────────────────────────── --}}
    <div class="welcome-section">
        <div class="welcome-content">
            <h2>Welcome back, {{ $firstName }}!</h2>
            <p>Manage your application and track its status below.</p>
        </div>
        <div class="welcome-action">
            <a href="{{ route('applications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus" aria-hidden="true"></i> New Application
            </a>
        </div>
    </div>

    {{-- ── Stats Cards ──────────────────────────────────────── --}}
    <div class="stats-grid">
        <div class="stat-card info">
            <div class="stat-icon info">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
            </div>
            <div class="stat-value">{{ $stats['total_applications'] }}</div>
            <div class="stat-label">Total Applications</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon warning">
                <i class="fas fa-clock" aria-hidden="true"></i>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending / Under Review</div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon primary">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
            </div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Approved</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-icon danger">
                <i class="fas fa-times-circle" aria-hidden="true"></i>
            </div>
            <div class="stat-value">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    {{-- ── Applications Table ───────────────────────────────── --}}
    <div class="content-card" id="applications">
        <div class="card-header">
            <h3 class="card-title">My Applications</h3>
            <div class="card-actions">
                <a class="btn btn-primary btn-sm" href="{{ route('applications.create') }}">
                    <i class="fas fa-plus" aria-hidden="true"></i> New
                </a>
            </div>
        </div>

        <div class="card-body">

            {{-- Desktop Table --}}
            <div class="table-container" role="region" aria-label="Applications table">
                <table class="data-table" aria-label="My applications">
                    <thead>
                        <tr>
                            <th scope="col">App ID</th>
                            <th scope="col">Ref No.</th>
                            <th scope="col">Visa Type</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $app)
                            @php
                                $si = $statusMap[$app->status] ?? ['label' => ucfirst($app->status), 'class' => 'pending'];
                                $docs = $app->documents->map(fn ($d) => [
                                    'type' => $d->document_type,
                                    'name' => $d->original_name,
                                    'mime' => $d->mime_type ?? '',
                                    'size' => $d->size_bytes ?? 0,
                                ]);
                                $submittedAt = ($app->submitted_at ?? $app->created_at)?->format('d M Y, H:i');
                                $address = implode(', ', array_filter([$app->address, $app->city, $app->state]));
                            @endphp
                            <tr>
                                <td><span class="app-id-cell">#{{ $app->id }}</span></td>
                                <td class="font-mono">{{ $app->ack_ref_number ?: '—' }}</td>
                                <td>{{ $app->visa_category ?: '—' }}</td>
                                <td>{{ optional($app->created_at)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $si['class'] }}">
                                        {{ $si['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        {{-- View Details --}}
                                        <button class="action-btn view"
                                            type="button"
                                            title="View Details"
                                            aria-label="View details for application #{{ $app->id }}"
                                            data-action="view"
                                            data-application-id="{{ $app->id }}"
                                            data-ref="{{ $app->ack_ref_number }}"
                                            data-name="{{ $app->full_name }}"
                                            data-passport="{{ $app->passport_number }}"
                                            data-nationality="{{ $app->nationality }}"
                                            data-visa="{{ $app->visa_category }}"
                                            data-arrival="{{ $app->arrival_date?->format('d M Y') }}"
                                            data-submitted="{{ $submittedAt }}"
                                            data-status="{{ $app->status }}"
                                            data-status-label="{{ $si['label'] }}"
                                            data-address="{{ $address }}"
                                            data-docs="{{ e(json_encode($docs)) }}">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>

                                        {{-- Acknowledgement --}}
                                        <button class="action-btn ack-preview"
                                            type="button"
                                            title="View Acknowledgement"
                                            aria-label="View acknowledgement for application #{{ $app->id }}"
                                            data-app-id="{{ $app->id }}"
                                            data-ack-ref="{{ $app->ack_ref_number }}"
                                            data-submitted="{{ $submittedAt }}"
                                            data-name="{{ $app->full_name }}"
                                            data-passport="{{ $app->passport_number }}"
                                            data-nationality="{{ $app->nationality }}"
                                            data-visa="{{ $app->visa_category }}"
                                            data-arrival="{{ $app->arrival_date?->format('d M Y') }}"
                                            data-status="{{ $app->status }}">
                                            <i class="fas fa-receipt" aria-hidden="true"></i>
                                        </button>

                                        @if ($app->status === 'pending')
                                            <a class="action-btn edit"
                                               title="Edit Application"
                                               aria-label="Edit application #{{ $app->id }}"
                                               href="{{ route('applications.show', $app->id) }}">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state-cell">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                                        </div>
                                        <h3 class="empty-title">No applications yet</h3>
                                        <p class="empty-text">Start your registration by submitting your first application.</p>
                                        <a href="{{ route('applications.create') }}" class="btn btn-primary" style="margin-top:1rem;">
                                            <i class="fas fa-plus" aria-hidden="true"></i> New Application
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            @if ($applications->isNotEmpty())
            <div class="app-card-list" aria-label="Applications list">
                @foreach ($applications as $app)
                    @php
                        $si = $statusMap[$app->status] ?? ['label' => ucfirst($app->status), 'class' => 'pending'];
                        $submittedAt = ($app->submitted_at ?? $app->created_at)?->format('d M Y, H:i');
                        $address = implode(', ', array_filter([$app->address, $app->city, $app->state]));
                        $docs = $app->documents->map(fn ($d) => [
                            'type' => $d->document_type,
                            'name' => $d->original_name,
                            'mime' => $d->mime_type ?? '',
                            'size' => $d->size_bytes ?? 0,
                        ]);
                    @endphp
                    <div class="app-card">
                        <div class="app-card-head">
                            <span class="app-id-cell">#{{ $app->id }}</span>
                            <span class="status-badge {{ $si['class'] }}">{{ $si['label'] }}</span>
                        </div>
                        <div class="app-card-body">
                            <div class="app-card-row">
                                <span class="app-card-label">Ref No.</span>
                                <span class="font-mono">{{ $app->ack_ref_number ?: '—' }}</span>
                            </div>
                            <div class="app-card-row">
                                <span class="app-card-label">Visa Type</span>
                                <span>{{ $app->visa_category ?: '—' }}</span>
                            </div>
                            <div class="app-card-row">
                                <span class="app-card-label">Submitted</span>
                                <span>{{ optional($app->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="app-card-actions">
                            <button class="action-btn view"
                                type="button" title="View Details"
                                data-action="view"
                                data-application-id="{{ $app->id }}"
                                data-ref="{{ $app->ack_ref_number }}"
                                data-name="{{ $app->full_name }}"
                                data-passport="{{ $app->passport_number }}"
                                data-nationality="{{ $app->nationality }}"
                                data-visa="{{ $app->visa_category }}"
                                data-arrival="{{ $app->arrival_date?->format('d M Y') }}"
                                data-submitted="{{ $submittedAt }}"
                                data-status="{{ $app->status }}"
                                data-status-label="{{ $si['label'] }}"
                                data-address="{{ $address }}"
                                data-docs="{{ e(json_encode($docs)) }}">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                            <button class="action-btn ack-preview"
                                type="button" title="Acknowledgement"
                                data-app-id="{{ $app->id }}"
                                data-ack-ref="{{ $app->ack_ref_number }}"
                                data-submitted="{{ $submittedAt }}"
                                data-name="{{ $app->full_name }}"
                                data-passport="{{ $app->passport_number }}"
                                data-nationality="{{ $app->nationality }}"
                                data-visa="{{ $app->visa_category }}"
                                data-arrival="{{ $app->arrival_date?->format('d M Y') }}"
                                data-status="{{ $app->status }}">
                                <i class="fas fa-receipt" aria-hidden="true"></i>
                            </button>
                            @if ($app->status === 'pending')
                                <a class="action-btn edit"
                                   href="{{ route('applications.show', $app->id) }}"
                                   title="Edit Application">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

</div>
        </main>
    </div>

    {{-- ── Application Details Modal ────────────────────────── --}}
    <div class="modal-overlay" id="applicationModal"
         role="dialog" aria-modal="true" aria-labelledby="appModalTitle">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="appModalTitle">Application Details</h3>
                    <p class="modal-subtitle" id="modalAppRef"></p>
                </div>
                <button class="modal-close" type="button" aria-label="Close modal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body">
                {{-- Status Banner --}}
                <div class="modal-status-banner">
                    <span class="status-badge" id="modalStatus">—</span>
                    <span class="modal-status-date" id="modalStatusDate"></span>
                </div>

                {{-- Applicant Information --}}
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-user" aria-hidden="true"></i> Applicant Information
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value" id="modalApplicantName">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Passport Number</div>
                            <div class="detail-value font-mono" id="modalPassportNumber">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Nationality</div>
                            <div class="detail-value" id="modalNationality">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Visa Category</div>
                            <div class="detail-value" id="modalVisaCategory">—</div>
                        </div>
                    </div>
                </div>

                {{-- Travel Information --}}
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-plane" aria-hidden="true"></i> Travel Information
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Arrival Date</div>
                            <div class="detail-value" id="modalArrivalDate">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Submitted</div>
                            <div class="detail-value" id="modalSubmittedDate">—</div>
                        </div>
                        <div class="detail-item" id="modalAddressItem">
                            <div class="detail-label">Address in Nigeria</div>
                            <div class="detail-value" id="modalAddress">—</div>
                        </div>
                    </div>
                </div>

                {{-- Uploaded Documents (populated by JS from real data) --}}
                <div class="detail-section" id="modalDocsSection">
                    <h4 class="detail-section-title">
                        <i class="fas fa-folder-open" aria-hidden="true"></i> Uploaded Documents
                    </h4>
                    <div class="document-list" id="modalDocumentList"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Acknowledgement Modal ───────────────────────────── --}}
    <div class="modal-overlay" id="ackModal"
         role="dialog" aria-modal="true" aria-labelledby="ackModalTitle">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title" id="ackModalTitle">Application Acknowledgement</h3>
                <button class="modal-close" type="button" aria-label="Close acknowledgement">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body" id="ackContent"></div>
        </div>
    </div>

@include('partials.footer')
