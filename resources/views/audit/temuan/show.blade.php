@extends('layouts.app')

@section('title', 'Detail Temuan')
@section('page-title', 'Detail Temuan')
@section('page-subtitle', $temuan->kode_temuan)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Temuan</li>
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
                        <th>Kode</th>
                        <td><code class="text-primary">{{ $temuan->kode_temuan }}</code></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{!! $temuan->kategori_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Klausul</th>
                        <td>{{ $temuan->klausul_standar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($temuan->status === 'open')
                                <span class="badge bg-danger">Open</span>
                            @elseif($temuan->status === 'in_progress')
                                <span class="badge bg-warning text-dark">In Progress</span>
                            @elseif($temuan->status === 'closed')
                                <span class="badge bg-success">Closed</span>
                            @else
                                <span class="badge bg-primary">Verified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Batas TL</th>
                        <td>{{ $temuan->batas_tindak_lanjut?->format('d M Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Auditor</th>
                        <td>{{ $temuan->auditor->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Uraian & Bukti --}}
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-file-text me-2 text-primary"></i>
                <h6 class="mb-0">Uraian & Bukti</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Uraian Temuan:</strong>
                    <p class="mb-0 mt-1">{{ $temuan->uraian_temuan }}</p>
                </div>
                @if($temuan->bukti_objektif)
                <div>
                    <strong>Bukti Objektif:</strong>
                    <p class="mb-0 mt-1">{{ $temuan->bukti_objektif }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Tindak Lanjut --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h6 class="mb-0"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Riwayat Tindak Lanjut</h6>
                <a href="{{ route('tindak-lanjut.create', ['temuan' => $temuan->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah TL
                </a>
            </div>
            <div class="card-body p-0">
                @if($temuan->tindakLanjuts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Tindak Lanjut</th>
                                <th>Penanggung Jawab</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($temuan->tindakLanjuts as $tl)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>{{ $tl->created_at->format('d M Y') }}</td>
                                <td>{{ Str::limit($tl->rencana_tindakan, 60) }}</td>
                                <td>{{ $tl->penanggungJawab->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if($tl->status === 'proses')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @elseif($tl->status === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('tindak-lanjut.show', $tl) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-clipboard-x d-block fs-2 mb-2"></i>
                    Belum ada tindak lanjut
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('audit.temuan.edit', [$audit, $temuan]) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit Temuan
            </a>
            <a href="{{ route('audit.show', $audit) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Audit
            </a>
        </div>
    </div>
</div>
@endsection