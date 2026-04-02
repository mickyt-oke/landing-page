/**
 * NIS Admin Dashboard – Charts, filters, modals, approval/rejection
 * Depends on: chart.js (CDN), dashboard.js (base sidebar/toast)
 */

(function () {
  'use strict';

  var chartData = {};
  var appData   = [];

  // ── Init ──────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    loadDataIslands();
    initCharts();
    initFilterTabs();
    initSearch();
    initModalDelegation();
    initApprovalForm();
    initRejectionForm();
    initAccountDropdown();
    initFlashMeta();
    initImageFallback();
  });

  // ── Data Islands ─────────────────────────────────────────
  function loadDataIslands() {
    var chartEl = document.getElementById('chartDataIsland');
    var appEl   = document.getElementById('appDataIsland');
    try { if (chartEl) chartData = JSON.parse(chartEl.textContent); } catch (e) { /* noop */ }
    try { if (appEl)   appData   = JSON.parse(appEl.textContent);   } catch (e) { /* noop */ }
  }

  // ── Charts ───────────────────────────────────────────────
  function initCharts() {
    if (typeof Chart === 'undefined') return;

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size   = 12;

    var palette = {
      green:  { bg: 'rgba(30,132,73,0.75)',  border: '#1E8449' },
      blue:   { bg: 'rgba(11,60,93,0.75)',   border: '#0B3C5D' },
      yellow: { bg: 'rgba(243,156,18,0.75)', border: '#f39c12' },
      red:    { bg: 'rgba(192,57,43,0.75)',  border: '#C0392B' },
      info:   { bg: 'rgba(52,152,219,0.75)', border: '#3498db' },
    };

    var tickCfg = { beginAtZero: true, precision: 0 };
    var noLegend = { legend: { display: false } };

    /* ── Bar: applications by nationality ─────────────────── */
    var barEl = document.getElementById('applicationsChart');
    if (barEl && chartData.nationalities) {
      new Chart(barEl, {
        type: 'bar',
        data: {
          labels:   Object.keys(chartData.nationalities),
          datasets: [{
            label:           'Applications',
            data:            Object.values(chartData.nationalities),
            backgroundColor: palette.green.bg,
            borderColor:     palette.green.border,
            borderWidth:     1,
            borderRadius:    5,
          }],
        },
        options: {
          responsive:  true,
          plugins:     Object.assign({}, noLegend, {
            tooltip: { callbacks: { label: function (ctx) { return ' ' + ctx.parsed.y + ' applications'; } } },
          }),
          scales: { y: { ticks: tickCfg } },
        },
      });
    }

    /* ── Doughnut: status distribution ───────────────────── */
    var pieEl = document.getElementById('nationalitiesPieChart');
    if (pieEl && chartData.statusCounts) {
      new Chart(pieEl, {
        type: 'doughnut',
        data: {
          labels:   Object.keys(chartData.statusCounts),
          datasets: [{
            data:            Object.values(chartData.statusCounts),
            backgroundColor: [palette.yellow.bg, palette.info.bg, palette.green.bg, palette.red.bg],
            borderColor:     ['#fff', '#fff', '#fff', '#fff'],
            borderWidth:     3,
          }],
        },
        options: {
          responsive: true,
          cutout:     '60%',
          plugins:    { legend: { position: 'bottom', labels: { padding: 14 } } },
        },
      });
    }

    /* ── Bar: overstay distribution (histogram) ───────────── */
    var histEl = document.getElementById('countryHistogramChart');
    if (histEl && chartData.overstayRanges) {
      new Chart(histEl, {
        type: 'bar',
        data: {
          labels:   Object.keys(chartData.overstayRanges),
          datasets: [{
            label:           'Applications',
            data:            Object.values(chartData.overstayRanges),
            backgroundColor: palette.blue.bg,
            borderColor:     palette.blue.border,
            borderWidth:     1,
            borderRadius:    5,
          }],
        },
        options: {
          responsive: true,
          plugins:    noLegend,
          scales:     { y: { ticks: tickCfg } },
        },
      });
    }

    /* ── Line: 30-day submission trend ────────────────────── */
    var lineEl = document.getElementById('submissionLineChart');
    if (lineEl && chartData.trendLabels) {
      new Chart(lineEl, {
        type: 'line',
        data: {
          labels:   chartData.trendLabels,
          datasets: [{
            label:           'Submissions',
            data:            chartData.trendCounts,
            borderColor:     palette.green.border,
            backgroundColor: 'rgba(30,132,73,0.08)',
            borderWidth:     2,
            fill:            true,
            tension:         0.4,
            pointRadius:     3,
            pointHoverRadius: 5,
            pointBackgroundColor: palette.green.border,
          }],
        },
        options: {
          responsive: true,
          plugins:    noLegend,
          scales: {
            x: { ticks: { maxRotation: 45, autoSkip: true, maxTicksLimit: 10 } },
            y: { ticks: tickCfg },
          },
        },
      });
    }
  }

  // ── Filter Tabs ──────────────────────────────────────────
  function initFilterTabs() {
    var tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');
        applyFilter(this.dataset.filter || 'all');
      });
    });
  }

  function applyFilter(filter) {
    var rows = document.querySelectorAll('#applicationsTableBody tr[data-status]');
    rows.forEach(function (row) {
      var match = filter === 'all' || row.dataset.status === filter;
      row.style.display = match ? '' : 'none';
    });
    syncEmptyState();
  }

  // ── Table Search ─────────────────────────────────────────
  function initSearch() {
    var input = document.getElementById('tableSearch');
    if (!input) return;
    input.addEventListener('input', function () {
      var q = this.value.toLowerCase().trim();
      var rows = document.querySelectorAll('#applicationsTableBody tr[data-status]');
      rows.forEach(function (row) {
        row.style.display = (!q || row.textContent.toLowerCase().includes(q)) ? '' : 'none';
      });
      syncEmptyState();
    });
  }

  function syncEmptyState() {
    var emptyRow = document.getElementById('tableEmptyRow');
    if (!emptyRow) return;
    var visible = document.querySelectorAll(
      '#applicationsTableBody tr[data-status]:not([style*="display: none"]):not([style*="display:none"])'
    );
    emptyRow.style.display = visible.length === 0 ? '' : 'none';
  }

  // ── Modal Delegation ─────────────────────────────────────
  function initModalDelegation() {
    document.addEventListener('click', function (e) {

      // View application details
      var viewBtn = e.target.closest('[data-action="view-app"]');
      if (viewBtn) { openApplicationModal(viewBtn.dataset.appId); return; }

      // Open approve modal from table row
      var approveBtn = e.target.closest('[data-action="open-approve"]');
      if (approveBtn) {
        openApprovalModal(approveBtn.dataset.appId, approveBtn.dataset.appName);
        return;
      }

      // Open reject modal from table row
      var rejectBtn = e.target.closest('[data-action="open-reject"]');
      if (rejectBtn) {
        openRejectionModal(rejectBtn.dataset.appId, rejectBtn.dataset.appName);
        return;
      }

      // Approve from within details modal
      var approveFromDetails = e.target.closest('[data-action="approve-from-details"]');
      if (approveFromDetails) {
        var detailModal = document.getElementById('applicationModal');
        var appId = detailModal ? detailModal.dataset.currentAppId : null;
        var appName = getText('modalApplicantName');
        if (appId) { closeAllModals(); setTimeout(function () { openApprovalModal(appId, appName); }, 260); }
        return;
      }

      // Reject from within details modal
      var rejectFromDetails = e.target.closest('[data-action="reject-from-details"]');
      if (rejectFromDetails) {
        var detailModal2 = document.getElementById('applicationModal');
        var appId2 = detailModal2 ? detailModal2.dataset.currentAppId : null;
        var appName2 = getText('modalApplicantName');
        if (appId2) { closeAllModals(); setTimeout(function () { openRejectionModal(appId2, appName2); }, 260); }
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

    // ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllModals();
    });
  }

  function openModal(id) {
    var el = document.getElementById(id);
    if (el) { el.classList.add('active'); document.body.style.overflow = 'hidden'; }
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(function (m) { m.classList.remove('active'); });
    document.body.style.overflow = '';
  }

  // ── Application Details Modal ────────────────────────────
  function openApplicationModal(appId) {
    var app = findApp(appId);
    if (!app) return;

    var modal = document.getElementById('applicationModal');
    if (!modal) return;
    modal.dataset.currentAppId = String(appId);

    // Status badge
    var statusEl = document.getElementById('modalStatus');
    if (statusEl) {
      statusEl.textContent = formatStatus(app.status);
      statusEl.className   = 'status-badge ' + (app.status || '').replace('_', '-');
    }

    setText('modalAppRef',        'Ref: ' + (app.ref || '—'));
    setText('modalStatusDate',    app.submitted ? 'Submitted: ' + app.submitted : '');
    setText('modalApplicantName', app.name        || '—');
    setText('modalPassportNumber',app.passport    || '—');
    setText('modalNationality',   app.nationality || '—');
    setText('modalVisaCategory',  app.visa        || '—');
    setText('modalArrivalDate',   app.arrival     || '—');
    setText('modalSubmittedDate', app.submitted   || '—');
    setText('modalApplicationId', '#' + (app.id  || '—'));
    setText('modalOverstayDays',  app.overstay != null ? app.overstay + ' day(s)' : '—');

    // Address
    var addrItem = document.getElementById('modalAddressItem');
    var addrEl   = document.getElementById('modalAddress');
    if (addrItem && addrEl) {
      if (app.address) { addrEl.textContent = app.address; addrItem.style.display = ''; }
      else             { addrItem.style.display = 'none'; }
    }

    // Reviewer notes
    var notesSection = document.getElementById('reviewerNotesSection');
    var notesEl      = document.getElementById('modalReviewerNotes');
    if (notesSection && notesEl) {
      if (app.reviewerComment) { notesEl.textContent = app.reviewerComment; notesSection.style.display = ''; }
      else                     { notesSection.style.display = 'none'; }
    }

    // Documents
    renderDocumentList(app.docs || []);

    // Show/hide approve+reject footer buttons
    // Only visible for under_review applications (server-side guard enforces this too)
    var approveBtn = document.getElementById('modalApproveBtn');
    var rejectBtn  = document.getElementById('modalRejectBtn');
    var canAct     = app.status === 'under_review';
    if (approveBtn) approveBtn.style.display = canAct ? '' : 'none';
    if (rejectBtn)  rejectBtn.style.display  = canAct ? '' : 'none';

    openModal('applicationModal');
  }

  function renderDocumentList(docs) {
    var list    = document.getElementById('modalDocumentList');
    var section = document.getElementById('modalDocsSection');
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
      var iconClass = mimeIcons[doc.mime] || 'fa-file-alt';

      var item    = document.createElement('div');
      item.className = 'document-item';

      var iconDiv = document.createElement('div');
      iconDiv.className = 'document-icon';
      var icon = document.createElement('i');
      icon.className = 'fas ' + iconClass;
      icon.setAttribute('aria-hidden', 'true');
      iconDiv.appendChild(icon);

      var info    = document.createElement('div');
      info.className = 'document-info';

      var nameEl  = document.createElement('div');
      nameEl.className   = 'document-name';
      nameEl.textContent = doc.name || formatDocType(doc.type || '');

      var metaEl  = document.createElement('div');
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

  // ── Approval Modal ────────────────────────────────────────
  function openApprovalModal(appId, appName) {
    var form = document.getElementById('approvalForm');
    if (form) {
      form.action = (form.dataset.baseUrl || '/admin/applications') + '/' + appId + '/approve';
    }
    var idInput = document.getElementById('approvalApplicationId');
    if (idInput) idInput.value = appId;
    setText('approvalApplicantName', appName || '—');
    openModal('approvalModal');
  }

  function initApprovalForm() {
    var form = document.getElementById('approvalForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var submitBtn = document.getElementById('approvalSubmitBtn');
      setLoading(submitBtn, true, '<i class="fas fa-spinner fa-spin"></i> Approving...');

      fetch(form.action, {
        method:      'POST',
        headers:     { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'text/html, application/json' },
        body:        new FormData(form),
        redirect:    'follow',
        credentials: 'same-origin',
      })
      .then(function (res) {
        if (res.ok || res.redirected) {
          closeAllModals();
          showToast('Application approved successfully.', 'success');
          setTimeout(function () { window.location.reload(); }, 1200);
        } else {
          return res.json().then(function (d) { throw new Error(d.message || 'Approval failed.'); });
        }
      })
      .catch(function (err) {
        showToast(err.message || 'An error occurred. Please try again.', 'error');
        setLoading(submitBtn, false, '<i class="fas fa-check"></i> Confirm Approval');
      });
    });
  }

  // ── Rejection Modal ───────────────────────────────────────
  function openRejectionModal(appId, appName) {
    var form = document.getElementById('rejectionForm');
    if (form) {
      form.action = (form.dataset.baseUrl || '/admin/applications') + '/' + appId + '/reject';
    }
    var idInput = document.getElementById('rejectionApplicationId');
    if (idInput) idInput.value = appId;
    setText('rejectionApplicantName', appName || '—');

    // Reset fields
    var reason = document.getElementById('rejectionReason');
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

      if (!reason)   { showToast('Please select a rejection reason.',            'error'); return; }
      if (!comments) { showToast('Please provide detailed rejection comments.',  'error'); return; }

      var submitBtn = document.getElementById('rejectionSubmitBtn');
      setLoading(submitBtn, true, '<i class="fas fa-spinner fa-spin"></i> Rejecting...');

      fetch(form.action, {
        method:      'POST',
        headers:     { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'text/html, application/json' },
        body:        new FormData(form),
        redirect:    'follow',
        credentials: 'same-origin',
      })
      .then(function (res) {
        if (res.ok || res.redirected) {
          closeAllModals();
          showToast('Application rejected.', 'info');
          setTimeout(function () { window.location.reload(); }, 1200);
        } else {
          return res.json().then(function (d) { throw new Error(d.message || 'Rejection failed.'); });
        }
      })
      .catch(function (err) {
        showToast(err.message || 'An error occurred. Please try again.', 'error');
        setLoading(submitBtn, false, '<i class="fas fa-times"></i> Confirm Rejection');
      });
    });
  }

  // ── Account Dropdown ─────────────────────────────────────
  function initAccountDropdown() {
    var btn = document.getElementById('adminAccountDropdownBtn');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', String(!expanded));
      var content = this.nextElementSibling;
      if (content) content.classList.toggle('show', !expanded);
    });

    document.addEventListener('click', function () {
      btn.setAttribute('aria-expanded', 'false');
      var content = btn.nextElementSibling;
      if (content) content.classList.remove('show');
    });
  }

  // ── Flash meta-tag reader ────────────────────────────────
  function initFlashMeta() {
    var success = document.querySelector('meta[name="flash-success"]');
    var error   = document.querySelector('meta[name="flash-error"]');
    if (success && success.content) showToast(success.content, 'success');
    if (error   && error.content)   showToast(error.content,   'error');
  }

  // ── Image fallback (capture-phase) ───────────────────────
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

  function getText(id) {
    var el = document.getElementById(id);
    return el ? el.textContent : '';
  }

  function setText(id, value) {
    var el = document.getElementById(id);
    if (el) el.textContent = value || '';
  }

  function getCsrf() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
  }

  function showToast(message, type) {
    if (window.Dashboard && typeof window.Dashboard.showToast === 'function') {
      window.Dashboard.showToast(message, type);
    }
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

  // ── Public API extensions (called from dashboard.js hooks) ─
  window.Dashboard = window.Dashboard || {};

  window.Dashboard.openApprovalModalFromDetails = function () {
    var modal = document.getElementById('applicationModal');
    var appId = modal ? modal.dataset.currentAppId : null;
    if (!appId) return;
    closeAllModals();
    setTimeout(function () { openApprovalModal(appId, getText('modalApplicantName')); }, 260);
  };

  window.Dashboard.openRejectionModalFromDetails = function () {
    var modal = document.getElementById('applicationModal');
    var appId = modal ? modal.dataset.currentAppId : null;
    if (!appId) return;
    closeAllModals();
    setTimeout(function () { openRejectionModal(appId, getText('modalApplicantName')); }, 260);
  };

}());
