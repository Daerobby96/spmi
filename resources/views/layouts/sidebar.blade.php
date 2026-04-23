@auth
<nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

    <!-- Logo - menggunakan data dari View Composer (cached) -->
    @php
        $appName = $sidebarSettings['appName'] ?? 'SPMI';
        $appTagline = $sidebarSettings['appTagline'] ?? 'Penjaminan Mutu';
        $logo = $sidebarSettings['logo'] ?? null;
        $periode = $periodeData['aktif'] ?? null;
        $allPeriodes = $periodeData['all'] ?? collect();
    @endphp
    <div class="sidebar-brand">
        <div class="brand-logo">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
            @else
                <i class="bi bi-shield-check"></i>
            @endif
        </div>
        <div class="brand-text">
            <span class="brand-name">{{ $appName }}</span>
            <span class="brand-sub">{{ $appTagline }}</span>
        </div>
        <button class="sidebar-close-btn d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Periode Aktif -->
    <div class="sidebar-periode dropdown">
        <button class="btn btn-link p-0 text-decoration-none w-100 text-start d-flex align-items-center gap-2" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-calendar3"></i>
            <span>{{ $periode?->nama ?? 'Pilih Periode' }}</span>
            <i class="bi bi-chevron-down ms-auto small"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-dark">
            @foreach($allPeriodes as $p)
                <li>
                    <form action="{{ route('set-periode') }}" method="POST">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $p->id }}">
                        <button type="submit" class="dropdown-item {{ $p->is_aktif ? 'active' : '' }}">
                            <i class="bi {{ $p->is_aktif ? 'bi-check-circle-fill text-success' : 'bi-circle' }} me-2"></i>
                            {{ $p->nama }}
                        </button>
                    </form>
                </li>
            @endforeach
            @if($allPeriodes->isEmpty())
                <li><span class="dropdown-item text-muted small">Belum ada periode</span></li>
            @endif
        </ul>
    </div>

    <!-- Navigation Menu -->
    <ul class="sidebar-menu list-unstyled">

        {{-- Dashboard --}}
        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} group ring-0 outline-none">
            <a href="{{ route('dashboard') }}" class="sidebar-link group-hover:bg-white/5 transition-all duration-200" data-title="Dashboard">
                <i class="bi bi-speedometer2 group-hover:scale-110 transition-transform duration-200"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Dokumen & Standar --}}
        @if(auth()->user()->canManageDokumen() || auth()->user()->isSuperAdmin())
        <li class="sidebar-heading">Dokumen & Standar</li>

        <li class="sidebar-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
            <a href="{{ route('dokumen.index') }}" class="sidebar-link" data-title="Dokumen Mutu">
                <i class="bi bi-folder2-open"></i>
                <span>Dokumen Mutu</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('standar.*') ? 'active' : '' }}">
            <a href="{{ route('standar.index') }}" class="sidebar-link" data-title="Standar Mutu">
                <i class="bi bi-bookmark-check"></i>
                <span>Standar Mutu</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('kategori-dokumen.*') ? 'active' : '' }}">
            <a href="{{ route('kategori-dokumen.index') }}" class="sidebar-link" data-title="Kategori Dokumen">
                <i class="bi bi-tags"></i>
                <span>Kategori Dokumen</span>
            </a>
        </li>
        @endif

        {{-- Monitoring & Evaluasi --}}
        <li class="sidebar-heading">Monitoring & Evaluasi</li>

        @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
        <li class="sidebar-item {{ request()->routeIs('indikator-kinerja.*') ? 'active' : '' }}">
            <a href="{{ route('indikator-kinerja.index') }}" class="sidebar-link" data-title="Indikator Kinerja">
                <i class="bi bi-bullseye"></i>
                <span>Indikator Kinerja</span>
            </a>
        </li>
        @endif

        <li class="sidebar-item {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
            <a href="{{ route('monitoring.index') }}" class="sidebar-link" data-title="Monitoring IKU/IKT">
                <i class="bi bi-bar-chart-line"></i>
                <span>Monitoring IKU/IKT</span>
            </a>
        </li>

        @if(auth()->user()->canManageAudit() || auth()->user()->isPimpinan())
        <li class="sidebar-item {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
            <a href="{{ route('evaluasi.index') }}" class="sidebar-link" data-title="Evaluasi">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Evaluasi</span>
            </a>
        </li>
        @endif


        {{-- Audit Mutu Internal --}}
        @if(auth()->user()->canManageAudit() || auth()->user()->isAuditee() || auth()->user()->isPimpinan())
        <li class="sidebar-heading">Audit Mutu Internal</li>

        @if(auth()->user()->canManageAudit() || auth()->user()->isPimpinan())
        <li class="sidebar-item {{ request()->routeIs('audit.*') ? 'active' : '' }}">
            <a href="{{ route('audit.index') }}" class="sidebar-link" data-title="Pelaksanaan Audit">
                <i class="bi bi-clipboard2-check"></i>
                <span>Pelaksanaan Audit</span>
            </a>
        </li>
        @endif

        <li class="sidebar-item {{ request()->routeIs('tindak-lanjut.*') ? 'active' : '' }}">
            <a href="{{ route('tindak-lanjut.index') }}" class="sidebar-link" data-title="Tindak Lanjut">
                <i class="bi bi-arrow-repeat"></i>
                <span>Tindak Lanjut</span>
                @if($openTemuanCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $openTemuanCount }}</span>
                @endif
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('rtm.*') ? 'active' : '' }} group">
            <a href="{{ route('rtm.index') }}" class="sidebar-link group-hover:bg-white/5 transition-all duration-200" data-title="Rapat Tinjauan Manajemen (RTM)">
                <i class="bi bi-people-fill group-hover:scale-110 transition-transform duration-200"></i>
                <span>Tinjauan Manajemen (RTM)</span>
            </a>
        </li>
        @endif

        {{-- Laporan --}}
        <li class="sidebar-heading">Pelaporan</li>

        <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.index') }}" class="sidebar-link" data-title="Laporan">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan</span>
            </a>
        </li>

        {{-- Partisipasi User (Survei) --}}
        <li class="sidebar-heading">Umpan Balik</li>
        @if(!auth()->user()->isSuperAdmin())
        <li class="sidebar-item {{ request()->routeIs('user-kuesioner.*') ? 'active' : '' }}">
            <a href="{{ route('user-kuesioner.index') }}" class="sidebar-link" data-title="Survei & Kuesioner">
                <i class="bi bi-clipboard2-data"></i>
                <span>Survei & Kuesioner</span>
            </a>
        </li>
        @endif
        <li class="sidebar-item {{ request()->routeIs('kinerja-dosen.*') ? 'active' : '' }}">
            <a href="{{ route('kinerja-dosen.index') }}" class="sidebar-link" data-title="Kinerja Dosen (EDOM)">
                <i class="bi bi-person-badge"></i>
                <span>Kinerja Dosen (EDOM)</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('tracer-study.*') ? 'active' : '' }}">
            <a href="{{ route('tracer-study.index') }}" class="sidebar-link" data-title="Tracer Study (Alumni)">
                <i class="bi bi-mortarboard"></i>
                <span>Tracer Study (Alumni)</span>
            </a>
        </li>

        {{-- Manajemen (Super Admin) --}}
        @if(auth()->user()->isSuperAdmin())
        <li class="sidebar-heading">Administrasi</li>

        <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="sidebar-link" data-title="Manajemen User">
                <i class="bi bi-people"></i>
                <span>Manajemen User</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('periode.*') ? 'active' : '' }}">
            <a href="{{ route('periode.index') }}" class="sidebar-link" data-title="Manajemen Periode">
                <i class="bi bi-calendar3"></i>
                <span>Manajemen Periode</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('kuesioner.*') ? 'active' : '' }}">
            <a href="{{ route('kuesioner.index') }}" class="sidebar-link" data-title="Manajemen Kuesioner">
                <i class="bi bi-ui-checks"></i>
                <span>Manajemen Kuesioner</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}" class="sidebar-link" data-title="Pengaturan Aplikasi">
                <i class="bi bi-palette"></i>
                <span>Pengaturan Aplikasi</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
            <a href="{{ route('activity-log.index') }}" class="sidebar-link" data-title="Activity Log">
                <i class="bi bi-clock-history"></i>
                <span>Activity Log</span>
            </a>
        </li>
        @endif
    </ul>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-mini-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-mini-info">
                <span class="user-mini-name">{{ auth()->user()->name }}</span>
                <span class="user-mini-role">{{ auth()->user()->role->display_name }}</span>
            </div>
        </div>
    </div>
    @endauth
</nav>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>