@include('admin.partials.header')

@php
    /* ── Computed counters ─────────────────────────────────── */
    $pendingCount     = $applications->where('status', 'pending')->count();
    $underReviewCount = $applications->where('status', 'under_review')->count();
    $approvedCount    = $stats['approved'];
    $rejectedCount    = $stats['rejected'];
    $totalCount       = $stats['total_applications'];
    $approvalRate     = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100) : 0;

    /* ── Chart data ────────────────────────────────────────── */
    $nationalityData = $applications
        ->groupBy('nationality')
        ->map(fn($g) => $g->count())
        ->sortByDesc(fn($c) => $c)
        ->take(8);

    $statusCounts = [
        'Pending'      => $pendingCount,
        'Under Review' => $underReviewCount,
        'Approved'     => $approvedCount,
        'Rejected'     => $rejectedCount,
    ];

    $overstayRanges = [
        '0–7 days'   => $applications->filter(fn($a) => $a->overstay_days >= 0  && $a->overstay_days <= 7)->count(),
        '8–14 days'  => $applications->filter(fn($a) => $a->overstay_days >= 8  && $a->overstay_days <= 14)->count(),
        '15–30 days' => $applications->filter(fn($a) => $a->overstay_days >= 15 && $a->overstay_days <= 30)->count(),
        '31–60 days' => $applications->filter(fn($a) => $a->overstay_days >= 31 && $a->overstay_days <= 60)->count(),
        '61–90 days' => $applications->filter(fn($a) => $a->overstay_days >= 61 && $a->overstay_days <= 90)->count(),
        '90+ days'   => $applications->filter(fn($a) => $a->overstay_days > 90)->count(),
    ];

    $trendLabels = [];
    $trendCounts = [];
    for ($i = 29; $i >= 0; $i--) {
        $day = now()->subDays($i);
        $trendLabels[] = $day->format('M j');
        $trendCounts[] = $applications->filter(
            fn($a) => $a->submitted_at && $a->submitted_at->format('Y-m-d') === $day->format('Y-m-d')
        )->count();
    }

    /* ── Compact application list for JS modal ─────────────── */
    $appData = $applications->map(fn($app) => [
        'id'              => $app->id,
        'ref'             => $app->application_reference,
        'ackRef'          => $app->ack_ref_number,
        'name'            => $app->full_name,
        'passport'        => $app->passport_number,
        'nationality'     => $app->nationality,
        'visa'            => $app->visa_category,
        'arrival'         => $app->arrival_date?->format('d M Y'),
        'submitted'       => $app->submitted_at?->format('d M Y, H:i'),
        'status'          => $app->status,
        'overstay'        => $app->overstay_days,
        'address'         => trim(implode(', ', array_filter([
                                $app->address, $app->city, $app->state
                            ]))),
        'note'            => $app->applicant_note,
        'reviewerComment' => $app->reviewer_comment,
        'docs'            => $app->documents->map(fn($d) => [
            'name' => $d->original_name,
            'type' => $d->document_type,
            'mime' => $d->mime_type,
            'size' => $d->size_bytes,
        ])->values()->all(),
    ])->values()->all();

    /* ── Status label/class map ─────────────────────────────── */
    $statusLabels  = [
        'pending'      => 'Pending',
        'under_review' => 'Under Review',
        'approved'     => 'Approved',
        'rejected'     => 'Rejected',
    ];
    $statusClasses = [
        'pending'      => 'pending',
        'under_review' => 'review',
        'approved'     => 'approved',
        'rejected'     => 'rejected',
    ];

    /* ── Rejection reasons ──────────────────────────────────── */
    $rejectionReasons = [
        'incomplete_documents'  => 'Incomplete or invalid documents',
        'ineligible_status'     => 'Does not meet eligibility criteria',
        'expired_documents'     => 'Expired travel documents',
        'false_information'     => 'False or misleading information provided',
        'duplicate_application' => 'Duplicate application detected',
        'other'                 => 'Other (specify in comments)',
    ];
@endphp

{{-- ── Flash messages ─────────────────────────────────────── --}}
@if(session('status'))
    <meta name="flash-success" content="{{ e(session('status')) }}">
