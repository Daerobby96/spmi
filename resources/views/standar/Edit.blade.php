@extends('layouts.app')

@section('title', 'Edit Standar')
@section('page-title', 'Edit Standar Mutu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('standar.index') }}">Standar Mutu</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card card-custom">
    <div class="card-header-custom">
        <i class="bi bi-bookmark-check me-2 text-primary"></i>
        <h6 class="mb-0">Edit Standar — <code>{{ $standar->kode }}</code></h6>
    </div>
    <div class="card-body">
        <form action="{{ route('standar.update', $standar) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Standar <span class="text-danger">*</span></label>
                    <input type="text" name="kode"
                        class="form-control @error('kode') is-invalid @enderror"
                        value="{{ old('kode', $standar->kode) }}"
                        style="text-transform:uppercase" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Standar <span class="text-danger">*</span></label>
                    <input type="text" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $standar->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control"
                        placeholder="Deskripsi standar mutu...">{{ old('deskripsi', $standar->deskripsi) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_aktif"
                            id="isAktif" value="1"
                            {{ old('is_aktif', $standar->is_aktif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAktif">Standar Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                    <a href="{{ route('standar.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Perbarui
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection