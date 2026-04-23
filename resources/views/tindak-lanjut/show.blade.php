@extends('layouts.app')

@section('title', 'Detail Tindak Lanjut')
@section('page-title', 'Detail Tindak Lanjut')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tindak-lanjut.index') }}">Tindak Lanjut</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Temuan --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-exclamation-triangle me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Temuan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Kode Temuan</th>
                        <td><code class="text-primary">{{ $tindakLanjut->temuan->kode_temuan }}</code></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{!! $tindakLanjut->temuan->kategori_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Audit</th>
                        <td>{{ $tindakLanjut->temuan->audit->nama_audit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Unit Diaudit</th>
                        <td>{{ $tindakLanjut->temuan->audit->unit_yang_diaudit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Temuan</th>
                        <td>
                            @if($tindakLanjut->temuan->status === 'open')
                                <span class="badge bg-danger">Open</span>
                            @elseif($tindakLanjut->temuan->status === 'in_progress')
                                <span class="badge bg-warning text-dark">In Progress</span>
                            @else
                                <span class="badge bg-success">Closed</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <hr>
                <div>
                    <strong>Uraian Temuan:</strong>
                    <p class="mb-0 mt-1">{{ $tindakLanjut->temuan->uraian_temuan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Tindak Lanjut --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Tindak Lanjut</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $tindakLanjut->penanggungJawab->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Target Selesai</th>
                        <td>{{ $tindakLanjut->target_selesai->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Realisasi</th>
                        <td>{{ $tindakLanjut->tanggal_realisasi?->translatedFormat('d F Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($tindakLanjut->status === 'proses')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @elseif($tindakLanjut->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="mb-3">
                    <strong>Analisa Penyebab:</strong>
                    <p class="mb-0 mt-1">{{ $tindakLanjut->analisa_penyebab }}</p>
                </div>
                <div class="mb-3">
                    <strong>Rencana Tindakan:</strong>
                    <p class="mb-0 mt-1">{{ $tindakLanjut->rencana_tindakan }}</p>
                </div>
                @if($tindakLanjut->bukti_tindakan)
                <div>
                    <strong>Bukti Tindakan:</strong>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $tindakLanjut->bukti_tindakan) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Unduh Bukti
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Verifikasi --}}
    @if($tindakLanjut->hasil_verifikasi)
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-check2-circle me-2 text-success"></i>
                <h6 class="mb-0">Hasil Verifikasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Hasil</th>
                        <td>
                            @if($tindakLanjut->hasil_verifikasi === 'diterima')
                                <span class="badge bg-success">Diterima</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Verifikator</th>
                        <td>{{ $tindakLanjut->verifikator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Verifikasi</th>
                        <td>{{ $tindakLanjut->tanggal_verifikasi?->translatedFormat('d F Y') ?? '-' }}</td>
                    </tr>
                    @if($tindakLanjut->verifikasi_auditor)
                    <tr>
                        <th>Catatan Verifikasi</th>
                        <td>{{ $tindakLanjut->verifikasi_auditor }}</td>
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
            <a href="{{ route('tindak-lanjut.edit', $tindakLanjut) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection