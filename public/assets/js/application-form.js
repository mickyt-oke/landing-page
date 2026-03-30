// application-form.js - Handles the multi-step application form

(function() {
  'use strict';

  const TOTAL_STEPS = 4;
  let currentStep = 1;

  // DOM elements
  const form = document.getElementById('registrationForm');
  const stateEl = document.getElementById('state');
  const cityEl = document.getElementById('city');
  const reasonRequestEl = document.getElementById('reasonRequest');
  const reasonOtherTextEl = document.getElementById('reasonOtherText');
  const visaCategoryEl = document.getElementById('visaCategory');
  const svvCategoryEl = document.getElementById('svvCategory');
  const trvCategoryEl = document.getElementById('trvCategory');
  const cancellationProofGroupEl = document.getElementById('cancellationProofGroup');

  // File inputs for preview
  const fileInputs = [
    'passportDataFile',
    'visaFile',
    'stampFile',
    'ticketFile',
    'cancellationProofFile'
  ];

  // Initialize
  document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    loadStatesCities();
    updateProgress();
  });

  function initializeEventListeners() {
    // Step navigation
    document.querySelectorAll('[data-next-step]').forEach(btn => {
      btn.addEventListener('click', handleNextStep);
    });

    document.querySelectorAll('[data-prev-step]').forEach(btn => {
      btn.addEventListener('click', handlePrevStep);
    });

    // Submit button
    const submitBtn = document.querySelector('[data-submit-application]');
    if (submitBtn) {
      submitBtn.addEventListener('click', handleSubmit);
    }

    // Visa category change
    if (visaCategoryEl) {
      visaCategoryEl.addEventListener('change', handleVisaCategoryChange);
    }

    // Reason request change
    if (reasonRequestEl) {
      reasonRequestEl.addEventListener('change', handleReasonChange);
    }

    // State change
    if (stateEl) {
      stateEl.addEventListener('change', handleStateChange);
    }

    // File input changes for preview
    fileInputs.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('change', handleFileChange);
      }
    });
  }

  function handleNextStep(e) {
    e.preventDefault();
    const step = parseInt(this.dataset.nextStep);
    if (validateStep(currentStep)) {
      showStep(step);
    }
  }

  function handlePrevStep(e) {
    e.preventDefault();
    const step = parseInt(this.dataset.prevStep);
    showStep(step);
  }

  function handleSubmit(e) {
    e.preventDefault();
    if (validateStep(3)) {
      form.submit();
    }
  }

  function handleVisaCategoryChange() {
    const category = this.value;
    if (svvCategoryEl) svvCategoryEl.style.display = category === 'SVV' ? 'block' : 'none';
    if (trvCategoryEl) trvCategoryEl.style.display = category === 'TRV' ? 'block' : 'none';
  }

  function handleReasonChange() {
    const reason = this.value;
    if (reasonOtherTextEl) {
      reasonOtherTextEl.style.display = reason === 'Other' ? 'block' : 'none';
    }
    if (cancellationProofGroupEl) {
      cancellationProofGroupEl.style.display = reason === 'Flight Cancellation' ? 'block' : 'none';
    }
  }

  function handleStateChange() {
    updateCityOptions(this.value);
  }

  function handleFileChange(e) {
    const file = e.target.files[0];
    if (file) {
      updateFileName(e.target);
      if (currentStep === 4) {
        updateDocumentPreview(e.target.id, file);
      }
    }
  }

  function updateFileName(input) {
    const wrapper = input.closest('.file-input-wrapper');
    const nameSpan = wrapper.querySelector('.file-name');
    if (nameSpan) {
      nameSpan.textContent = input.files[0] ? input.files[0].name : 'No file chosen';
    }
  }

  function updateDocumentPreview(inputId, file) {
    let previewId;
    switch(inputId) {
      case 'passportDataFile': previewId = 'passportDataPreview'; break;
      case 'visaFile': previewId = 'visaPreview'; break;
      case 'stampFile': previewId = 'stampPreview'; break;
      case 'ticketFile': previewId = 'ticketPreview'; break;
      default: return;
    }
    const previewEl = document.getElementById(previewId);
    if (!previewEl) return;

    previewEl.innerHTML = '';

    if (file.type.startsWith('image/')) {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.style.maxWidth = '150px';
      img.style.maxHeight = '150px';
      img.style.border = '1px solid #ddd';
      img.style.borderRadius = '8px';
      previewEl.appendChild(img);
    } else {
      const icon = document.createElement('i');
      icon.className = 'fas fa-file-alt';
      icon.style.fontSize = '2rem';
      icon.style.color = '#666';
      previewEl.appendChild(icon);
    }
    const name = document.createElement('p');
    name.textContent = file.name;
    name.style.fontSize = '0.8rem';
    name.style.marginTop = '0.5rem';
    previewEl.appendChild(name);
  }

  function loadStatesCities() {
    fetch('/assets/data/states_cities.json')
      .then(response => response.json())
      .then(data => {
        window.statesCities = data;
        populateStates(data);
        const oldState = document.getElementById('oldState')?.value;
        if (oldState && stateEl) {
          stateEl.value = oldState;
          updateCityOptions(oldState);
        }
      })
      .catch(() => {
        console.warn('Could not load states/cities JSON.');
      });
  }

  function populateStates(data) {
    if (!stateEl) return;
    Object.keys(data).sort().forEach(state => {
      const option = document.createElement('option');
      option.value = state;
      option.textContent = state;
      stateEl.appendChild(option);
    });
  }

  function updateCityOptions(state) {
    if (!cityEl) return;
    cityEl.innerHTML = '<option value="">Select city</option>';
    cityEl.disabled = true;

    if (!state || !window.statesCities || !window.statesCities[state]) {
      return;
    }

    window.statesCities[state].forEach(city => {
      const option = document.createElement('option');
      option.value = city;
      option.textContent = city;
      const oldCity = document.getElementById('oldCity')?.value;
      if (city === oldCity) {
        option.selected = true;
      }
      cityEl.appendChild(option);
    });

    cityEl.disabled = false;
  }

  function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= TOTAL_STEPS; i++) {
      const stepEl = document.getElementById('step' + i + 'Form');
      if (stepEl) stepEl.style.display = 'none';
    }

    // Show current step
    const currentStepEl = document.getElementById('step' + step + 'Form');
    if (currentStepEl) currentStepEl.style.display = 'block';

    // Update indicators
    updateStepIndicators(step);
    updateProgress();

    currentStep = step;

    if (step === 4) {
      fillReview();
    }
  }

  function updateStepIndicators(step) {
    for (let i = 1; i <= TOTAL_STEPS; i++) {
      const indicator = document.getElementById('step' + i);
      if (!indicator) continue;

      indicator.classList.remove('active', 'completed');
      if (i < step) {
        indicator.classList.add('completed');
      } else if (i === step) {
        indicator.classList.add('active');
      }
    }
  }

  function updateProgress() {
    const progressEl = document.querySelector('.progress-percentage');
    if (progressEl) {
      const percentage = Math.round(((currentStep - 1) / 3) * 100);
      progressEl.textContent = percentage + '%';
    }

    const indicator = document.querySelector('.step-indicator');
    if (indicator) {
      indicator.setAttribute('data-progress', Math.round(((currentStep - 1) / 3) * 100));
    }
  }

  function fillReview() {
    const getVal = id => document.getElementById(id)?.value || '';
    document.getElementById('reviewSurname').textContent = getVal('surname');
    document.getElementById('reviewFirstName').textContent = getVal('first_name');
    document.getElementById('reviewOtherNames').textContent = getVal('other_names');
    document.getElementById('reviewPassport').textContent = getVal('passport_number');
    document.getElementById('reviewNationality').textContent = getVal('nationality');
    document.getElementById('reviewVisaCategory').textContent = visaCategoryEl?.options[visaCategoryEl.selectedIndex]?.text || '';
    document.getElementById('reviewArrivalDate').textContent = getVal('arrival_date');
    document.getElementById('reviewAddress').textContent = getVal('address');
    document.getElementById('reviewCityState').textContent = getVal('city') + (getVal('city') && getVal('state') ? ', ' : '') + getVal('state');

    // Update document previews
    fileInputs.forEach(id => {
      const input = document.getElementById(id);
      if (input && input.files[0]) {
        updateDocumentPreview(id, input.files[0]);
      }
    });
  }

  function validateStep(step) {
    const container = document.getElementById('step' + step + 'Form');
    if (!container) return true;

    const requiredFields = container.querySelectorAll('[required]');
    for (let field of requiredFields) {
      if (!field.value.trim()) {
        showAlert('Please fill in all required fields');
        field.focus();
        return false;
      }
    }

    // Step-specific validation
    if (step === 2) {
      const arrivalDate = new Date(document.getElementById('arrival_date')?.value);
      if (arrivalDate > new Date()) {
        showAlert('Arrival date cannot be in the future');
        return false;
      }

      const visaCat = visaCategoryEl?.value;
      if (visaCat === 'SVV' && !document.getElementById('svvSubCat')?.value) {
        showAlert('Please choose a sub-category for the short visit visa');
        return false;
      }
      if (visaCat === 'TRV' && !document.getElementById('trvSubCat')?.value) {
        showAlert('Please choose a sub-category for the temporary residence visa');
        return false;
      }
    }

    if (step === 3) {
      const files = ['passportDataFile', 'visaFile', 'stampFile', 'ticketFile'];
      for (let id of files) {
        const el = document.getElementById(id);
        if (!el || !el.files.length) {
          showAlert('Please upload all required documents');
          return false;
        }
      }
    }

    return true;
  }

  function showAlert(message) {
    // Simple alert for now - can be enhanced with toast
    alert(message);
  }
})();