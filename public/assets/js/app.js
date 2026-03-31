/**
 * Foreigners Registration - Main Application Script
 * Handles modals, forms, registration steps, and navigation
 */

(function() {
  'use strict';

  // ===== CONSTANTS / STATE =====
  const TOTAL_STEPS = 4;
  let currentStep = 1;

  // ===== DOM REFERENCES =====
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  const overlay = document.getElementById('overlay');
  const loginModal = document.getElementById('loginModal');
  const registerModal = document.getElementById('registerModal');
  const loginForm = document.getElementById('loginForm');
  const visaCategory = document.getElementById('visaCategory');
  const svvCategory = document.getElementById('svvCategory');
  const trvCategory = document.getElementById('trvCategory');

  // ===== INITIALIZATION =====
  // Handle broken images via capture-phase delegation (runs before DOMContentLoaded)
  document.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG' && e.target.hasAttribute('data-img-fallback')) {
      e.target.style.display = 'none';
    }
  }, true);

  document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initBanners();
    initFlashMeta();
  });

  // ===== EVENT LISTENERS =====
  function initializeEventListeners() {
    // Mobile menu toggle
    if (hamburger) {
      hamburger.addEventListener('click', toggleMenu);
    }
    
    if (overlay) {
      overlay.addEventListener('click', toggleMenu);
    }

    // Modal triggers
    document.querySelectorAll('.modal-trigger').forEach(el => {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        const modalName = this.dataset.modal;
        if (modalName) {
          openModal(modalName);
        }
      });
    });

    // Modal switch triggers
    document.querySelectorAll('.modal-switch-trigger').forEach(el => {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        const fromModal = this.dataset.from;
        const toModal = this.dataset.to;
        if (fromModal && toModal) {
          switchModal(fromModal, toModal);
        }
      });
    });

    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(el => {
      el.addEventListener('click', function() {
        const modal = this.closest('.modal');
        if (modal) {
          closeModalElement(modal);
        }
      });
    });

    // Password toggle buttons
    document.querySelectorAll('.password-toggle-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const inputId = this.dataset.input;
        if (inputId) {
          togglePassword(inputId, this);
        }
      });
    });

    // Login form submission to use authentication API
    if (loginForm) {
      loginForm.addEventListener('submit', handleLogin);
    }



    // Registration step navigation
    document.querySelectorAll('[data-next-step]').forEach(el => {
      el.addEventListener('click', function() {
        const step = parseInt(this.dataset.nextStep, 10);
        if (!isNaN(step)) {
          nextStep(step);
        }
      });
    });

    document.querySelectorAll('[data-prev-step]').forEach(el => {
      el.addEventListener('click', function() {
        const step = parseInt(this.dataset.prevStep, 10);
        if (!isNaN(step)) {
          prevStep(step);
        }
      });
    });

    // Submit application button on review step
    const submitBtn = document.getElementById('submitApplicationBtn');
    if (submitBtn) {
      submitBtn.addEventListener('click', function() {
        submitApplication();
      });
    }
  
    // Check status modal and button
    const checkStatusBtn = document.getElementById('checkStatusBtn');
    if (checkStatusBtn) {
      checkStatusBtn.addEventListener('click', function() {
        openModal('checkStatus');
      });
    }



    // Visa category change
    if (visaCategory) {
      visaCategory.addEventListener('change', showVisaSubCategory);
    }

    // Close modal on outside click
    document.addEventListener('click', function(e) {
      if (e.target.classList && e.target.classList.contains('modal')) {
        closeModalElement(e.target);
      }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });

    // Completed step click navigation (delegated; avoids duplicate listeners)
    const stepIndicator = document.querySelector('.step-indicator');
    if (stepIndicator) {
      stepIndicator.addEventListener('click', function(e) {
        const stepEl = e.target.closest('.step.completed');
        if (!stepEl) return;

        const stepNum = parseInt(stepEl.id.replace('step', ''), 10);
        if (!isNaN(stepNum) && stepNum < currentStep) {
          showStep(stepNum);
          showAlert('Navigated to step ' + stepNum, 'success');
        }
      });
    }

    // Header sticky shadow (throttled with requestAnimationFrame)
    let headerTicking = false;
    window.addEventListener('scroll', function() {
      if (headerTicking) return;
      headerTicking = true;
      requestAnimationFrame(() => {
        updateHeaderShadow();
        headerTicking = false;
      });
    });
  }

  // ===== MOBILE MENU =====
  function toggleMenu() {
    if (!hamburger || !mobileMenu) return;

    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('active');
    
    if (overlay) {
      overlay.classList.toggle('active');
    }

    const spans = hamburger.querySelectorAll('span');
    if (hamburger.classList.contains('active')) {
      spans[0].style.transform = 'rotate(45deg) translate(8px, 8px)';
      spans[1].style.opacity = '0';
      spans[2].style.transform = 'rotate(-45deg) translate(8px, -8px)';
    } else {
      spans[0].style.transform = 'none';
      spans[1].style.opacity = '1';
      spans[2].style.transform = 'none';
    }
  }

  function closeMenu() {
    if (!hamburger || !mobileMenu) return;
    
    if (hamburger.classList.contains('active')) {
      toggleMenu();
    }
  }

  // ===== MODAL MANAGEMENT =====
  function openModal(type) {
    const modal = document.getElementById(type + 'Modal');
    if (!modal) return;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    closeMenu();

    // Reset registration to step 1 when opening
    if (type === 'register') {
      currentStep = 1;
      showStep(1);
    }
  }

  function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
      closeModalElement(modal);
    });
  }

  function closeModalElement(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';

    if (modal.id === 'registerModal') {
      currentStep = 1;
      showStep(1);
    }
  }

  function switchModal(fromType, toType) {
    const fromModal = document.getElementById(fromType + 'Modal');
    const toModal   = document.getElementById(toType   + 'Modal');

    if (!fromModal || !toModal) {
      if (fromModal) closeModalElement(fromModal);
      openModal(toType);
      return;
    }

    const fromContainer = fromModal.querySelector('.modal-container');
    const toContainer   = toModal.querySelector('.modal-container');

    // login → register slides left; register → login slides right
    const slideOut = (fromType === 'login') ? 'slide-out-left'  : 'slide-out-right';
    const slideIn  = (fromType === 'login') ? 'slide-in-right'  : 'slide-in-left';

    // Show destination with a transparent backdrop so there is no flash
    toModal.classList.add('active', 'modal-switching');
    document.body.style.overflow = 'hidden';
    if (toType === 'register') { currentStep = 1; showStep(1); }

    if (fromContainer) fromContainer.classList.add(slideOut);
    if (toContainer)   toContainer.classList.add(slideIn);

    setTimeout(function() {
      fromModal.classList.remove('active');
      if (fromContainer) fromContainer.classList.remove(slideOut);

      // Restore backdrop on destination modal
      toModal.classList.remove('modal-switching');

      setTimeout(function() {
        if (toContainer) toContainer.classList.remove(slideIn);
      }, 280);
    }, 220);
  }

  // ===== PASSWORD VISIBILITY TOGGLE =====
  function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const icon = button.querySelector('i');
    if (!icon) return;

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
      button.setAttribute('aria-label', 'Hide password');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
      button.setAttribute('aria-label', 'Show password');
    }
  }

  // ===== LOGIN HANDLING =====
  async function handleLogin(e) {
    e.preventDefault();

    const emailEl = document.getElementById('loginEmail');
    const passwordEl = document.getElementById('loginPassword');

    if (!emailEl || !passwordEl) return;

    const email = emailEl.value.trim();
    const password = passwordEl.value.trim();

    if (!email || !password) {
      showAlert('Please fill in all fields');
      return;
    }

    // Validate email format
    if (!isValidEmail(email)) {
      showAlert('Please enter a valid email address');
      return;
    }

    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        showAlert(data.message || data.error || 'Login failed');
        return;
      }

      if (data.access_token) {
        localStorage.setItem('access_token', data.access_token);
        showAlert(data.message || 'Login successful', 'success');

        loginForm?.reset();
        if (loginModal) closeModalElement(loginModal);

        if (data.route) {
          window.location.href = data.route;
          return;
        }

        window.location.href = '/dashboard';
        return;
      }

      showAlert(data.message || 'Login failed');
    } catch (error) {
      console.error('Error during login:', error);
      showAlert('An error occurred. Please try again.');
    }
  }

  // ===== REGISTRATION STEPS =====
  function showStep(step) {
    // Hide all forms
    for (let i = 1; i <= TOTAL_STEPS; i++) {
      const form = document.getElementById('step' + i + 'Form');
      if (form) {
        form.style.display = i === step ? 'block' : 'none';
      }
    }

    // Update step indicators
    for (let i = 1; i <= TOTAL_STEPS; i++) {
      const stepEl = document.getElementById('step' + i);
      if (!stepEl) continue;

      stepEl.classList.remove('active', 'completed');

      if (i < step) {
        stepEl.classList.add('completed');
      } else if (i === step) {
        stepEl.classList.add('active');
      }
    }

    currentStep = step;

    // Update progress percentage
    updateProgressPercentage(step);

    // Update step indicator progress bar
    updateStepIndicatorProgress(step);

    // Add click handlers to completed steps
    updateCompletedStepHandlers();

    // Smooth scroll to active step on mobile
    scrollToActiveStep(step);

    if (step === TOTAL_STEPS) {
      fillReview();
    }
  }

  // Update progress percentage display
  function updateProgressPercentage(step) {
    const progressEl = document.querySelector('.step-progress .progress-percentage');
    if (progressEl) {
      const percentage = Math.round(((step - 1) / 3) * 100);
      progressEl.textContent = percentage + '%';
    }
  }

  // Update step indicator progress bar
  function updateStepIndicatorProgress(step) {
    const indicator = document.querySelector('.step-indicator');
    if (indicator) {
      const progress = Math.round(((step - 1) / 3) * 100);
      indicator.setAttribute('data-progress', progress);
    }
  }

  // Add click handlers to completed steps for navigation
  function updateCompletedStepHandlers() {
    document.querySelectorAll('.step.completed').forEach(stepEl => {
      stepEl.addEventListener('click', function() {
        const stepNum = parseInt(this.id.replace('step', ''), 10);
        if (!isNaN(stepNum) && stepNum < currentStep) {
          // Navigate to the clicked completed step
          showStep(stepNum);
          
          // Visual feedback
          showAlert('Navigated to step ' + stepNum, 'success');
        }
      });
    });
  }

  // Smooth scroll to active step on mobile
  function scrollToActiveStep(step) {
    // Only scroll on mobile devices (screen width <= 768px)
    if (window.innerWidth <= 768) {
      const activeStepEl = document.getElementById('step' + step);
      const stepIndicator = document.querySelector('.step-indicator');
      
      if (activeStepEl && stepIndicator) {
        // Calculate the scroll position to center the active step
        const indicatorRect = stepIndicator.getBoundingClientRect();
        const stepRect = activeStepEl.getBoundingClientRect();
        
        // Scroll the step indicator into view if needed
        if (stepRect.left < indicatorRect.left || stepRect.right > indicatorRect.right) {
          activeStepEl.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest',
            inline: 'center'
          });
        }
      }

      // Also scroll to the form content
      const formContainer = document.getElementById('step' + step + 'Form');
      if (formContainer) {
        setTimeout(() => {
          formContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start'
          });
        }, 100);
      }
    }
  }

  // populate review step with values from earlier inputs
  function fillReview() {
    const getVal = id => document.getElementById(id)?.value || '';
    document.getElementById('reviewSurname').textContent = getVal('regSurname');
    document.getElementById('reviewFirstName').textContent = getVal('regFirstName');
    document.getElementById('reviewOtherNames').textContent = getVal('regOtherNames');
    document.getElementById('reviewPassport').textContent = getVal('regPassport');
    document.getElementById('reviewNationality').textContent = getVal('regNationality');
    document.getElementById('reviewPassportExpiry').textContent = getVal('regPassportExpiry');

    const visaText = visaCategory.options[visaCategory.selectedIndex]?.text || '';
    document.getElementById('reviewVisaCategory').textContent = visaText;
    document.getElementById('reviewArrivalDate').textContent = getVal('arrivalDate');
    document.getElementById('reviewAddress').textContent = getVal('addressNigeria');
    const city = getVal('city');
    const state = getVal('state');
    document.getElementById('reviewCityState').textContent = city + (city && state ? ', ' : '') + state;
  }

  function nextStep(current) {
    // Validate current step before proceeding
    if (!validateStep(current)) {
      return;
    }

    if (current < 4) {
      showStep(current + 1);
    }
  }

  function prevStep(current) {
    if (current > 1) {
      showStep(current - 1);
    }
  }

  function validateStep(step) {
    const container = document.getElementById('step' + step + 'Form');
    if (!container) return true;

    // Check all required fields within current step container
    const requiredFields = container.querySelectorAll('[required]');
    for (let field of requiredFields) {
      if (!field.value.trim()) {
        field.setAttribute('aria-invalid', 'true');
        showAlert('Please fill in all required fields');
        return false;
      } else {
        field.removeAttribute('aria-invalid');
      }
    }

    // step‑specific validation
    if (step === 1) {
      // Validate email format on step 1
      const emailField = document.getElementById('regEmail');
      if (emailField && !isValidEmail(emailField.value.trim())) {
        emailField.setAttribute('aria-invalid', 'true');
        showAlert('Please enter a valid email address');
        return false;
      }

      // Check password match
      const pwd1 = document.getElementById('regPassword1');
      const pwd2 = document.getElementById('regPassword2');
      if (pwd1 && pwd2 && pwd1.value !== pwd2.value) {
        pwd1.setAttribute('aria-invalid', 'true');
        pwd2.setAttribute('aria-invalid', 'true');
        showAlert('Passwords do not match');
        return false;
      }

      if (pwd1 && pwd1.value.length < 8) {
        pwd1.setAttribute('aria-invalid', 'true');
        showAlert('Password must be at least 8 characters');
        return false;
      }
    }


    // Validate date on step 2
    if (step === 2) {
      const expiryDate = new Date(document.getElementById('regPassportExpiry')?.value);
      const today = new Date();
      if (expiryDate < today) {
        showAlert('Passport expiry date must be in the future');
        return false;
      }

      const arrivalDate = new Date(document.getElementById('arrivalDate')?.value);
      if (arrivalDate > today) {
        showAlert('Arrival date cannot be in the future');
        return false;
      }

      // ensure a sub-category is selected when visa type requires it
      const visaCat = visaCategory.value;
      if (visaCat === 'SVV' && document.getElementById('svvSubCat').value === '') {
        showAlert('Please choose a sub-category for the short visit visa');
        return false;
      }
      if (visaCat === 'TRV' && document.getElementById('trvSubCat').value === '') {
        showAlert('Please choose a sub-category for the temporary residence visa');
        return false;
      }
    }

    return true;
  }

  // ===== VISA SUB-CATEGORY DISPLAY =====
  function showVisaSubCategory() {
    if (!visaCategory) return;

    const category = visaCategory.value;
    
    if (svvCategory) {
      svvCategory.style.display = category === 'SVV' ? 'block' : 'none';
    }
    
    if (trvCategory) {
      trvCategory.style.display = category === 'TRV' ? 'block' : 'none';
    }
  }

  // check file inputs for step 3 (called before actual submission)
  function validateFilesStep() {
    const inputs = ['passportDataFile','visaFile','stampFile','ticketFile'];
    const allowed = ['application/pdf','image/jpeg','image/png'];
    const max = 5 * 1024 * 1024; // 5MB
    for (let id of inputs) {
      const el = document.getElementById(id);
      if (!el || !el.files.length) {
        showAlert('Please upload all required documents');
        return false;
      }
      const file = el.files[0];
      if (!allowed.includes(file.type)) {
        showAlert('File ' + id + ' must be jpeg, png or pdf');
        return false;
      }
      if (file.size > max) {
        showAlert('File ' + id + ' exceeds 5 MB limit');
        return false;
      }
    }
    return true;
  }

  // ===== APPLICATION SUBMISSION =====
  function submitApplication() {
    // before submitting, validate files again
    const fileValid = validateFilesStep();
    if (!fileValid) {
      return;
    }

    // final client‑side check passed; submit native form to trigger PHP validation
    const mainForm = document.getElementById('registrationForm');
    if (mainForm) {
      mainForm.submit();
    }
  }

  // ===== UTILITY FUNCTIONS =====
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function showAlert(message, type = 'error') {
    // If dashboard.js showToast is available, delegate to it for consistent styling
    if (window.Dashboard && typeof window.Dashboard.showToast === 'function') {
      window.Dashboard.showToast(message, type);
      return;
    }

    let container = document.getElementById('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toastContainer';
      container.className = 'toast-container';
      container.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:10000;display:flex;flex-direction:column;gap:0.5rem;';
      document.body.appendChild(container);
    }

    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;

    const iconDiv = document.createElement('div');
    iconDiv.className = 'toast-icon';
    const iconEl = document.createElement('i');
    iconEl.className = 'fas ' + (icons[type] || icons.info);
    iconDiv.appendChild(iconEl);

    const contentDiv = document.createElement('div');
    contentDiv.className = 'toast-content';
    const titleDiv = document.createElement('div');
    titleDiv.className = 'toast-title';
    titleDiv.textContent = type.charAt(0).toUpperCase() + type.slice(1);
    const msgDiv = document.createElement('div');
    msgDiv.className = 'toast-message';
    msgDiv.textContent = message;
    contentDiv.appendChild(titleDiv);
    contentDiv.appendChild(msgDiv);

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'toast-close';
    closeBtn.setAttribute('aria-label', 'Dismiss');
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.addEventListener('click', () => toast.remove());

    toast.appendChild(iconDiv);
    toast.appendChild(contentDiv);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(() => {
      toast.classList.add('fade-out');
      toast.addEventListener('transitionend', () => toast.remove());
    }, 4500);
  }

  function updateHeaderShadow() {
    const header = document.querySelector('.header');
    if (!header) return;

    if (window.scrollY > 50) {
      header.style.boxShadow = '0 5px 30px rgba(0,0,0,0.15)';
    } else {
      header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.05)';
    }
  }

  // INDEX PAGE REGISTER MODAL FOR USERS WHO CLICK REGISTER INSTEAD OF LOGIN
  const registerTrigger = document.getElementById('registerTrigger');
  if (registerTrigger) {
    registerTrigger.addEventListener('click', function(e) {
      e.preventDefault();
      switchModal('login', 'register');
    });
  }

  // INDEX PAGE REGISTER FORM VALIDATION
  const indexRegisterForm = document.getElementById('indexRegisterForm');
  if (indexRegisterForm) {
    indexRegisterForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const emailEl = document.getElementById('indexRegEmail');
      const passwordEl = document.getElementById('indexRegPassword');
      const confirmEl = document.getElementById('indexRegConfirm');

      if (!emailEl || !passwordEl || !confirmEl) return;

      const email = emailEl.value.trim();
      const password = passwordEl.value.trim();
      const confirm = confirmEl.value.trim();

      if (!email || !password || !confirm) {
        showAlert('Please fill in all fields');
        return;
      }

      if (!isValidEmail(email)) {
        showAlert('Please enter a valid email address');
        return;
      }

      if (password.length < 8) {
        showAlert('Password must be at least 8 characters');
        return;
      }

      if (password !== confirm) {
        showAlert('Passwords do not match');
        return;
      }

      // If validation passes, submit the form
      indexRegisterForm.submit();
    });
  } 


  // Check status modal trigger
  const checkStatusBtn = document.getElementById('checkStatusBtn');
  if (checkStatusBtn) {
    checkStatusBtn.addEventListener('click', function() {
      openModal('checkStatus');
    });
  }

  // Check status form handling
  const checkStatusForm = document.getElementById('checkStatusForm');
  if (checkStatusForm) {
    checkStatusForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const reference = document.getElementById('statusReference')?.value.trim();
      const passport  = document.getElementById('statusPassport')?.value.trim();

      if (!reference && !passport) {
        showAlert('Please enter your passport number or reference number');
        return;
      }

      const submitBtn = document.getElementById('checkStatusSubmitBtn');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
      }

      try {
        const response = await fetch('/api/status/check', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ reference: reference || undefined, passport_number: passport || undefined })
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
          showAlert(data.message || data.error || 'No application found for the details provided');
          return;
        }

        if (data.status) {
          renderStatusResult(data);
        } else {
          showAlert('No application found for the details provided');
        }
      } catch (error) {
        console.error('Error checking status:', error);
        showAlert('An error occurred. Please try again.');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.classList.remove('loading');
        }
      }
    });
  }

  function renderStatusResult(data) {
    const resultEl  = document.getElementById('statusResult');
    const badgeEl   = document.getElementById('statusBadge');
    const rowsEl    = document.getElementById('statusResultRows');
    if (!resultEl || !badgeEl || !rowsEl) return;

    const statusMap = {
      pending:  { label: 'Pending',     cls: 'pending',  icon: 'fa-clock' },
      approved: { label: 'Approved',    cls: 'approved', icon: 'fa-check-circle' },
      rejected: { label: 'Rejected',    cls: 'rejected', icon: 'fa-times-circle' },
      review:   { label: 'Under Review',cls: 'review',   icon: 'fa-search' }
    };

    const s = statusMap[data.status.toLowerCase()] || { label: data.status, cls: 'pending', icon: 'fa-info-circle' };

    badgeEl.className = 'status-badge ' + s.cls;
    badgeEl.innerHTML = '<i class="fas ' + s.icon + '" aria-hidden="true"></i> ' + s.label;

    const rows = [
      { label: 'Applicant',   value: data.name       || '—' },
      { label: 'Reference',   value: data.reference  || '—' },
      { label: 'Submitted',   value: data.created_at || '—' },
      { label: 'Last Updated',value: data.updated_at || '—' }
    ];

    rowsEl.innerHTML = rows.map(function(r) {
      return '<div class="status-result-row">'
           + '<span class="status-result-label">' + r.label + '</span>'
           + '<span class="status-result-value">' + r.value + '</span>'
           + '</div>';
    }).join('');

    resultEl.style.display = 'block';
  }

  // ===== WELCOME + CONSENT BANNERS =====
  function initBanners() {
    const welcomeBanner = document.getElementById('welcomeBanner');
    const welcomeClose  = document.getElementById('welcomeClose');

    if (welcomeBanner && welcomeClose) {
      welcomeClose.addEventListener('click', function() {
        welcomeBanner.classList.add('hidden');
        const consentBanner = document.getElementById('consentBanner');
        if (consentBanner && localStorage.getItem('nis_consent') !== 'accepted') {
          consentBanner.classList.remove('hidden');
        }
      });

      welcomeBanner.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') welcomeClose.click();
      });

      welcomeClose.focus();
    }

    const consentBanner  = document.getElementById('consentBanner');
    const consentAccept  = document.getElementById('consentAccept');
    const consentDecline = document.getElementById('consentDecline');

    if (consentAccept) {
      consentAccept.addEventListener('click', function() {
        localStorage.setItem('nis_consent', 'accepted');
        consentBanner.classList.add('hidden');
      });
    }

    if (consentDecline) {
      consentDecline.addEventListener('click', function() {
        consentBanner.classList.add('hidden');
        showAlert('You must agree to the data notice to use this portal.', 'error');
      });
    }
  }

  // ===== LOGOUT DELEGATION =====
  // Handles <a data-action="logout" data-form="..."> without inline onclick
  document.addEventListener('click', function(e) {
    const link = e.target.closest('[data-action="logout"]');
    if (!link) return;
    e.preventDefault();
    const formId = link.dataset.form;
    const form   = formId ? document.getElementById(formId) : null;
    if (form) form.submit();
  });

  // ===== FLASH MESSAGES VIA META TAGS =====
  // Reads <meta name="flash-success|flash-error"> placed by Blade
  // instead of injecting inline <script> blocks (CSP-safe)
  function initFlashMeta() {
    const success = document.querySelector('meta[name="flash-success"]');
    const error   = document.querySelector('meta[name="flash-error"]');
    if (success && success.content) showAlert(success.content, 'success');
    if (error   && error.content)   showAlert(error.content,   'error');
  }

  // Expose functions to window
  window.AppPortal = {
    openModal,
    closeAllModals,
    showStep,
    nextStep,
    prevStep,
    showAlert
  };

  // Flush any flash messages queued before this script ran
  if (Array.isArray(window.__flash)) {
    window.__flash.forEach(function(f) { showAlert(f.msg, f.type || 'error'); });
    window.__flash = [];
  }

})();
