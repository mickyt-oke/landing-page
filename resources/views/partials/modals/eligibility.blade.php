<div class="modal" id="eligibilityModal" role="dialog" aria-modal="true" aria-labelledby="eligibilityModalTitle">
  <div class="modal-container">
    <button class="close-modal" aria-label="Close eligibility modal">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <div class="modal-header">
      <div class="modal-logo">
        <img src="{{ asset('assets/images/nis-logo-white.png') }}" alt="NIS Logo" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </div>
      <h2 id="eligibilityModalTitle">Eligibility Criteria</h2>
      <p>Ensure you meet all requirements before applying</p>
    </div>

    <div class="modal-body">
      <div class="eligibility-grid">

        <div class="eligibility-item">
          <div class="eligibility-item-icon">
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
          </div>
          <div class="eligibility-item-text">
            <strong>Presence in Nigeria</strong>
            <span>You must currently be in Nigeria and have entered due to displacement from the Middle-East crisis.</span>
          </div>
        </div>

        <div class="eligibility-item">
          <div class="eligibility-item-icon">
            <i class="fas fa-passport" aria-hidden="true"></i>
          </div>
          <div class="eligibility-item-text">
            <strong>Valid Travel Documents</strong>
            <span>You must possess a valid international passport (ordinary, diplomatic, service, or UN laissez-passer).</span>
          </div>
        </div>

        <div class="eligibility-item">
          <div class="eligibility-item-icon">
            <i class="fas fa-shield-alt" aria-hidden="true"></i>
          </div>
          <div class="eligibility-item-text">
            <strong>No Prior Immigration Violations</strong>
            <span>Applicants must not have a recorded history of immigration violations or criminal offences in Nigeria.</span>
          </div>
        </div>

        <div class="eligibility-item">
          <div class="eligibility-item-icon">
            <i class="fas fa-check-double" aria-hidden="true"></i>
          </div>
          <div class="eligibility-item-text">
            <strong>Accurate &amp; Complete Information</strong>
            <span>All information provided must be truthful and verifiable. Misrepresentation may lead to rejection.</span>
          </div>
        </div>

      </div>

      <div class="eligibility-notice">
        <i class="fas fa-info-circle" aria-hidden="true"></i>
        <span>Meeting all criteria does not guarantee approval. Each application is reviewed individually by NIS officials.</span>
      </div>

      <div class="eligibility-cta">
        <a href="#" class="modal-btn modal-switch-trigger" data-from="eligibility" data-to="register"
           style="text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; gap: .5rem;">
          <i class="fas fa-user-plus" aria-hidden="true"></i> Proceed to Register
        </a>
        <button class="close-modal btn-outline-secondary">
          X
        </button>
      </div>
    </div>
  </div>
</div>
