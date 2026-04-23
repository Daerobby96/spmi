@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Sambutan --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#1e3a5f] via-[#2d5a87] to-[#1e3a5f] p-6 text-white shadow-lg mb-6 group">
    <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h5 class="text-xl font-bold mb-1 flex items-center gap-2">
                <span class="animate-bounce">👋</span> Selamat datang, {{ auth()->user()->name }}!
            </h5>
            <p class="text-white/80 text-sm flex items-center gap-2">
                <i class="bi bi-calendar3"></i>
                @php
                    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    $tanggal = $hari[now()->dayOfWeek] . ', ' . now()->day . ' ' . $bulan[now()->month] . ' ' . now()->year;
                @endphp
                {{ $tanggal }}
                @if($periode)
                    <span class="mx-2 opacity-30">|</span>
                    <span class="bg-white/10 px-2 py-0.5 rounded text-xs border border-white/10 self-center">
                        Periode Aktif: <strong>{{ $periode->nama }}</strong>
                    </span>
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('scan.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-[#1e3a5f] shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                <i class="bi bi-qr-code-scan text-lg"></i>
                <span>Scan QR Lapangan</span>
            </a>
        </div>
    </div>
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5 blur-3xl transition-all group-hover:scale-150"></div>
    <div class="absolute -bottom-10 left-1/2 h-32 w-32 -translate-x-1/2 rounded-full bg-primary/10 blur-2xl"></div>
</div>

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-blue-500">
        <div class="flex items-center justify-between mb-4">
            <div class="rounded-xl bg-blue-50 p-2.5 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                <i class="bi bi-clipboard2-check text-xl"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['total_audit'] }}</div>
        </div>
        <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Audit</div>
        <div class="text-xs text-slate-400">{{ $stats['audit_aktif'] }} sedang berjalan</div>
    </div>

    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-orange-500">
        <div class="flex items-center justify-between mb-4">
            <div class="rounded-xl bg-orange-50 p-2.5 text-orange-600 transition-colors group-hover:bg-orange-600 group-hover:text-white">
                <i class="bi bi-exclamation-triangle text-xl"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['total_temuan'] }}</div>
        </div>
        <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Temuan</div>
        <div class="text-xs text-slate-400">{{ $stats['temuan_open'] }} belum ditangani</div>
    </div>

    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-emerald-500">
        <div class="flex items-center justify-between mb-4">
            <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                <i class="bi bi-folder2-open text-xl"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['total_dokumen'] }}</div>
        </div>
        <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Dokumen Aktif</div>
        <div class="text-xs text-slate-400">{{ $stats['dokumen_kadaluarsa'] }} akan kadaluarsa</div>
    </div>

    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_1px_3px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-blue-500">
        <div class="flex items-center justify-between mb-4">
            <div class="rounded-xl bg-blue-50 p-2.5 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                <i class="bi bi-bar-chart-line text-xl"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['total_monitoring'] }}</div>
        </div>
        <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1">Data Monitoring</div>
        <div class="text-xs text-slate-400">{{ $stats['total_user'] }} pengguna aktif</div>
    </div>
</div>

