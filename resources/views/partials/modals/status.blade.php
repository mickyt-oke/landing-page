<div class="modal" id="checkStatusModal" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle">
  <div class="modal-container">
    <button class="close-modal" aria-label="Close status check modal">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <div class="modal-header">
      <div class="modal-logo">
        <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </div>
      <h2 id="statusModalTitle">Check Application Status</h2>
      <p>Enter your reference number to track your application</p>
    </div>

    <div class="modal-body">
      <form id="checkStatusForm" novalidate>
        @csrf

        <div class="form-group">
          <label for="statusPassport">Passport Number</label>
          <div class="input-wrapper">
            <i class="fas fa-passport" aria-hidden="true"></i>
            <input type="text" id="statusPassport" name="passport_number"
                   placeholder="Enter your passport number"
                   autocomplete="off"
                   inputmode="text">
          </div>
        </div>

        <div class="status-divider">
          <span>OR</span>
        </div>

        <div class="form-group">
          <label for="statusReference">Reference Number</label>
          <div class="input-wrapper">
            <i class="fas fa-hashtag" aria-hidden="true"></i>
            <input type="text" id="statusReference" name="reference"
                   placeholder="e.g. NIS-2026-XXXXX"
                   autocomplete="off"
                   inputmode="text">
          </div>
          <small style="color: var(--gray); font-size: 0.8rem; margin-top: 0.4rem; display: block;">
            Your reference number was sent to your email upon registration.
          </small>
        </div>

        <button type="submit" class="modal-btn" id="checkStatusSubmitBtn">
          <i class="fas fa-search" aria-hidden="true"></i> Check Status
        </button>
      </form>

      <!-- Result area — shown after successful lookup -->
      <div id="statusResult" class="status-result" style="display: none;" role="region" aria-live="polite" aria-label="Application status result">
        <div class="status-result-header">
          <span class="status-result-title">Application Details</span>
          <span class="status-badge" id="statusBadge"></span>
        </div>
        <div id="statusResultRows"></div>
      </div>
    </div>
  </div>
</div>
