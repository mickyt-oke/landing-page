<!-- <div class="modal-footer">
                <button class="btn btn-outline modal-close-btn">Close</button>
                <button class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Application
                </button>
            </div> -->
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    <!-- Include modal functionality from main app -->
    <script>
        // Modal trigger functionality
        document.querySelectorAll('.modal-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalName = this.dataset.modal;
                if (modalName === 'register') {
                    // Redirect to index.php with register modal open
                    window.location.href = 'index.php?action=register';
                }
            });
        });

        // Modal close functionality
        document.querySelectorAll('.modal-close, .modal-close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    modal.classList.remove('active');
                });
            });
        });
    </script>
      <!-- Carousel Script -->
  <script src="{{ asset('assets/js/carousel.js') }}" defer></script>
  <!-- Main Application Script -->
  <script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>