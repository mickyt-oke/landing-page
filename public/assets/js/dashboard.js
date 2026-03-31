/**
 * NIS Dashboard – Main Script
 * Sidebar management and application modals wired to real server-rendered data.
 */

(function () {
  'use strict';

  // ===== DOM REFERENCES =====
  var sidebar          = document.getElementById('sidebar');
  var sidebarToggle    = document.getElementById('sidebarToggle');
  var mobileMenuToggle = document.getElementById('mobileMenuToggle');

  // ===== INITIALIZATION =====
  document.addEventListener('DOMContentLoaded', function () {
    initializeSidebar();
    initializeEventListeners();
  });

  // ===== SIDEBAR =====
  function initializeSidebar() {
    if (!sidebar) return;
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      sidebar.classList.add('collapsed');
    }
  }

  function toggleSidebar() {
    if (!sidebar) return;
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
  }

  function toggleMobileMenu() {
    if (!sidebar) return;
    sidebar.classList.toggle('active');
  }

  // ===== EVENT LISTENERS =====
  function initializeEventListeners() {
    if (sidebarToggle)    sidebarToggle.addEventListener('click', toggleSidebar);
    if (mobileMenuToggle) mobileMenuToggle.addEventListener('click', toggleMobileMenu);

    // Close sidebar on outside-click (mobile)
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 992 && sidebar &&
          !sidebar.contains(e.target) &&
          mobileMenuToggle && !mobileMenuToggle.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 992 && sidebar) sidebar.classList.remove('active');
    });

    // View-details buttons (desktop table + mobile cards)
    document.querySelectorAll('.action-btn.view').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        openApplicationModal(this.dataset);
      });
    });

    // Acknowledgement-preview buttons
    document.querySelectorAll('.action-btn.ack-preview').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        openAckModal(this.dataset);
      });
    });

    // Close buttons inside modals
    document.querySelectorAll('.modal-close').forEach(function (btn) {
      btn.addEventListener('click', closeAllModals);
    });

    // Click on backdrop to close
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
      overlay.addEventListener('click', function (e) {
        if (e.target === this) closeAllModals();
      });
    });

    // ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllModals();
    });
  }

  // ===== MODAL HELPERS =====
  function openModal(id) {
    var modal = document.getElementById(id);
    if (modal) modal.classList.add('active');
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(function (m) {
      m.classList.remove('active');
    });
  }

  function setText(id, value) {
    var el = document.getElementById(id);
    if (el) el.textContent = value || '';
  }

  // ===== APPLICATION DETAILS MODAL =====
  function openApplicationModal(data) {
    var modal = document.getElementById('applicationModal');
    if (!modal) return;

    // Header subtitle (ref number)
    setText('modalAppRef', data.ref ? 'Ref: ' + data.ref : '');

    // Status banner
    var statusEl = document.getElementById('modalStatus');
    if (statusEl) {
      statusEl.textContent = data.statusLabel || (data.status || '—');
      statusEl.className   = 'status-badge ' + (data.status || '').replace(/_/g, '-');
    }
    setText('modalStatusDate', data.submitted ? 'Submitted: ' + data.submitted : '');

    // Applicant info
    setText('modalApplicantName',  data.name        || '—');
    setText('modalPassportNumber', data.passport     || '—');
    setText('modalNationality',    data.nationality  || '—');
    setText('modalVisaCategory',   data.visa         || '—');

    // Travel info
    setText('modalArrivalDate',    data.arrival      || '—');
    setText('modalSubmittedDate',  data.submitted    || '—');

    // Address — hide block when empty
    var addressItem = document.getElementById('modalAddressItem');
    var addressEl   = document.getElementById('modalAddress');
    if (addressItem && addressEl) {
      var addr = (data.address || '').trim();
      if (addr) {
        addressEl.textContent    = addr;
        addressItem.style.display = '';
      } else {
        addressItem.style.display = 'none';
      }
    }

    // Uploaded documents
    renderDocumentList(data.docs);

    openModal('applicationModal');
  }

  function renderDocumentList(docsJson) {
    var list    = document.getElementById('modalDocumentList');
    var section = document.getElementById('modalDocsSection');
    if (!list) return;

    // Clear existing items
    while (list.firstChild) list.removeChild(list.firstChild);

    var docs = [];
    try { if (docsJson) docs = JSON.parse(docsJson); } catch (e) { /* noop */ }

    if (!docs.length) {
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
      var sizeTxt   = doc.size ? formatBytes(doc.size) : '';
      var typeTxt   = doc.type ? formatDocType(doc.type) : '';

      var item = document.createElement('div');
      item.className = 'document-item';

      var iconDiv = document.createElement('div');
      iconDiv.className = 'document-icon';
      var icon = document.createElement('i');
      icon.className = 'fas ' + iconClass;
      icon.setAttribute('aria-hidden', 'true');
      iconDiv.appendChild(icon);

      var info = document.createElement('div');
      info.className = 'document-info';

      var nameDiv = document.createElement('div');
      nameDiv.className   = 'document-name';
      nameDiv.textContent = doc.name || typeTxt || 'Document';

      var metaDiv = document.createElement('div');
      metaDiv.className   = 'document-meta';
      metaDiv.textContent = [typeTxt, sizeTxt].filter(Boolean).join(' · ');

      info.appendChild(nameDiv);
      info.appendChild(metaDiv);

      item.appendChild(iconDiv);
      item.appendChild(info);
      list.appendChild(item);
    });
  }

  function formatBytes(bytes) {
    bytes = parseInt(bytes, 10);
    if (isNaN(bytes) || bytes === 0) return '';
    if (bytes < 1024)    return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
  }

  function formatDocType(type) {
    return String(type).replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
  }

  // ===== ACKNOWLEDGEMENT MODAL =====
  function openAckModal(data) {
    var contentEl = document.getElementById('ackContent');
    if (!contentEl) return;

    // Clear previous content
    while (contentEl.firstChild) contentEl.removeChild(contentEl.firstChild);

    var card = document.createElement('div');
    card.className = 'ack-card';

    /* ── Header ─────────────────────────────────── */
    var header = document.createElement('div');
    header.className = 'ack-card-header';

    var logo = document.createElement('div');
    logo.className = 'ack-logo';
    var logoImg = document.createElement('img');
    logoImg.alt = 'NIS Logo';
    logoImg.style.cssText = 'width:60px;height:auto;';
    logoImg.onerror = function () { this.style.display = 'none'; };
    // Derive asset path from existing logo on page, fallback to relative path
    var existingLogo = document.querySelector('.sidebar-logo img');
    logoImg.src = existingLogo ? existingLogo.src : 'assets/images/nis-logo.jpg';
    logo.appendChild(logoImg);

    var titleWrap = document.createElement('div');
    var title = document.createElement('h3');
    title.className   = 'ack-title';
    title.textContent = 'Application Acknowledgement';
    var subtitle = document.createElement('p');
    subtitle.className   = 'ack-subtitle';
    subtitle.textContent = 'Nigeria Immigration Service';
    titleWrap.appendChild(title);
    titleWrap.appendChild(subtitle);

    header.appendChild(logo);
    header.appendChild(titleWrap);

    /* ── Reference section ───────────────────────── */
    var refSection = document.createElement('div');
    refSection.className = 'ack-ref-section';

    var refLabel = document.createElement('span');
    refLabel.className   = 'ack-ref-label';
    refLabel.textContent = 'Reference Number';

    var refBadge = document.createElement('div');
    refBadge.className   = 'ack-ref-badge';
    refBadge.textContent = data.ackRef || '—';

    var dateSpan = document.createElement('div');
    dateSpan.className   = 'ack-date';
    dateSpan.textContent = 'Submitted: ' + (data.submitted || '—');

    refSection.appendChild(refLabel);
    refSection.appendChild(refBadge);
    refSection.appendChild(dateSpan);

    /* ── Details table ───────────────────────────── */
    var tableWrap = document.createElement('div');
    tableWrap.className = 'ack-details-table';

    var statusRaw = data.status || '';
    var statusLabel = statusRaw
      .replace(/_/g, ' ')
      .replace(/\b\w/g, function (c) { return c.toUpperCase(); }) || '—';

    var rows = [
      ['Full Name',       data.name        ],
      ['Passport Number', data.passport    ],
      ['Nationality',     data.nationality ],
      ['Visa Category',   data.visa        ],
      ['Arrival Date',    data.arrival     ],
      ['Status',          statusLabel      ],
    ];

    rows.forEach(function (row) {
      var rowEl = document.createElement('div');
      rowEl.className = 'ack-detail-row';

      var labelEl = document.createElement('span');
      labelEl.className   = 'ack-detail-label';
      labelEl.textContent = row[0];

      var valueEl = document.createElement('span');
      valueEl.className   = 'ack-detail-value';
      valueEl.textContent = row[1] || '—';

      rowEl.appendChild(labelEl);
      rowEl.appendChild(valueEl);
      tableWrap.appendChild(rowEl);
    });

    /* ── Notice ──────────────────────────────────── */
    var notice = document.createElement('div');
    notice.className = 'ack-next-steps';

    var noticeIcon = document.createElement('i');
    noticeIcon.className = 'fas fa-info-circle';
    noticeIcon.setAttribute('aria-hidden', 'true');

    var noticeText = document.createElement('p');
    noticeText.textContent =
      'Your application is being processed by the Nigeria Immigration Service. ' +
      'You will be notified of any updates. Please keep this reference number for future inquiries.';

    notice.appendChild(noticeIcon);
    notice.appendChild(noticeText);

    /* ── Print action ────────────────────────────── */
    var actions = document.createElement('div');
    actions.className = 'ack-actions';

    var printBtn = document.createElement('button');
    printBtn.type      = 'button';
    printBtn.className = 'btn btn-primary';
    var printIcon = document.createElement('i');
    printIcon.className = 'fas fa-print';
    printIcon.setAttribute('aria-hidden', 'true');
    printBtn.appendChild(printIcon);
    printBtn.appendChild(document.createTextNode(' Print'));
    printBtn.addEventListener('click', function () { window.print(); });

    actions.appendChild(printBtn);

    /* ── Assemble ─────────────────────────────────── */
    card.appendChild(header);
    card.appendChild(refSection);
    card.appendChild(tableWrap);
    card.appendChild(notice);
    card.appendChild(actions);
    contentEl.appendChild(card);

    openModal('ackModal');
  }

  // ===== TOAST NOTIFICATIONS =====
  function showToast(message, type) {
    type = type || 'info';

    var container = document.getElementById('toastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id        = 'toastContainer';
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    var icons = {
      success: 'fa-check-circle',
      error:   'fa-exclamation-circle',
      warning: 'fa-exclamation-triangle',
      info:    'fa-info-circle',
    };

    var toast = document.createElement('div');
    toast.className = 'toast ' + type;

    var iconDiv = document.createElement('div');
    iconDiv.className = 'toast-icon';
    var iconEl = document.createElement('i');
    iconEl.className = 'fas ' + (icons[type] || icons.info);
    iconDiv.appendChild(iconEl);

    var contentDiv = document.createElement('div');
    contentDiv.className = 'toast-content';

    var titleDiv = document.createElement('div');
    titleDiv.className   = 'toast-title';
    titleDiv.textContent = type.charAt(0).toUpperCase() + type.slice(1);

    var msgDiv = document.createElement('div');
    msgDiv.className   = 'toast-message';
    msgDiv.textContent = message;

    contentDiv.appendChild(titleDiv);
    contentDiv.appendChild(msgDiv);

    var closeBtn = document.createElement('button');
    closeBtn.type      = 'button';
    closeBtn.className = 'toast-close';
    closeBtn.setAttribute('aria-label', 'Dismiss');
    var closeIcon = document.createElement('i');
    closeIcon.className = 'fas fa-times';
    closeBtn.appendChild(closeIcon);
    closeBtn.addEventListener('click', function () { toast.remove(); });

    toast.appendChild(iconDiv);
    toast.appendChild(contentDiv);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(function () {
      toast.style.opacity   = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(function () { if (toast.parentNode) toast.remove(); }, 300);
    }, 5000);
  }

  // ===== PUBLIC API =====
  window.Dashboard = {
    toggleSidebar:  toggleSidebar,
    showToast:      showToast,
    closeAllModals: closeAllModals,
  };

}());
