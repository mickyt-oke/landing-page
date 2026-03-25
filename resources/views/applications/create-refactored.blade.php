{{-- 
  User Dashboard - Migrants Overstay Portal
  Nigeria Immigration Service
  Refactored: Pure Blade syntax, form processing in controller
--}}

@include('partials.header')

@if ($errors->any())
  <div class="form-messages error" role="alert">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@elseif (isset($ackRef))
  {{-- Acknowledgement Section --}}
  <style>
    @media print {
      .no-print { display: none !important; }
      .ack-section { max-width: 8.5in; margin: 0 auto; padding: 1rem; font-size: 12pt; }
      body, html { margin: 0; padding: 0; }
    }
    .nis-header { background: linear-gradient(135deg, #003087, #0056b3); color: white; padding: 2rem 1rem; text-align: center; }
    .ref-badge { background: #28a745; color: white; padding: 1rem 2rem; border-radius: 30px; font-weight: bold; font-size: 1.5em; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    .ack-details { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 1rem 0; }
    .ack-details table td:first-child { font-weight: bold; width: 40%; padding-right: 1rem; }
    .next-steps { background: linear-gradient(to right, #f8f9fa, #e9ecef); border-left: 5px solid #28a745; padding: 1.5rem; border-radius: 8px; }
  </style>
  <script>
    window.onload = function() { if (window.matchMedia && window.matchMedia('(print)').matches) return; };
    window.onafterprint = function() { location.reload(); };
  </script>
  <div class="ack-section p-4">
    <div class="no-print text-center mb-4">
      <button onclick="window.print()" style="background: #28a745; color: white; border: none; padding: 1rem 2.5rem; font-size: 1.2em; border-radius: 30px; cursor: pointer; box-shadow: 0 4px 12px rgba(40,167,69,0.3);">
        <i class="fas fa-print"></i> Print This Acknowledgement
      </button>
    </div>
    <div class="nis-header mb-4">
      <img src="{{ asset('assets/images/nis-logo.png') }}" alt="NIS Logo" style="height: 80px; margin-bottom: 1rem;">
      <h1 style="font-size: 2.2em; margin: 0;">Nigeria Immigration Service</h1>
      <p style="font-size: 1.3em; opacity: 0.9;">Foreigners Registration &amp; Overstay Clearance Portal</p>
    </div>
    <div class="ack-details">
      <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="color: #003087; font-size: 2.8em; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">APPLICATION ACKNOWLEDGEMENT</h1>
        <div class="ref-badge mb-3" style="font-size: 1.6em; padding: 1.2rem 2.5rem;">REFERENCE NUMBER: {{ $ackRef }}</div>
        <p style="font-size: 1.4em; color: #333; margin: 0;">Submission Date: <strong>{{ $submittedDate }}</strong></p>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 2.5rem;">
        <div>
          <h3 style="color: #003087; border-bottom: 3px solid #003087; padding-bottom: 0.8rem; margin-bottom: 1.5rem;">Applicant Information</h3>
          <table class="ack-details">
            <tr><td>Full Name:</td><td>{{ $applicantName }}</td></tr>
            <tr><td>Passport Number:</td><td>{{ $passportNo }}</td></tr>
            <tr><td>Nationality:</td><td>{{ $nationality }}</td></tr>
            <tr><td>Visa Category:</td><td>{{ $visaCategory }}</td></tr>
          </table>
        </div>
        <div>
          <h3 style="color: #003087; border-bottom: 3px solid #003087; padding-bottom: 0.8rem; margin-bottom: 1.5rem;">Application Details</h3>
          <table class="ack-details">
            <tr><td>Arrival Date:</td><td>{{ $arrivalDate }}</td></tr>
            <tr><td>Current Status:</td><td style="color: #28a745; font-weight: bold;">Submitted - Pending Review</td></tr>
            <tr><td>Application Type:</td><td>Middle East Temporary Program</td></tr>
          </table>
        </div>
      </div>
      <div class="next-steps">
        <h4 style="margin-top: 0; color: #28a745;">📋 Next Steps</h4>
        <ul style="font-size: 1.1em; line-height: 1.7;">
          <li><strong>✅</strong> Your application has been successfully received.</li>
          <li><strong>🔍</strong> Login to your dashboard to track real-time status.</li>
          <li><strong>⏱️</strong> Expected processing time: 5-10 working days.</li>
          <li><strong>📧</strong> Confirmation email sent (check spam).</li>
          <li><strong>❓</strong> No update in 14 days? Contact support@immigration.gov.ng</li>
        </ul>
      </div>
      <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 2px dashed #ddd;">
        <p><em>Computer-generated acknowledgement. Keep for your records.</em></p>
        <p>Nigeria Immigration Service © {{ now()->year }}</p>
      </div>
    </div>
  </div>
@else
  {{-- Registration Form --}}
  <div class="dashboard-content">
    <div class="welcome-section">
      <div class="welcome-content">
        <h2>New Application</h2>
        <p>Complete your profile and upload your documents</p>
      </div>
    </div>

    <div class="col-md-12 col-lg-10 mx-auto mb-5">
      <div class="step-progress">
        Progress: <span class="progress-percentage">0%</span> completed
      </div>

      <div class="step-indicator" data-progress="0">
        <div class="step active" id="step1" data-step="Biodata">
          <div class="step-number">1</div>
          <div class="step-text">Biodata</div>
        </div>
        <div class="step" id="step2" data-step="Travel Info">
          <div class="step-number">2</div>
          <div class="step-text">Travel Info</div>
        </div>
        <div class="step" id="step3" data-step="Documents">
          <div class="step-number">3</div>
          <div class="step-text">Documents</div>
        </div>
        <div class="step" id="step4" data-step="Review">
          <div class="step-number">4</div>
          <div class="step-text">Review</div>
        </div>
      </div>

      <div class="modal-body">
        <form id="registrationForm" method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data" novalidate>
          @csrf

          {{-- STEP 1: BIODATA --}}
          <div id="step1Form" style="display: block;">
            <div class="form-group">
              <label for="surname">Surname</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="surname" name="surname" placeholder="Enter surname" required value="{{ old('surname') }}">
              </div>
              @error('surname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="first_name">First Name</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required value="{{ old('first_name') }}">
              </div>
              @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="other_names">Other Names (Optional)</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="other_names" name="other_names" placeholder="Enter other names" value="{{ old('other_names') }}">
              </div>
            </div>

            <div class="form-group">
              <label for="passport_number">Passport Number</label>
              <div class="input-wrapper">
                <i class="fas fa-passport"></i>
                <input type="text" id="passport_number" name="passport_number" placeholder="Enter passport number" required value="{{ old('passport_number') }}">
              </div>
              @error('passport_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="nationality">Nationality</label>
              <div class="input-wrapper">
                <i class="fas fa-flag"></i>
                <select id="nationality" name="nationality" required>
                  <option value="">Select nationality</option>
                  @foreach ($nationalities as $nat)
                    <option value="{{ $nat }}" {{ old('nationality') === $nat ? 'selected' : '' }}>{{ $nat }}</option>
                  @endforeach
                </select>
              </div>
              @error('nationality') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="passport_expiry">Passport Expiry Date</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar"></i>
                <input type="date" id="passport_expiry" name="passport_expiry" required value="{{ old('passport_expiry') }}">
              </div>
              @error('passport_expiry') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="button" class="modal-btn" data-next-step="1">Next <i class="fas fa-arrow-right"></i></button>
          </div>

          {{-- STEP 2: TRAVEL INFORMATION --}}
          <div id="step2Form" style="display: none;">
            <div class="form-group">
              <label for="visa_category">Visa Category</label>
              <div class="input-wrapper">
                <i class="fas fa-visa"></i>
                <select id="visa_category" name="visa_category" required>
                  <option value="">Select Visa Category</option>
                  <option value="SVV" {{ old('visa_category') === 'SVV' ? 'selected' : '' }}>Short Visit Visa (SVV)</option>
                  <option value="TRV" {{ old('visa_category') === 'TRV' ? 'selected' : '' }}>Temporary Residence Visa (TRV)</option>
                </select>
              </div>
              @error('visa_category') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" id="svvCategory" style="display: none;">
              <label for="svv_subcategory">SVV Sub-category</label>
              <div class="input-wrapper">
                <i class="fas fa-list"></i>
                <select id="svv_subcategory" name="svv_subcategory">
                  <option value="">Select Type</option>
                  <option value="F4A" {{ old('svv_subcategory') === 'F4A' ? 'selected' : '' }}>F4A</option>
                  <option value="F4B" {{ old('svv_subcategory') === 'F4B' ? 'selected' : '' }}>F4B</option>
                  <option value="F5A" {{ old('svv_subcategory') === 'F5A' ? 'selected' : '' }}>F5A</option>
                  <option value="F6A" {{ old('svv_subcategory') === 'F6A' ? 'selected' : '' }}>F6A</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="trvCategory" style="display: none;">
              <label for="trv_subcategory">TRV Sub-category</label>
              <div class="input-wrapper">
                <i class="fas fa-list"></i>
                <select id="trv_subcategory" name="trv_subcategory">
                  <option value="">Select Type</option>
                  <option value="R2A" {{ old('trv_subcategory') === 'R2A' ? 'selected' : '' }}>R2A</option>
                  <option value="R5A" {{ old('trv_subcategory') === 'R5A' ? 'selected' : '' }}>R5A</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="arrival_date">Arrival Date</label>
              <div class="input-wrapper">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" id="arrival_date" name="arrival_date" required value="{{ old('arrival_date') }}">
              </div>
              @error('arrival_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="address_nigeria">Address in Nigeria</label>
              <div class="input-wrapper">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="address_nigeria" name="address_nigeria" placeholder="Street address" required value="{{ old('address_nigeria') }}">
              </div>
              @error('address_nigeria') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="city">City</label>
              <div class="input-wrapper">
                <i class="fas fa-city"></i>
                <input type="text" id="city" name="city" placeholder="City" required value="{{ old('city') }}">
              </div>
              @error('city') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="state">State</label>
              <div class="input-wrapper">
                <i class="fas fa-map"></i>
                <input type="text" id="state" name="state" placeholder="State" required value="{{ old('state') }}">
              </div>
              @error('state') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="reason_request">Reason for Request</label>
              <div class="input-wrapper">
                <i class="fas fa-pen"></i>
                <textarea id="reason_request" name="reason_request" rows="3" placeholder="Explain your reason" required>{{ old('reason_request') }}</textarea>
              </div>
              @error('reason_request') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
              <button type="button" class="modal-btn" data-prev-step="2" style="background: var(--gray);">Previous</button>
              <button type="button" class="modal-btn" data-next-step="2">Next <i class="fas fa-arrow-right"></i></button>
            </div>
          </div>

          {{-- STEP 3: DOCUMENTS --}}
          <div id="step3Form" style="display: none;">
            <div class="form-group">
              <label for="passport_data_file">Passport Data Page</label>
              <div class="file-input-wrapper">
                <input type="file" id="passport_data_file" name="passport_data_file" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only (max 5MB)</small>
              @error('passport_data_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="visa_file">Entry Visa</label>
              <div class="file-input-wrapper">
                <input type="file" id="visa_file" name="visa_file" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only (max 5MB)</small>
              @error('visa_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="stamp_file">Entry Stamp</label>
              <div class="file-input-wrapper">
                <input type="file" id="stamp_file" name="stamp_file" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only (max 5MB)</small>
              @error('stamp_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="ticket_file">Return Ticket</label>
              <div class="file-input-wrapper">
                <input type="file" id="ticket_file" name="ticket_file" accept=".pdf,.jpg,.jpeg,.png" required>
              </div>
              <small style="color: var(--gray);">PDF, JPEG, PNG only (max 5MB)</small>
              @error('ticket_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
              <button type="button" class="modal-btn" data-prev-step="3" style="background: var(--gray);">Previous</button>
              <button type="button" class="modal-btn" data-next-step="3">Next <i class="fas fa-arrow-right"></i></button>
            </div>
          </div>

          {{-- STEP 4: REVIEW & SUBMIT --}}
          <div id="step4Form" style="display: none;">
            <div style="text-align: center; margin-bottom: 2rem;">
              <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--primary);"></i>
              <h3 style="color: var(--secondary); margin: 1rem 0;">Review Your Information</h3>
              <p style="color: var(--gray);">Please review all information before submitting</p>
            </div>

            <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
              <h4 style="color: var(--secondary); margin-bottom: 1rem;">Personal Information</h4>
              <p><strong>Surname:</strong> <span id="reviewSurname"></span></p>
              <p><strong>First Name:</strong> <span id="reviewFirstName"></span></p>
              <p><strong>Other Names:</strong> <span id="reviewOtherNames"></span></p>
              <p><strong>Passport:</strong> <span id="reviewPassport"></span></p>
              <p><strong>Nationality:</strong> <span id="reviewNationality"></span></p>
              <p><strong>Passport Expiry:</strong> <span id="reviewPassportExpiry"></span></p>
            </div>

            <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
              <h4 style="color: var(--secondary); margin-bottom: 1rem;">Travel Information</h4>
              <p><strong>Visa Category:</strong> <span id="reviewVisaCategory"></span></p>
              <p><strong>Arrival Date:</strong> <span id="reviewArrivalDate"></span></p>
              <p><strong>Address:</strong> <span id="reviewAddress"></span></p>
              <p><strong>City/State:</strong> <span id="reviewCityState"></span></p>
            </div>

            <div class="review-section" style="background: var(--light); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
              <h4 style="color: var(--secondary); margin-bottom: 1rem;">Documents</h4>
              <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Passport Data Page</p>
              <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Entry Visa</p>
              <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Entry Stamp</p>
              <p><i class="fas fa-check-circle" style="color: var(--primary);"></i> Return Ticket</p>
            </div>

            <div style="display: flex; gap: 1rem;">
              <button type="button" class="modal-btn" data-prev-step="4" style="background: var(--gray);">Edit</button>
              <button type="submit" class="modal-btn">
                <i class="fas fa-check"></i> Submit Application
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif

@include('partials.footer')
