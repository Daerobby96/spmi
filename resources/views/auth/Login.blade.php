<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $appSettings['appName'] ?? 'SPMI' }}</title>
    @if(isset($appSettings['favicon']) && $appSettings['favicon'])
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $appSettings['favicon']) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/spmi.css') }}" rel="stylesheet">
    <style>
        @media (max-width: 991.98px) {
            body.login-page {
                background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%) !important;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .login-wrapper {
                min-height: auto;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-right {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
                padding: 2.5rem 2rem !important;
                width: 100%;
                max-width: 420px;
                margin: auto;
            }
            .login-logo-sm {
                background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
                box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
            }
        }
    </style>
</head>
<body class="login-page">

<div class="login-wrapper">

    <!-- Left Panel -->
    <div class="login-left d-none d-lg-flex">
        <div class="login-left-content">
            <div class="login-logo mb-4">
                @if(isset($appSettings['logo']) && $appSettings['logo'])
                    <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" class="img-fluid mb-2" style="max-height: 80px;">
                @else
                    <i class="bi bi-shield-check"></i>
                @endif
            </div>
            <h2 class="fw-bold mb-3">{{ $appSettings['appName'] ?? 'Sistem Penjaminan Mutu Internal' }}</h2>
            <p class="text-white-50 mb-5">
                Platform terintegrasi untuk pengelolaan audit mutu, dokumen standar,
                monitoring, dan pelaporan penjaminan mutu perguruan tinggi.
            </p>
            <div class="login-features">
                <div class="login-feature-item">
                    <div class="feature-icon"><i class="bi bi-clipboard2-check"></i></div>
                    <div>
                        <div class="fw-semibold">Audit Mutu Internal</div>
                        <div class="text-white-50 small">Kelola siklus audit end-to-end</div>
                    </div>
                </div>
                <div class="login-feature-item">
                    <div class="feature-icon"><i class="bi bi-folder2-open"></i></div>
                    <div>
                        <div class="fw-semibold">Manajemen Dokumen</div>
                        <div class="text-white-50 small">Dokumen SOP, SK, PM terpusat</div>
                    </div>
                </div>
                <div class="login-feature-item">
                    <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <div>
                        <div class="fw-semibold">Monitoring & Evaluasi</div>
                        <div class="text-white-50 small">Pantau IKU/IKT secara real-time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="login-right">
        <div class="login-form-wrapper">
            <!-- Mobile Logo -->
            <div class="text-center d-lg-none mb-4">
                <div class="login-logo-sm">
                    @if(isset($appSettings['logo']) && $appSettings['logo'])
                        <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" height="50">
                    @else
                        <i class="bi bi-shield-check"></i>
                    @endif
                </div>
                <h5 class="fw-bold mt-2">{{ $appSettings['appName'] ?? 'SPMI' }}</h5>
            </div>

            <h4 class="fw-bold mb-1">Selamat Datang</h4>
            <p class="text-muted mb-4">Masuk ke akun SPMI Anda</p>

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input
                            type="email"
                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@institusi.ac.id"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label fw-medium">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <button class="btn btn-light border" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100 btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
                Lupa password? Hubungi <strong>Administrator</strong>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });


</script>
</body>
</html>