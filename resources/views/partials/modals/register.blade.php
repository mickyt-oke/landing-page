<div class="modal" id="registerModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close registration modal">
        <i class="fas fa-times"></i>
      </button>
      
      <div class="modal-header">
        <h2>Create New Account</h2>
        <p></p>
      </div>

      <div class="modal-body mb-0 mx-0">
        <div class="container" style="display: block;">
          <form action="{{ route('register') }}" method="POST" id="registerUserForm">
            @csrf
            <div class="form-group">
              <label for="Surname">Surname</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" name="sname" placeholder="Enter surname" required>
              </div>
            </div>

            <div class="form-group">
              <label for="FirstName">Given Names</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" name="fname" placeholder="Firstname Othernames" required>
              </div>
            </div>

            <div class="form-group">
              <label for="Passport">Passport Number</label>
              <div class="input-wrapper">
                <i class="fas fa-passport"></i>
                <input type="text" name="pptno" placeholder="Enter passport number" required>
              </div>
            </div>

            <div class="form-group">
              <label for="PptType">Type of Passport</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar"></i>
                <select name="ppttype" required>
                  <option value="" disabled selected>Select passport type</option>
                  <option value="ordinary">Ordinary/Standard</option>
                  <option value="diplomatic">Diplomatic</option>
                  <option value="service">Service/Official</option>
                  <option value="UN_Official">UN Laissez Passez</option>
                </select>
              </div>
            </div>
            <!-- nationality list passed from json file in controller -->
             <div class="form-group">
              <label for="Nationality">Nationality</label>
              <div class="input-wrapper">
                <i class="fas fa-globe"></i>
                <select name="nationality" required>
                  <option value="" disabled selected>Select Nationality</option>
                  @foreach ($countries as $country)
                    <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="Email">Email Address</label>
              <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter email" required>
              </div>
            </div>

            <div class="form-group">
              <label for="regPassword1">Password</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="regPassword1" name="password" placeholder="Create password" required>
                <button type="button" class="password-toggle-btn" data-input="regPassword1" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label for="regPassword2">Confirm Password</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="regPassword2" name="password_confirmation" placeholder="Confirm password" required>
                <button type="button" class="password-toggle-btn" data-input="regPassword2" aria-label="Toggle password visibility">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            @if (session('error'))
              <div class="alert alert-error">
                {{ session('error') }}
              </div>
            @endif
            @if (session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif

            <button type="submit" class="modal-btn w-full">Submit</button>
            <div class="modal-footer">
              Already have an account? 
              <a href="#" class="modal-switch-trigger" data-from="register" data-to="login">Login here</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
