<div class="modal" id="checkStatusModal">
    <div class="modal-container">
      <button class="close-modal" aria-label="Close check status modal">
        <i class="fas fa-times"></i>
      </button>

      <div class="modal-header">
        <div class="modal-logo">
          <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/80x80?text=NIS'">
        </div>
        <h2>Check Application Status</h2>
        <p>Enter your passport number or reference number to check the status of your application</p>
      </div>

      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="passportNumber">Passport Number</label>
            <input type="text" id="passportNumber" class="form-control" placeholder="Enter your passport number">
          </div>
          <div class="form-group">
            <label for="referenceNumber">Reference Number</label>
            <input type="text" id="referenceNumber" class="form-control" placeholder="Enter your reference number">
          </div>
          <button type="submit" class="btn btn-primary">Check Status</button>
        </form>
      </div>
    </div>
  </div>