@endif
@if(session('error'))
    <meta name="flash-error" content="{{ e(session('error')) }}">
@endif

{{-- ── Page header ─────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Application Management</h1>
        <p class="page-subtitle">Review, process and manage foreigner registration applications</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline btn-sm"
           title="Refresh dashboard">
            <i class="fas fa-sync-alt" aria-hidden="true"></i> Refresh
        </a>
    </div>
</div>

{{-- ── KPI Stats cards ─────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-top:1.5rem;">

    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value">{{ $totalCount }}</div>
        <div class="stat-label">Total Applications</div>
    </div>

    <div class="stat-card warning">
        <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
        <div class="stat-value">{{ $pendingCount }}</div>
        <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card info">
        <div class="stat-icon info"><i class="fas fa-search"></i></div>
        <div class="stat-value">{{ $underReviewCount }}</div>
        <div class="stat-label">Under Review</div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon primary"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value">{{ $approvedCount }}</div>
        <div class="stat-label">Approved</div>
    </div>

    <div class="stat-card" style="border-left:4px solid var(--accent);">
        <div class="stat-icon" style="background:rgba(192,57,43,0.1);">
            <i class="fas fa-times-circle" style="color:var(--accent);"></i>
        </div>
        <div class="stat-value" style="color:var(--accent);">{{ $rejectedCount }}</div>
        <div class="stat-label">Rejected</div>
    </div>

</div>

{{-- ── Quick metrics row ────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-top:1.25rem;">

    {{-- Approval rate gauge --}}
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Approval Rate</h3>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                <div style="position:relative;width:110px;height:110px;flex-shrink:0;">
                    <svg viewBox="0 0 36 36" style="width:100%;height:100%;transform:rotate(-90deg);">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none" stroke="#ecf0f1" stroke-width="3.5"/>
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none" stroke="#1E8449" stroke-width="3.5"
                              stroke-dasharray="{{ $approvalRate }}, 100"
                              stroke-linecap="round"/>
                    </svg>
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:1.4rem;font-weight:700;color:var(--primary);">
                        {{ $approvalRate }}%
                    </div>
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:0.6rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.9rem;background:rgba(30,132,73,0.08);border-radius:8px;">
                        <span style="font-size:0.875rem;"><i class="fas fa-check" style="color:var(--primary);margin-right:0.4rem;"></i>Approved</span>
                        <strong style="color:var(--primary);">{{ $approvedCount }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.9rem;background:rgba(192,57,43,0.08);border-radius:8px;">
                        <span style="font-size:0.875rem;"><i class="fas fa-times" style="color:var(--accent);margin-right:0.4rem;"></i>Rejected</span>
                        <strong style="color:var(--accent);">{{ $rejectedCount }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0.9rem;background:rgba(243,156,18,0.08);border-radius:8px;">
                        <span style="font-size:0.875rem;"><i class="fas fa-hourglass-half" style="color:var(--warning);margin-right:0.4rem;"></i>In Progress</span>
                        <strong style="color:var(--warning);">{{ $pendingCount + $underReviewCount }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Applications received --}}
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Applications Overview</h3>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                <div style="width:72px;height:72px;background:rgba(30,132,73,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-file-alt" style="font-size:1.75rem;color:var(--primary);" aria-hidden="true"></i>
                </div>
                <div>
                    <div style="font-size:2.25rem;font-weight:700;color:var(--secondary);">{{ $totalCount }}</div>
                    <div style="font-size:0.9rem;color:var(--gray);">Total applications received</div>
                    @if($totalCount > 0)
                    <div style="margin-top:0.5rem;font-size:0.8rem;color:var(--gray);">
                        Nationalities represented:
                        <strong>{{ $applications->pluck('nationality')->unique()->count() }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Charts ──────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;margin-top:1.25rem;">

    <div class="chart-container">
        <h3 class="chart-title">Applications by Nationality</h3>
        <canvas id="applicationsChart" aria-label="Bar chart of applications by nationality" role="img"></canvas>
    </div>

    <div class="chart-container">
        <h3 class="chart-title">Status Distribution</h3>
        <canvas id="nationalitiesPieChart" aria-label="Doughnut chart of status distribution" role="img"></canvas>
    </div>

    <div class="chart-container">
        <h3 class="chart-title">Overstay Duration Distribution</h3>
        <canvas id="countryHistogramChart" aria-label="Bar chart of overstay duration ranges" role="img"></canvas>
    </div>

    <div class="chart-container">
        <h3 class="chart-title">Submission Trend (Last 30 Days)</h3>
        <canvas id="submissionLineChart" aria-label="Line chart of submission trend" role="img"></canvas>
    </div>

</div>

{{-- ── Applications table ───────────────────────────────────── --}}
<div class="section content-card" id="applicationsSection" style="margin-top:1.25rem;">
    <div class="card-header">
        <h3 class="card-title">Applications Queue</h3>
        <div class="card-actions">
            <a href="#applicationsSection" id="exportBtn" data-apps="{{ htmlspecialchars(json_encode($appData), ENT_QUOTES, 'UTF-8') }}"
               class="btn btn-outline btn-sm">
                <i class="fas fa-download" aria-hidden="true"></i> Export
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter tabs --}}
        <div class="filter-tabs" role="tablist" aria-label="Filter applications by status">
            <button class="filter-tab active" data-filter="all" role="tab" aria-selected="true">
                All <span class="count">{{ $totalCount }}</span>
            </button>
            <button class="filter-tab" data-filter="pending" role="tab" aria-selected="false">
                Pending <span class="count">{{ $pendingCount }}</span>
            </button>
            <button class="filter-tab" data-filter="under_review" role="tab" aria-selected="false">
                Under Review <span class="count">{{ $underReviewCount }}</span>
            </button>
            <button class="filter-tab" data-filter="approved" role="tab" aria-selected="false">
                Approved <span class="count">{{ $approvedCount }}</span>
            </button>
            <button class="filter-tab" data-filter="rejected" role="tab" aria-selected="false">
                Rejected <span class="count">{{ $rejectedCount }}</span>
            </button>
        </div>

        {{-- Table --}}
        <div class="table-container">
            <table class="data-table" aria-label="Applications queue">
                <thead>
                    <tr>
                        <th scope="col">Applicant</th>
                        <th scope="col">Passport</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Visa</th>
                        <th scope="col">Arrival</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Overstay</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="applicationsTableBody">
                    @forelse($applications as $app)
                    <tr data-status="{{ $app->status }}" data-app-id="{{ $app->id }}">
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ e($app->full_name) }}</div>
                            <div style="font-size:0.75rem;color:var(--gray);">Ref: {{ e($app->application_reference) }}</div>
                        </td>
                        <td style="font-family:monospace;font-size:0.875rem;">{{ e($app->passport_number) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->nationality) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->visa_category) }}</td>
                        <td style="font-size:0.875rem;">
                            {{ $app->arrival_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td style="font-size:0.875rem;">
                            {{ $app->submitted_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td style="font-size:0.875rem;">
                            @if($app->overstay_days > 0)
                                <span style="color:{{ $app->overstay_days > 30 ? 'var(--accent)' : 'var(--warning)' }};font-weight:600;">
                                    {{ $app->overstay_days }}d
                                </span>
                            @else
                                <span style="color:var(--gray);">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClasses[$app->status] ?? 'pending' }}">
                                {{ $statusLabels[$app->status] ?? ucfirst($app->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                                {{-- View details --}}
                                <button type="button"
                                        class="btn btn-outline btn-sm action-btn view"
                                        data-action="view-app"
                                        data-app-id="{{ $app->id }}"
                                        title="View application details"
                                        aria-label="View details for {{ e($app->full_name) }}">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>

                                {{-- Start Review (pending → under_review) --}}
                                @if($app->status === 'pending' && optional($currentUser)->hasAnyRole(['reviewer','admin','superadmin']))
                                <form method="POST"
                                      action="{{ route('admin.applications.start-review', $app) }}"
                                      style="display:inline-block;margin:0;">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm"
                                            style="background:var(--info);color:#fff;"
                                            title="Start review"
                                            aria-label="Start reviewing {{ e($app->full_name) }}"
                                            onclick="return confirm('Move this application to Under Review?');">
                                        <i class="fas fa-search" aria-hidden="true"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Approve / Reject (under_review only, admin+) --}}
                                @if($app->status === 'under_review' && optional($currentUser)->hasAnyRole(['admin','superadmin']))
                                <button type="button"
                                        class="btn btn-sm"
                                        style="background:var(--primary);color:#fff;"
                                        data-action="open-approve"
                                        data-app-id="{{ $app->id }}"
                                        data-app-name="{{ e($app->full_name) }}"
                                        title="Approve application"
                                        aria-label="Approve {{ e($app->full_name) }}">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm"
                                        style="background:var(--accent);color:#fff;"
                                        data-action="open-reject"
                                        data-app-id="{{ $app->id }}"
                                        data-app-name="{{ e($app->full_name) }}"
                                        title="Reject application"
                                        aria-label="Reject {{ e($app->full_name) }}">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="tableEmptyRow">
                        <td colspan="9" style="text-align:center;padding:3rem;color:var(--gray);">
                            <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:0.75rem;display:block;" aria-hidden="true"></i>
                            No applications found.
                        </td>
                    </tr>
                    @endforelse
                    {{-- Empty state row for JS filtering --}}
                    @if($applications->isNotEmpty())
                    <tr id="tableEmptyRow" style="display:none;">
                        <td colspan="9" style="text-align:center;padding:2rem;color:var(--gray);">
                            No applications match the selected filter.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODALS                                                   --}}
{{-- ════════════════════════════════════════════════════════ --}}

{{-- Application Details Modal --}}
<div class="modal-overlay" id="applicationModal" role="dialog" aria-modal="true" aria-labelledby="modalAppTitle">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="modalAppTitle">Application Details</h3>
                <div style="font-size:0.8rem;color:var(--gray);margin-top:0.2rem;" id="modalAppRef"></div>
            </div>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>

        <div class="modal-body">
            {{-- Status banner --}}
            <div style="display:flex;align-items:center;gap:1rem;padding:0.875rem 1rem;background:var(--light);border-radius:8px;margin-bottom:1.25rem;">
                <span class="status-badge" id="modalStatus">—</span>
                <span style="font-size:0.8rem;color:var(--gray);" id="modalStatusDate"></span>
            </div>

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
                        <div class="detail-value" id="modalPassportNumber" style="font-family:monospace;">—</div>
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
                    <div class="detail-item">
                        <div class="detail-label">Overstay Days</div>
                        <div class="detail-value" id="modalOverstayDays">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Application ID</div>
                        <div class="detail-value" id="modalApplicationId" style="font-family:monospace;">—</div>
                    </div>
                    <div class="detail-item" id="modalAddressItem">
                        <div class="detail-label">Address</div>
                        <div class="detail-value" id="modalAddress">—</div>
                    </div>
                </div>
            </div>

            <div class="detail-section" id="modalDocsSection">
                <h4 class="detail-section-title">
                    <i class="fas fa-folder-open" aria-hidden="true"></i> Uploaded Documents
                </h4>
                <div class="document-list" id="modalDocumentList"></div>
            </div>

            <div class="detail-section" id="reviewerNotesSection" style="display:none;">
                <h4 class="detail-section-title">
                    <i class="fas fa-sticky-note" aria-hidden="true"></i> Reviewer Notes
                </h4>
                <div style="background:#fff8e1;border-left:4px solid var(--warning);padding:1rem;border-radius:0 8px 8px 0;">
                    <div id="modalReviewerNotes" style="font-style:italic;color:var(--dark);">—</div>
                </div>
            </div>
        </div>

        <div class="modal-footer" id="applicationModalFooter">
            <button class="btn btn-outline modal-close-btn">Close</button>
            <button class="btn btn-success" id="modalApproveBtn" data-action="approve-from-details">
                <i class="fas fa-check" aria-hidden="true"></i> Approve
            </button>
            <button class="btn btn-danger" id="modalRejectBtn" data-action="reject-from-details">
                <i class="fas fa-times" aria-hidden="true"></i> Reject
            </button>
        </div>
    </div>
</div>

{{-- Approval Modal --}}
<div class="modal-overlay" id="approvalModal" role="dialog" aria-modal="true" aria-labelledby="approvalModalTitle">
    <div class="modal-container" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="approvalModalTitle">Approve Application</h3>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="text-align:center;margin-bottom:1.75rem;">
                <div style="width:80px;height:80px;background:rgba(30,132,73,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="fas fa-check" style="font-size:2.5rem;color:var(--primary);" aria-hidden="true"></i>
                </div>
                <h4 style="color:var(--dark);margin-bottom:0.4rem;">Confirm Approval</h4>
                <p style="color:var(--gray);font-size:0.9rem;">
                    You are about to approve the application for
                    <strong id="approvalApplicantName">—</strong>
                </p>
            </div>

            <form id="approvalForm"
                  method="POST"
                  action=""
                  data-base-url="{{ url('/admin/applications') }}">
                @csrf
                <input type="hidden" id="approvalApplicationId" name="_app_id">

                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="approvalComments">Comments (optional)</label>
                    <textarea class="form-control" id="approvalComments" name="reviewer_comment"
                              rows="3" maxlength="2000"
                              placeholder="Enter any notes about this approval..."></textarea>
                </div>

                <div style="background:#e8f5e9;padding:0.875rem 1rem;border-radius:8px;font-size:0.875rem;color:var(--dark);">
                    <i class="fas fa-info-circle" style="color:var(--primary);margin-right:0.4rem;" aria-hidden="true"></i>
                    The applicant will be notified by email once approved.
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-close-btn">Cancel</button>
            <button type="submit" form="approvalForm" class="btn btn-success" id="approvalSubmitBtn">
                <i class="fas fa-check" aria-hidden="true"></i> Confirm Approval
            </button>
        </div>
    </div>
</div>

{{-- Rejection Modal --}}
<div class="modal-overlay" id="rejectionModal" role="dialog" aria-modal="true" aria-labelledby="rejectionModalTitle">
    <div class="modal-container" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="rejectionModalTitle">Reject Application</h3>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="text-align:center;margin-bottom:1.75rem;">
                <div style="width:80px;height:80px;background:rgba(192,57,43,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="fas fa-times" style="font-size:2.5rem;color:var(--accent);" aria-hidden="true"></i>
                </div>
                <h4 style="color:var(--dark);margin-bottom:0.4rem;">Confirm Rejection</h4>
                <p style="color:var(--gray);font-size:0.9rem;">
                    You are about to reject the application for
                    <strong id="rejectionApplicantName">—</strong>
                </p>
            </div>

            <form id="rejectionForm"
                  method="POST"
                  action=""
                  data-base-url="{{ url('/admin/applications') }}">
                @csrf
                <input type="hidden" id="rejectionApplicationId" name="_app_id">

                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="rejectionReason">
                        Rejection Reason <span style="color:var(--accent);" aria-hidden="true">*</span>
                    </label>
                    <select class="form-control" id="rejectionReason" name="rejection_reason" required>
                        <option value="">Select a reason...</option>
                        @foreach($rejectionReasons as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="rejectionComments">
                        Detailed Comments <span style="color:var(--accent);" aria-hidden="true">*</span>
                    </label>
                    <textarea class="form-control" id="rejectionComments" name="reviewer_comment"
                              rows="4" maxlength="2000"
                              placeholder="Provide a detailed explanation for the rejection..." required></textarea>
                </div>

                <div style="background:#ffebee;padding:0.875rem 1rem;border-radius:8px;font-size:0.875rem;color:var(--dark);">
                    <i class="fas fa-exclamation-triangle" style="color:var(--accent);margin-right:0.4rem;" aria-hidden="true"></i>
                    The applicant will be notified and may reapply after addressing the issues.
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-close-btn">Cancel</button>
            <button type="submit" form="rejectionForm" class="btn btn-danger" id="rejectionSubmitBtn">
                <i class="fas fa-times" aria-hidden="true"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>

{{-- ── JSON data islands ───────────────────────────────────── --}}
<script type="application/json" id="chartDataIsland">
{
    "nationalities": @json($nationalityData),
    "statusCounts":  @json($statusCounts),
    "overstayRanges":@json($overstayRanges),
    "trendLabels":   @json($trendLabels),
    "trendCounts":   @json($trendCounts)
}
</script>

<script type="application/json" id="appDataIsland">@json($appData)</script>

@include('admin.partials.footer')
