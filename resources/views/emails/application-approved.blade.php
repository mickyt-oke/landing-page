<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Approved – Nigeria Immigration Service</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: #eef2f7; font-family: Arial, Helvetica, sans-serif; color: #222; -webkit-font-smoothing: antialiased; }

        /* ── Outer wrapper ── */
        .wrapper { max-width: 640px; margin: 36px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.10); }

        /* ── Header ── */
        .header { background: linear-gradient(135deg, #002878 0%, #0050b3 100%); padding: 28px 40px 24px; text-align: center; }
        .header img { height: 60px; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto; }
        .header h1 { color: #ffffff; font-size: 18px; margin: 0 0 4px; letter-spacing: 0.4px; }
        .header p { color: rgba(255,255,255,0.80); font-size: 12px; margin: 0; }

        /* ── Approved banner ── */
        .approved-banner { background: #1a7f3c; color: #fff; text-align: center; padding: 16px 24px; font-size: 15px; font-weight: bold; letter-spacing: 0.6px; border-bottom: 3px solid #145e2c; }
        .approved-banner .check { display: inline-block; width: 22px; height: 22px; background: #fff; color: #1a7f3c; border-radius: 50%; font-size: 13px; line-height: 22px; text-align: center; margin-right: 8px; font-weight: bold; vertical-align: middle; }

        /* ── Body ── */
        .body { padding: 32px 40px; }
        .greeting { font-size: 15px; color: #444; line-height: 1.7; margin: 0 0 24px; }
        .greeting strong { color: #002878; }

        /* ── Reference card ── */
        .ref-card { background: linear-gradient(135deg, #e8f5e9, #f1f8e9); border: 1.5px solid #81c784; border-radius: 8px; padding: 18px 24px; text-align: center; margin: 0 0 20px; }
        .ref-card .ref-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #2e7d32; font-weight: bold; margin-bottom: 6px; }
        .ref-card .ref-number { font-size: 24px; font-weight: bold; color: #1b5e20; letter-spacing: 3px; font-family: 'Courier New', monospace; }

        /* ── 30-day extension notice ── */
        .extension-notice { background: #fff8e1; border: 1.5px solid #ffc107; border-left: 5px solid #f59e0b; border-radius: 6px; padding: 16px 20px; margin: 0 0 20px; }
        .extension-notice .notice-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #b45309; font-weight: bold; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .extension-notice .notice-icon { display: inline-block; width: 18px; height: 18px; background: #f59e0b; color: #fff; border-radius: 50%; font-size: 11px; line-height: 18px; text-align: center; font-weight: bold; }
        .extension-notice p { margin: 0; font-size: 13px; color: #78350f; line-height: 1.7; }
        .extension-notice strong { color: #92400e; }

        /* ── Slip card (printable) ── */
        .slip-card { border: 1.5px solid #d1d9e8; border-radius: 8px; overflow: hidden; margin: 20px 0; }
        .slip-card-header { background: #f0f4f8; padding: 12px 20px; border-bottom: 1px solid #d1d9e8; display: flex; justify-content: space-between; align-items: center; }
        .slip-card-header .slip-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; color: #002878; }
        .slip-card-header .slip-badge { background: #1a7f3c; color: #fff; font-size: 10px; font-weight: bold; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px; }

        /* ── Applicant identity row ── */
        .identity-row { display: flex; align-items: flex-start; gap: 20px; padding: 20px; border-bottom: 1px solid #e8edf4; }
        .photo-box { width: 88px; min-width: 88px; height: 108px; border: 2px solid #c5d0e0; border-radius: 4px; overflow: hidden; background: #f0f4f8; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .photo-placeholder { text-align: center; color: #94a3b8; font-size: 11px; padding: 8px; line-height: 1.4; }
        .photo-placeholder .icon { font-size: 28px; display: block; margin-bottom: 4px; }
        .identity-info { flex: 1; }
        .identity-info .applicant-name { font-size: 17px; font-weight: bold; color: #002878; margin: 0 0 4px; }
        .identity-info .applicant-passport { font-size: 12px; color: #555; margin: 0 0 10px; letter-spacing: 0.5px; }

        /* ── Visa type pill ── */
        .visa-pill { display: inline-block; background: #e8f0fe; color: #1a3a8f; border: 1px solid #b0c4f8; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; letter-spacing: 0.5px; }

        /* ── Dates table (Date Applied / Date Approved / Valid Till) ── */
        .dates-row { display: flex; border-bottom: 1px solid #e8edf4; }
        .dates-cell { flex: 1; padding: 14px 16px; text-align: center; border-right: 1px solid #e8edf4; }
        .dates-cell:last-child { border-right: none; }
        .dates-cell .dc-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #6b7a99; font-weight: bold; margin-bottom: 5px; }
        .dates-cell .dc-value { font-size: 13px; font-weight: bold; color: #002878; }
        .dates-cell.valid-till .dc-value { color: #1a7f3c; }
        .dates-cell.valid-till .dc-label { color: #2e7d32; }

        /* ── Details table ── */
        .details-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .details-table tr:last-child td { border-bottom: none; }
        .details-table td { padding: 10px 16px; border-bottom: 1px solid #e8edf4; vertical-align: top; }
        .details-table td:first-child { font-weight: bold; color: #4a5568; width: 38%; background: #f8fafc; }

        /* ── CTA button ── */
        .btn-wrap { text-align: center; margin: 28px 0 20px; }
        .btn { display: inline-block; background: #002878; color: #ffffff !important; text-decoration: none; padding: 13px 34px; border-radius: 6px; font-size: 14px; font-weight: bold; letter-spacing: 0.3px; }

        /* ── Info notice ── */
        .info-notice { background: #e3f2fd; border-left: 4px solid #1976d2; border-radius: 4px; padding: 13px 18px; font-size: 12px; color: #0d47a1; line-height: 1.7; margin: 0 0 8px; }

        /* ── Footer ── */
        .footer { background: #f0f4f8; padding: 20px 40px; text-align: center; font-size: 11px; color: #9aa5b4; border-top: 1px solid #dde4ee; line-height: 1.8; }
        .footer strong { color: #6b7a99; }
    </style>
</head>
<body>

@php
    $approvedAt  = $application->reviewed_at ?? now();
    $validTill   = \Carbon\Carbon::parse($approvedAt)->addDays(30);
    $submittedAt = $application->submitted_at ?? $application->created_at;
    $hasPhoto    = ! empty($application->passport_photo_url);
@endphp

<div class="wrapper">

    {{-- ── Header ── --}}
    <div class="header">
        <img src="{{ asset('assets/images/nis-logo-2.png') }}" alt="NIS Logo">
        <h1>Nigeria Immigration Service</h1>
        <p>Foreigners Registration Portal – Official Communication</p>
    </div>

    {{-- ── Approved banner ── --}}
    <div class="approved-banner">
        <span class="check">&#10003;</span> APPLICATION APPROVED
    </div>

    <div class="body">

        {{-- ── Greeting ── --}}
        <p class="greeting">
            Dear <strong>{{ $application->user->first_name ?? $application->full_name }}</strong>,<br>
            We are pleased to inform you that your registration application submitted to the
            Nigeria Immigration Service has been reviewed and <strong>approved</strong>.
        </p>

        {{-- ── Application reference card ── --}}
        <div class="ref-card">
            <div class="ref-label">Application Reference</div>
            <div class="ref-number">{{ $application->application_reference }}</div>
        </div>

        {{-- ── 30-day extension notice ── --}}
        <div class="extension-notice">
            <div class="notice-title">
                <span class="notice-icon">!</span> Important Notice – 30-Day Extension
            </div>
            <p>
                You are hereby granted a <strong>30-day stay extension</strong> commencing from your
                approval date (<strong>{{ \Carbon\Carbon::parse($approvedAt)->format('d M Y') }}</strong>)
                and valid until <strong>{{ $validTill->format('d M Y') }}</strong>.
                Please use this period to regularise your stay with the relevant immigration authority.
                Failure to do so within the validity period may result in further immigration action.
            </p>
        </div>

        {{-- ── Printable approval slip card ── --}}
        <div class="slip-card">

            <div class="slip-card-header">
                <span class="slip-title">Approval Slip</span>
                <span class="slip-badge">&#10003; APPROVED</span>
            </div>

            {{-- Applicant identity row with photo --}}
            <div class="identity-row">
                <div class="photo-box">
                    @if($hasPhoto)
                        <img src="{{ $application->passport_photo_url }}" alt="Applicant Photo">
                    @else
                        <div class="photo-placeholder">
                            <span class="icon">&#128100;</span>
                            Passport<br>Photo
                        </div>
                    @endif
                </div>
                <div class="identity-info">
                    <div class="applicant-name">{{ strtoupper($application->full_name) }}</div>
                    <div class="applicant-passport">Passport No: {{ $application->passport_number }}</div>
                    <div class="applicant-passport">Nationality: {{ $application->nationality }}</div>
                    <div style="margin-top:10px;">
                        <span class="visa-pill">{{ $application->visa_category }}</span>
                    </div>
                </div>
            </div>

            {{-- Dates row: Date Applied / Date Approved / Valid Till --}}
            <div class="dates-row">
                <div class="dates-cell">
                    <div class="dc-label">Date Applied</div>
                    <div class="dc-value">{{ \Carbon\Carbon::parse($submittedAt)->format('d M Y') }}</div>
                </div>
                <div class="dates-cell">
                    <div class="dc-label">Date Approved</div>
                    <div class="dc-value">{{ \Carbon\Carbon::parse($approvedAt)->format('d M Y') }}</div>
                </div>
                <div class="dates-cell valid-till">
                    <div class="dc-label">Valid Till</div>
                    <div class="dc-value">{{ $validTill->format('d M Y') }}</div>
                </div>
            </div>

            {{-- Full details table --}}
            <table class="details-table">
                <tr>
                    <td>Full Name</td>
                    <td>{{ $application->full_name }}</td>
                </tr>
                <tr>
                    <td>Passport Number</td>
                    <td>{{ $application->passport_number }}</td>
                </tr>
                <tr>
                    <td>Nationality</td>
                    <td>{{ $application->nationality }}</td>
                </tr>
                <tr>
                    <td>Visa Category</td>
                    <td>{{ $application->visa_category }}</td>
                </tr>
                <tr>
                    <td>Arrival Date</td>
                    <td>{{ $application->arrival_date?->format('d M Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Address in Nigeria</td>
                    <td>{{ implode(', ', array_filter([$application->address, $application->city, $application->state])) }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><strong style="color:#1a7f3c;">&#10003; Approved</strong></td>
                </tr>
                @if($application->reviewer_comment)
                <tr>
                    <td>Officer's Note</td>
                    <td>{{ $application->reviewer_comment }}</td>
                </tr>
                @endif
                <tr>
                    <td>Ack. Reference</td>
                    <td style="font-family:'Courier New',monospace; font-weight:bold;">{{ $application->ack_ref_number }}</td>
                </tr>
            </table>

        </div>{{-- end slip-card --}}

        {{-- ── CTA ── --}}
        <div class="btn-wrap">
            <a href="{{ route('applications.acknowledgement', $application) }}" class="btn">
                Download &amp; Print Approval Slip
            </a>
        </div>

        {{-- ── Info notice ── --}}
        <div class="info-notice">
            Log in to the portal to download your official printable approval slip. Carry a printed copy when
            required to present documentation to immigration or law enforcement officers during the validity period.
        </div>

    </div>{{-- end body --}}

    {{-- ── Footer ── --}}
    <div class="footer">
        <strong>Nigeria Immigration Service</strong><br>
        This is an official system-generated communication. Do not reply to this email.<br>
        &copy; {{ date('Y') }} Nigeria Immigration Service. All rights reserved.
    </div>

</div>
</body>
</html>
