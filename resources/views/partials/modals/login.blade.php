<div class="modal" id="loginModal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
  <div class="modal-container">
    <button class="close-modal" aria-label="Close login modal">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <div class="modal-header">
      <div class="modal-logo">
        <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </div>
      @guest
        <h2 id="loginModalTitle">Welcome Back</h2>
        <p>Sign in to your account</p>
      @else
        <h2 id="loginModalTitle">Already Logged In</h2>
        <p>You have an active session</p>
      @endguest
    </div>

    @guest
    <div class="auth-tabs" role="tablist">
      <button class="auth-tab active" role="tab" aria-selected="true">
        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
      </button>
      <button class="auth-tab modal-switch-trigger" role="tab" aria-selected="false"
              data-from="login" data-to="register">
        <i class="fas fa-user-plus" aria-hidden="true"></i> Register
      </button>
    </div>
    @endguest

    @if ($errors->any())
      <div class="modal-alert modal-alert-error" role="alert">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    @if (session('success'))
      <div class="modal-alert modal-alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif

    <div class="modal-body">
      @guest
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
          <label for="loginEmail">Email Address</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope" aria-hidden="true"></i>
            <input type="email" id="loginEmail" name="email"
                   placeholder="Enter your email"
                   required autocomplete="email"
                   value="{{ old('email') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="loginPassword">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <input type="password" id="loginPassword" name="password"
                   placeholder="Enter your password"
                   required autocomplete="current-password">
            <button type="button" class="password-toggle-btn"
                    data-input="loginPassword"
                    aria-label="Show password">
              <i class="fas fa-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="modal-btn">
          <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
        </button>

        {{-- Password reset/Forgot Password div --}}
        @if (Route::has('password.request'))
          <div class="password-reset">
            <a href="{{ route('password.request') }}" class="password-reset-link">
              <i class="fas fa-unlock-alt" aria-hidden="true"></i> Forgot Password?
            </a>
          </div>
        @endif


        <div class="modal-footer">
          Don't have an account?
          <a href="#" class="modal-switch-trigger" data-from="login" data-to="register">Register here</a>
        </div>
      </form>
      @else
      <div style="text-align: center; padding: 2rem 0;">
        <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem; display: block;" aria-hidden="true"></i>
        <p style="font-size: 1.1em; margin-bottom: 1.5rem;">You are logged in and can access your dashboard.</p>
        <a href="{{ route('dashboard') }}" class="modal-btn" style="display: inline-block; text-decoration: none;">
          <i class="fas fa-tachometer-alt" aria-hidden="true"></i> Go to Dashboard
        </a>
      </div>
      @endguest
    </div>
  </div>
</div>
