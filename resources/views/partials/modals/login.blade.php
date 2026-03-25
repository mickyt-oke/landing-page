<div class="modal" id="loginModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close login modal">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        @guest
          <h2>Welcome Back</h2>
          <p>Login to your Account</p>
        @else
          <h2>Already Logged In</h2>
          <p>You have an active session</p>
        @endguest
      </div>
      <!-- PHP error and success message handling -->
      @if ($errors->any())
        <div class="alert alert-error">
          @foreach ($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">
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
              <i class="fas fa-envelope"></i>
              <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required autocomplete="email">
            </div>
          </div>

          <div class="form-group">
            <label for="loginPassword">Password</label>
            <div class="input-wrapper">
              
              <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required autocomplete="current-password">
              <button type="button" class="password-toggle-btn" data-input="loginPassword" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="modal-btn">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>

          <div class="modal-footer">
            Don't have an account? <a href="#" class="modal-switch-trigger" data-from="login" data-to="register">Register here</a>
          </div>
        </form>
        @else
        <div style="text-align: center; padding: 2rem 0;">
          <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem; display: block;"></i>
          <p style="font-size: 1.1em; margin-bottom: 1.5rem;">You are logged in and can access your dashboard.</p>
          <a href="{{ route('dashboard') }}" class="modal-btn" style="display: inline-block;">
            <i class="fas fa-tachometer-alt"></i> Go to Dashboard
          </a>
        </div>
        @endguest
    </div>
  </div>
