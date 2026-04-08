@include('partials.header')

<div class="dashboard-content" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 520px; width: 100%; margin: 40px auto; padding: 0 16px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">

                <div style="width:64px;height:64px;background:#e3f2fd;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                    <i class="fas fa-unlock-alt fa-2x" style="color:#003087;"></i>
                </div>

                <h2 style="color:#003087;font-size:1.4rem;margin-bottom:12px;">Reset Your Password</h2>
                <p class="text-muted mb-4" style="font-size:0.95rem;line-height:1.7;">
                    Enter your registered email address and we will send you a link to reset your password.
                </p>

                @if (session('status'))
                    <div class="alert alert-success py-2" role="alert" style="font-size:0.9rem;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2" role="alert" style="font-size:0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="text-start">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="Enter your registered email"
                               required autocomplete="email">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                </form>

                <hr class="my-4">

                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>

            </div>
        </div>
    </div>
</div>

@include('partials.footer')
