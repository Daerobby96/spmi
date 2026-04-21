<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        // Gunakan data dari View Composer (cached)
        $appName = $appSettings['appName'] ?? 'SPMI';
        $themePrimary = $appSettings['themePrimary'] ?? '#4e73df';
        $themeSidebar = $appSettings['themeSidebar'] ?? 'dark';
        $logo = $appSettings['logo'] ?? null;
        $favicon = $appSettings['favicon'] ?? null;
    @endphp
    <title>@yield('title', 'Dashboard') — {{ $appName }}</title>

    <!-- Favicon -->
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite (Tailwind 4 & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS (Legacy) -->
    <link href="{{ asset('css/spmi.css') }}" rel="stylesheet">

    <!-- Dynamic Theme -->
    <style>
        :root {
            --primary-color: {{ $themePrimary }};
            --primary-color-rgb: {{ implode(',', sscanf($themePrimary, "#%02x%02x%02x")) }};
        }
        /* Override Bootstrap primary colors */
        .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .btn-primary:hover, .btn-primary:active {
            filter: brightness(0.85);
        }
        .btn-outline-primary {
            color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
            color: #fff !important;
        }
        .text-primary { color: {{ $themePrimary }} !important; }
        .bg-primary { background-color: {{ $themePrimary }} !important; }
        .border-primary { border-color: {{ $themePrimary }} !important; }
        .badge.bg-primary { background-color: {{ $themePrimary }} !important; }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: {{ $themePrimary }} !important;
        }
        .page-link.active, .active > .page-link {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: {{ $themePrimary }} !important;
            box-shadow: 0 0 0 0.25rem {{ $themePrimary }}40 !important;
        }
        .card-header-custom {
            border-left: 3px solid {{ $themePrimary }};
        }
        .sidebar-item.active > .sidebar-link {
            background: linear-gradient(90deg, {{ $themePrimary }}20, transparent);
            border-left-color: {{ $themePrimary }};
        }
        .sidebar-item.active > .sidebar-link i {
            color: {{ $themePrimary }};
        }
        a { color: {{ $themePrimary }}; }
        a:hover { color: {{ $themePrimary }}; filter: brightness(0.85); }

        /* Sidebar Theme - Light */
        .sidebar-theme-light .sidebar-wrapper {
            background: #f8f9fa !important;
            border-right: 1px solid #dee2e6;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-brand {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-brand .brand-name,
        .sidebar-theme-light .sidebar-wrapper .sidebar-brand .brand-sub {
            color: #333 !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-brand .brand-logo {
            color: {{ $themePrimary }} !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-periode {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            color: #333 !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-periode span {
            color: #333 !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-heading {
            color: #6c757d !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-link {
            color: #495057 !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-link:hover {
            background: rgba(0,0,0,0.05);
            color: {{ $themePrimary }} !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-link i {
            color: #6c757d !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-item.active > .sidebar-link {
            background: {{ $themePrimary }}15;
            color: {{ $themePrimary }} !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-item.active > .sidebar-link i {
            color: {{ $themePrimary }} !important;
        }
        .sidebar-theme-light .sidebar-wrapper .sidebar-footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
        }
        .sidebar-theme-light .sidebar-wrapper .user-mini-name {
            color: #333 !important;
        }
        .sidebar-theme-light .sidebar-wrapper .user-mini-role {
            color: #6c757d !important;
        }
        .sidebar-theme-light .sidebar-wrapper .dropdown-menu-dark {
            background: #fff;
            border: 1px solid #dee2e6;
        }
        .sidebar-theme-light .sidebar-wrapper .dropdown-menu-dark .dropdown-item {
            color: #333;
        }
        .sidebar-theme-light .sidebar-wrapper .dropdown-menu-dark .dropdown-item:hover {
            background: #f8f9fa;
        }
        .sidebar-theme-light .sidebar-wrapper .badge.bg-danger {
            background-color: #dc3545 !important;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ── Wrapper ── -->
<div class="d-flex {{ $themeSidebar === 'light' ? 'sidebar-theme-light' : '' }}" id="wrapper">

    <!-- ── Sidebar ── -->
    @include('layouts.sidebar')

    <!-- ── Page Content ── -->
    <div id="page-content-wrapper">

        <!-- ── Navbar ── -->
        @include('layouts.navbar')

        <!-- ── Main Content ── -->
        <main class="main-content min-h-[calc(100vh-var(--topbar-height))]">

            {{-- Breadcrumb --}}
            @hasSection('breadcrumb')
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            {{-- Page Header --}}
            @hasSection('page-title')
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="page-title mb-0">@yield('page-title')</h4>
                    @hasSection('page-subtitle')
                    <p class="text-muted small mb-0 mt-1">@yield('page-subtitle')</p>
                    @endif
                </div>
                <div class="page-actions">
                    @yield('page-actions')
                </div>
            </div>
            @endif

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Content --}}
            @yield('content')
        </main>

        <!-- ── Footer ── -->
        <footer class="main-footer text-center text-muted small py-3">
            &copy; {{ date('Y') }} SPMI — Sistem Penjaminan Mutu Internal. All rights reserved.
        </footer>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Toggle sidebar - works differently on mobile vs desktop
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        const wrapper = document.getElementById('wrapper');
        const isMobile = window.innerWidth < 992;
        
        if (isMobile) {
            // Mobile: toggle sidebar open/close
            wrapper.classList.toggle('sidebar-open');
        } else {
            // Desktop: toggle sidebar collapsed/expanded
            wrapper.classList.toggle('sidebar-collapsed');
            // Save preference
            localStorage.setItem('sidebarCollapsed', wrapper.classList.contains('sidebar-collapsed'));
        }
    });

    // Close sidebar when clicking overlay (mobile only)
    document.querySelector('.sidebar-overlay')?.addEventListener('click', function () {
        document.getElementById('wrapper').classList.remove('sidebar-open');
    });

    // Close sidebar when clicking close button (mobile only)
    document.getElementById('sidebarClose')?.addEventListener('click', function () {
        document.getElementById('wrapper').classList.remove('sidebar-open');
    });

    // Restore sidebar state on page load (desktop only)
    if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
        document.getElementById('wrapper').classList.add('sidebar-collapsed');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert.alert-success, .alert.alert-info').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>