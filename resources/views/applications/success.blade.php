@include('partials.header')

<main class="dashboard-content px-3 py-4" id="main-content" aria-label="Application submission success">
    <div class="container-fluid" style="max-width: 1200px;">

        {{-- Flash alert --}}
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" aria-live="polite">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss notification"></button>
            </div>
        @endif

        {{-- Success Banner --}}
        <div class="rounded-3 overflow-hidden shadow mb-4" style="background: linear-gradient(135deg, var(--primary-dark, #145A32) 0%, var(--primary, #1E8449) 100%);">
            <div class="p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-white"
                                  style="width: 40px; height: 40px; min-width: 40px;" aria-hidden="true">
                                <i class="fas fa-check text-success fs-5"></i>
                            </span>
                            <span class="badge text-success fw-semibold px-3 py-2"
                                  style="background: rgba(255,255,255,0.9); font-size: 0.8rem; letter-spacing: 0.05em; text-transform: uppercase;">
                                Submission Successful
                            </span>
                        </div>
                        <h1 class="h3 fw-bold mb-2">Your application has been submitted</h1>
                        <p class="mb-0" style="color: rgba(255,255,255,0.75); max-width: 480px;">
                            Your overstay clearance application is now under review.
                            Save your reference numbers and download your acknowledgement slip.
                        </p>
                    </div>

                    {{-- Reference card --}}
                    <div class="bg-white text-dark rounded-3 shadow-sm p-3 p-md-4 text-center"
                         style="min-width: 200px;" aria-label="Application reference number">
                        <div class="small text-muted text-uppercase fw-semibold mb-1" style="letter-spacing: 0.06em;">
                            Application Reference
                        </div>
                        <div class="fw-bold font-monospace" style="font-size: 1.4rem; color: var(--primary, #1E8449);">
                            {{ $application->application_reference }}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">
                                {{ str_replace('_', ' ', $application->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress strip --}}
            <div class="d-flex border-top border-white border-opacity-25" style="background: rgba(0,0,0,0.15);" aria-label="Application progress">
                @php
                    $steps = [
                        ['icon' => 'fa-file-alt',     'label' => 'Submitted',  'done' => true],
                        ['icon' => 'fa-search',        'label' => 'Under Review','done' => false],
                        ['icon' => 'fa-clipboard-check','label' => 'Decision',  'done' => false],
                        ['icon' => 'fa-check-double',  'label' => 'Completed',  'done' => false],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                    <div class="flex-fill text-center py-3 px-2 {{ $i < count($steps) - 1 ? 'border-end border-white border-opacity-25' : '' }}">
                        <i class="fas {{ $step['icon'] }} mb-1 d-block"
                           style="color: {{ $step['done'] ? 'rgba(255,255,255,1)' : 'rgba(255,255,255,0.45)' }}; font-size: 1rem;"
                           aria-hidden="true"></i>
                        <small class="fw-{{ $step['done'] ? 'semibold' : 'normal' }}"
                               style="color: {{ $step['done'] ? 'rgba(255,255,255,1)' : 'rgba(255,255,255,0.5)' }}; font-size: 0.72rem;">
                            {{ $step['label'] }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Primary action bar --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid var(--primary, #1E8449) !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h2 class="h6 fw-bold mb-1">Download Your Acknowledgement Slip</h2>
                        <p class="mb-0 small text-muted">Official confirmation of your submission — required for tracking and support.</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-shrink-0">
                        <a href="{{ route('applications.acknowledgement', $application) }}"
                           class="btn btn-primary"
                           aria-label="Generate and download acknowledgement slip for application {{ $application->application_reference }}">
                            <i class="fas fa-file-download me-2" aria-hidden="true"></i>Download Acknowledgement
                        </a>
                        <a href="{{ route('applications.acknowledgement', $application) }}"
                           class="btn btn-outline-secondary"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="Open printable version of acknowledgement slip">
                            <i class="fas fa-print me-2" aria-hidden="true"></i>Print Version
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info grid --}}
        <div class="row g-4 mb-4">

            {{-- Submission details --}}
            <div class="col-lg-5">
                <section class="card border-0 shadow-sm h-100" aria-labelledby="submission-details-heading">
                    <div class="card-header bg-light border-bottom fw-semibold py-3" id="submission-details-heading">
                        <i class="fas fa-id-card me-2 text-muted" aria-hidden="true"></i>Submission Details
                    </div>
                    <div class="card-body p-0">
                        <dl class="mb-0">
                            @php
                                $details = [
                                    ['label' => 'Applicant Name',     'value' => $application->full_name],
                                    ['label' => 'Passport Number',    'value' => $application->passport_number],
                                    ['label' => 'Acknowledgement Ref','value' => $application->ack_ref_number],
                                    ['label' => 'Submitted At',       'value' => $application->submitted_at?->format('d M Y, h:i A')],
                                ];
                            @endphp
                            @foreach($details as $i => $item)
                                <div class="d-flex align-items-start gap-3 px-4 py-3 {{ $i < count($details) - 1 ? 'border-bottom' : '' }}">
                                    <dt class="text-muted small fw-normal mb-0 flex-shrink-0" style="min-width: 140px;">{{ $item['label'] }}</dt>
                                    <dd class="mb-0 fw-medium small text-truncate">{{ $item['value'] ?? '—' }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </section>
            </div>

            {{-- Next steps --}}
            <div class="col-lg-7">
                <section class="card border-0 shadow-sm h-100" aria-labelledby="next-steps-heading">
                    <div class="card-header bg-light border-bottom fw-semibold py-3" id="next-steps-heading">
                        <i class="fas fa-list-ol me-2 text-muted" aria-hidden="true"></i>What Happens Next
                    </div>
                    <div class="card-body p-4">
                        <ol class="mb-0 ps-0 list-unstyled">
                            @php
                                $steps = [
                                    ['icon' => 'fa-download',         'title' => 'Save your acknowledgement slip', 'desc' => 'Download or print now — you will need it for follow-up queries.'],
                                    ['icon' => 'fa-clock',            'title' => 'Await officer review',           'desc' => 'Processing typically takes 5–10 working days from submission.'],
                                    ['icon' => 'fa-search',           'title' => 'Track your application',         'desc' => 'Use your application reference on the dashboard to monitor status updates.'],
                                    ['icon' => 'fa-folder-open',      'title' => 'Keep your documents ready',      'desc' => 'An officer may request additional verification of uploaded documents.'],
                                ];
                            @endphp
                            @foreach($steps as $i => $step)
                                <li class="d-flex gap-3 {{ $i < count($steps) - 1 ? 'mb-3' : '' }}">
                                    <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                          style="width: 36px; height: 36px; min-width: 36px; background: rgba(30,132,73,0.1);"
                                          aria-hidden="true">
                                        <i class="fas {{ $step['icon'] }} small" style="color: var(--primary, #1E8449);"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold small">{{ $step['title'] }}</div>
                                        <div class="text-muted small">{{ $step['desc'] }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </section>
            </div>
        </div>

        {{-- Acknowledgement preview --}}
        <section class="card border-0 shadow-sm mb-4" aria-labelledby="ack-preview-heading">
            <div class="card-header bg-light border-bottom py-3" id="ack-preview-heading">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <div>
                        <span class="fw-semibold">
                            <i class="fas fa-file-alt me-2 text-muted" aria-hidden="true"></i>Acknowledgement Slip Preview
                        </span>
                        <span class="d-block d-sm-inline small text-muted ms-sm-2">Official document for your records</span>
                    </div>
                    <a href="{{ route('applications.acknowledgement', $application) }}"
                       class="btn btn-sm btn-outline-primary"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Open full printable acknowledgement slip">
                        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Open Full Version
                    </a>
                </div>
            </div>
            <div class="card-body p-3 p-md-4 bg-light">
                @include('partials.acknowledgement', ['application' => $application, 'isStandalone' => false])
            </div>
        </section>

        {{-- Footer navigation --}}
        <nav class="d-flex flex-column flex-sm-row gap-2 justify-content-end" aria-label="Page navigation">
            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-secondary"
               aria-label="Return to dashboard">
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
