<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Transparansi Mutu') — {{ config('app.name', 'SPMI') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --secondary-bg: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.85);

            --grad-primary: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            --grad-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff;
            color: #1e293b;
            scroll-behavior: smooth;
        }

        /* Premium Navbar */
        .navbar-public {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            padding: 1.25rem 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-public.scrolled {
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .nav-link {
            font-weight: 700;
            font-size: 0.95rem;
            color: #475569 !important;
            margin: 0 0.75rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 20px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Footer Premium */
        .footer-premium {
            background: #0f172a;
            color: rgba(255, 255, 255, 0.6);
            padding: 100px 0 50px;
            position: relative;
            overflow: hidden;
        }

        .footer-premium::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        .footer-title {
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            letter-spacing: 0.05em;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }

        .social-btn {
            width: 40px; height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
            color: white;
        }

        .btn-portal-primary {
            background: var(--primary-color);
            color: #fff;
            border-radius: 14px;
            padding: 10px 24px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-portal-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
            color: white;
        }

        @media (max-width: 991px) {
            .navbar-brand small { display: none; }
            .navbar-collapse {
                background: white;
                margin-top: 1rem;
                padding: 1.5rem;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
                @if(isset($appSettings['logo']) && $appSettings['logo'])
                    <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" height="42"
                        class="d-inline-block align-top">
                @else
                    <div class="bg-primary text-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                @endif
                <div>
                    <span class="fw-800 fs-4 d-block lh-1 text-dark">{{ $appSettings['appName'] ?? 'SPMI' }}</span>
                    <small class="text-muted small fw-500">Quality Intelligence Portal</small>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home.documents') ? 'active' : '' }}"
                            href="{{ route('home.documents') }}">Repositori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#progress">Analisis</a>
                    </li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-portal-primary">
                                <i class="bi bi-columns-gap me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-portal-primary">
                                <i class="bi bi-lock-fill me-2"></i>Internal Access
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-premium">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if(isset($appSettings['logo']) && $appSettings['logo'])
                            <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" height="40"
                                class="brightness-0 invert">
                        @else
                            <div class="bg-primary text-white p-2 rounded-3">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                        @endif
                        <span class="fw-800 fs-3 text-white">{{ $appSettings['appName'] ?? 'SPMI' }}</span>
                    </div>
                    <p class="mb-4">Sistem Intelligence Penjaminan Mutu Internal terpadu untuk monitoring berkelanjutan dan penguatan budaya mutu institusi.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <h5 class="footer-title">NAVIGASI</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                        <li><a href="{{ route('home.documents') }}" class="footer-link">Repositori</a></li>
                        <li><a href="#stats" class="footer-link">Statistik Mutu</a></li>
                        <li><a href="#progress" class="footer-link">Peta Kemajuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h5 class="footer-title">LAYANAN</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="#" class="footer-link">Audit Internal</a></li>
                        <li><a href="#" class="footer-link">Monitoring IKU</a></li>
                        <li><a href="#" class="footer-link">Rapat Tinjauan</a></li>
                        <li><a href="#" class="footer-link">Evaluasi Diri</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="footer-title">KONTAK KAMI</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex gap-3 align-items-start">
                            <i class="bi bi-geo-alt-fill text-primary"></i>
                            <span class="small">Gedung Pusat Penjaminan Mutu, Lt. 2 Kampus Utama Politeknik Krakatau</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-envelope-fill text-primary"></i>
                            <span class="small">mutu@polka.ac.id</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-telephone-fill text-primary"></i>
                            <span class="small">+62 254 123 4567</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 pt-5 border-top border-white border-opacity-10">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 small">© {{ date('Y') }} <strong>Kantor Penjaminan Mutu</strong>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <div class="d-flex justify-content-center justify-content-md-end gap-4 small">
                            <a href="#" class="footer-link p-0">Privacy Policy</a>
                            <a href="#" class="footer-link p-0">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                document.querySelector('.navbar-public').classList.add('scrolled');
            } else {
                document.querySelector('.navbar-public').classList.remove('scrolled');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>