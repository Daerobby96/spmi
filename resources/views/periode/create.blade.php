@extends('layouts.app')

@section('title', 'Tambah Periode')
@section('page-title', 'Tambah Periode Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('periode.index') }}">Manajemen Periode</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-calendar-plus me-2 text-primary"></i>
                <h6 class="mb-0">Form Tambah Periode</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('periode.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Periode <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Semester Ganjil 2024/2025" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun"
                                class="form-control @error('tahun') is-invalid @enderror"
                                value="{{ old('tahun', date('Y')) }}"
                                min="2020" max="2100" required>
                            @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="">Pilih Semester</option>
                                <option value="ganjil" {{ old('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="genap" {{ old('semester') === 'genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"
                                placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('periode.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
