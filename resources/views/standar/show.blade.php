@extends('layouts.app')

@section('title', 'Detail Standar')
@section('page-title', 'Detail Standar Mutu')
@section('page-subtitle', '{{ $standar->nama }}')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('standar.index') }}">Standar Mutu</a></li>
    <li class="breadcrumb-item active">{{ $standar->kode }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Standar --}}
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bookmark-check me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Standar</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 120px;">Kode</td>
                        <td>
                            <span class="badge bg-indigo text-white fw-bold">{{ $standar->kode }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $standar->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($standar->is_aktif)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dokumen</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $standar->dokumens->count() }} dokumen
                            </span>
                        </td>
                    </tr>
                </table>
                @if($standar->deskripsi)
                <hr class="my-3">
                <div class="text-muted small">
                    <strong>Deskripsi:</strong><br>
                    {{ $standar->deskripsi }}
                </div>
                @endif
            </div>
        </div>

        <div class="card card-custom mt-3">
            <div class="card-body d-flex gap-2">
                <a href="{{ route('standar.edit', $standar) }}" class="btn btn-outline-primary flex-fill">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('standar.index') }}" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Daftar Dokumen --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                <h6 class="mb-0">Dokumen Terkait</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Dokumen</th>
                                <th>Kategori</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($standar->dokumens as $dokumen)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $dokumen->judul }}</td>
                                <td>
                                    @if($dokumen->kategori)
                                        <span class="badge bg-{{ $dokumen->kategori->warna ?? 'secondary' }}">
                                            {{ $dokumen->kategori->kode }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $dokumen->periode->nama ?? '—' }}</td>
                                <td>
                                    @if($dokumen->status === 'aktif')
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokumen.show', $dokumen) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-file-earmark-x d-block fs-2 mb-2"></i>
                                    Belum ada dokumen untuk standar ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
