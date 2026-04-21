@extends('layouts.app')

@section('title', 'Dashboard Analisis Kuesioner')

@section('page-title', 'Dashboard Analisis')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kuesioner.index') }}">Kuesioner</a></li>
    <li class="breadcrumb-item active">Dashboard Analisis</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Main Header Card -->
    <div class="col-lg-12">
        <div class="card card-custom border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, var(--primary-color) 0%, #2a52be 100%);">
            <div class="card-body p-4 p-lg-5 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 fw-bold">
                            <i class="bi bi-file-earmark-bar-graph-fill me-2"></i>REKAPITULASI HASIL
                        </span>
                        <h2 class="fw-800 mb-2">{{ $kuesioner->full_judul }}</h2>
                        <p class="opacity-75 mb-4 fs-5">{{ $kuesioner->deskripsi }}</p>
                        <div class="d-flex gap-4">
                            <div class="text-center bg-white bg-opacity-10 p-3 rounded-4" style="min-width: 120px; backdrop-filter: blur(10px);">
                                <div class="fs-1 fw-800">{{ $kuesioner->calculateIndex() }}</div>
                                <div class="smaller fw-bold opacity-75 text-uppercase">Indeks Mutu</div>
                            </div>
                            <div class="text-center bg-white bg-opacity-10 p-3 rounded-4" style="min-width: 120px; backdrop-filter: blur(10px);">
                                <div class="fs-1 fw-800">{{ $kuesioner->jawabans()->count() }}</div>
                                <div class="smaller fw-bold opacity-75 text-uppercase">Responden</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white rounded-5 p-4 shadow-lg h-100">
                            <canvas id="radarChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights Section -->
    <div class="col-lg-6">
        <div class="card card-custom border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>BUTIR PERLU PERHATIAN (PRIORITAS RTL)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($bottomThree as $b)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold small text-dark">{{ Str::limit($b['pertanyaan'], 80) }}</div>
                                    <small class="text-muted">Skor Terendah #{{ $loop->iteration }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fs-4 fw-800 text-danger">{{ $b['avg'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-success"><i class="bi bi-star-fill me-2"></i>BUTIR DENGAN CAPAIAN TERBAIK</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($topThree as $t)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold small text-dark">{{ Str::limit($t['pertanyaan'], 80) }}</div>
                                    <small class="text-muted">Skor Tertinggi #{{ $loop->iteration }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fs-4 fw-800 text-success">{{ $t['avg'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0 text-dark">Daftar Analisis Lengkap</h5>
            <div class="d-flex gap-2">
                 <button class="btn btn-primary btn-sm px-4 shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>
        
        <div class="card card-custom border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-analysis">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px">No</th>
                            <th>Pertanyaan / Butir Standar</th>
                            <th class="text-center" style="width: 150px">Kategori</th>
                            <th class="text-center" style="width: 120px">Skor Mutu</th>
                            <th class="text-center" style="width: 100px">Heatmap</th>
                            <th class="text-center" style="width: 80px">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedQuestions as $category => $questions)
                        <tr class="bg-light-subtle">
                            <td colspan="6" class="ps-4 fw-bold text-primary small bg-opacity-10 py-3">
                                <i class="bi bi-folder-fill me-2"></i>KATEGORI: {{ strtoupper($category ?: 'Umum') }}
                            </td>
                        </tr>
                        @foreach($questions as $p)
                        @php $score = $results[$p->id]['avg'] ?? 0; @endphp
                        <tr>
                            <td class="ps-4 text-muted small fw-bold">{{ $p->urutan }}</td>
                            <td>
                                <div class="fw-bold text-dark lh-base">{{ $p->pertanyaan }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill smaller">{{ $category ?: 'Umum' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-5 fw-800 {{ $score < 3 ? 'text-danger' : ($score < 4 ? 'text-warning' : 'text-success') }}">
                                    {{ $score }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <div class="heatmap-cell" style="width: 40px; height: 10px; border-radius: 10px; background-color: {{ $score < 3 ? '#ffdede' : ($score < 4 ? '#fff4de' : '#def9de') }}; border: 1px solid {{ $score < 3 ? '#ff4d4d' : ($score < 4 ? '#ff9f43' : '#2ecc71') }}"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border-0 rounded-circle" type="button" data-bs-toggle="collapse" data-bs-target="#dist-{{ $p->id }}">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="dist-{{ $p->id }}">
                            <td colspan="6" class="bg-light p-4 shadow-inner">
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <div class="fw-bold mb-3 small text-uppercase tracking-wider">Distribusi Jawaban Responden</div>
                                        @if($p->tipe == 'likert')
                                            @php
                                                $dist = $results[$p->id]['dist'] ?? collect();
                                                $total = $results[$p->id]['total'] ?? 1;
                                            @endphp
                                            @for($i = 5; $i >= 1; $i--)
                                            @php $count = $dist[$i] ?? 0; $perc = ($count / max($total, 1)) * 100; @endphp
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="small fw-bold" style="width: 60px">Skor {{ $i }}</div>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar {{ $i >= 4 ? 'bg-success' : ($i == 3 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $perc }}%"></div>
                                                </div>
                                                <div class="small text-muted fw-bold" style="width: 50px">{{ $count }} org</div>
                                            </div>
                                            @endfor
                                        @else
                                            <div class="alert alert-info py-2 small">Data berupa teks, silakan ekspor ke Excel untuk melihat detail.</div>
                                        @endif
                                    </div>
                                    <div class="col-md-7 border-start ps-4">
                                        <div class="fw-bold mb-3 small text-uppercase tracking-wider">Analisis Singkat</div>
                                        @if($score < 3)
                                            <div class="alert alert-danger border-0 d-flex gap-3 mb-0">
                                                <i class="bi bi-lightning-fill"></i>
                                                <div>
                                                    <div class="fw-bold">Temuan Kritis</div>
                                                    <div class="small">Skor di bawah standar (3.0). Segera buat rencana tindak lanjut perbaikan sarana/layanan terkait.</div>
                                                </div>
                                            </div>
                                        @elseif($score >= 4.5)
                                            <div class="alert alert-success border-0 d-flex gap-3 mb-0">
                                                <i class="bi bi-award-fill"></i>
                                                <div>
                                                    <div class="fw-bold">Capaian Unggul</div>
                                                    <div class="small">Layanan ini telah memenuhi standar kepuasan yang sangat baik. Pertahankan dan jadikan benchmarking.</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-primary border-0 d-flex gap-3 mb-0">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <div>
                                                    <div class="fw-bold">Dalam Rentang Normal</div>
                                                    <div class="small">Tingkat kepuasan stabil. Rekomendasi: Lakukan evaluasi berkala untuk meningkatkan efisiensi.</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-analysis tr { transition: all 0.2s; }
    .table-analysis tr[data-bs-toggle="collapse"] { cursor: pointer; }
    .heatmap-cell { cursor: pointer; transition: transform 0.2s; }
    .heatmap-cell:hover { transform: scaleY(1.5); }
    @media print {
        #page-content-wrapper { padding: 0; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
        .collapse { display: block !important; height: auto !important; }
        .btn, #radarChart { display: none !important; }
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('radarChart').getContext('2d');
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#4e73df';
        
        const categories = {!! json_encode($groupedQuestions->keys()->map(fn($k) => Str::limit($k ?: 'Umum', 20))) !!};
        const averages = {!! json_encode($groupedQuestions->map(fn($q) => round($q->map(fn($p) => $results[$p->id]['avg'] ?? 0)->average(), 2))->values()) !!};

        // Otomatis tentukan tipe grafik: Bar jika kategori < 3 agar tidak "rusak"
        const chartType = categories.length < 3 ? 'bar' : 'radar';

        const chartConfig = {
            type: chartType,
            data: {
                labels: categories,
                datasets: [{
                    label: 'Skor Rerata',
                    data: averages,
                    backgroundColor: chartType === 'bar' ? primaryColor + '40' : 'rgba(78, 115, 223, 0.4)',
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 8, // Untuk bar chart agar rounded
                    pointBackgroundColor: '#fff',
                    pointBorderColor: primaryColor,
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 10, bottom: 10, left: 10, right: 20 } },
                scales: chartType === 'bar' ? {
                    y: { 
                        beginAtZero: true, 
                        max: 5,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false }
                    }
                } : {
                    r: {
                        angleLines: { display: true, color: 'rgba(0,0,0,0.05)' },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        suggestedMin: 0,
                        suggestedMax: 5,
                        ticks: { display: true, stepSize: 1, font: { size: 9 }, backdropColor: 'transparent' },
                        pointLabels: { font: { size: 10, weight: '600' } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1d21',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        };

        new Chart(ctx, chartConfig);
    });
</script>
@endpush
