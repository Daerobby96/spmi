@extends('layouts.app')

@section('title', 'Detail Evaluasi')
@section('page-title', 'Detail Evaluasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Monitoring --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                <h6 class="mb-0">Data Monitoring</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Periode</th>
                        <td>{{ $evaluasi->monitoring->periode->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Input</th>
                        <td>{{ $evaluasi->monitoring->tanggal_input->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Indikator</th>
                        <td>
                            <div class="fw-semibold">{{ $evaluasi->monitoring->indikator->nama ?? '-' }}</div>
                            <div class="text-muted small">{{ $evaluasi->monitoring->indikator->kode ?? '-' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <td>{{ $evaluasi->monitoring->indikator->unit_kerja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Standar</th>
                        <td>{{ $evaluasi->monitoring->indikator->standar->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>{{ $evaluasi->monitoring->pelapor->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Capaian --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-bullseye me-2 text-primary"></i>
                <h6 class="mb-0">Capaian</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @php $persen = $evaluasi->monitoring->persentase_capaian; @endphp
                    <div class="display-4 fw-bold {{ $persen >= 100 ? 'text-success' : ($persen >= 80 ? 'text-warning' : 'text-danger') }}">
                        {{ number_format($persen, 1) }}%
                    </div>
                    <div class="text-muted">Persentase Capaian</div>
                </div>
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Target</th>
                        <td class="fw-semibold">{{ $evaluasi->monitoring->indikator->target_nilai ?? '-' }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Capaian</th>
                        <td class="fw-semibold">{{ $evaluasi->monitoring->nilai_capaian }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Hasil Evaluasi --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clipboard-check me-2 text-success"></i>
                <h6 class="mb-0">Hasil Evaluasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless detail-table mb-0">
                            <tr>
                                <th>Tanggal Evaluasi</th>
                                <td>{{ $evaluasi->tanggal_evaluasi->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Evaluator</th>
                                <td>{{ $evaluasi->evaluator->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Hasil</th>
                                <td>
                                    @if($evaluasi->hasil === 'tercapai')
                                        <span class="badge bg-success fs-6">Tercapai</span>
                                    @elseif($evaluasi->hasil === 'tidak_tercapai')
                                        <span class="badge bg-danger fs-6">Tidak Tercapai</span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6">Perlu Perhatian</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Analisa:</strong>
                            <p class="mb-0 mt-1">{{ $evaluasi->analisa }}</p>
                        </div>
                        @if($evaluasi->rekomendasi)
                        <div>
                            <strong>Rekomendasi:</strong>
                            <p class="mb-0 mt-1">{{ $evaluasi->rekomendasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('evaluasi.edit', $evaluasi) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection