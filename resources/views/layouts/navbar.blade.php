<nav class="navbar-top sticky top-0 z-50 glass border-b border-slate-200/50 px-4 py-2">
    <div class="d-flex align-items-center gap-3">
        <!-- Toggle Sidebar -->
        @auth
        <button class="btn btn-icon sidebar-toggle-btn" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        @endauth

        <!-- Breadcrumb (desktop) -->
        <div class="d-none d-md-block">
            <h6 class="navbar-page-title mb-0">@yield('title', 'Dashboard')</h6>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Portal Publik -->
        <a href="{{ route('home') }}" class="btn btn-icon text-muted me-1 d-md-flex align-items-center d-none" title="Portal Publik">
            <i class="bi bi-globe fs-5"></i>
        </a>

        @auth
        <!-- Scan QR Pintasan -->
        <a href="{{ route('scan.index') }}" class="btn btn-icon text-primary me-1 d-md-flex align-items-center d-none" title="Scan QR Code">
            <i class="bi bi-qr-code-scan fs-5"></i>
        </a>

        <!-- Notifikasi -->
        @php
            $deadlineTemuan = $notifications['deadlineTemuan'] ?? 0;
            $overdueTemuan = $notifications['overdueTemuan'] ?? 0;
            $pendingDokumen = $notifications['pendingDokumen'] ?? 0;
            $totalNotif = $notifications['total'] ?? 0;
        @endphp
        <div class="dropdown">
            <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
                @if($totalNotif > 0)
                    <span class="badge bg-danger notification-badge">{{ $totalNotif }}</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notifikasi</span>
                    @if($totalNotif > 0)
                        <span class="badge bg-danger">{{ $totalNotif }}</span>
                    @endif
                </h6>
                @if($totalNotif > 0)
                    @if(isset($notifications['dbNotifications']))
                        @foreach($notifications['dbNotifications'] as $dbNotif)
                            <a href="{{ $dbNotif->data['url'] ?? '#' }}" class="dropdown-item">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="notif-icon bg-primary-subtle text-primary">
                                        <i class="bi bi-info-circle"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="small fw-semibold text-truncate">{{ $dbNotif->data['message'] ?? 'Notifikasi Baru' }}</div>
                                        <div class="text-muted" style="font-size:.7rem">
                                            {{ $dbNotif->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if($overdueTemuan > 0)
                        <a href="{{ route('tindak-lanjut.index', ['deadline' => 0]) }}" class="dropdown-item">
                            <div class="d-flex align-items-center gap-2">
                                <div class="notif-icon bg-danger-subtle text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="small fw-semibold text-danger">Temuan Terlambat!</div>
                                    <div class="text-muted" style="font-size:.7rem">
                                        {{ $overdueTemuan }} temuan melebihi batas waktu
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                @else
                    <div class="dropdown-item text-muted text-center small py-3">
                        <i class="bi bi-check-circle text-success me-1"></i>Tidak ada notifikasi
                    </div>
                @endif
            </div>
        </div>

        <!-- Divider -->
        <div class="vr opacity-25"></div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 user-dropdown-btn hover:bg-slate-100/50 transition-colors duration-200" data-bs-toggle="dropdown">
                <div class="user-avatar-sm shadow-sm ring-2 ring-white/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="small fw-semibold lh-1">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div class="text-muted" style="font-size:.7rem">{{ auth()->user()->role->display_name }}</div>
                </div>
                <i class="bi bi-chevron-down small text-muted group-hover:translate-y-0.5 transition-transform"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <div class="dropdown-item-text">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                        <div class="text-muted small">{{ auth()->user()->unit_kerja }}</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2"></i>Profil Saya
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </a>
        @endauth
    </div>
</nav>