@include('partials.header')

<main class="dashboard-content px-3 py-4">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4 no-print">
            <div>
                <h2 class="mb-1">Acknowledgement Slip</h2>
                <p class="text-muted mb-0">Print or download this acknowledgement slip for your records.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('applications.success', $application) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Success Page
                </a>
                <button type="button" onclick="window.print()" class="btn btn-success">
                    <i class="fas fa-print me-2"></i>Print / Save as PDF
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-3 p-md-4">
                @include('partials.acknowledgement', ['application' => $application, 'isStandalone' => true])
            </div>
        </div>
    </div>
</main>

@include('partials.footer')
