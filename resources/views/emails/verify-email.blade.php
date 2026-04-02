<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6f9; font-family: Arial, Helvetica, sans-serif; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #003087, #0056b3); padding: 32px 40px; text-align: center; }
        .header img { height: 56px; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; letter-spacing: 0.5px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; margin: 6px 0 0; }
        .body { padding: 40px; }
        .body h2 { font-size: 18px; color: #003087; margin: 0 0 16px; }
        .body p { font-size: 15px; line-height: 1.7; margin: 0 0 20px; color: #555; }
        .btn-wrap { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; background: #003087; color: #ffffff !important; text-decoration: none; padding: 14px 36px; border-radius: 6px; font-size: 15px; font-weight: bold; letter-spacing: 0.3px; }
        .notice { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 14px 18px; border-radius: 4px; font-size: 13px; color: #7a5c00; margin: 24px 0 0; }
        .fallback { font-size: 12px; color: #888; word-break: break-all; margin-top: 12px; }
        .footer { background: #f0f4f8; padding: 24px 40px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e2e8f0; }
        .footer a { color: #0056b3; text-decoration: none; }
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
        <h2>Confirm your email address</h2>
        <p>Dear {{ $user->first_name ?? $user->name }},</p>
        <p>
            Thank you for registering on the Nigeria Immigration Service Foreigners Registration Portal.
            Please verify your email address to activate your account and proceed with your application.
        </p>
        <div class="btn-wrap">
            <a href="{{ $verificationUrl }}" class="btn">Verify Email Address</a>
        </div>
        <div class="notice">
            <strong>This link expires in 60 minutes.</strong> If you did not create an account, no action is required.
        </div>
        <p class="fallback">
            If the button above does not work, copy and paste this link into your browser:<br>
            {{ $verificationUrl }}
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Nigeria Immigration Service. All rights reserved.<br>
        This is an automated message. Please do not reply directly to this email.
    </div>
</div>
</body>
</html>
