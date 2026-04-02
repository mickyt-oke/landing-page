/**
 * NIS Reviewer Dashboard
 * Handles: view details modal, vetting form, reject form, approve form,
 *          confirm-dialog for start-review, flash meta, image fallback.
 * Depends on: dashboard.js (sidebar, toast)
 */

(function () {
  'use strict';

  var appData = [];

  document.addEventListener('DOMContentLoaded', function () {
    loadDataIsland();
    initConfirmForms();
    initModalDelegation();
    initVetForm();
    initRejectionForm();
    initApprovalForm();
    initVetCharCount();
    initFlashMeta();
    initImageFallback();
  });

  // ── Data Island ──────────────────────────────────────────
  function loadDataIsland() {
    var el = document.getElementById('rvAppDataIsland');
    try { if (el) appData = JSON.parse(el.textContent); } catch (e) { /* noop */ }
  }

  // ── Confirm dialog for start-review forms ───────────────
  // Uses data-confirm attribute; no inline onclick needed.
  function initConfirmForms() {
    document.addEventListener('submit', function (e) {
      var form = e.target.closest('form[data-confirm]');
      if (!form) return;
      var msg = form.dataset.confirm || 'Are you sure?';
      if (!confirm(msg)) e.preventDefault();
    });
  }

  // ── Modal event delegation ────────────────────────────────
  function initModalDelegation() {
    document.addEventListener('click', function (e) {

      // View application details
      var viewBtn = e.target.closest('[data-action="view-app"]');
      if (viewBtn) { openDetailsModal(viewBtn.dataset.appId); return; }

      // Open vetting modal from table
      var vetBtn = e.target.closest('[data-action="open-vet"]');
      if (vetBtn) {
        openVetModal(vetBtn.dataset.appId, vetBtn.dataset.appName, vetBtn.dataset.existingNotes || '');
        return;
      }

      // Open rejection modal from table
      var rejectBtn = e.target.closest('[data-action="open-reject"]');
      if (rejectBtn) {
        openRejectionModal(rejectBtn.dataset.appId, rejectBtn.dataset.appName);
        return;
      }

      // Open approve modal from table (admin only)
      var approveBtn = e.target.closest('[data-action="open-approve"]');
      if (approveBtn) {
        openApprovalModal(approveBtn.dataset.appId, approveBtn.dataset.appName);
        return;
      }

      // Vet from details modal footer
      var vetFromDetails = e.target.closest('[data-action="vet-from-details"]');
      if (vetFromDetails) {
        var currentId   = getCurrentModalId();
        var currentName = getText('rvModalName');
        if (currentId) {
          var app = findApp(currentId);
          closeAllModals();
          setTimeout(function () {
            openVetModal(currentId, currentName, app ? (app.reviewerComment || '') : '');
          }, 260);
        }
        return;
      }

      // Reject from details modal footer
      var rejectFromDetails = e.target.closest('[data-action="reject-from-details"]');
      if (rejectFromDetails) {
        var rId   = getCurrentModalId();
        var rName = getText('rvModalName');
        if (rId) { closeAllModals(); setTimeout(function () { openRejectionModal(rId, rName); }, 260); }
        return;
      }

      // Approve from details modal footer (admin only)
      var approveFromDetails = e.target.closest('[data-action="approve-from-details"]');
      if (approveFromDetails) {
        var aId   = getCurrentModalId();
        var aName = getText('rvModalName');
        if (aId) { closeAllModals(); setTimeout(function () { openApprovalModal(aId, aName); }, 260); }
        return;
      }

      // Close buttons
      if (e.target.closest('.modal-close') || e.target.closest('.modal-close-btn')) {
        closeAllModals();
        return;
      }

      // Backdrop click
      if (e.target.classList.contains('modal-overlay')) {
        closeAllModals();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllModals();
    });
  }

  // ── Application Details Modal ────────────────────────────
  function openDetailsModal(appId) {
    var app = findApp(appId);
    if (!app) return;

    var modal = document.getElementById('applicationModal');
    if (!modal) return;
    modal.dataset.currentAppId = String(appId);

    // Status badge
    var statusEl = document.getElementById('rvModalStatus');
    if (statusEl) {
      statusEl.textContent = formatStatus(app.status);
      statusEl.className   = 'status-badge ' + (app.status || '').replace('_', '-');
    }

    setText('rvModalRef',        'Ref: ' + (app.ref  || '—'));
    setText('rvModalStatusDate', app.submitted ? 'Submitted: ' + app.submitted : '');
    setText('rvModalName',       app.name        || '—');
    setText('rvModalPassport',   app.passport    || '—');
    setText('rvModalNationality',app.nationality || '—');
    setText('rvModalVisa',       app.visa        || '—');
    setText('rvModalArrival',    app.arrival     || '—');
    setText('rvModalSubmitted',  app.submitted   || '—');
    setText('rvModalOverstay',   app.overstay != null ? app.overstay + ' day(s)' : '—');

    // Address
    toggleItem('rvModalAddressItem', 'rvModalAddress', app.address);

    // Applicant note
    var noteSection = document.getElementById('rvModalApplicantNoteSection');
    var noteEl      = document.getElementById('rvModalApplicantNote');
    if (noteSection && noteEl) {
      if (app.note) { noteEl.textContent = app.note; noteSection.style.display = ''; }
      else          { noteSection.style.display = 'none'; }
    }

    // Reviewer notes
    var rvNotesSection = document.getElementById('rvModalReviewerNotesSection');
    var rvNotesEl      = document.getElementById('rvModalReviewerNotes');
    if (rvNotesSection && rvNotesEl) {
      if (app.reviewerComment) { rvNotesEl.textContent = app.reviewerComment; rvNotesSection.style.display = ''; }
      else                     { rvNotesSection.style.display = 'none'; }
    }

    // Documents
    renderDocumentList(app.docs || []);

    // Footer action buttons — only visible for under_review
    var isUnderReview = app.status === 'under_review';
    showHide('rvModalVetBtn',     isUnderReview);
    showHide('rvModalRejectBtn',  isUnderReview);
    showHide('rvModalApproveBtn', isUnderReview); // only rendered in blade if admin

    openModal('applicationModal');
  }

  function renderDocumentList(docs) {
    var list    = document.getElementById('rvModalDocList');
    var section = document.getElementById('rvModalDocsSection');
    if (!list) return;

    while (list.firstChild) list.removeChild(list.firstChild);

    if (!docs || !docs.length) {
      if (section) section.style.display = 'none';
      return;
    }
    if (section) section.style.display = '';

    var mimeIcons = {
      'application/pdf': 'fa-file-pdf',
      'image/jpeg':      'fa-file-image',
      'image/jpg':       'fa-file-image',
      'image/png':       'fa-file-image',
    };

    docs.forEach(function (doc) {
      var item    = document.createElement('div');
      item.className = 'document-item';

      var iconDiv = document.createElement('div');
      iconDiv.className = 'document-icon';
      var icon = document.createElement('i');
      icon.className = 'fas ' + (mimeIcons[doc.mime] || 'fa-file-alt');
      icon.setAttribute('aria-hidden', 'true');
      iconDiv.appendChild(icon);

      var info   = document.createElement('div');
      info.className = 'document-info';

      var nameEl = document.createElement('div');
      nameEl.className   = 'document-name';
      nameEl.textContent = doc.name || formatDocType(doc.type || '');

      var metaEl = document.createElement('div');
      metaEl.className   = 'document-meta';
      var parts = [];
      if (doc.type) parts.push(formatDocType(doc.type));
      if (doc.size) parts.push(formatBytes(doc.size));
      metaEl.textContent = parts.join(' · ');

      info.appendChild(nameEl);
      info.appendChild(metaEl);
      item.appendChild(iconDiv);
      item.appendChild(info);
      list.appendChild(item);
    });
  }

  // ── Vetting Modal ─────────────────────────────────────────
  function openVetModal(appId, appName, existingNotes) {
    var form = document.getElementById('vetForm');
    if (form) {
      form.action = buildUrl('vetForm', appId, 'vet');
    }
    var idInput = document.getElementById('vetApplicationId');
    if (idInput) idInput.value = appId;
    setText('vetApplicantName', appName || '—');

    var textarea = document.getElementById('vetComment');
    if (textarea) {
      textarea.value = existingNotes || '';
      updateCharCount('vetComment', 'vetCharCount');
    }

    openModal('vetModal');
    if (textarea) setTimeout(function () { textarea.focus(); }, 100);
  }

  function initVetCharCount() {
    var textarea = document.getElementById('vetComment');
    if (!textarea) return;
    textarea.addEventListener('input', function () {
      updateCharCount('vetComment', 'vetCharCount');
    });
  }

  function updateCharCount(inputId, counterId) {
    var input   = document.getElementById(inputId);
    var counter = document.getElementById(counterId);
    if (input && counter) counter.textContent = input.value.length;
  }

  function initVetForm() {
    var form = document.getElementById('vetForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var comment = (document.getElementById('vetComment')?.value || '').trim();
      if (!comment) { showToast('Please enter your vetting assessment.', 'error'); return; }

      var submitBtn = document.getElementById('vetSubmitBtn');
      setLoading(submitBtn, true, '<i class="fas fa-spinner fa-spin"></i> Submitting...');

      submitForm(form.action, new FormData(form))
        .then(function () {
          closeAllModals();
          showToast('Vetting notes submitted. Application awaiting admin decision.', 'success');
          setTimeout(function () { window.location.reload(); }, 1200);
        })
        .catch(function (msg) {
          showToast(msg, 'error');
          setLoading(submitBtn, false, '<i class="fas fa-paper-plane"></i> Submit Vetting Notes');
        });
    });
  }

  // ── Rejection Modal ───────────────────────────────────────
  function openRejectionModal(appId, appName) {
    var form = document.getElementById('rejectionForm');
    if (form) {
      form.action = buildUrl('rejectionForm', appId, 'reject');
    }
    var idInput = document.getElementById('rejectionApplicationId');
    if (idInput) idInput.value = appId;
    setText('rvRejApplicantName', appName || '—');

    var reason   = document.getElementById('rejectionReason');
    var comments = document.getElementById('rejectionComments');
    if (reason)   reason.value   = '';
    if (comments) comments.value = '';

    openModal('rejectionModal');
  }

  function initRejectionForm() {
    var form = document.getElementById('rejectionForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var reason   = (document.getElementById('rejectionReason')?.value  || '').trim();
      var comments = (document.getElementById('rejectionComments')?.value || '').trim();
      if (!reason)   { showToast('Please select a rejection reason.', 'error');           return; }
      if (!comments) { showToast('Please provide detailed rejection comments.', 'error'); return; }

      var submitBtn = document.getElementById('rvRejSubmitBtn');
      setLoading(submitBtn, true, '<i class="fas fa-spinner fa-spin"></i> Rejecting...');

      submitForm(form.action, new FormData(form))
        .then(function () {
          closeAllModals();
          showToast('Application rejected.', 'info');
          setTimeout(function () { window.location.reload(); }, 1200);
        })
        .catch(function (msg) {
          showToast(msg, 'error');
          setLoading(submitBtn, false, '<i class="fas fa-times"></i> Confirm Rejection');
        });
    });
  }

  // ── Approval Modal (admin only) ───────────────────────────
  function openApprovalModal(appId, appName) {
    var form = document.getElementById('approvalForm');
    if (!form) return; // not rendered for non-admins

    form.action = buildUrl('approvalForm', appId, 'approve');

    var idInput = document.getElementById('approvalApplicationId');
    if (idInput) idInput.value = appId;
    setText('rvAppApplicantName', appName || '—');

    // Surface reviewer notes in the approve modal for admin context
    var app = findApp(appId);
    var notesCtx  = document.getElementById('rvAppReviewerNotesCtx');
    var notesText = document.getElementById('rvAppReviewerNotesText');
    if (notesCtx && notesText) {
      if (app && app.reviewerComment) {
        notesText.textContent = app.reviewerComment;
        notesCtx.style.display = '';
      } else {
        notesCtx.style.display = 'none';
      }
    }

    // Clear previous admin comment
    var comments = document.getElementById('approvalComments');
    if (comments) comments.value = '';

    openModal('approvalModal');
  }

  function initApprovalForm() {
    var form = document.getElementById('approvalForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var submitBtn = document.getElementById('rvAppSubmitBtn');
      setLoading(submitBtn, true, '<i class="fas fa-spinner fa-spin"></i> Approving...');

      submitForm(form.action, new FormData(form))
        .then(function () {
          closeAllModals();
          showToast('Application approved successfully.', 'success');
          setTimeout(function () { window.location.reload(); }, 1200);
        })
        .catch(function (msg) {
          showToast(msg, 'error');
          setLoading(submitBtn, false, '<i class="fas fa-check"></i> Confirm Approval');
        });
    });
  }

  // ── Flash meta ────────────────────────────────────────────
  function initFlashMeta() {
    var success = document.querySelector('meta[name="flash-success"]');
    var error   = document.querySelector('meta[name="flash-error"]');
    if (success && success.content) showToast(success.content, 'success');
    if (error   && error.content)   showToast(error.content,   'error');
  }

  // ── Image fallback ────────────────────────────────────────
  function initImageFallback() {
    document.addEventListener('error', function (e) {
      if (e.target.tagName === 'IMG' && e.target.hasAttribute('data-img-fallback')) {
        e.target.style.display = 'none';
      }
    }, true);
  }

  // ── Helpers ──────────────────────────────────────────────
  function findApp(appId) {
    return (appData || []).find(function (a) { return String(a.id) === String(appId); }) || null;
  }

  function getCurrentModalId() {
    var modal = document.getElementById('applicationModal');
    return modal ? modal.dataset.currentAppId : null;
  }

  function getText(id) {
    var el = document.getElementById(id);
    return el ? el.textContent : '';
  }

  function setText(id, value) {
    var el = document.getElementById(id);
    if (el) el.textContent = value || '';
  }

  function showHide(id, show) {
    var el = document.getElementById(id);
    if (el) el.style.display = show ? '' : 'none';
  }

  function toggleItem(itemId, valueId, value) {
    var item = document.getElementById(itemId);
    var el   = document.getElementById(valueId);
    if (!item || !el) return;
    if (value) { el.textContent = value; item.style.display = ''; }
    else        { item.style.display = 'none'; }
  }

  function getCsrf() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
  }

  function buildUrl(formId, appId, action) {
    var form = document.getElementById(formId);
    var base = form ? (form.dataset.baseUrl || '/admin/applications') : '/admin/applications';
    return base + '/' + appId + '/' + action;
  }

  function submitForm(url, formData) {
    return fetch(url, {
      method:      'POST',
      headers:     { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'text/html, application/json' },
      body:        formData,
      redirect:    'follow',
      credentials: 'same-origin',
    }).then(function (res) {
      if (res.ok || res.redirected) return res;
      return res.json()
        .then(function (d) { throw new Error(d.message || d.error || 'Request failed.'); })
        .catch(function () { throw new Error('Request failed with status ' + res.status + '.'); });
    });
  }

  function showToast(message, type) {
    if (window.Dashboard && typeof window.Dashboard.showToast === 'function') {
      window.Dashboard.showToast(message, type);
    }
  }

  function openModal(id) {
    var el = document.getElementById(id);
    if (el) { el.classList.add('active'); document.body.style.overflow = 'hidden'; }
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(function (m) { m.classList.remove('active'); });
    document.body.style.overflow = '';
  }

  function setLoading(btn, loading, html) {
    if (!btn) return;
    btn.disabled = loading;
    if (html) btn.innerHTML = html;
  }

  function formatStatus(status) {
    return String(status || '').replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
  }

  function formatDocType(type) {
    return String(type || '').replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
  }

  function formatBytes(bytes) {
    bytes = parseInt(bytes, 10);
    if (isNaN(bytes) || bytes === 0) return '';
    if (bytes < 1024)    return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
  }

}());
