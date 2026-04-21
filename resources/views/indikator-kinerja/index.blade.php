@extends('layouts.app')

@section('title', 'Indikator Kinerja')
@section('page-title', 'Indikator Kinerja')
@section('page-subtitle', 'Kelola indikator kinerja utama (IKU) dan indikator kinerja tambahan (IKT)')

@section('page-actions')
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
    </button>
    <a href="{{ route('indikator-kinerja.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Indikator
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Indikator Kinerja</li>
@endsection

@section('content')

{{-- Filter --}}
<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="standar_id" class="form-select">
                        <option value="">Semua Standar</option>
                        @foreach($standars as $s)
                            <option value="{{ $s->id }}" {{ request('standar_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->kode }} - {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari kode atau nama indikator..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
                    <a href="{{ route('indikator-kinerja.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Indikator</th>
                        <th>Unit Kerja</th>
                        <th>Standar</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indikators as $i)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><code class="text-primary">{{ $i->kode }}</code></td>
                        <td class="fw-semibold">{{ $i->nama }}</td>
                        <td>{{ $i->unit_kerja }}</td>
                        <td>
                            @if($i->standar)
                                <span class="badge bg-indigo">{{ $i->standar->kode }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($i->target_deskripsi)
                                <span class="fw-medium text-dark">{{ $i->target_deskripsi }}</span>
                                @if($i->target_nilai)
                                    <small class="text-muted d-block">({{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }})</small>
                                @endif
                            @else
                                {{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if($i->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('indikator-kinerja.edit', $i) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('indikator-kinerja.destroy', $i) }}" method="POST"
                                      onsubmit="return confirm('Hapus indikator ini?')">
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
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-bullseye d-block fs-2 mb-2"></i>
                            Belum ada data indikator kinerja
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($indikators->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $indikators->firstItem() }}-{{ $indikators->lastItem() }} dari {{ $indikators->total() }} data
        </div>
        {{ $indikators->links() }}
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content border-0 shadow'>
            <form action='{{ route('indikator-kinerja.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-primary text-white border-0'>
                    <h5 class='modal-title' id='importModalLabel'><i class='bi bi-file-earmark-excel me-2'></i>Import Indikator</h5>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 shadow-sm'>
                        <i class='bi bi-info-circle-fill me-2'></i>
                        Pastikan file Excel memiliki heading: <strong>kode, nama, unit_pengukuran, target_nilai, unit_kerja, kode_standar</strong>.
                    </div>
                    <div class='mb-3 text-center'>
                        <label class='form-label fw-bold d-block mb-2'>Format File Excel</label>
                        <a href="{{ route('indikator-kinerja.template') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download Template Indikator
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
