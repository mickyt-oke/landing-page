@include('admin.partials.header')

@php
    $pendingApps     = $activeApplications->where('status', 'pending');
    $underReviewApps = $activeApplications->where('status', 'under_review');

    $isAdmin = optional($currentUser)->hasAnyRole(['admin', 'superadmin']);

    $rejectionReasons = [
        'incomplete_documents'  => 'Incomplete or invalid documents',
        'ineligible_status'     => 'Does not meet eligibility criteria',
        'expired_documents'     => 'Expired travel documents',
        'false_information'     => 'False or misleading information provided',
        'duplicate_application' => 'Duplicate application detected',
        'other'                 => 'Other (specify in comments)',
    ];

    $statusLabels = [
        'pending'      => 'Pending',
        'under_review' => 'Under Review',
        'approved'     => 'Approved',
        'rejected'     => 'Rejected',
    ];

    /* JSON island for JS modal */
    $appData = $activeApplications->merge($recentCompleted)->map(fn($app) => [
        'id'              => $app->id,
        'ref'             => $app->application_reference,
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
        'reviewerName'    => optional($app->reviewer)->name,
        'docs'            => $app->documents->map(fn($d) => [
            'name' => $d->original_name,
            'type' => $d->document_type,
            'mime' => $d->mime_type,
            'size' => $d->size_bytes,
        ])->values()->all(),
    ])->keyBy('id')->all();
@endphp

{{-- Flash meta --}}
@if(session('status'))
<meta name="flash-success" content="{{ e(session('status')) }}">
@endif
@if(session('error'))
<meta name="flash-error" content="{{ e(session('error')) }}">
@endif

