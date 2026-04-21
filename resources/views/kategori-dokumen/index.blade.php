@extends('layouts.app')

@section('title', 'Kategori Dokumen')
@section('page-title', 'Kategori Dokumen')
@section('page-subtitle', 'Kelola kategori untuk pengelompokan dokumen mutu')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori Dokumen</li>
@endsection

@section('content')
<div class="row g-4">

    {{-- Daftar Kategori --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Kategori</th>
                                <th>Warna Badge</th>
                                <th class="text-center">Dokumen</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $k)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-{{ $k->warna ?? 'secondary' }}">
                                        {{ $k->kode }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $k->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $k->warna ?? 'secondary' }}">
                                        {{ $k->warna ?? 'secondary' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">
                                        {{ $k->dokumens_count }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ Str::limit($k->keterangan, 40) ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('kategori-dokumen.edit', $k) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori-dokumen.destroy', $k) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                {{ $k->dokumens_count > 0 ? 'disabled title=Masih ada dokumen' : '' }}>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada kategori dokumen
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Tambah Cepat --}}
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-tags me-2 text-primary"></i>
                <h6 class="mb-0">Tambah Kategori Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kategori-dokumen.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" name="kode"
                            class="form-control @error('kode') is-invalid @enderror"
                            value="{{ old('kode') }}"
                            placeholder="SOP / SK / PM / IK / FR"
                            style="text-transform:uppercase"
                            maxlength="10" required>
                        @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Singkatan max 10 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Standar Operasional Prosedur"
                            required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge</label>
                        <select name="warna" class="form-select">
                            @foreach(['primary','success','danger','warning','info','dark','secondary','indigo'] as $w)
                            <option value="{{ $w }}" {{ old('warna') == $w ? 'selected' : '' }}>
                                {{ ucfirst($w) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control"
                            placeholder="Deskripsi singkat...">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
                    </button>
                </form>
            </div>
        </div>

        {{-- Info warna yang tersedia --}}
        <div class="card card-custom mt-3">
            <div class="card-header-custom">
                <i class="bi bi-palette me-2 text-primary"></i>
                <h6 class="mb-0">Contoh Warna Badge</h6>
            </div>
            <div class="card-body d-flex flex-wrap gap-2">
                @foreach(['primary','success','danger','warning','info','dark','secondary'] as $w)
                    <span class="badge bg-{{ $w }}">{{ ucfirst($w) }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection