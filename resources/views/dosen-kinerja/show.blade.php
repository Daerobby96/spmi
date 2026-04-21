@extends('layouts.app')

@section('title', 'Detail Kinerja Dosen')

@section('page-title', 'Detail Performa')

@section('page-actions')
    <a href="{{ route('kinerja-dosen.export-pdf', $kinerja->id) }}" class="btn btn-outline-dark d-flex align-items-center gap-2" target="_blank">
        <i class="bi bi-file-pdf"></i>
        <span>Cetak Rapor (PDF)</span>
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kinerja-dosen.index') }}">Kinerja Dosen</a></li>
    <li class="breadcrumb-item active">{{ $kinerja->dosen_name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Profil Dosen Card -->
    <div class="col-lg-4">
        <div class="card card-custom border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <div class="avatar-circle mx-auto mb-3 bg-primary text-white fs-1 fw-bold">
                        {{ strtoupper(substr($kinerja->dosen_name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $kinerja->dosen_name }}</h5>
                    <p class="text-muted small mb-0">{{ $kinerja->dosen_nip }}</p>
                    <span class="badge bg-light text-dark border mt-2">{{ $kinerja->homebase }}</span>
                </div>
                
                <hr class="opacity-10 my-4">
                
                <div class="row g-0">
                    <div class="col-6 border-end">
                        <div class="text-muted smaller fw-bold text-uppercase">Nilai Akhir</div>
                        <div class="fs-2 fw-800 text-primary">{{ $kinerja->total_rerata }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted smaller fw-bold text-uppercase">Predikat</div>
                        @php
                            $score = $kinerja->total_rerata;
                            $label = 'Cukup'; $class = 'text-warning';
                            if($score >= 4.5) { $label = 'Sangat Baik'; $class = 'text-success'; }
                            elseif($score >= 3.75) { $label = 'Baik'; $class = 'text-primary'; }
                            elseif($score < 3) { $label = 'Kurang'; $class = 'text-danger'; }
                        @endphp
                        <div class="fs-4 fw-bold {{ $class }}">{{ $label }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-light p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">Periode:</span>
                    <span class="small fw-bold">{{ $kinerja->periode->nama }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small fw-bold text-muted">Status Dosen:</span>
                    <span class="badge bg-success rounded-pill px-3">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Chart Card -->
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">Analisis per Aspek Penilaian</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="aspectChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Scores List -->
    <div class="col-md-6 mt-4">
        <div class="card card-custom border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">Detail Rerata per Kategori</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light smaller fw-bold text-muted text-uppercase">
                            <tr>
                                <th class="ps-4">Kategori Aspek</th>
                                <th class="text-center">Skor</th>
                                <th class="pe-4" style="width: 200px">Visualisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kinerja->kategori_scores as $s)
                            <tr>
                                <td class="ps-4 small fw-bold text-dark">{{ $s['kategori'] }}</td>
                                <td class="text-center fw-bold text-primary">{{ $s['skor'] }}</td>
                                <td class="pe-4">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ ($s['skor'] / 5) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Scores List -->
    <div class="col-md-6 mt-4">
        <div class="card card-custom border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">Performa per Mata Kuliah & Kelas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light smaller fw-bold text-muted text-uppercase">
                            <tr>
                                <th class="ps-4">Mata Kuliah / Kelas</th>
                                <th class="text-center">Responden</th>
                                <th class="text-center pe-4">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kinerja->mata_kuliah_scores as $m)
                            <tr>
                                <td class="ps-4">
                                    <div class="small fw-bold text-dark">{{ $m['mk'] }}</div>
                                    <div class="smaller text-muted">{{ $m['kelas'] }} — {{ $m['prodi'] }}</div>
                                </td>
                                <td class="text-center small">{{ $m['responden'] }}</td>
                                <td class="text-center pe-4">
                                    <span class="fw-bold {{ $m['skor'] >= 4 ? 'text-success' : 'text-primary' }}">{{ $m['skor'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.3);
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('aspectChart').getContext('2d');
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#4e73df';
        
        const labels = {!! json_encode(collect($kinerja->kategori_scores)->pluck('kategori')) !!};
        const data = {!! json_encode(collect($kinerja->kategori_scores)->pluck('skor')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor Aspek',
                    data: data,
                    backgroundColor: primaryColor + '20',
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 10,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 5,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
