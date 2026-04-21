@extends('layouts.public')

@section('title', 'Portal Transparansi Mutu — SPMI Digital')

@section('content')
<!-- Glass Hero Section -->
<section class="hero-premium">
    <div class="hero-backdrop"></div>
    <div class="hero-dots"></div>
    <div class="container position-relative z-2">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="hero-chip mb-4">
                    <span class="pulse-icon"></span>
                    <span class="text-uppercase tracking-wider fw-700">Digital Quality Assurance Portal</span>
                </div>
                <h1 class="hero-title mb-4">
                    Elevating <span class="text-gradient">Academic</span> Integrity & Performance
                </h1>
                <p class="hero-desc mb-5">
                    Platform terpadu untuk monitoring, evaluasi, dan pengembangan standar mutu pendidikan secara transparan dan akuntabel di setiap unit kerja.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('home.documents') }}" class="btn btn-glow btn-lg px-5">
                        <i class="bi bi-folder2-open me-2"></i>E-Repositori
                    </a>
                    <a href="#stats" class="btn btn-glass-outline btn-lg px-5 ms-0 ms-md-3">
                        Statistik Mutu <i class="bi bi-chevron-down ms-2 small"></i>
                    </a>
                </div>
                
                <div class="hero-meta mt-5 d-flex align-items-center gap-4">
                    <div class="d-flex -space-x-2">
                        <img src="https://ui-avatars.com/api/?name=Auditor+1&background=4f46e5&color=fff" class="avatar-ring shadow-sm" alt="">
                        <img src="https://ui-avatars.com/api/?name=Admin+Mutu&background=06b6d4&color=fff" class="avatar-ring shadow-sm" alt="">
                        <img src="https://ui-avatars.com/api/?name=Unit+Kerja&background=7c3aed&color=fff" class="avatar-ring shadow-sm" alt="">
                    </div>
                    <div class="small fw-500 text-white-50">
                        Dipercaya oleh <span class="text-white fw-700">15+ Unit Kerja</span> <br> dalam pengelolaan standar mutu.
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 d-none d-lg-block" data-aos="zoom-in-left" data-aos-delay="200">
                <div class="hero-card-wrapper">
                    <div class="hero-glass-card main-card">
                        <div class="card-header-glass">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-dot"></div>
                                <span class="text-white-50 small fw-bold">LIVE METRICS</span>
                            </div>
                            <div class="card-controls">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <div class="card-body-glass text-center py-4">
                            <h2 class="text-white fw-800 mb-0">94.8%</h2>
                            <p class="text-white-50 small">Rerata Kepatuhan Standar</p>
                            <div class="hero-chart-container mt-3">
                                <canvas id="heroMiniChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-glass-card floating-card-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box-glass bg-success">
                                <i class="bi bi-check2-circle text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0 fw-bold">Approved</h6>
                                <p class="text-white-50 small mb-0">{{ $stats['total_dokumen'] }} Dokumen</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-glass-card floating-card-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box-glass bg-primary">
                                <i class="bi bi-graph-up text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0 fw-bold">Performance</h6>
                                <p class="text-white-50 small mb-0">{{ $stats['avg_capaian'] }}% Capaian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Ticker -->
