@extends('layouts.app')

@section('title', 'Edit Indikator Kinerja')
@section('page-title', 'Edit Indikator Kinerja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('indikator-kinerja.index') }}">Indikator Kinerja</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bullseye me-2 text-primary"></i>
                <h6 class="mb-0">Edit Indikator — {{ $indikator->kode }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('indikator-kinerja.update', $indikator) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode"
                                class="form-control @error('kode') is-invalid @enderror"
                                value="{{ old('kode', $indikator->kode) }}"
                                style="text-transform:uppercase" required>
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $indikator->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="unit_kerja"
                                class="form-control @error('unit_kerja') is-invalid @enderror"
                                value="{{ old('unit_kerja', $indikator->unit_kerja) }}" required>
                            @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Standar Terkait</label>
                            <select name="standar_id" class="form-select">
                                <option value="">Pilih Standar (Opsional)</option>
                                @foreach($standars as $s)
                                    <option value="{{ $s->id }}" {{ old('standar_id', $indikator->standar_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->kode }} - {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-dark fw-semibold">Target Deskripsi</label>
                            <textarea name="target_deskripsi" rows="2"
                                class="form-control @error('target_deskripsi') is-invalid @enderror"
                                placeholder="Contoh: ≥ 80% atau Memenuhi 3 aspek kriteria...">{{ old('target_deskripsi', $indikator->target_deskripsi) }}</textarea>
                            <div class="form-text mt-1 italic small text-muted">Isi kolom ini jika target berupa teks deskriptif seperti pada dokumen standar.</div>
                            @error('target_deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Angka <span class="text-muted small">(Opsional)</span></label>
                            <input type="number" name="target_nilai" step="0.01" min="0"
                                class="form-control @error('target_nilai') is-invalid @enderror"
                                value="{{ old('target_nilai', $indikator->target_nilai) }}">
                            <div class="form-text italic small text-muted">Gunakan untuk tracking grafik (hanya angka).</div>
                            @error('target_nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Pengukuran <span class="text-danger">*</span></label>
                            <input type="text" name="unit_pengukuran"
                                class="form-control @error('unit_pengukuran') is-invalid @enderror"
                                value="{{ old('unit_pengukuran', $indikator->unit_pengukuran) }}" required>
                            @error('unit_pengukuran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_aktif"
                                    id="isAktif" value="1"
                                    {{ old('is_aktif', $indikator->is_aktif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isAktif">Indikator Aktif</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('indikator-kinerja.index') }}" class="btn btn-outline-secondary">
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