<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Acknowledgement</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6f9; font-family: Arial, Helvetica, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #003087, #0056b3); padding: 32px 40px; text-align: center; }
        .header img { height: 56px; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; margin: 6px 0 0; }
        .body { padding: 40px; }
        .body h2 { font-size: 18px; color: #003087; margin: 0 0 16px; }
        .body p { font-size: 15px; line-height: 1.7; margin: 0 0 20px; color: #555; }
        .ref-box { background: #e8f5e9; border: 1px solid #a5d6a7; border-radius: 6px; padding: 20px 24px; margin: 24px 0; text-align: center; }
        .ref-box .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #388e3c; font-weight: bold; margin-bottom: 6px; }
        .ref-box .ref { font-size: 22px; font-weight: bold; color: #1b5e20; letter-spacing: 2px; }
        .details-table { width: 100%; border-collapse: collapse; font-size: 14px; margin: 20px 0; }
        .details-table tr:nth-child(even) td { background: #f8fafc; }
        .details-table td { padding: 10px 12px; border: 1px solid #e2e8f0; }
        .details-table td:first-child { font-weight: bold; color: #003087; width: 40%; }
        .status-badge { display: inline-block; background: #fff3cd; color: #856404; border: 1px solid #ffc107; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .notice { background: #e3f2fd; border-left: 4px solid #1976d2; padding: 14px 18px; border-radius: 4px; font-size: 13px; color: #0d47a1; }
        .footer { background: #f0f4f8; padding: 24px 40px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="{{ asset('assets/images/nis-logo-2.png') }}" alt="NIS Logo">
        <h1>Nigeria Immigration Service</h1>
        <p>Foreigners Registration Portal</p>
    </div>
    <div class="body">
        <h2>Application Received</h2>
        <p>Dear {{ $application->user->first_name ?? $application->full_name }},</p>
        <p>
            Your registration application has been successfully submitted to the Nigeria Immigration Service.
            Please keep your acknowledgement reference number for tracking purposes.
        </p>

        <div class="ref-box">
            <div class="label">Acknowledgement Reference</div>
            <div class="ref">{{ $application->ack_ref_number }}</div>
        </div>

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
                <td>Submitted</td>
                <td>{{ $application->submitted_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td><span class="status-badge">Pending Review</span></td>
            </tr>
        </table>

        <div class="notice">
            Your application is currently under review. You will receive a further email notification once a decision has been made. You may also log in to the portal at any time to check your application status.
        </div>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Nigeria Immigration Service. All rights reserved.<br>
        This is an automated message. Please do not reply directly to this email.
    </div>
</div>
</body>
</html>
