@extends('layouts.app')

@section('title', 'Detail Monitoring')
@section('page-title', 'Detail Data Monitoring')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring IKU/IKT</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Utama --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Monitoring</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Periode</th>
                        <td>{{ $monitoring->periode->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Input</th>
                        <td>{{ $monitoring->tanggal_input->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Indikator</th>
                        <td>
                            <div class="fw-semibold">{{ $monitoring->indikator->nama ?? '-' }}</div>
                            <div class="text-muted small">{{ $monitoring->indikator->kode ?? '-' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <td>{{ $monitoring->indikator->unit_kerja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Standar</th>
                        <td>{{ $monitoring->indikator->standar->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>{{ $monitoring->pelapor->name ?? '-' }}</td>
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
                    @php $persen = $monitoring->persentase_capaian; @endphp
                    <div class="display-4 fw-bold {{ $persen >= 100 ? 'text-success' : ($persen >= 80 ? 'text-warning' : 'text-danger') }}">
                        {{ number_format($persen, 1) }}%
                    </div>
                    <div class="text-muted">Persentase Capaian</div>
                </div>
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Target</th>
                        <td class="fw-semibold">{{ $monitoring->indikator->target_nilai ?? '-' }} {{ $monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Capaian</th>
                        <td class="fw-semibold">{{ $monitoring->nilai_capaian }} {{ $monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($monitoring->status === 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($monitoring->status === 'submitted')
                                <span class="badge bg-primary">Submitted</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Keterangan & Bukti --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-file-text me-2 text-primary"></i>
                <h6 class="mb-0">Keterangan & Bukti</h6>
            </div>
            <div class="card-body">
                @if($monitoring->keterangan)
                <div class="mb-3">
                    <strong>Keterangan:</strong>
                    <p class="mb-0 mt-1">{{ $monitoring->keterangan }}</p>
                </div>
                @endif

                @if($monitoring->bukti_dokumen)
                <div>
                    <strong>Bukti Dokumen:</strong>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $monitoring->bukti_dokumen) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Unduh Bukti
                        </a>
                    </div>
                </div>
                @endif

                @if(!$monitoring->keterangan && !$monitoring->bukti_dokumen)
                <div class="text-muted text-center py-3">
                    Tidak ada keterangan atau bukti dokumen
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Evaluasi --}}
    @if($monitoring->evaluasi)
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-check2-circle me-2 text-success"></i>
                <h6 class="mb-0">Hasil Evaluasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Hasil</th>
                        <td>
                            @if($monitoring->evaluasi->hasil === 'tercapai')
                                <span class="badge bg-success">Tercapai</span>
                            @else
                                <span class="badge bg-danger">Tidak Tercapai</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Evaluator</th>
                        <td>{{ $monitoring->evaluasi->evaluator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Evaluasi</th>
                        <td>{{ $monitoring->evaluasi->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($monitoring->evaluasi->catatan)
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $monitoring->evaluasi->catatan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('monitoring.edit', $monitoring) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection