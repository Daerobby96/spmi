@extends('layouts.app')

@section('title', 'Edit Kategori Dokumen')
@section('page-title', 'Edit Kategori Dokumen')
@section('page-subtitle', 'Ubah data kategori dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kategori-dokumen.index') }}">Kategori Dokumen</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-pencil me-2 text-primary"></i>
                <h6 class="mb-0">Edit Kategori: {{ $kategoriDokumen->nama }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kategori-dokumen.update', $kategoriDokumen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" name="kode"
                            class="form-control @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $kategoriDokumen->kode) }}"
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
                            value="{{ old('nama', $kategoriDokumen->nama) }}"
                            placeholder="Standar Operasional Prosedur"
                            required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge</label>
                        <select name="warna" class="form-select">
                            @foreach(['primary','success','danger','warning','info','dark','secondary','indigo'] as $w)
                            <option value="{{ $w }}" {{ (old('warna', $kategoriDokumen->warna) ?? 'primary') == $w ? 'selected' : '' }}>
                                {{ ucfirst($w) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control"
                            placeholder="Deskripsi singkat...">{{ old('keterangan', $kategoriDokumen->keterangan) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('kategori-dokumen.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Kategori</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Kode</td>
                        <td>
                            <span class="badge bg-{{ $kategoriDokumen->warna ?? 'secondary' }}">
                                {{ $kategoriDokumen->kode }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $kategoriDokumen->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Warna</td>
                        <td>
                            <span class="badge bg-{{ $kategoriDokumen->warna ?? 'secondary' }}">
                                {{ $kategoriDokumen->warna ?? 'secondary' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Dokumen</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $kategoriDokumen->dokumens()->count() }} dokumen
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Keterangan</td>
                        <td>{{ $kategoriDokumen->keterangan ?? '—' }}</td>
                    </tr>
                </table>
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
