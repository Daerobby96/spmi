@extends('layouts.app')

@section('title', 'Tambah Indikator Kinerja')
@section('page-title', 'Tambah Indikator Kinerja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('indikator-kinerja.index') }}">Indikator Kinerja</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bullseye me-2 text-primary"></i>
                <h6 class="mb-0">Form Tambah Indikator Kinerja</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('indikator-kinerja.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode"
                                class="form-control @error('kode') is-invalid @enderror"
                                value="{{ old('kode') }}"
                                placeholder="IKU-01" style="text-transform:uppercase" required>
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}"
                                placeholder="Persentase kelulusan mahasiswa" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="unit_kerja"
                                class="form-control @error('unit_kerja') is-invalid @enderror"
                                value="{{ old('unit_kerja') }}"
                                placeholder="Fakultas Teknik" required>
                            @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Standar Terkait</label>
                            <select name="standar_id" class="form-select">
                                <option value="">Pilih Standar (Opsional)</option>
                                @foreach($standars as $s)
                                    <option value="{{ $s->id }}" {{ old('standar_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->kode }} - {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Nilai <span class="text-danger">*</span></label>
                            <input type="number" name="target_nilai" step="0.01" min="0"
                                class="form-control @error('target_nilai') is-invalid @enderror"
                                value="{{ old('target_nilai') }}" placeholder="100" required>
                            @error('target_nilai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Pengukuran <span class="text-danger">*</span></label>
                            <input type="text" name="unit_pengukuran"
                                class="form-control @error('unit_pengukuran') is-invalid @enderror"
                                value="{{ old('unit_pengukuran') }}"
                                placeholder="%, Orang, Dokumen" required>
                            @error('unit_pengukuran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_aktif"
                                    id="isAktif" value="1" checked>
                                <label class="form-check-label" for="isAktif">Indikator Aktif</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('indikator-kinerja.index') }}" class="btn btn-outline-secondary">
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