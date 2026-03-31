@props(['application', 'userName' => null, 'isStandalone' => false])

<div class="acknowledgement-page {{ $isStandalone ? '' : 'modal-body' }}" id="ackContent">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .acknowledgement-page { max-width: 8.5in; margin: 0 auto; font-size: 12pt; }
        }
        .nis-header { background: linear-gradient(135deg, #003087, #0056b3); color: white; padding: 1rem; text-align: center; }
        .ref-badge { background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 25px; font-weight: bold; font-size: 1.2em; }
    </style>

    @if(!$isStandalone)
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-success mb-3">
                <i class="fas fa-print"></i> Print Acknowledgement
            </button>
        </div>
    @endif

    <div class="">
        <img src="{{ asset('assets/images/nis-logo-2.png') }}" alt="NIS Logo" style="height: 60px; margin-bottom: 0.5rem;">
        <h2>Nigeria Immigration Service</h2>
        <p>Foreigners Registration Portal</p>
    </div>

    <div class="p-5 border">
        <div class="text-center mb-4">
            <h4 class="mb-2">APPLICATION ACKNOWLEDGEMENT</h4>
            <div class="ref-badge d-inline-block mb-3">
                Reference Number: {{ $application->ack_ref_number ?? 'N/A' }}
            </div>
            <p class="lead">Date: {{ $application->submitted_at?->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Personal Details</h4>
                <table class="table table-borderless">
                    <tr><td><strong>Full Name:</strong></td><td>{{ $application->full_name }}</td></tr>
                    <tr><td><strong>Passport No:</strong></td><td>{{ $application->passport_number }}</td></tr>
                    <tr><td><strong>Nationality:</strong></td><td>{{ $application->nationality }}</td></tr>
                    <tr><td><strong>Visa Category:</strong></td><td>{{ $application->visa_category }}</td></tr>
                </table>
            </div>
            <div class="hr d-md-none my-4"></div>
            <div class="col-md-6">
                <h4>Application Details</h4>
                <table class="table table-borderless">
                    <tr><td><strong>Arrival Date:</strong></td><td>{{ $application->arrival_date?->format('d M Y') }}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($application->status) }}</td></tr>
                    <tr><td><strong>Type:</strong></td><td>Overstay Clearance</td></tr>
                </table>
            </div>
        </div>

        <div class="mt-4 p-3 border-top">
            <h5>Next Steps:</h5>
            <ul>
                <li>Your application has been received and is under review</li>
                <li>Track status via dashboard using reference number</li>
                <li>Processing time: 5-10 working days</li>
                <li>Contact support if no update within 14 days</li>
            </ul>
        </div>

        <div class="text-center mt-4 no-print">
            <p><small>This is a system-generated acknowledgement. Keep this for your records.</small></p>
        </div>
    </div>
</div>

<script>
    if (window.print) {
        window.addEventListener('afterprint', () => {
            if (!{{ $isStandalone ? 'true' : 'false' }}) {
                document.getElementById('ackModal')?.classList.remove('show');
            }
        });
    }
</script>

