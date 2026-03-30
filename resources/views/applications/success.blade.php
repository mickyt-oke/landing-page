@include('partials.header')

<main class="dashboard-content px-3 py-4">
    <div class="container-fluid">
        @if(session('status'))
            <div class="alert alert-success shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-success text-white p-4 p-md-5">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                        <div>
                            <span class="badge bg-light text-success mb-3 px-3 py-2">Submission Successful</span>
                            <h2 class="mb-2">Your application has been submitted</h2>
                            <p class="mb-0 text-white-50">
                                Keep your acknowledgement slip and reference numbers for tracking and support.
                            </p>
                        </div>

                        <div class="bg-white text-dark rounded-3 shadow-sm px-4 py-3">
                            <div class="small text-muted text-uppercase">Application Reference</div>
                            <div class="fs-4 fw-bold">{{ $application->application_reference }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-secondary-subtle shadow-sm">
                                <div class="card-header bg-light fw-semibold">Submission Details</div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5">Applicant Name</dt>
                                        <dd class="col-sm-7">{{ $application->full_name }}</dd>

                                        <dt class="col-sm-5">Passport Number</dt>
                                        <dd class="col-sm-7">{{ $application->passport_number }}</dd>

                                        <dt class="col-sm-5">Acknowledgement Ref</dt>
                                        <dd class="col-sm-7">{{ $application->ack_ref_number }}</dd>

                                        <dt class="col-sm-5">Submitted At</dt>
                                        <dd class="col-sm-7">{{ $application->submitted_at?->format('d M Y h:i A') }}</dd>

                                        <dt class="col-sm-5">Status</dt>
                                        <dd class="col-sm-7">
                                            <span class="badge bg-warning text-dark text-uppercase">
                                                {{ str_replace('_', ' ', $application->status) }}
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-secondary-subtle shadow-sm">
                                <div class="card-header bg-light fw-semibold">Next Steps</div>
                                <div class="card-body">
                                    <ul class="mb-0 ps-3">
                                        <li class="mb-2">Download or print your acknowledgement slip immediately.</li>
                                        <li class="mb-2">Use your application reference to track your application on the dashboard.</li>
                                        <li class="mb-2">Your submission will remain under review until processed by an officer.</li>
                                        <li>Keep all uploaded documents available in case further verification is required.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 align-items-stretch align-items-md-center justify-content-between mb-4">
                        <a href="{{ route('applications.acknowledgement', $application) }}" class="btn btn-primary">
                            <i class="fas fa-file-download me-2"></i>Generate / Download Acknowledgement Slip
                        </a>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>Back to Dashboard
                            </a>
                            <a href="{{ route('applications.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus-circle me-2"></i>Start New Registration
                            </a>
                        </div>
                    </div>

                    <div class="border rounded-3 p-3 p-md-4 bg-light">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h4 class="mb-1">Acknowledgement Slip Preview</h4>
                                <p class="text-muted mb-0">
                                    This is the official acknowledgement generated for your submission.
                                </p>
                            </div>
                            <a href="{{ route('applications.acknowledgement', $application) }}" class="btn btn-success">
                                <i class="fas fa-print me-2"></i>Open Printable Version
                            </a>
                        </div>

                        @include('partials.acknowledgement', ['application' => $application, 'isStandalone' => false])
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.footer')
