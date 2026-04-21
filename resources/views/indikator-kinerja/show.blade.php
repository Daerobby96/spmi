@extends('layouts.app')

@section('title', 'Detail Indikator Kinerja')
@section('page-title', 'Detail Indikator Kinerja')
@section('page-subtitle', $indikator->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('indikator-kinerja.index') }}">Indikator Kinerja</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-info-circle me-1"></i>Informasi Dasar</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th width="150">Kode Indikator</th>
                        <td><code class="text-primary fw-bold">{{ $indikator->kode }}</code></td>
                    </tr>
                    <tr>
                        <th>Standar Terkait</th>
                        <td>
                            @if($indikator->standar)
                                <span class="badge bg-primary-subtle text-primary">{{ $indikator->standar->kode }}</span>
                                <div class="small text-muted mt-1">{{ $indikator->standar->nama }}</div>
                            @else
                                <span class="text-muted small italic">Tidak ada standar terkait</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Unit Pengukuran</th>
                        <td>{{ $indikator->unit_pengukuran }}</td>
                    </tr>
                    <tr>
                        <th>Target Nilai</th>
                        <td class="fw-bold">
                            @if($indikator->target_deskripsi)
                                <div class="text-dark">{{ $indikator->target_deskripsi }}</div>
                                @if($indikator->target_nilai)
                                    <small class="text-muted fw-normal">({{ $indikator->target_nilai + 0 }} {{ $indikator->unit_pengukuran }})</small>
                                @endif
                            @else
                                {{ $indikator->target_nilai + 0 }} {{ $indikator->unit_pengukuran }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <td><span class="badge bg-secondary">{{ $indikator->unit_kerja }}</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($indikator->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-graph-up me-1"></i>Historis Capaian (Monitoring)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Nilai Capaian</th>
                                <th>Hasil</th>
                                <th>Analisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($indikator->monitorings as $mon)
                            <tr>
                                <td class="fw-bold">{{ $mon->periode->nama ?? '-' }}</td>
                                <td class="fw-bold text-primary">{{ $mon->nilai_capaian }} {{ $indikator->unit_pengukuran }}</td>
                                <td>
                                    @if($mon->evaluasi)
                                        @if($mon->evaluasi->hasil === 'tercapai')
                                            <span class="badge bg-success">Tercapai</span>
                                        @else
                                            <span class="badge bg-danger">Lampaui</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted border">Belum Evaluasi</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($mon->evaluasi->analisa_penyebab ?? '-', 50) }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small italic">
                                    Belum ada data monitoring untuk indikator ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <a href="{{ route('indikator-kinerja.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('indikator-kinerja.edit', $indikator) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Indikator
        </a>
    </div>
</div>
@endsection