{{-- ── Page header ─────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Reviewer Dashboard</h1>
        <p class="page-subtitle">
            @if($isAdmin)
                You are viewing as <strong>{{ ucfirst(optional($currentUser)->role) }}</strong> — you can vet, reject, and approve applications.
            @else
                Vet pending applications and submit notes for admin approval.
            @endif
        </p>
    </div>
    <a href="{{ route('admin.reviewer.dashboard') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-sync-alt" aria-hidden="true"></i> Refresh
    </a>
</div>

{{-- ── Workflow banner ──────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid var(--gray-light);border-radius:12px;padding:1.25rem 1.5rem;margin-top:1.25rem;display:flex;align-items:center;gap:0;flex-wrap:wrap;overflow:hidden;">
    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;font-weight:600;flex-wrap:wrap;width:100%;justify-content:center;gap:0.25rem;">
        <span style="background:var(--gray-light);padding:0.35rem 0.9rem;border-radius:20px;color:var(--dark);">
            <i class="fas fa-user" aria-hidden="true"></i> Applicant Submits
        </span>
        <i class="fas fa-arrow-right" style="color:var(--gray);font-size:0.7rem;" aria-hidden="true"></i>
        <span style="background:rgba(243,156,18,0.15);padding:0.35rem 0.9rem;border-radius:20px;color:#b7770d;border:1px solid rgba(243,156,18,0.3);">
            <i class="fas fa-clock" aria-hidden="true"></i> Pending
        </span>
        <i class="fas fa-arrow-right" style="color:var(--gray);font-size:0.7rem;" aria-hidden="true"></i>
        <span style="background:rgba(52,152,219,0.15);padding:0.35rem 0.9rem;border-radius:20px;color:#1a6fa8;border:1px solid rgba(52,152,219,0.3);">
            <i class="fas fa-search" aria-hidden="true"></i> Reviewer: Start Review
        </span>
        <i class="fas fa-arrow-right" style="color:var(--gray);font-size:0.7rem;" aria-hidden="true"></i>
        <span style="background:rgba(52,152,219,0.15);padding:0.35rem 0.9rem;border-radius:20px;color:#1a6fa8;border:1px solid rgba(52,152,219,0.3);">
            <i class="fas fa-pencil-alt" aria-hidden="true"></i> Reviewer: Vet &amp; Add Notes
        </span>
        <i class="fas fa-arrow-right" style="color:var(--gray);font-size:0.7rem;" aria-hidden="true"></i>
        <span style="background:rgba(11,60,93,0.1);padding:0.35rem 0.9rem;border-radius:20px;color:var(--secondary);border:1px solid rgba(11,60,93,0.2);">
            <i class="fas fa-user-shield" aria-hidden="true"></i> Admin: Approve / Reject
        </span>
        <i class="fas fa-arrow-right" style="color:var(--gray);font-size:0.7rem;" aria-hidden="true"></i>
        <span style="background:rgba(30,132,73,0.12);padding:0.35rem 0.9rem;border-radius:20px;color:var(--primary);border:1px solid rgba(30,132,73,0.25);">
            <i class="fas fa-check-circle" aria-hidden="true"></i> Approved
        </span>
        <span style="color:var(--gray);font-size:0.7rem;margin:0 0.1rem;">/</span>
        <span style="background:rgba(192,57,43,0.1);padding:0.35rem 0.9rem;border-radius:20px;color:var(--accent);border:1px solid rgba(192,57,43,0.2);">
            <i class="fas fa-times-circle" aria-hidden="true"></i> Rejected
        </span>
    </div>
</div>

{{-- ── KPI Stats ────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.25rem;margin-top:1.25rem;">

    <div class="stat-card warning">
        <div class="stat-icon warning"><i class="fas fa-inbox"></i></div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Queue</div>
        <div style="font-size:0.75rem;color:var(--gray);margin-top:0.25rem;">Awaiting reviewer</div>
    </div>

    <div class="stat-card info">
        <div class="stat-icon info"><i class="fas fa-search"></i></div>
        <div class="stat-value">{{ $stats['under_review'] }}</div>
        <div class="stat-label">Under Review</div>
        <div style="font-size:0.75rem;color:var(--gray);margin-top:0.25rem;">In progress</div>
    </div>

    <div class="stat-card" style="border-left:4px solid var(--secondary);">
        <div class="stat-icon" style="background:rgba(11,60,93,0.1);">
            <i class="fas fa-clipboard-check" style="color:var(--secondary);"></i>
        </div>
        <div class="stat-value" style="color:var(--secondary);">{{ $stats['my_vetted'] }}</div>
        <div class="stat-label">My Vetted</div>
        <div style="font-size:0.75rem;color:var(--gray);margin-top:0.25rem;">Awaiting admin decision</div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon primary"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value">{{ $stats['approved'] }}</div>
        <div class="stat-label">Total Approved</div>
    </div>

    <div class="stat-card" style="border-left:4px solid var(--accent);">
        <div class="stat-icon" style="background:rgba(192,57,43,0.1);">
            <i class="fas fa-times-circle" style="color:var(--accent);"></i>
        </div>
        <div class="stat-value" style="color:var(--accent);">{{ $stats['rejected'] }}</div>
        <div class="stat-label">Total Rejected</div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- PENDING APPLICATIONS TABLE                              --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="content-card" style="margin-top:1.5rem;" id="pendingSection">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <span style="width:10px;height:10px;border-radius:50%;background:var(--warning);flex-shrink:0;"></span>
            <h3 class="card-title" style="margin:0;">Pending Applications</h3>
            <span style="background:rgba(243,156,18,0.15);color:#b7770d;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.78rem;font-weight:600;">
                {{ $pendingApps->count() }}
            </span>
        </div>
        <p style="font-size:0.8rem;color:var(--gray);margin:0.25rem 0 0 1.75rem;">
            Click <strong>Start Review</strong> to pick up an application and move it to your review queue.
        </p>
    </div>

    <div class="card-body">
        @if($pendingApps->isEmpty())
        <div style="text-align:center;padding:3rem;color:var(--gray);">
            <i class="fas fa-inbox" style="font-size:2.5rem;margin-bottom:0.75rem;display:block;opacity:0.4;" aria-hidden="true"></i>
            No pending applications. All caught up!
        </div>
        @else
        <div class="table-container">
            <table class="data-table" aria-label="Pending applications">
                <thead>
                    <tr>
                        <th scope="col">Applicant</th>
                        <th scope="col">Passport</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Visa</th>
                        <th scope="col">Arrival</th>
                        <th scope="col">Overstay</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApps as $app)
                    <tr data-app-id="{{ $app->id }}">
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ e($app->full_name) }}</div>
                            <div style="font-size:0.75rem;color:var(--gray);">Ref: {{ e($app->application_reference) }}</div>
                        </td>
                        <td style="font-family:monospace;font-size:0.875rem;">{{ e($app->passport_number) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->nationality) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->visa_category) }}</td>
                        <td style="font-size:0.875rem;">{{ $app->arrival_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if($app->overstay_days > 0)
                                <span style="font-weight:600;color:{{ $app->overstay_days > 30 ? 'var(--accent)' : 'var(--warning)' }};">
                                    {{ $app->overstay_days }}d
                                </span>
                            @else
                                <span style="color:var(--gray);">—</span>
                            @endif
                        </td>
                        <td style="font-size:0.85rem;">{{ $app->submitted_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                                <button type="button"
                                        class="btn btn-outline btn-sm"
                                        data-action="view-app"
                                        data-app-id="{{ $app->id }}"
                                        title="View details">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.applications.start-review', $app) }}"
                                      style="display:inline;margin:0;"
                                      data-confirm="Move '{{ e($app->full_name) }}' to Under Review?">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm"
                                            style="background:var(--info);color:#fff;"
                                            title="Start review"
                                            aria-label="Start reviewing {{ e($app->full_name) }}">
                                        <i class="fas fa-search" aria-hidden="true"></i> Start Review
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- UNDER REVIEW TABLE                                      --}}
{{-- ════════════════════════════════════════════════════════ --}}
<div class="content-card" style="margin-top:1.25rem;" id="underReviewSection">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <span style="width:10px;height:10px;border-radius:50%;background:var(--info);flex-shrink:0;"></span>
            <h3 class="card-title" style="margin:0;">Under Review</h3>
            <span style="background:rgba(52,152,219,0.15);color:#1a6fa8;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.78rem;font-weight:600;">
                {{ $underReviewApps->count() }}
            </span>
        </div>
        <p style="font-size:0.8rem;color:var(--gray);margin:0.25rem 0 0 1.75rem;">
            Add your vetting notes and recommendation. Admins will make the final approval decision.
        </p>
    </div>

    <div class="card-body">
        @if($underReviewApps->isEmpty())
        <div style="text-align:center;padding:3rem;color:var(--gray);">
            <i class="fas fa-check-double" style="font-size:2.5rem;margin-bottom:0.75rem;display:block;opacity:0.4;" aria-hidden="true"></i>
            No applications currently under review.
        </div>
        @else
        <div class="table-container">
            <table class="data-table" aria-label="Applications under review">
                <thead>
                    <tr>
                        <th scope="col">Applicant</th>
                        <th scope="col">Passport</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Visa</th>
                        <th scope="col">Overstay</th>
                        <th scope="col">Reviewer Notes</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($underReviewApps as $app)
                    @php $hasNotes = !empty($app->reviewer_comment); @endphp
                    <tr data-app-id="{{ $app->id }}"
                        style="{{ $hasNotes ? '' : 'background:rgba(243,156,18,0.04);' }}">
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ e($app->full_name) }}</div>
                            <div style="font-size:0.75rem;color:var(--gray);">Ref: {{ e($app->application_reference) }}</div>
                        </td>
                        <td style="font-family:monospace;font-size:0.875rem;">{{ e($app->passport_number) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->nationality) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->visa_category) }}</td>
                        <td>
                            @if($app->overstay_days > 0)
                                <span style="font-weight:600;color:{{ $app->overstay_days > 30 ? 'var(--accent)' : 'var(--warning)' }};">
                                    {{ $app->overstay_days }}d
                                </span>
                            @else
                                <span style="color:var(--gray);">—</span>
                            @endif
                        </td>
                        <td style="max-width:200px;">
                            @if($hasNotes)
                                <div style="font-size:0.8rem;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;"
                                     title="{{ e($app->reviewer_comment) }}">
                                    {{ e(Str::limit($app->reviewer_comment, 60)) }}
                                </div>
                                <div style="font-size:0.7rem;color:var(--primary);margin-top:2px;">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i> Notes submitted
                                </div>
                            @else
                                <span style="font-size:0.8rem;color:var(--warning);">
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i> No notes yet
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($hasNotes)
                                <span class="status-badge review" style="background:rgba(11,60,93,0.1);color:var(--secondary);">
                                    <i class="fas fa-user-shield" aria-hidden="true"></i> Awaiting Admin
                                </span>
                            @else
                                <span class="status-badge review">
                                    <i class="fas fa-search" aria-hidden="true"></i> Under Review
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                                <button type="button"
                                        class="btn btn-outline btn-sm"
                                        data-action="view-app"
                                        data-app-id="{{ $app->id }}"
                                        title="View details">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm"
                                        style="background:var(--secondary);color:#fff;"
                                        data-action="open-vet"
                                        data-app-id="{{ $app->id }}"
                                        data-app-name="{{ e($app->full_name) }}"
                                        data-existing-notes="{{ e($app->reviewer_comment ?? '') }}"
                                        title="{{ $hasNotes ? 'Update vetting notes' : 'Add vetting notes' }}">
                                    <i class="fas fa-{{ $hasNotes ? 'edit' : 'pencil-alt' }}" aria-hidden="true"></i>
                                    {{ $hasNotes ? 'Update Notes' : 'Vet & Note' }}
                                </button>
                                <button type="button"
                                        class="btn btn-sm"
                                        style="background:var(--accent);color:#fff;"
                                        data-action="open-reject"
                                        data-app-id="{{ $app->id }}"
                                        data-app-name="{{ e($app->full_name) }}"
                                        title="Reject application">
                                    <i class="fas fa-times" aria-hidden="true"></i> Reject
                                </button>
                                @if($isAdmin)
                                <button type="button"
                                        class="btn btn-sm"
                                        style="background:var(--primary);color:#fff;"
                                        data-action="open-approve"
                                        data-app-id="{{ $app->id }}"
                                        data-app-name="{{ e($app->full_name) }}"
                                        title="Approve application (Admin)">
                                    <i class="fas fa-check" aria-hidden="true"></i> Approve
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════ --}}
{{-- RECENTLY COMPLETED (read-only reference)                --}}
{{-- ════════════════════════════════════════════════════════ --}}
@if($recentCompleted->isNotEmpty())
<div class="content-card" style="margin-top:1.25rem;" id="completedSection">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <span style="width:10px;height:10px;border-radius:50%;background:var(--primary);flex-shrink:0;"></span>
            <h3 class="card-title" style="margin:0;">Recently Completed</h3>
            <span style="background:rgba(30,132,73,0.12);color:var(--primary);padding:0.2rem 0.6rem;border-radius:20px;font-size:0.78rem;font-weight:600;">
                Last {{ $recentCompleted->count() }}
            </span>
        </div>
        <p style="font-size:0.8rem;color:var(--gray);margin:0.25rem 0 0 1.75rem;">
            Read-only reference. Final decisions made by admins.
        </p>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="data-table" aria-label="Recently completed applications">
                <thead>
                    <tr>
                        <th scope="col">Applicant</th>
                        <th scope="col">Passport</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Final Status</th>
                        <th scope="col">Reviewed By</th>
                        <th scope="col">Reviewed At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentCompleted as $app)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ e($app->full_name) }}</div>
                            <div style="font-size:0.75rem;color:var(--gray);">{{ e($app->application_reference) }}</div>
                        </td>
                        <td style="font-family:monospace;font-size:0.875rem;">{{ e($app->passport_number) }}</td>
                        <td style="font-size:0.875rem;">{{ e($app->nationality) }}</td>
                        <td>
                            <span class="status-badge {{ $app->status === 'approved' ? 'approved' : 'rejected' }}">
                                {{ $app->status === 'approved' ? 'Approved' : 'Rejected' }}
                            </span>
                        </td>
                        <td style="font-size:0.85rem;">
                            {{ optional($app->reviewer)->name ?? '—' }}
                        </td>
                        <td style="font-size:0.85rem;">
                            {{ $app->reviewed_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td>
                            <button type="button"
                                    class="btn btn-outline btn-sm"
                                    data-action="view-app"
                                    data-app-id="{{ $app->id }}"
                                    title="View details">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════ --}}
{{-- MODALS                                                   --}}
{{-- ════════════════════════════════════════════════════════ --}}

