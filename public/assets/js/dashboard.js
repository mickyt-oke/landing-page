/**
 * NIS Dashboard - Main Dashboard Script
 * Handles sidebar, application management, modals, and admin workflow
 */

(function() {
  'use strict';

  // ===== STATE MANAGEMENT =====
  const state = {
    sidebarCollapsed: false,
    currentFilter: 'all',
    applications: [],
    notifications: [],
    currentUser: null,
    charts: {
      applicationsBar: null,
      nationalitiesPie: null,
      histogram: null,
      submissionLine: null
    }
  };

  // ===== DOM REFERENCES =====
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const mainContent = document.getElementById('mainContent');
  
  // ===== INITIALIZATION =====
  document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
  });

  function initializeDashboard() {
    initializeEventListeners();
    initializeSidebar();
    loadDashboardData();
    initializeFilters();
    initializeModals();
  }

  // ===== EVENT LISTENERS =====
  function initializeEventListeners() {
    // Sidebar toggle
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', toggleSidebar);
    }

    // Mobile menu toggle
    if (mobileMenuToggle) {
      mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 992) {
        if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });

    // Filter tabs
    document.querySelectorAll('.filter-tab').forEach(tab => {
      tab.addEventListener('click', function() {
        const filter = this.dataset.filter;
        setFilter(filter);
      });
    });

    // Action buttons
    document.querySelectorAll('.action-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const action = this.dataset.action;
        const applicationId = this.dataset.applicationId;
        
        if (action && applicationId) {
          handleAction(action, applicationId);
        }
      });
    });

    // Modal close buttons
    document.querySelectorAll('.modal-close, .modal-overlay').forEach(el => {
      el.addEventListener('click', function(e) {
        if (e.target === this) {
          closeAllModals();
        }
      });
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });

    // Approval/Rejection forms
    const approvalForm = document.getElementById('approvalForm');
    if (approvalForm) {
      approvalForm.addEventListener('submit', handleApprovalSubmit);
    }

    const rejectionForm = document.getElementById('rejectionForm');
    if (rejectionForm) {
      rejectionForm.addEventListener('submit', handleRejectionSubmit);
    }

    // Window resize handler
    window.addEventListener('resize', handleResize);
  }

  // ===== SIDEBER MANAGEMENT =====
  function initializeSidebar() {
    // Check localStorage for sidebar state
    const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (collapsed) {
      sidebar.classList.add('collapsed');
      state.sidebarCollapsed = true;
    }
  }

  function toggleSidebar() {
    sidebar.classList.toggle('collapsed');
    state.sidebarCollapsed = !state.sidebarCollapsed;
    localStorage.setItem('sidebarCollapsed', state.sidebarCollapsed);
  }

  function toggleMobileMenu() {
    sidebar.classList.toggle('active');
  }

  function handleResize() {
    if (window.innerWidth > 992) {
      sidebar.classList.remove('active');
    }
  }

  // ===== DATA LOADING =====
  function loadDashboardData() {
    // Simulate loading data from API
    // In production, this would be fetch('/api/dashboard-data')
    
    loadApplications();
    updateStats();
    loadNotifications();
    initializeCharts();
  }

  function loadApplications() {
    // Sample data - replace with actual API call
    state.applications = [
      {
        id: 'APP001',
        applicant: 'John Doe',
        passport: 'A12345678',
        nationality: 'United States',
        visaCategory: 'Short Visit Visa (F4A)',
        arrivalDate: '2024-01-15',
        status: 'pending',
        submittedDate: '2024-03-10',
        documents: {
          passport: 'passport_scan.pdf',
          visa: 'entry_visa.pdf',
          stamp: 'entry_stamp.pdf',
          ticket: 'return_ticket.pdf'
        }
      },
      {
        id: 'APP002',
        applicant: 'Jane Smith',
        passport: 'B87654321',
        nationality: 'United Kingdom',
        visaCategory: 'Temporary Residence (R2A)',
        arrivalDate: '2023-11-20',
        status: 'approved',
        submittedDate: '2024-03-08',
        documents: {
          passport: 'passport_scan.pdf',
          visa: 'entry_visa.pdf',
          stamp: 'entry_stamp.pdf',
          ticket: 'return_ticket.pdf'
        }
      },
      {
        id: 'APP003',
        applicant: 'Michael Johnson',
        passport: 'C11223344',
        nationality: 'Canada',
        visaCategory: 'Short Visit Visa (F4B)',
        arrivalDate: '2024-02-01',
        status: 'under-review',
        submittedDate: '2024-03-12',
        documents: {
          passport: 'passport_scan.pdf',
          visa: 'entry_visa.pdf',
          stamp: 'entry_stamp.pdf',
          ticket: 'return_ticket.pdf'
        }
      },
      {
        id: 'APP004',
        applicant: 'Sarah Williams',
        passport: 'D55667788',
        nationality: 'Australia',
        visaCategory: 'Short Visit Visa (F5A)',
        arrivalDate: '2023-12-10',
        status: 'rejected',
        submittedDate: '2024-03-05',
        documents: {
          passport: 'passport_scan.pdf',
          visa: 'entry_visa.pdf',
          stamp: 'entry_stamp.pdf',
          ticket: 'return_ticket.pdf'
        }
      }
    ];

    renderApplications();
    renderCharts();
  }

  function updateStats() {
    const total = state.applications.length;
    const pending = state.applications.filter(app => app.status === 'pending').length;
    const approved = state.applications.filter(app => app.status === 'approved').length;
    const rejected = state.applications.filter(app => app.status === 'rejected').length;

    // Update stat cards
    updateStatValue('totalApplications', total);
    updateStatValue('pendingApplications', pending);
    updateStatValue('approvedApplications', approved);
    updateStatValue('rejectedApplications', rejected);
  }

  function updateStatValue(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
      element.textContent = value;
    }
  }

  function loadNotifications() {
    state.notifications = [
      { id: 1, message: 'New application received', time: '5 minutes ago', read: false },
      { id: 2, message: 'Application APP001 status updated', time: '1 hour ago', read: false },
      { id: 3, message: 'System maintenance scheduled', time: '2 hours ago', read: true }
    ];

    updateNotificationBadge();
  }

  function updateNotificationBadge() {
    const badge = document.getElementById('notificationBadge');
    const unreadCount = state.notifications.filter(n => !n.read).length;
    
    if (badge) {
      badge.textContent = unreadCount;
      badge.style.display = unreadCount > 0 ? 'flex' : 'none';
    }
  }

  // ===== FILTERING =====
  function initializeFilters() {
    // Set initial filter
    setFilter('all');
  }

  function setFilter(filter) {
    state.currentFilter = filter;

    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
      tab.classList.toggle('active', tab.dataset.filter === filter);
    });

    renderApplications();
  }

  function renderApplications() {
    const tbody = document.getElementById('applicationsTableBody');
    if (!tbody) return;

    let filteredApps = state.applications;

    if (state.currentFilter !== 'all') {
      filteredApps = state.applications.filter(app => app.status === state.currentFilter);
    }

    if (filteredApps.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="empty-state-cell">
            <div class="empty-state">
              <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
              </div>
              <h3 class="empty-title">No applications found</h3>
              <p class="empty-text">There are no applications matching your current filter.</p>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filteredApps.map(app => `
      <tr data-application-id="${app.id}">
        <td>
          <div style="font-weight: 600; color: var(--dark);">${app.applicant}</div>
          <div style="font-size: 0.85rem; color: var(--gray);">${app.passport}</div>
        </td>
        <td>${app.nationality}</td>
        <td>${app.visaCategory}</td>
        <td>${formatDate(app.arrivalDate)}</td>
        <td>${formatDate(app.submittedDate)}</td>
        <td>
          <span class="status-badge ${app.status}">
            ${getStatusLabel(app.status)}
          </span>
        </td>
        <td>
          <div class="action-btns">
            <button class="action-btn view" data-action="view" data-application-id="${app.id}" title="View Details">
              <i class="fas fa-eye"></i>
            </button>
            ${app.status === 'pending' || app.status === 'under-review' ? `
              <button class="action-btn approve" data-action="approve" data-application-id="${app.id}" title="Approve">
                <i class="fas fa-check"></i>
              </button>
              <button class="action-btn reject" data-action="reject" data-application-id="${app.id}" title="Reject">
                <i class="fas fa-times"></i>
              </button>
            ` : ''}
          </div>
        </td>
      </tr>
    `).join('');

    // Re-attach event listeners to new buttons
    tbody.querySelectorAll('.action-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const action = this.dataset.action;
        const applicationId = this.dataset.applicationId;
        
        if (action && applicationId) {
          handleAction(action, applicationId);
        }
      });
    });
  }

  function getStatusLabel(status) {
    const labels = {
      'pending': 'Pending',
      'under-review': 'Under Review',
      'approved': 'Approved',
      'rejected': 'Rejected'
    };
    return labels[status] || status;
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  }

  // ===== ACTION HANDLING =====
  function handleAction(action, applicationId) {
    const application = state.applications.find(app => app.id === applicationId);
    
    if (!application) {
      showToast('Application not found', 'error');
      return;
    }

    switch(action) {
      case 'view':
        openApplicationModal(application);
        break;
      case 'approve':
        openApprovalModal(application);
        break;
      case 'reject':
        openRejectionModal(application);
        break;
      case 'edit':
        // Handle edit action
        showToast('Edit functionality coming soon', 'warning');
        break;
    }
  }

  // ===== MODAL MANAGEMENT =====
  function initializeModals() {
    // Modal initialization if needed
  }

  function openApplicationModal(application) {
    const modal = document.getElementById('applicationModal');
    if (!modal) return;

    // Populate modal with application data
    document.getElementById('modalApplicantName').textContent = application.applicant;
    document.getElementById('modalPassportNumber').textContent = application.passport;
    document.getElementById('modalNationality').textContent = application.nationality;
    document.getElementById('modalVisaCategory').textContent = application.visaCategory;
    document.getElementById('modalArrivalDate').textContent = formatDate(application.arrivalDate);
    document.getElementById('modalSubmittedDate').textContent = formatDate(application.submittedDate);
    document.getElementById('modalStatus').textContent = getStatusLabel(application.status);
    document.getElementById('modalStatus').className = `status-badge ${application.status}`;

    // Store current application ID for actions
    modal.dataset.currentApplicationId = application.id;

    modal.classList.add('active');
  }

  function openApprovalModal(application) {
    const modal = document.getElementById('approvalModal');
    if (!modal) return;

    document.getElementById('approvalApplicantName').textContent = application.applicant;
    document.getElementById('approvalApplicationId').value = application.id;
    
    modal.classList.add('active');
  }

  function openRejectionModal(application) {
    const modal = document.getElementById('rejectionModal');
    if (!modal) return;

    document.getElementById('rejectionApplicantName').textContent = application.applicant;
    document.getElementById('rejectionApplicationId').value = application.id;
    
    modal.classList.add('active');
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
      modal.classList.remove('active');
    });
  }

  // ===== FORM HANDLING =====
  function handleApprovalSubmit(e) {
    e.preventDefault();
    
    const applicationId = document.getElementById('approvalApplicationId').value;
    const comments = document.getElementById('approvalComments').value;
    
    // Simulate API call
    setTimeout(() => {
      updateApplicationStatus(applicationId, 'approved', comments);
      closeAllModals();
      showToast('Application approved successfully', 'success');
      
      // Reset form
      e.target.reset();
    }, 500);
  }

  function handleRejectionSubmit(e) {
    e.preventDefault();
    
    const applicationId = document.getElementById('rejectionApplicationId').value;
    const reason = document.getElementById('rejectionReason').value;
    const comments = document.getElementById('rejectionComments').value;
    
    if (!reason) {
      showToast('Please select a rejection reason', 'error');
      return;
    }
    
    // Simulate API call
    setTimeout(() => {
      updateApplicationStatus(applicationId, 'rejected', comments, reason);
      closeAllModals();
      showToast('Application rejected', 'warning');
      
      // Reset form
      e.target.reset();
    }, 500);
  }

  function updateApplicationStatus(applicationId, status, comments, reason = null) {
    const application = state.applications.find(app => app.id === applicationId);
    
    if (application) {
      application.status = status;
      application.comments = comments;
      if (reason) application.rejectionReason = reason;
      application.updatedAt = new Date().toISOString();
      
      // Re-render
      renderApplications();
      updateStats();
      renderCharts();
      
      // Add notification
      addNotification(`Application ${applicationId} has been ${status}`);
    }
  }

  function addNotification(message) {
    const notification = {
      id: Date.now(),
      message: message,
      time: 'Just now',
      read: false
    };
    
    state.notifications.unshift(notification);
    updateNotificationBadge();
  }

  // ===== CHARTS =====
  function initializeCharts() {
    if (typeof Chart === 'undefined') return;
    renderCharts();
  }

  function renderCharts() {
    if (typeof Chart === 'undefined') return;

    const countryData = getCountryCounts();
    const topNationalities = getTopNationalities(6);
    const submissionTrend = getSubmissionTrend();
    const histogramData = getHistogramFromCounts(Object.values(countryData));

    renderApplicationsBarChart(countryData);
    renderNationalitiesPieChart(topNationalities);
    renderCountryHistogramChart(histogramData);
    renderSubmissionLineChart(submissionTrend);
  }

  function getCountryCounts() {
    return state.applications.reduce((acc, app) => {
      const key = app.nationality || 'Unknown';
      acc[key] = (acc[key] || 0) + 1;
      return acc;
    }, {});
  }

  function getTopNationalities(limit = 5) {
    const counts = getCountryCounts();
    return Object.entries(counts)
      .sort((a, b) => b[1] - a[1])
      .slice(0, limit);
  }

  function getSubmissionTrend() {
    const trend = state.applications.reduce((acc, app) => {
      const dateKey = app.submittedDate || '';
      if (!dateKey) return acc;
      acc[dateKey] = (acc[dateKey] || 0) + 1;
      return acc;
    }, {});

    return Object.entries(trend)
      .sort((a, b) => new Date(a[0]) - new Date(b[0]))
      .map(([date, count]) => ({ date, count }));
  }

  function getHistogramFromCounts(values) {
    const bins = {
      '1': 0,
      '2': 0,
      '3': 0,
      '4+': 0
    };

    values.forEach(value => {
      if (value <= 1) bins['1'] += 1;
      else if (value === 2) bins['2'] += 1;
      else if (value === 3) bins['3'] += 1;
      else bins['4+'] += 1;
    });

    return bins;
  }

  function destroyChart(chartRef) {
    if (chartRef) {
      chartRef.destroy();
    }
  }

  function renderApplicationsBarChart(countryData) {
    const canvas = document.getElementById('applicationsChart');
    if (!canvas) return;

    destroyChart(state.charts.applicationsBar);
    state.charts.applicationsBar = new Chart(canvas, {
      type: 'bar',
      data: {
        labels: Object.keys(countryData),
        datasets: [{
          label: 'Applications',
          data: Object.values(countryData),
          backgroundColor: 'rgba(30, 132, 73, 0.7)',
          borderColor: 'rgba(30, 132, 73, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  }

  function renderNationalitiesPieChart(topNationalities) {
    const canvas = document.getElementById('nationalitiesPieChart');
    if (!canvas) return;

    const labels = topNationalities.map(([name]) => name);
    const data = topNationalities.map(([, count]) => count);

    destroyChart(state.charts.nationalitiesPie);
    state.charts.nationalitiesPie = new Chart(canvas, {
      type: 'pie',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: [
            '#1E8449', '#3498DB', '#F39C12', '#C0392B', '#8E44AD', '#16A085'
          ]
        }]
      },
      options: {
        responsive: true
      }
    });
  }

  function renderCountryHistogramChart(histogramData) {
    const canvas = document.getElementById('countryHistogramChart');
    if (!canvas) return;

    destroyChart(state.charts.histogram);
    state.charts.histogram = new Chart(canvas, {
      type: 'bar',
      data: {
        labels: Object.keys(histogramData),
        datasets: [{
          label: 'Number of Countries',
          data: Object.values(histogramData),
          backgroundColor: 'rgba(52, 152, 219, 0.6)',
          borderColor: 'rgba(52, 152, 219, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  }

  function renderSubmissionLineChart(submissionTrend) {
    const canvas = document.getElementById('submissionLineChart');
    if (!canvas) return;

    destroyChart(state.charts.submissionLine);
    state.charts.submissionLine = new Chart(canvas, {
      type: 'line',
      data: {
        labels: submissionTrend.map(item => formatDate(item.date)),
        datasets: [{
          label: 'Submitted Applications',
          data: submissionTrend.map(item => item.count),
          borderColor: 'rgba(192, 57, 43, 1)',
          backgroundColor: 'rgba(192, 57, 43, 0.15)',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  }

  // ===== TOAST NOTIFICATIONS =====
  function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) {
      // Create container if it doesn't exist
      const newContainer = document.createElement('div');
      newContainer.id = 'toastContainer';
      newContainer.className = 'toast-container';
      document.body.appendChild(newContainer);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icons = {
      success: 'fa-check-circle',
      error: 'fa-exclamation-circle',
      warning: 'fa-exclamation-triangle',
      info: 'fa-info-circle'
    };

    toast.innerHTML = `
      <div class="toast-icon">
        <i class="fas ${icons[type]}"></i>
      </div>
      <div class="toast-content">
        <div class="toast-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
        <div class="toast-message">${message}</div>
      </div>
      <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `;

    const toastContainer = document.getElementById('toastContainer') || newContainer;
    toastContainer.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }

  // ===== UTILITY FUNCTIONS =====
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // ===== EXPOSED API =====
  window.Dashboard = {
    toggleSidebar,
    setFilter,
    showToast,
    closeAllModals,
    refreshData: loadDashboardData
  };

})();
