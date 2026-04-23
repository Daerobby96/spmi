@extends('layouts.app')

@section('title', 'Dokumen Mutu')

@section('page-title', 'Dokumen Mutu')
@section('page-subtitle', 'Kelola semua dokumen standar mutu internal')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i>Import Metadata
        </button>
        <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Dokumen
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dokumen Mutu</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-primary-subtle text-primary"><i class="bi bi-folder2-open"></i></div>
            <div>
                <div class="mini-stat-value">{{ $stats['total'] }}</div>
                <div class="mini-stat-label">Total Dokumen</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="mini-stat-value">{{ $stats['approved'] }}</div>
                <div class="mini-stat-label">Approved</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="mini-stat-value">{{ $stats['review'] }}</div>
                <div class="mini-stat-label">Sedang Review</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-danger-subtle text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="mini-stat-value">{{ $stats['kadaluarsa'] }}</div>
                <div class="mini-stat-label">Kadaluarsa</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('dokumen.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="🔍 Cari judul, kode, atau unit..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                        <option value="review"   {{ request('status') == 'review'   ? 'selected' : '' }}>Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="obsolete" {{ request('status') == 'obsolete' ? 'selected' : '' }}>Obsolete</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="standar_id" class="form-select">
                        <option value="">Semua Standar</option>
                        @foreach($standars as $s)
                            <option value="{{ $s->id }}" {{ request('standar_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->kode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if(request()->hasAny(['search','kategori_id','status','standar_id']))
                        <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Daftar Dokumen (Tabel) --}}
@if($dokumens->count())
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Kode</th>
                        <th>Judul Dokumen</th>
                        <th>Kategori</th>
                        <th>Unit</th>
                        <th>Standar</th>
                        <th>Versi</th>
                        <th>Tgl Terbit</th>
                        <th>Status</th>
                        <th>Visibilitas</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumens as $dok)
                    <tr>
                        <td class="text-center text-muted">{{ $dokumens->firstItem() + $loop->index }}</td>
                        <td>
                            <code class="text-primary">{{ $dok->kode_dokumen }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="dok-icon dok-icon-{{ $dok->file_type ?? 'default' }}" style="width:32px;height:32px;font-size:.9rem">
                                    @switch($dok->file_type)
                                        @case('pdf')  <i class="bi bi-file-earmark-pdf"></i>  @break
                                        @case('docx')
                                        @case('doc')  <i class="bi bi-file-earmark-word"></i> @break
                                        @case('xlsx')
                                        @case('xls')  <i class="bi bi-file-earmark-excel"></i>@break
                                        @case('pptx')
                                        @case('ppt')  <i class="bi bi-file-earmark-slides"></i>@break
                                        @default       <i class="bi bi-file-earmark"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <span class="fw-semibold">{{ $dok->judul }}</span>
                                    @if($dok->tanggal_kadaluarsa && $dok->tanggal_kadaluarsa <= now())
                                        <br><span class="text-danger small"><i class="bi bi-exclamation-triangle"></i> Kadaluarsa</span>
                                    @elseif($dok->tanggal_kadaluarsa && $dok->tanggal_kadaluarsa <= now()->addDays(30))
                                        <br><span class="text-warning small"><i class="bi bi-clock"></i> {{ $dok->tanggal_kadaluarsa->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $dok->kategori->warna ?? 'secondary' }} badge-sm">
                                {{ $dok->kategori->kode ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $dok->unit_pemilik }}</td>
                        <td>
                            @foreach($dok->standars as $standar)
                                <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $standar->kode }}</span>
                            @endforeach
                            @if($dok->standars->isEmpty())
                                -
                            @endif
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary">v{{ $dok->versi }}</span></td>
                        <td>{{ $dok->tanggal_terbit->format('d M Y') }}</td>
                        <td>
                            @php
                                $statusConfig = [
                                    'draft'    => ['secondary', 'Draft'],
                                    'review'   => ['warning',   'Review'],
                                    'approved' => ['success',   'Approved'],
                                    'obsolete' => ['dark',      'Obsolete'],
                                ];
                                $sc = $statusConfig[$dok->status] ?? ['secondary', $dok->status];
                            @endphp
                            <span class="badge bg-{{ $sc[0] }}">{{ $sc[1] }}</span>
                        </td>
                        <td>
                            @if($dok->is_public)
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    <i class="bi bi-globe me-1"></i>Publik
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">
                                    <i class="bi bi-lock-fill me-1"></i>Internal
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($dok->file_path)
                                <a href="{{ route('dokumen.download', $dok) }}"
                                   class="btn btn-sm btn-outline-success" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                <a href="{{ route('dokumen.show', $dok) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('dokumen.edit', $dok) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dokumen.destroy', $dok) }}" method="POST"
                                      onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $dokumens->firstItem() }}-{{ $dokumens->lastItem() }} dari {{ $dokumens->total() }} dokumen
        </div>
        {{ $dokumens->links() }}
    </div>
</div>

@else
<div class="empty-state text-center py-5">
    <i class="bi bi-folder2 text-muted" style="font-size:3rem"></i>
    <h6 class="mt-3 text-muted">Belum ada dokumen</h6>
    <p class="text-muted small">Mulai tambahkan dokumen mutu pertama Anda</p>
    <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Dokumen
    </a>
</div>
@endif


{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content border-0 shadow'>
            <form action='{{ route('dokumen.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-primary text-white border-0'>
                    <h5 class='modal-title' id='importModalLabel'><i class='bi bi-file-earmark-excel me-2'></i>Import Metadata Dokumen</h5>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 shadow-sm small'>
                        <i class='bi bi-info-circle-fill me-2'></i>
                        Fitur ini hanya mengimport metadata. File fisik dokumen harus diunggah manual setelahnya.
                        <br><br>
                        <strong>Heading:</strong> nama, kategori, kode_standar, versi, tahun, deskripsi
                    </div>
                    <div class='mb-3 text-center'>
                        <label class='form-label fw-bold d-block mb-2'>Format File Excel</label>
                        <a href="{{ route('dokumen.template') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download Template Dokumen
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