{{-- Application Details Modal --}}
<div class="modal-overlay" id="applicationModal" role="dialog" aria-modal="true" aria-labelledby="rvModalTitle">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="rvModalTitle">Application Details</h3>
                <div style="font-size:0.8rem;color:var(--gray);margin-top:0.2rem;" id="rvModalRef"></div>
            </div>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="display:flex;align-items:center;gap:1rem;padding:0.875rem 1rem;background:var(--light);border-radius:8px;margin-bottom:1.25rem;">
                <span class="status-badge" id="rvModalStatus">—</span>
                <span style="font-size:0.8rem;color:var(--gray);" id="rvModalStatusDate"></span>
            </div>

            <div class="detail-section">
                <h4 class="detail-section-title"><i class="fas fa-user" aria-hidden="true"></i> Applicant Information</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value" id="rvModalName">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Passport Number</div>
                        <div class="detail-value" id="rvModalPassport" style="font-family:monospace;">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Nationality</div>
                        <div class="detail-value" id="rvModalNationality">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Visa Category</div>
                        <div class="detail-value" id="rvModalVisa">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Arrival Date</div>
                        <div class="detail-value" id="rvModalArrival">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Overstay Days</div>
                        <div class="detail-value" id="rvModalOverstay">—</div>
                    </div>
                    <div class="detail-item" id="rvModalAddressItem">
                        <div class="detail-label">Address</div>
                        <div class="detail-value" id="rvModalAddress">—</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Submitted</div>
                        <div class="detail-value" id="rvModalSubmitted">—</div>
                    </div>
                </div>
            </div>

            <div class="detail-section" id="rvModalApplicantNoteSection">
                <h4 class="detail-section-title"><i class="fas fa-comment-alt" aria-hidden="true"></i> Applicant Note</h4>
                <div style="background:var(--light);padding:0.875rem;border-radius:8px;font-size:0.875rem;color:var(--dark);" id="rvModalApplicantNote">—</div>
            </div>

            <div class="detail-section" id="rvModalDocsSection">
                <h4 class="detail-section-title"><i class="fas fa-folder-open" aria-hidden="true"></i> Uploaded Documents</h4>
                <div class="document-list" id="rvModalDocList"></div>
            </div>

            <div class="detail-section" id="rvModalReviewerNotesSection" style="display:none;">
                <h4 class="detail-section-title"><i class="fas fa-sticky-note" aria-hidden="true"></i> Reviewer Notes</h4>
                <div style="background:#fff8e1;border-left:4px solid var(--warning);padding:1rem;border-radius:0 8px 8px 0;">
                    <div id="rvModalReviewerNotes" style="font-size:0.875rem;color:var(--dark);">—</div>
                </div>
            </div>
        </div>
        <div class="modal-footer" id="rvModalFooter">
            <button class="btn btn-outline modal-close-btn">Close</button>
            <button class="btn btn-sm"
                    style="background:var(--secondary);color:#fff;display:none;"
                    id="rvModalVetBtn"
                    data-action="vet-from-details">
                <i class="fas fa-pencil-alt" aria-hidden="true"></i> Vet &amp; Note
            </button>
            <button class="btn btn-danger btn-sm"
                    style="display:none;"
                    id="rvModalRejectBtn"
                    data-action="reject-from-details">
                <i class="fas fa-times" aria-hidden="true"></i> Reject
            </button>
            @if($isAdmin)
            <button class="btn btn-success btn-sm"
                    style="display:none;"
                    id="rvModalApproveBtn"
                    data-action="approve-from-details">
                <i class="fas fa-check" aria-hidden="true"></i> Approve
            </button>
            @endif
        </div>
    </div>
