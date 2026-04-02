        </div>{{-- /.dashboard-content --}}
    </main>
</div>{{-- /.dashboard-container --}}

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

@if(request()->routeIs('admin.dashboard'))
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endif
<!-- Dashboard base (sidebar, toast, modal helpers) -->
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@if(request()->routeIs('admin.reviewer.dashboard'))
<!-- Reviewer dashboard -->
<script src="{{ asset('assets/js/reviewer-dashboard.js') }}"></script>
@else
<!-- Admin dashboard -->
<script src="{{ asset('assets/js/admin-dashboard.js') }}"></script>
@endif
</body>
</html>