<section id="stats" class="stats-ticker-section py-5 bg-light-soft">
    <div class="container position-relative z-3">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="premium-card bg-white p-4 h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="p-3 bg-primary-subtle rounded-4 text-primary">
                            <i class="bi bi-bookmark-check fs-2"></i>
                        </div>
                        <span class="badge bg-green-subtle text-green fw-bold">+12% Monthly</span>
                    </div>
                    <div class="card-body p-0">
                        <h2 class="fw-800 mb-1"><span class="counter" data-target="{{ $stats['total_dokumen'] }}">0</span></h2>
                        <p class="text-muted fw-500 mb-0">Total Dokumen Mutu Aktif</p>
                    </div>
                    <div class="progress mt-4 bg-light shadow-none" style="height: 6px;">
                        <div class="progress-bar bg-primary rounded-pill" style="width: 85%"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="premium-card bg-white p-4 h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="p-3 bg-success-subtle rounded-4 text-success">
                            <i class="bi bi-trophy fs-2"></i>
                        </div>
                        <span class="badge bg-green-subtle text-green fw-bold">On Target</span>
                    </div>
                    <div class="card-body p-0">
                        <h2 class="fw-800 mb-1"><span class="counter" data-target="{{ $stats['avg_capaian'] }}">0</span>%</h2>
                        <p class="text-muted fw-500 mb-0">Rerata Kinerja Indikator</p>
                    </div>
                    <div class="progress mt-4 bg-light shadow-none" style="height: 6px;">
                        <div class="progress-bar bg-success rounded-pill" style="width: {{ $stats['avg_capaian'] }}%"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12" data-aos="fade-up" data-aos-delay="300">
                <div class="premium-card bg-white p-4 h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="p-3 bg-warning-subtle rounded-4 text-warning">
                            <i class="bi bi-activity fs-2"></i>
                        </div>
                        <span class="badge bg-orange-subtle text-orange fw-bold">Siklus Aktif</span>
                    </div>
                    <div class="card-body p-0">
                        <h2 class="fw-800 mb-1"><span class="counter" data-target="{{ $stats['audit_progress'] }}">0</span>%</h2>
                        <p class="text-muted fw-500 mb-0">Progres Audit Mutu Internal</p>
                    </div>
                    <div class="progress mt-4 bg-light shadow-none" style="height: 6px;">
                        <div class="progress-bar bg-warning rounded-pill" style="width: {{ $stats['audit_progress'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PPEPP Modern Section -->
<section class="py-100 bg-soft-white overflow-hidden">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h5 class="text-primary fw-bold text-uppercase tracking-widest mb-3">Framework Kerja</h5>
            <h2 class="display-6 fw-800">Siklus Kontinu <span class="text-gradient">PPEPP</span></h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Implementasi Siklus Penjaminan Mutu di setiap unit kerja untuk memastikan peningkatan mutu berkelanjutan (Continuous Quality Improvement).</p>
        </div>
        
        <div class="row g-4 mt-5">
            @php
                $cycles = [
                    ['Penetapan', 'bi-file-earmark-plus', 'Perumusan standar mutu institusi yang kompetitif.', 'primary'],
                    ['Pelaksanaan', 'bi-play-circle', 'Penerapan standar dalam operasional harian.', 'indigo'],
                    ['Evaluasi', 'bi-search-heart', 'Monitoring ketat terhadap capaian indikator.', 'success'],
                    ['Pengendalian', 'bi-shield-check', 'Tindakan koreksi atas temuan ketidaksesuaian.', 'danger'],
                    ['Peningkatan', 'bi-graph-up-arrow', 'Upgrade standar berdasarkan hasil evaluasi.', 'warning']
                ];
            @endphp
            @foreach($cycles as $index => $c)
            <div class="col-lg col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="ppepp-card-modern hover-lift h-100">
                    <div class="ppepp-icon-wrapper bg-{{ $c[3] }}-subtle text-{{ $c[3] }}">
                        <i class="bi {{ $c[1] }} fs-3"></i>
                    </div>
                    <div class="ppepp-divider"></div>
                    <h5 class="fw-bold text-dark mt-3 mb-2">{{ $c[0] }}</h5>
                    <p class="small text-muted mb-0">{{ $c[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Visual Data Intelligence Section -->
<section id="progress" class="py-100 bg-white">
    <div class="container">
        <div class="row align-items-center mb-5" data-aos="fade-up">
            <div class="col-lg-6">
                <h2 class="fw-800 mb-0">Intelligence <span class="text-primary">Dashboard</span></h2>
                <p class="text-muted mt-2">Visualisasi capaian standar mutu secara real-time berdasarkan data audit terkini.</p>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('home.documents') }}" class="btn btn-outline-dark rounded-pill px-4">
                    Lihat Laporan Lengkap <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        @if(count($capaianPerStandar) > 0)
        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="premium-card p-4 h-100 border-0 shadow-soft">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-radar"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Radar Pemenuhan Standar</h5>
                        </div>
                        <span class="badge bg-light text-dark border fw-500">Periode {{ $periode->tahun ?? '2024' }}</span>
                    </div>
                    <div class="chart-wrapper-main" style="height: 450px;">
                        <canvas id="publicChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-left">
                <div class="d-flex flex-column gap-4 h-100">
                    <div class="premium-card p-4 border-0 gradient-primary-card text-white">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="p-3 bg-white bg-opacity-20 rounded-4">
                                <i class="bi bi-award fs-3"></i>
                            </div>
                            <span class="badge bg-white text-primary">Top Standard</span>
                        </div>
                        @php
                            $sorted = collect($capaianPerStandar)->sortDesc();
                            $top = $sorted->keys()->first();
                            $topVal = $sorted->first();
                        @endphp
                        <h4 class="fw-800 mb-1 text-truncate">{{ $top }}</h4>
                        <div class="h3 fw-800 mb-3">{{ $topVal }}%</div>
                        <p class="opacity-75 small mb-0">Capaian tertinggi pada periode audit kali ini.</p>
                    </div>
                    
                    <div class="premium-card p-4 border-0 bg-light-soft h-100">
                        <h6 class="text-uppercase text-muted fw-bold small mb-4 tracking-widest">Detail Capaian</h6>
                        <div class="d-flex flex-column gap-4">
                            @foreach($sorted->take(3) as $name => $val)
                            <div class="capaian-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-700 text-dark small text-truncate pe-3">{{ $name }}</span>
                                    <span class="fw-800 text-primary small">{{ $val }}%</span>
                                </div>
                                <div class="progress bg-white shadow-none" style="height: 5px;">
                                    <div class="progress-bar bg-primary rounded-pill" style="width: {{ $val }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-auto pt-4 text-center">
                            <span class="text-muted small">Updated: <span class="fw-bold">{{ now()->format('d M Y') }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="premium-card p-100 text-center bg-light border-dashed">
            <i class="bi bi-clipboard-x display-1 text-muted opacity-10"></i>
            <h4 class="mt-4 text-dark fw-bold">Data Progres Belum Tersedia</h4>
            <p class="text-muted">Masuk ke sistem internal untuk menginput monitoring.</p>
        </div>
        @endif
    </div>
</section>

<!-- Survey & Public Feedback Section -->
<section id="survey" class="py-100 bg-light-soft">
    <div class="container">
        <div class="row align-items-center mb-5" data-aos="fade-up">
            <div class="col-lg-6">
                <h5 class="text-primary fw-bold text-uppercase tracking-widest mb-3">Survey & Kepuasan</h5>
                <h2 class="fw-800 mb-0">Umpan Balik <span class="text-primary">Stakeholder</span></h2>
            </div>
            <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-flex align-items-center bg-white p-3 rounded-4 shadow-sm border">
                    <div class="me-4 text-start">
                        <div class="text-muted smaller fw-bold mb-1">INDREKS KEPUASAN (EDOM)</div>
                        <div class="h3 fw-800 text-primary mb-0">{{ $stats['avg_edom'] }} <span class="fs-6 text-muted fw-normal">/ 5.0</span></div>
                    </div>
                    <div class="fs-2 text-warning">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($publicKuesioners as $idx => $k)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($idx + 1) }}">
                <div class="premium-card-survey bg-white h-100 position-relative overflow-hidden transition-all duration-300">
                    <div class="card-glow"></div>
                    <div class="position-absolute top-0 end-0 p-4 opacity-10">
                        <i class="bi bi-clipboard2-check display-4"></i>
                    </div>
                    <div class="card-content p-4 p-xl-5">
                        <div class="mb-4">
                            <div class="icon-box-premium bg-primary text-white mb-4 shadow-primary">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                            <h4 class="fw-800 text-dark mb-3 survey-title">{{ $k->judul }}</h4>
                            <p class="text-muted fw-500 mb-4 survey-desc">
                                Sampaikan aspirasi Anda untuk membantu kami meningkatkan standar kualitas layanan pendidikan secara berkelanjutan.
                            </p>
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('user-kuesioner.fill', $k->id) }}" class="btn btn-premium-action w-100 py-3">
                                <span>Isi Survey Sekarang</span>
                                <i class="bi bi-arrow-right-short ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="zoom-in">
                <div class="p-5 bg-white rounded-5 shadow-soft border border-dashed">
                    <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                    <h5 class="mt-3 text-muted fw-800">Saat ini tidak ada survey publik yang aktif.</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-100 position-relative overflow-hidden">
    <div class="container">
        <div class="cta-banner gradient-dark p-lg-5 p-4 rounded-5 shadow-lg position-relative overflow-hidden text-center text-white" data-aos="zoom-in">
            <div class="cta-overlay"></div>
            <div class="position-relative z-2">
                <h2 class="display-5 fw-800 mb-4">Budaya Mutu Adalah <br> <span class="text-gradient-cyan">Tanggung Jawab Bersama</span></h2>
                <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 700px;">Akses dashboard internal untuk mengelola dokumen, monitoring capaian, dan menindaklanjuti temuan audit secara efisien.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-white btn-lg px-5 text-primary fw-800 hover-lift">
                        Login Internal <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Premium Root Variables */
    :root {
        --premium-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        --soft-shadow: 0 10px 30px rgba(0,0,0,0.02);
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --dark-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        --glass-bg: rgba(255, 255, 255, 0.08);
        --glass-border: rgba(255, 255, 255, 0.12);
    }

    .py-100 { padding: 100px 0; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-500 { font-weight: 500; }
    .tracking-widest { letter-spacing: 0.15em; }
    .tracking-wider { letter-spacing: 0.05em; }

    /* Hero Section Premium */
    .hero-premium {
        background-color: #0f172a;
        padding: 180px 0 160px;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .hero-backdrop {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(79, 70, 229, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 40%);
        z-index: 1;
    }

    .hero-dots {
        position: absolute;
        width: 100%; height: 100%;
        top: 0; left: 0;
        background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: 1;
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.05);
        padding: 8px 20px;
        border-radius: 100px;
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
    }

    .pulse-icon {
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 1); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .hero-title {
        font-size: 4rem;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .text-gradient {
        background: linear-gradient(135deg, #818cf8 0%, #22d3ee 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .text-gradient-cyan {
        background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-desc {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.6);
        max-width: 540px;
    }

    .btn-glow {
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 16px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .btn-glow:hover {
        background: #4338ca;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.4);
    }

    .btn-glass-outline {
        background: rgba(255,255,255,0.05);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 16px;
        font-weight: 600;
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }

    .btn-glass-outline:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        border-color: rgba(255,255,255,0.4);
    }

    .avatar-ring {
        width: 44px; height: 44px;
        border-radius: 50%;
        border: 3px solid #0f172a;
    }

    .-space-x-2 > * + * { margin-left: -12px; }

    /* Hero Floating Cards */
    .hero-card-wrapper {
        position: relative;
        height: 500px;
    }

    .hero-glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.3);
    }

    .main-card {
        width: 380px;
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
    }

    .floating-card-1 {
        position: absolute;
        top: 15%; right: 0%;
        z-index: 3;
        animation: float-y 4s ease-in-out infinite;
    }

    .floating-card-2 {
        position: absolute;
        bottom: 15%; left: 0%;
        z-index: 3;
        animation: float-y 4s ease-in-out infinite 2s;
    }

    @keyframes float-y {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .card-header-glass {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .status-dot {
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
    }

    .card-controls span {
        width: 8px; height: 8px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: inline-block;
        margin-left: 4px;
    }

    .icon-box-glass {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Premium Ticker Card */
    .premium-card {
        border-radius: 28px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: var(--soft-shadow);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .premium-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--premium-shadow);
        border-color: rgba(79, 70, 229, 0.1);
    }

    .bg-soft-white { background-color: #f8fafc; }
    
    .ppepp-card-modern {
        background: white;
        padding: 40px 30px;
        border-radius: 32px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .ppepp-card-modern:hover {
        background: white;
        transform: scale(1.05);
        box-shadow: 0 30px 60px rgba(0,0,0,0.05);
    }

    .ppepp-icon-wrapper {
        width: 72px; height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }

    .ppepp-divider {
        width: 40px; height: 4px;
        background-color: currentColor;
        opacity: 0.1;
        border-radius: 10px;
        margin: 0 auto;
    }

    /* Gradient Cards */
    .gradient-primary-card {
        background: var(--primary-gradient);
        position: relative;
        overflow: hidden;
    }

    .gradient-primary-card::after {
        content: '';
        position: absolute;
        top: -20%; right: -20%;
        width: 60%; height: 60%;
        background: rgba(255,255,255,0.1);
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        z-index: 1;
    }

    .gradient-dark { background: var(--dark-gradient); }

    .shadow-soft { box-shadow: 0 15px 45px rgba(0,0,0,0.04); }

    .cta-banner {
        padding: 80px 40px;
        position: relative;
    }

    .cta-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 15% 50%, rgba(79, 70, 229, 0.2) 0%, transparent 40%);
        z-index: 1;
    }

    .btn-white {
        background: white;
        color: #4f46e5;
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .btn-white:hover {
        background: #f8fafc;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* Helper Classes */
    .bg-primary-subtle { background-color: rgba(79, 70, 229, 0.1) !important; }
    .bg-success-subtle { background-color: rgba(16, 185, 129, 0.1) !important; }
    .bg-warning-subtle { background-color: rgba(245, 158, 11, 0.1) !important; }
    .bg-green-subtle { background-color: #dcfce7 !important; }
    .text-green { color: #166534 !important; }
    .bg-orange-subtle { background-color: #ffedd5 !important; }
    .text-orange { color: #9a3412 !important; }
    .bg-light-soft { background-color: #f1f5f9 !important; }
    .opacity-05 { opacity: 0.05; }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .hero-title { font-size: 2.8rem; }
        .py-100 { padding: 60px 0; }
        .hero-premium { padding: 140px 0 80px; text-align: center; }
        .hero-chip, .hero-desc, .hero-meta { margin-left: auto; margin-right: auto; }
        .hero-actions { justify-content: center; }
    }

    @media (max-width: 576px) {
        .hero-title { font-size: 2.2rem; }
        .btn-lg { width: 100%; margin-bottom: 1rem; }
        .premium-card { padding: 1.5rem !important; }
    }

    /* Survey Card Enhanced */
    .premium-card-survey {
        border-radius: 32px;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
    }

    .premium-card-survey:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(79, 70, 229, 0.12);
        border-color: rgba(79, 70, 229, 0.2);
    }

    .card-glow {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 100% 0%, rgba(79, 70, 229, 0.05) 0%, transparent 40%);
        pointer-events: none;
    }

    .icon-box-premium {
        width: 64px; height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .shadow-primary {
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .survey-title {
        line-height: 1.3;
        letter-spacing: -0.01em;
    }

    .survey-desc {
        line-height: 1.7;
        font-size: 0.95rem;
    }

    .btn-premium-action {
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 20px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .btn-premium-action:hover {
        background: #4338ca;
        color: white;
        transform: scale(1.02);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
    }

    .btn-premium-action i {
        font-size: 1.4rem;
        transition: transform 0.3s ease;
    }

    .btn-premium-action:hover i {
        transform: translateX(5px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        const countUpObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    const duration = 2000;
                    const start = 0;
                    const startTime = performance.now();

                    const update = (now) => {
                        const elapsed = now - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const val = Math.floor(progress * (target - start) + start);
                        counter.innerText = val;
                        
                        if (progress < 1) {
                            requestAnimationFrame(update);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    requestAnimationFrame(update);
                    countUpObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => countUpObserver.observe(c));

        // Radar Chart Logic
        const radarCtx = document.getElementById('publicChart').getContext('2d');
        const chartData = @json($capaianPerStandar);
        
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    label: 'Capaian Mutu (%)',
                    data: Object.values(chartData),
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.15)',
                    borderColor: '#4f46e5',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { display: false, stepSize: 20 },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        angleLines: { color: 'rgba(0,0,0,0.05)' },
                        pointLabels: {
                            font: { 
                                size: 12, 
                                weight: '700',
                                family: "'Plus Jakarta Sans', sans-serif" 
                            },
                            color: '#64748b'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ` Capaian: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });

        // Hero Mini Decoration Chart
        const heroMiniCtx = document.getElementById('heroMiniChart').getContext('2d');
        const heroGradient = heroMiniCtx.createLinearGradient(0, 0, 0, 100);
        heroGradient.addColorStop(0, 'rgba(34, 211, 238, 0.4)');
        heroGradient.addColorStop(1, 'rgba(34, 211, 238, 0)');

        new Chart(heroMiniCtx, {
            type: 'line',
            data: {
                labels: ['M1', 'M2', 'M3', 'M4', 'M5', 'M6'],
                datasets: [{
                    data: [65, 78, 62, 85, 80, 94],
                    borderColor: '#22d3ee',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0,
                    fill: true,
                    backgroundColor: heroGradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false, min: 20 }
                }
            }
        });
    });
</script>
@endpush