</div>

{{-- Vetting / Notes Modal --}}
<div class="modal-overlay" id="vetModal" role="dialog" aria-modal="true" aria-labelledby="vetModalTitle">
    <div class="modal-container" style="max-width:520px;">
        <div class="modal-header">
            <h3 class="modal-title" id="vetModalTitle">Vetting Notes</h3>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="background:rgba(11,60,93,0.06);border:1px solid rgba(11,60,93,0.15);border-radius:8px;padding:0.875rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;">
                <strong style="color:var(--secondary);">
                    <i class="fas fa-info-circle" aria-hidden="true"></i> Reviewer Role
                </strong>
                <p style="margin:0.4rem 0 0;color:var(--dark);">
                    Add your professional assessment of the application for <strong id="vetApplicantName">—</strong>.
                    Your notes will be visible to admins who make the final approval decision.
                </p>
            </div>

            <form id="vetForm"
                  method="POST"
                  action=""
                  data-base-url="{{ url('/admin/applications') }}">
                @csrf
                <input type="hidden" id="vetApplicationId">

                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="vetComment">
                        Vetting Assessment <span style="color:var(--accent);" aria-hidden="true">*</span>
                    </label>
                    <textarea class="form-control"
                              id="vetComment"
                              name="reviewer_comment"
                              rows="5"
                              maxlength="2000"
                              placeholder="Describe your assessment: document authenticity, eligibility check, overstay circumstances, recommendation for admin..."
                              required></textarea>
                    <div style="text-align:right;font-size:0.75rem;color:var(--gray);margin-top:0.25rem;">
                        <span id="vetCharCount">0</span> / 2000
                    </div>
                </div>

                <div style="background:#e8f5e9;padding:0.875rem 1rem;border-radius:8px;font-size:0.85rem;color:var(--dark);">
                    <i class="fas fa-lightbulb" style="color:var(--primary);margin-right:0.4rem;" aria-hidden="true"></i>
                    After submitting, the application will be flagged <strong>Awaiting Admin</strong> for the final approve/reject decision.
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-close-btn">Cancel</button>
            <button type="submit" form="vetForm" class="btn" style="background:var(--secondary);color:#fff;" id="vetSubmitBtn">
                <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit Vetting Notes
            </button>
        </div>
    </div>
