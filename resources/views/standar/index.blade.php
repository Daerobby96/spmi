@extends('layouts.app')

@section('title', 'Standar Mutu')
@section('page-title', 'Standar Mutu')
@section('page-subtitle', 'Kelola standar mutu yang menjadi acuan dokumen')

@section('page-actions')
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
    </button>
    <a href="{{ route('standar.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Standar
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Standar Mutu</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="🔍 Cari nama atau kode standar..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
                    @if(request('search'))
                        <a href="{{ route('standar.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Standar</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($standars as $s)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-indigo text-white fw-bold">{{ $s->kode }}</span></td>
                        <td class="fw-semibold">{{ $s->nama }}</td>
                        <td class="text-muted">{{ Str::limit($s->deskripsi, 60) ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('standar.show', $s) }}"
                               class="badge bg-primary-subtle text-primary text-decoration-none">
                                {{ $s->dokumens_count }} dokumen
                            </a>
                        </td>
                        <td class="text-center">
                            @if($s->is_aktif)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('standar.show', $s) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('standar.edit', $s) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('standar.destroy', $s) }}" method="POST"
                                      onsubmit="return confirm('Hapus standar ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-bookmark-x d-block fs-2 mb-2"></i>
                            Belum ada data standar mutu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($standars->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $standars->firstItem() }}-{{ $standars->lastItem() }} dari {{ $standars->total() }} data
        </div>
        {{ $standars->links() }}
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content border-0 shadow'>
            <form action='{{ route('standar.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-primary text-white border-0'>
                    <h5 class='modal-title' id='importModalLabel'><i class='bi bi-file-earmark-excel me-2'></i>Import Standar</h5>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 shadow-sm'>
                        <i class='bi bi-info-circle-fill me-2'></i>
                        Pastikan file Excel memiliki heading: <strong>kode, nama, deskripsi</strong>.
                    </div>
                    <div class='mb-3 text-center'>
                        <label class='form-label fw-bold d-block mb-2'>Format File Excel</label>
                        <a href="{{ route('standar.template') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download Template Standar
                        </a>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label fw-semibold'>Pilih File (.xlsx / .csv)</label>
                        <input type='file' name='file' class='form-control' accept='.xlsx,.xls,.csv' required>
                    </div>
                </div>
                <div class='modal-footer bg-light border-0'>
                    <button type='button' class='btn btn-secondary px-3' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='btn btn-primary px-4'>Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
