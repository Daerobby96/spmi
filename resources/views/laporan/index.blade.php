@extends('layouts.app')

@section('title', 'Laporan SPMI')
@section('page-title', 'Laporan SPMI')
@section('page-subtitle', 'Ringkasan dan laporan Sistem Penjaminan Mutu Internal')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Filter Periode --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select">
                            <option value="">Semua Periode</option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->tahun }} - {{ $p->semester ?? 'Semester ' . $p->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="col-md-3">
        <div class="card card-custom bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Audit</h6>
                        <h2 class="mb-0">{{ $ringkasan['audit']['total'] }}</h2>
                        <small class="text-white-50">{{ $ringkasan['audit']['selesai'] }} selesai</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('laporan.audit') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Temuan</h6>
                        <h2 class="mb-0">{{ $ringkasan['temuan']['total'] }}</h2>
                        <small class="text-white-50">{{ $ringkasan['temuan']['open'] }} open</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('laporan.audit') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Dokumen</h6>
                        <h2 class="mb-0">{{ $ringkasan['dokumen']['total'] }}</h2>
                        <small class="text-white-50">{{ $ringkasan['dokumen']['approved'] }} approved</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('laporan.dokumen') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Monitoring</h6>
                        <h2 class="mb-0">{{ $ringkasan['monitoring']['total'] }}</h2>
                        <small class="text-white-50">{{ $ringkasan['monitoring']['tercapai'] }} tercapai</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('laporan.monitoring') }}" class="text-white text-decoration-none small">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Menu Laporan --}}
    <div class="col-12">
        <h5 class="mb-3">Jenis Laporan</h5>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 border-0 shadow-sm hover-lift">
            <div class="card-body text-center py-4">
                <div class="fs-1 text-primary mb-3">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h5>Laporan Audit</h5>
                <p class="text-muted small mb-3">
                    Laporan hasil audit internal meliputi temuan, kategori, dan status tindak lanjut.
                </p>
                <a href="{{ route('laporan.audit') }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 border-0 shadow-sm hover-lift">
            <div class="card-body text-center py-4">
                <div class="fs-1 text-success mb-3">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h5>Laporan Dokumen</h5>
                <p class="text-muted small mb-3">
                    Laporan dokumen mutu berdasarkan kategori, standar, dan status persetujuan.
                </p>
                <a href="{{ route('laporan.dokumen') }}" class="btn btn-outline-success">
                    <i class="bi bi-eye me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 border-0 shadow-sm hover-lift">
            <div class="card-body text-center py-4">
                <div class="fs-1 text-info mb-3">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h5>Laporan Monitoring</h5>
                <p class="text-muted small mb-3">
                    Laporan pelaksanaan dan evaluasi capaian indikator kinerja.
                </p>
                <a href="{{ route('laporan.monitoring') }}" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 border-0 shadow-sm hover-lift">
            <div class="card-body text-center py-4">
                <div class="fs-1 text-warning mb-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h5>Laporan EDOM</h5>
                <p class="text-muted small mb-3">
                    Laporan hasil Evaluasi Dosen Oleh Mahasiswa (EDOM) dan kinerja dosen.
                </p>
                <a href="{{ route('laporan.export.pdf', ['type' => 'edom', 'periode_id' => request('periode_id')]) }}" class="btn btn-outline-dark" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i>Cetak PDF Resmi
                </a>
                <a href="{{ route('kinerja-dosen.index') }}" class="btn btn-link btn-sm mt-2 d-block text-decoration-none">
                    Lihat Dashboard
                </a>
            </div>
        </div>
    </div>

   
</div>

@push('styles')
<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>
@endpush
@endsection