</div>

{{-- Rejection Modal --}}
<div class="modal-overlay" id="rejectionModal" role="dialog" aria-modal="true" aria-labelledby="rvRejModalTitle">
    <div class="modal-container" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="rvRejModalTitle">Reject Application</h3>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="width:72px;height:72px;background:rgba(192,57,43,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.875rem;">
                    <i class="fas fa-times" style="font-size:2rem;color:var(--accent);" aria-hidden="true"></i>
                </div>
                <h4 style="color:var(--dark);margin-bottom:0.4rem;">Confirm Rejection</h4>
                <p style="color:var(--gray);font-size:0.875rem;">
                    You are about to reject the application for <strong id="rvRejApplicantName">—</strong>
                </p>
            </div>

            <form id="rejectionForm"
                  method="POST"
                  action=""
                  data-base-url="{{ url('/admin/applications') }}">
                @csrf
                <input type="hidden" id="rejectionApplicationId">

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
                    <textarea class="form-control"
                              id="rejectionComments"
                              name="reviewer_comment"
                              rows="4"
                              maxlength="2000"
                              placeholder="Provide a detailed explanation..." required></textarea>
                </div>

                <div style="background:#ffebee;padding:0.875rem 1rem;border-radius:8px;font-size:0.85rem;color:var(--dark);">
                    <i class="fas fa-exclamation-triangle" style="color:var(--accent);margin-right:0.4rem;" aria-hidden="true"></i>
                    The applicant will be notified and may reapply after addressing the issues.
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-close-btn">Cancel</button>
            <button type="submit" form="rejectionForm" class="btn btn-danger" id="rvRejSubmitBtn">
                <i class="fas fa-times" aria-hidden="true"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>

{{-- Approve Modal (admin/superadmin only) --}}
@if($isAdmin)
<div class="modal-overlay" id="approvalModal" role="dialog" aria-modal="true" aria-labelledby="rvAppModalTitle">
    <div class="modal-container" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="rvAppModalTitle">Approve Application</h3>
            <button class="modal-close" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="modal-body">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="width:72px;height:72px;background:rgba(30,132,73,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.875rem;">
                    <i class="fas fa-check" style="font-size:2rem;color:var(--primary);" aria-hidden="true"></i>
                </div>
                <h4 style="color:var(--dark);margin-bottom:0.4rem;">Confirm Approval</h4>
                <p style="color:var(--gray);font-size:0.875rem;">
                    Approving application for <strong id="rvAppApplicantName">—</strong>
                </p>
            </div>

            {{-- Show reviewer notes for admin context --}}
            <div id="rvAppReviewerNotesCtx"
                 style="background:#fff8e1;border-left:4px solid var(--warning);padding:0.875rem 1rem;border-radius:0 8px 8px 0;margin-bottom:1rem;display:none;">
                <div style="font-size:0.75rem;font-weight:600;color:#b7770d;margin-bottom:0.4rem;">
                    <i class="fas fa-sticky-note" aria-hidden="true"></i> Reviewer Notes
                </div>
                <div id="rvAppReviewerNotesText" style="font-size:0.85rem;color:var(--dark);"></div>
            </div>

            <form id="approvalForm"
                  method="POST"
                  action=""
                  data-base-url="{{ url('/admin/applications') }}">
                @csrf
                <input type="hidden" id="approvalApplicationId">

                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="approvalComments">Admin Comments (optional)</label>
                    <textarea class="form-control"
                              id="approvalComments"
                              name="reviewer_comment"
                              rows="3"
                              maxlength="2000"
                              placeholder="Optional comments on this approval..."></textarea>
                </div>

                <div style="background:#e8f5e9;padding:0.875rem 1rem;border-radius:8px;font-size:0.85rem;color:var(--dark);">
                    <i class="fas fa-info-circle" style="color:var(--primary);margin-right:0.4rem;" aria-hidden="true"></i>
                    The applicant will be notified once approved.
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline modal-close-btn">Cancel</button>
            <button type="submit" form="approvalForm" class="btn btn-success" id="rvAppSubmitBtn">
                <i class="fas fa-check" aria-hidden="true"></i> Confirm Approval
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── JSON data island ────────────────────────────────────── --}}
<script type="application/json" id="rvAppDataIsland">@json(array_values($appData))</script>

@include('admin.partials.footer')
