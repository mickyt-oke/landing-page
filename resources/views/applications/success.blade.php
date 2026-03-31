@include('partials.header')

<main class="dashboard-content" id="main-content" aria-label="Application submission confirmation">
    <div style="max-width: 1100px;">

        {{-- Flash status alert --}}
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert" aria-live="polite" aria-atomic="true">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss notification"></button>
            </div>
        @endif

        {{-- ── Page header ─────────────────────────────────────────── --}}
        <div class="page-header">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                <div>
                    <h1 class="page-title mb-1">
                        <i class="fas fa-check-circle me-2" style="color: var(--primary);" aria-hidden="true"></i>
                        Submission Confirmed
                    </h1>
                    <p class="page-subtitle mb-0">
                        Your overstay clearance application has been received and is under review.
                    </p>
                </div>
                <span class="status-badge {{ $application->status }}" role="status"
                      aria-label="Application status: {{ str_replace('_', ' ', $application->status) }}">
                    {{ ucwords(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>
        </div>

        {{-- ── Reference + action strip ─────────────────────────────── --}}
        <div class="content-card mb-0" style="margin-bottom: 1.5rem !important;">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">

                    {{-- Reference numbers --}}
                    <div class="d-flex flex-column flex-sm-row gap-4">
                        <div>
                            <p class="mb-1 small fw-semibold text-uppercase"
                               style="color: var(--gray); letter-spacing: 0.06em;">Application Reference</p>
                            <p class="mb-0 fw-bold font-monospace" style="font-size: 1.3rem; color: var(--dark);">
                                {{ $application->application_reference }}
                            </p>
                        </div>
                        <div style="border-left: 1px solid var(--gray-light);" class="d-none d-sm-block ps-4">
                            <p class="mb-1 small fw-semibold text-uppercase"
                               style="color: var(--gray); letter-spacing: 0.06em;">Acknowledgement Ref</p>
                            <p class="mb-0 fw-bold font-monospace" style="font-size: 1.3rem; color: var(--dark);">
                                {{ $application->ack_ref_number }}
                            </p>
                        </div>
                    </div>

                    {{-- Primary actions --}}
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-shrink-0">
                        <a href="{{ route('applications.acknowledgement', $application) }}"
                           class="btn btn-primary"
                           aria-label="Download acknowledgement slip for application {{ $application->application_reference }}">
                            <i class="fas fa-file-download me-2" aria-hidden="true"></i>Download Slip
                        </a>
                        <a href="{{ route('applications.acknowledgement', $application) }}"
                           class="btn btn-outline-secondary"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="Open printable acknowledgement slip in a new tab">
                            <i class="fas fa-print me-2" aria-hidden="true"></i>Print Version
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Progress tracker ─────────────────────────────────────── --}}
        

        {{-- ── Details + Next steps ─────────────────────────────────── --}}
        <div class="row g-4 mb-0" style="margin-bottom: 1.5rem !important;">

            {{-- Submission details --}}
            <div class="col-lg-5">
                <section class="content-card h-100 mb-0" aria-labelledby="details-heading">
                    <div class="card-header">
                        <h2 class="card-title" id="details-heading" style="font-size: 1rem;">
                            <i class="fas fa-id-card me-2" style="color: var(--primary);" aria-hidden="true"></i>
                            Submission Details
                        </h2>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $fields = [
                                ['label' => 'Applicant Name',  'value' => $application->full_name],
                                ['label' => 'Passport Number', 'value' => $application->passport_number],
                                ['label' => 'Ack. Reference',  'value' => $application->ack_ref_number],
                                ['label' => 'Submitted',       'value' => $application->submitted_at?->format('d M Y, h:i A')],
                            ];
                        @endphp
                        <dl class="mb-0">
                            @foreach($fields as $i => $field)
                                <div class="d-flex align-items-start gap-3 px-4 py-3 {{ $i < count($fields) - 1 ? 'border-bottom' : '' }}"
                                     style="border-color: var(--gray-light) !important;">
                                    <dt class="mb-0 small fw-normal flex-shrink-0"
                                        style="color: var(--gray); min-width: 130px;">{{ $field['label'] }}</dt>
                                    <dd class="mb-0 small fw-semibold" style="color: var(--dark); word-break: break-word;">
                                        {{ $field['value'] ?? '—' }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </section>
            </div>

            {{-- Next steps --}}
            <div class="col-lg-7">
                <section class="content-card h-100 mb-0" aria-labelledby="nextsteps-heading">
                    <div class="card-header">
                        <h2 class="card-title" id="nextsteps-heading" style="font-size: 1rem;">
                            <i class="fas fa-list-ol me-2" style="color: var(--primary);" aria-hidden="true"></i>
                            What Happens Next
                        </h2>
                    </div>
                    <div class="card-body">
                        <ol class="list-unstyled mb-0 ps-0">
                            @php
                                $steps = [
                                    ['icon' => 'fa-download',     'color' => 'primary', 'title' => 'Save your acknowledgement slip',
                                     'desc'  => 'Download or print now — you will need it for any follow-up queries or support.'],
                                    ['icon' => 'fa-clock',        'color' => 'warning',  'title' => 'Await officer review',
                                     'desc'  => 'Processing typically takes 5–10 working days from the date of submission.'],
                                    ['icon' => 'fa-tachometer-alt','color' => 'info',    'title' => 'Track on your dashboard',
                                     'desc'  => 'Use your application reference number to monitor status updates.'],
                                    ['icon' => 'fa-folder-open',  'color' => 'danger',   'title' => 'Keep documents ready',
                                     'desc'  => 'An officer may request additional verification of uploaded documents.'],
                                ];
                            @endphp
                            @foreach($steps as $i => $step)
                                <li class="d-flex gap-3 {{ $i < count($steps) - 1 ? 'mb-3' : '' }}">
                                    <div class="stat-icon {{ $step['color'] }} flex-shrink-0"
                                         style="width: 36px; height: 36px; border-radius: 10px; font-size: 0.9rem;"
                                         aria-hidden="true">
                                        <i class="fas {{ $step['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-semibold small" style="color: var(--dark);">{{ $step['title'] }}</p>
                                        <p class="mb-0 small" style="color: var(--gray);">{{ $step['desc'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </section>
            </div>
        </div>

        {{-- ── Acknowledgement preview ───────────────────────────────── --}}
        <section class="content-card" aria-labelledby="ack-preview-heading" style="margin-bottom: 1.5rem !important;">
            <div class="card-header">
                <h2 class="card-title" id="ack-preview-heading" style="font-size: 1rem;">
                    <i class="fas fa-file-alt me-2" style="color: var(--primary);" aria-hidden="true"></i>
                    Acknowledgement Slip Preview
                </h2>
                <a href="{{ route('applications.acknowledgement', $application) }}"
                   class="btn btn-outline-primary btn-sm"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="Open full printable acknowledgement slip in a new tab">
                    <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Full Version
                </a>
            </div>
            {{-- <div class="card-body" style="background: var(--light);">
                @include('partials.acknowledgement', ['application' => $application, 'isStandalone' => false])
            </div> --}}
        </section>

        {{-- ── Footer navigation ────────────────────────────────────── --}}
        <nav class="d-flex flex-column flex-sm-row gap-2 justify-content-end pb-2"
             aria-label="Page navigation actions">
            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-secondary"
               aria-label="Return to your dashboard">
                <i class="fas fa-home me-2" aria-hidden="true"></i>Back to Dashboard
            </a>
            <a href="{{ route('applications.create') }}"
               class="btn btn-outline-primary"
               aria-label="Start a new application registration">
                <i class="fas fa-plus-circle me-2" aria-hidden="true"></i>New Application
            </a>
        </nav>

    </div>
</main>

@include('partials.footer')