{{-- ── Premium Charts ── --}}
<div class="row g-3 mb-4">
    {{-- Radar Chart: Keseimbangan Capaian --}}
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom h-100 shadow-sm border-0">
            <div class="card-header-custom bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bullseye me-2 text-primary"></i>Keseimbangan Capaian</h6>
                <small class="text-muted">Rata-rata % capaian per standar</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div style="width: 100%; height: 300px;">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Line Chart: Tren Performa Capaian --}}
    <div class="col-lg-8 col-md-6">
        <div class="card card-custom h-100 shadow-sm border-0">
            <div class="card-header-custom bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Tren Performa Capaian</h6>
                    <small class="text-muted">Perkembangan % rata-rata indikator</small>
                </div>
                <div class="badge bg-success-subtle text-success px-3 py-2">
                    <i class="bi bi-chevron-double-up me-1"></i>High Performance
                </div>
            </div>
            <div class="card-body p-4">
                <div style="width: 100%; height: 280px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Progress Bar: Kelengkapan Dokumen --}}
    <div class="col-lg-4">
        <div class="card card-custom h-100 shadow-sm border-0">
            <div class="card-header-custom bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-files me-2 text-blue-600"></i>Kelengkapan Dokumen</h6>
                <small class="text-muted">% Dokumen yang sudah Approved</small>
            </div>
            <div class="card-body p-4">
                @foreach($standarProgress as $sp)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-truncate" style="max-width: 200px;" title="{{ $sp['nama'] }}">
                            {{ $sp['kode'] }} - {{ $sp['nama'] }}
                        </span>
                        <span class="small text-muted">{{ $sp['approved'] }}/{{ $sp['total'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 10px; background-color: #f0f2f5;">
                        <div class="progress-bar bg-blue-600 shadow-sm" role="progressbar" 
                             style="width: {{ $sp['percent'] }}%; border-radius: 10px;" 
                             aria-valuenow="{{ $sp['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Chart Temuan per Kategori --}}
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom h-100 shadow-sm border-0">
            <div class="card-header-custom bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart me-2 text-primary"></i>Temuan per Kategori</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <canvas id="temuanChart" style="max-height:280px"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart Tren Temuan --}}
    <div class="col-lg-4 col-md-6">
        <div class="card card-custom h-100 shadow-sm border-0">
            <div class="card-header-custom bg-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bug me-2 text-danger"></i>Tren Temuan (Kualitas)</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="trenChart" style="max-height:280px"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Temuan Deadline Terdekat --}}
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Temuan Deadline Terdekat</h6>
                <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($temuanDeadline as $t)
                <div class="deadline-item p-3 border-bottom">
                    <div class="d-flex align-items-start gap-3">
                        <div class="deadline-badge">
                            {!! $t->kategori_badge !!}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 small fw-semibold text-truncate">{{ $t->uraian_temuan }}</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem">
                                <i class="bi bi-clipboard2 me-1"></i>{{ $t->audit->nama_audit }}
                            </p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @php $daysLeft = now()->diffInDays($t->batas_tindak_lanjut, false); @endphp
                            <span class="badge {{ $daysLeft < 0 ? 'bg-danger' : ($daysLeft <= 3 ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $daysLeft < 0 ? 'Terlambat ' . abs($daysLeft) . ' hari' : $daysLeft . ' hari lagi' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                    Tidak ada temuan yang mendekati deadline
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Dokumen Akan Kadaluarsa --}}
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h6 class="mb-0"><i class="bi bi-file-earmark-diff me-2 text-danger"></i>Dokumen Akan Kadaluarsa</h6>
                <a href="{{ route('dokumen.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($listDokumenKadaluarsa as $doc)
                <div class="deadline-item p-3 border-bottom">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon bg-light text-danger fs-5 px-2 rounded">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 small fw-semibold text-truncate">{{ $doc->judul }}</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem">
                                <i class="bi bi-building me-1"></i>{{ $doc->unit_pemilik }}
                            </p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge {{ $doc->tanggal_kadaluarsa <= now() ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $doc->tanggal_kadaluarsa->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-shield-check fs-2 text-success d-block mb-2"></i>
                    Semua dokumen masih berlaku
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Audit Terbaru ── --}}
<div class="card card-custom">
    <div class="card-header-custom d-flex align-items-center justify-content-between">
        <h6 class="mb-0"><i class="bi bi-clipboard2-check me-2 text-primary"></i>Audit Terbaru</h6>
        <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Audit</th>
                        <th>Nama Audit</th>
                        <th>Unit Diaudit</th>
                        <th>Ketua Auditor</th>
                        <th>Tgl Audit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditTerbaru as $audit)
                    <tr>
                        <td><code class="text-primary">{{ $audit->kode_audit }}</code></td>
                        <td>{{ $audit->nama_audit }}</td>
                        <td>{{ $audit->unit_yang_diaudit }}</td>
                        <td>{{ $audit->ketuaAuditor->name }}</td>
                        <td>{{ $audit->tanggal_audit->translatedFormat('d F Y') }}</td>
                        <td>{!! $audit->status_badge !!}</td>
                        <td>
                            <a href="{{ route('audit.show', $audit) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data audit
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-custom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }
    .progress-bar {
        transition: width 1.5s cubic-bezier(0.1, 0, 0.2, 1);
    }
    .bg-indigo { background-color: #2563eb !important; }
    .text-indigo { color: #2563eb !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; color: #16a34a !important; }
    .card-header-custom {
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }
    canvas {
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.02));
    }
</style>
@endpush

@endsection

@push('scripts')
<script>
    // Common configurations
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Radar Chart: Keseimbangan Capaian
    const ctxRadar = document.getElementById('radarChart').getContext('2d');
    new Chart(ctxRadar, {
        type: 'radar',
        data: {
            labels: {!! json_encode($radarLabels) !!},
            datasets: [{
                label: 'Capaian %',
                data: {!! json_encode($radarData) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: '#0d6efd',
                borderWidth: 2,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { display: true, color: 'rgba(0,0,0,0.05)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { backdropColor: 'transparent', stepSize: 20 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Performance Trend Chart
    const ctxPerf = document.getElementById('performanceChart').getContext('2d');
    const perfGradient = ctxPerf.createLinearGradient(0, 0, 0, 400);
    perfGradient.addColorStop(0, 'rgba(25, 135, 84, 0.2)');
    perfGradient.addColorStop(1, 'rgba(25, 135, 84, 0)');

    new Chart(ctxPerf, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!}, // Using same period labels
            datasets: [{
                label: 'Rata-rata Capaian (%)',
                data: {!! json_encode($perfTrendData) !!},
                borderColor: '#198754',
                backgroundColor: perfGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#198754',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: value => value + '%' }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: false
                }
            }
        }
    });

    // 3. Temuan Chart (Doughnut)
    const ctxTemuan = document.getElementById('temuanChart').getContext('2d');
    new Chart(ctxTemuan, {
        type: 'doughnut',
        data: {
            labels: ['KTS Mayor', 'KTS Minor', 'Observasi', 'Rekomendasi'],
            datasets: [{
                data: [
                    {{ $temuanPerKategori['KTS_Mayor'] ?? 0 }},
                    {{ $temuanPerKategori['KTS_Minor'] ?? 0 }},
                    {{ $temuanPerKategori['OB'] ?? 0 }},
                    {{ $temuanPerKategori['Rekomendasi'] ?? 0 }},
                ],
                backgroundColor: ['#ef4444', '#f59e0b', '#0ea5e9', '#10b981'],
                hoverOffset: 12,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20, usePointStyle: true, font: { size: 12 } }
                }
            }
        }
    });

    // 4. Tren Temuan Chart (Line)
    const ctxTren = document.getElementById('trenChart').getContext('2d');
    new Chart(ctxTren, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{
                label: 'Jumlah Temuan',
                data: {!! json_encode($trenData) !!},
                borderColor: '#ef4444',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.1,
                pointRadius: 4,
                pointBackgroundColor: '#ef4444'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush