<div class="modal" id="registerModal" role="dialog" aria-modal="true" aria-labelledby="registerModalTitle">
  <div class="modal-container">
    <button class="close-modal" aria-label="Close registration modal">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <div class="modal-header">
      <div class="modal-logo">
        <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </div>
      <h2 id="registerModalTitle">Create Account</h2>
      <p>Fill in your details to register</p>
    </div>

    <div class="auth-tabs" role="tablist">
      <button class="auth-tab modal-switch-trigger" role="tab" aria-selected="false"
              data-from="register" data-to="login">
        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
      </button>
      <button class="auth-tab active" role="tab" aria-selected="true">
        <i class="fas fa-user-plus" aria-hidden="true"></i> Register
      </button>
    </div>

    @if (session('error'))
      <div class="modal-alert modal-alert-error" role="alert">
        {{ session('error') }}
      </div>
    @endif

    @if (session('success'))
      <div class="modal-alert modal-alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif

    <div class="modal-body">
      <form action="{{ route('register') }}" method="POST" id="registerUserForm">
        @csrf

        <div class="form-group">
          <label for="regSname">Surname</label>
          <div class="input-wrapper">
            <i class="fas fa-user" aria-hidden="true"></i>
            <input type="text" id="regSname" name="sname"
                   placeholder="Enter surname"
                   required autocomplete="family-name"
                   value="{{ old('sname') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="regFname">Given Names</label>
          <div class="input-wrapper">
            <i class="fas fa-user" aria-hidden="true"></i>
            <input type="text" id="regFname" name="fname"
                   placeholder="Firstname Othernames"
                   required autocomplete="given-name"
                   value="{{ old('fname') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="regPptno">Passport Number</label>
          <div class="input-wrapper">
            <i class="fas fa-passport" aria-hidden="true"></i>
            <input type="text" id="regPptno" name="pptno"
                   placeholder="Enter passport number"
                   required autocomplete="off"
                   value="{{ old('pptno') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="regPpttype">Type of Passport</label>
          <div class="input-wrapper">
            <i class="fas fa-id-card" aria-hidden="true"></i>
            <select id="regPpttype" name="ppttype" required>
              <option value="" disabled selected>Select passport type</option>
              <option value="ordinary"   {{ old('ppttype') === 'ordinary'    ? 'selected' : '' }}>Ordinary / Standard</option>
              <option value="diplomatic" {{ old('ppttype') === 'diplomatic'  ? 'selected' : '' }}>Diplomatic</option>
              <option value="service"    {{ old('ppttype') === 'service'     ? 'selected' : '' }}>Service / Official</option>
              <option value="UN_Official"{{ old('ppttype') === 'UN_Official' ? 'selected' : '' }}>UN Laissez-Passer</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="regNationality">Nationality</label>
          <div class="input-wrapper">
            <i class="fas fa-globe" aria-hidden="true"></i>
            <select id="regNationality" name="nationality" required>
              <option value="" disabled selected>Select Nationality</option>
              @foreach ($countries as $country)
                <option value="{{ $country['name'] }}" {{ old('nationality') === $country['name'] ? 'selected' : '' }}>
                  {{ $country['name'] }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="regEmail">Email Address</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope" aria-hidden="true"></i>
            <input type="email" id="regEmail" name="email"
                   placeholder="Enter email"
                   required autocomplete="email"
                   value="{{ old('email') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="regPassword1">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <input type="password" id="regPassword1" name="password"
                   placeholder="Create password (min. 8 characters)"
                   required autocomplete="new-password">
            <button type="button" class="password-toggle-btn"
                    data-input="regPassword1"
                    aria-label="Show password">
              <i class="fas fa-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="regPassword2">Confirm Password</label>
          <div class="input-wrapper">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <input type="password" id="regPassword2" name="password_confirmation"
                   placeholder="Confirm password"
                   required autocomplete="new-password">
            <button type="button" class="password-toggle-btn"
                    data-input="regPassword2"
                    aria-label="Show confirm password">
              <i class="fas fa-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="modal-btn">
          <i class="fas fa-user-plus" aria-hidden="true"></i> Create Account
        </button>

        <div class="modal-footer">
          Already have an account?
          <a href="#" class="modal-switch-trigger" data-from="register" data-to="login">Login here</a>
        </div>
      </form>
    </div>
  </div>
</div>
