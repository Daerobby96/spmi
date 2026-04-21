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
    <ul class="sidebar-menu list-unstyled" id="sidebarAccordion">

        {{-- Dashboard --}}
        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} group ring-0 outline-none">
            <a href="{{ route('dashboard') }}" class="sidebar-link group-hover:bg-white/5 transition-all duration-200" data-title="Dashboard">
                <i class="bi bi-speedometer2 group-hover:scale-110 transition-transform duration-200"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Dokumen Modul --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs(['dokumen.*', 'standar.*', 'kategori-dokumen.*']) ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" data-bs-target="#menuDokumen" role="button" aria-expanded="false">
                <i class="bi bi-folder2-open"></i>
                <span>Dokumen & Standar</span>
                <i class="bi bi-chevron-down ms-auto small"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['dokumen.*', 'standar.*', 'kategori-dokumen.*']) ? 'show' : '' }}" 
                 id="menuDokumen" data-bs-parent="#sidebarAccordion">
                <ul class="list-unstyled ps-4 pb-2 bg-white/5">
                    <li><a href="{{ route('dokumen.index') }}" class="sub-link {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">Daftar Dokumen</a></li>
                    <li><a href="{{ route('standar.index') }}" class="sub-link {{ request()->routeIs('standar.*') ? 'active' : '' }}">Standar Mutu</a></li>
                    @if(auth()->user()->isSuperAdmin())
                    <li><a href="{{ route('kategori-dokumen.index') }}" class="sub-link {{ request()->routeIs('kategori-dokumen.*') ? 'active' : '' }}">Kategori</a></li>
                    @endif
                </ul>
            </div>
        </li>

        {{-- Siklus PPEPP (AMI & Monev) --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs(['monitoring.*', 'evaluasi.*', 'audit.*', 'tindak-lanjut.*', 'rtm.*', 'indikator-kinerja.*']) ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" data-bs-target="#menuPPEPP" role="button" aria-expanded="false">
                <i class="bi bi-arrow-repeat"></i>
                <span>Siklus PPEPP</span>
                <i class="bi bi-chevron-down ms-auto small"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['monitoring.*', 'evaluasi.*', 'audit.*', 'tindak-lanjut.*', 'rtm.*', 'indikator-kinerja.*']) ? 'show' : '' }}" 
                 id="menuPPEPP" data-bs-parent="#sidebarAccordion">
                <ul class="list-unstyled ps-4 pb-2 bg-white/5">
                    @if(auth()->user()->isSuperAdmin())
                        <li><a href="{{ route('indikator-kinerja.index') }}" class="sub-link {{ request()->routeIs('indikator-kinerja.*') ? 'active' : '' }}">Indikator (IKU)</a></li>
                    @endif
                    <li><a href="{{ route('monitoring.index') }}" class="sub-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">Monitoring</a></li>
                    @if(auth()->user()->canManageAudit())
                    <li><a href="{{ route('evaluasi.index') }}" class="sub-link {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">Evaluasi</a></li>
                    @endif
                    <li><a href="{{ route('audit.index') }}" class="sub-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">Pelaksanaan AMI</a></li>
                    <li><a href="{{ route('tindak-lanjut.index') }}" class="sub-link {{ request()->routeIs('tindak-lanjut.*') ? 'active' : '' }}">
                        Tindak Lanjut @if($openTemuanCount > 0) <span class="badge bg-danger ms-1 small px-1">{{ $openTemuanCount }}</span> @endif
                    </a></li>
                    <li><a href="{{ route('rtm.index') }}" class="sub-link {{ request()->routeIs('rtm.*') ? 'active' : '' }}">RTM</a></li>
                </ul>
            </div>
        </li>

        {{-- Umpan Balik --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs(['user-kuesioner.*', 'kinerja-dosen.*', 'tracer-study.*']) ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" data-bs-target="#menuFeedback" role="button" aria-expanded="false">
                <i class="bi bi-chat-heart"></i>
                <span>Umpan Balik</span>
                <i class="bi bi-chevron-down ms-auto small"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['user-kuesioner.*', 'kinerja-dosen.*', 'tracer-study.*']) ? 'show' : '' }}" 
                 id="menuFeedback" data-bs-parent="#sidebarAccordion">
                <ul class="list-unstyled ps-4 pb-2 bg-white/5">
                    <li><a href="{{ route('user-kuesioner.index') }}" class="sub-link {{ request()->routeIs('user-kuesioner.*') ? 'active' : '' }}">Survei Kepuasan</a></li>
                    <li><a href="{{ route('kinerja-dosen.index') }}" class="sub-link {{ request()->routeIs('kinerja-dosen.*') ? 'active' : '' }}">Kinerja Dosen</a></li>
                    <li><a href="{{ route('tracer-study.index') }}" class="sub-link {{ request()->routeIs('tracer-study.*') ? 'active' : '' }}">Tracer Study</a></li>
                </ul>
            </div>
        </li>

        {{-- Laporan --}}
        <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.index') }}" class="sidebar-link">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Pusat Laporan</span>
            </a>
        </li>

        {{-- Administrasi --}}
        @if(auth()->user()->isSuperAdmin())
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs(['users.*', 'periode.*', 'kuesioner.*', 'settings.*', 'activity-log.*']) ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" data-bs-target="#menuAdmin" role="button" aria-expanded="false">
                <i class="bi bi-gear"></i>
                <span>Administrasi</span>
                <i class="bi bi-chevron-down ms-auto small"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['users.*', 'periode.*', 'kuesioner.*', 'settings.*', 'activity-log.*']) ? 'show' : '' }}" 
                 id="menuAdmin" data-bs-parent="#sidebarAccordion">
                <ul class="list-unstyled ps-4 pb-2 bg-white/5">
                    <li><a href="{{ route('users.index') }}" class="sub-link {{ request()->routeIs('users.*') ? 'active' : '' }}">User & Role</a></li>
                    <li><a href="{{ route('periode.index') }}" class="sub-link {{ request()->routeIs('periode.*') ? 'active' : '' }}">Periode</a></li>
                    <li><a href="{{ route('kuesioner.index') }}" class="sub-link {{ request()->routeIs('kuesioner.*') ? 'active' : '' }}">Manajemen Kuesioner</a></li>
                    <li><a href="{{ route('settings.index') }}" class="sub-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">Pengaturan</a></li>
                    <li><a href="{{ route('activity-log.index') }}" class="sub-link {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">Log Aktivitas</a></li>
                </ul>
            </div>
        </li>
        @endif
    </ul>style>
        .sub-link {
            display: block;
            padding: 8px 12px;
            color: #bdc3c7 !important;
            text-decoration: none;
            font-size: 0.85rem;
            border-left: 2px solid transparent;
            transition: all 0.3s;
        }
        .sub-link:hover, .sub-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.05);
            border-left: 2px solid #3b82f6;
        }
        .sidebar-link.collapsed i.bi-chevron-down {
            transform: rotate(-90deg);
        }
        .sidebar-link i.bi-chevron-down {
            transition: transform 0.3s;
        }
        /* Hide submenus when sidebar is collapsed */
        #wrapper.sidebar-collapsed .sidebar-item .collapse {
            display: none !important;
        }
    </style>
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