@include('partials.header')

<div class="dashboard-content" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 520px; width: 100%; margin: 40px auto; padding: 0 16px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">

                <div style="width:64px;height:64px;background:#e3f2fd;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                    <i class="fas fa-envelope-open-text fa-2x" style="color:#003087;"></i>
                </div>

                <h2 style="color:#003087;font-size:1.4rem;margin-bottom:12px;">Verify your email address</h2>

                <p class="text-muted mb-4" style="font-size:0.95rem;line-height:1.7;">
                    Before you can log in, please verify your email address by clicking the link we sent to
                    <strong>{{ auth()->user()?->email ?? session('pending_email', 'your email') }}</strong>.
                    Check your spam folder if you do not see it within a few minutes.
                </p>

                @if(session('resent'))
                    <div class="alert alert-success py-2" role="alert" style="font-size:0.9rem;">
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2" role="alert" style="font-size:0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                    </button>
                </form>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i>Log out
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@include('partials.footer')
