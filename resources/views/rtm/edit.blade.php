@extends('layouts.app')

@section('title', 'Edit RTM')
@section('page-title', 'Edit RTM')
@section('page-subtitle', $rTM->judul_rapat)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rtm.index') }}">RTM</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{ route('rtm.update', $rTM) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        {{-- Sisi Kiri: Info Umum --}}
        <div class="col-lg-4">
            <div class="card card-custom sticky-top" style="top: 80px;">
                <div class="card-header-custom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Header & Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Rapat <span class="text-danger">*</span></label>
                        <input type="text" name="judul_rapat" class="form-control @error('judul_rapat') is-invalid @enderror" 
                               required value="{{ old('judul_rapat', $rTM->judul_rapat) }}">
                        @error('judul_rapat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rapat" class="form-control @error('tanggal_rapat') is-invalid @enderror" 
                               required value="{{ old('tanggal_rapat', $rTM->tanggal_rapat->format('Y-m-d')) }}">
                        @error('tanggal_rapat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Rapat</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $rTM->status) == 'draft' ? 'selected' : '' }}>Draft (Perencanaan)</option>
                            <option value="selesai" {{ old('status', $rTM->status) == 'selesai' ? 'selected' : '' }}>Selesai (Final)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Absensi</label>
                        <input type="file" name="file_absensi" class="form-control">
                        @if($rTM->file_absensi)
                            <div class="mt-2 small text-muted"><i class="bi bi-file-earmark-check me-1"></i>File: {{ basename($rTM->file_absensi) }}</div>
                        @endif
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('rtm.show', $rTM) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Input Tinjauan --}}
        <div class="col-lg-8">
            <div class="card card-custom mb-4">
                <div class="card-header-custom bg-light">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-text me-2"></i>Agenda & Input Tinjauan (Standar ISO/SPMI)</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Agenda Utama</label>
                        <textarea name="agenda" rows="3" class="form-control">{{ old('agenda', $rTM->agenda) }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">1. Hasil Audit Internal</label>
                            <textarea name="input_audit_internal" rows="4" class="form-control">{{ old('input_audit_internal', $rTM->input_audit_internal) }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">2. Umpan Balik Pelanggan</label>
                            <textarea name="input_umpan_balik" rows="4" class="form-control">{{ old('input_umpan_balik', $rTM->input_umpan_balik) }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">3. Kinerja Proses</label>
                            <textarea name="input_kinerja_proses" rows="4" class="form-control">{{ old('input_kinerja_proses', $rTM->input_kinerja_proses) }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">4. Status Tindakan Perbaikan</label>
                            <textarea name="input_status_tindakan" rows="4" class="form-control">{{ old('input_status_tindakan', $rTM->input_status_tindakan) }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">5. Perubahan Sistem Pengelolaan</label>
                            <textarea name="input_perubahan_sistem" rows="4" class="form-control">{{ old('input_perubahan_sistem', $rTM->input_perubahan_sistem) }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">6. Rekomendasi Peningkatan</label>
                            <textarea name="input_rekomendasi" rows="4" class="form-control">{{ old('input_rekomendasi', $rTM->input_rekomendasi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header-custom bg-success-subtle">
                    <h6 class="mb-0 fw-bold text-success"><i class="bi bi-check-circle me-2"></i>Output & Keputusan RTM</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4 text-section">
                        <label class="form-label fw-bold">Notulensi Rapat (Umum)</label>
                        <textarea name="notulensi" rows="5" class="form-control">{{ old('notulensi', $rTM->notulensi) }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Keputusan Terkait Keefektifan SPMI</label>
                        <textarea name="output_keefektifan" rows="3" class="form-control">{{ old('output_keefektifan', $rTM->output_keefektifan) }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Keputusan Perbaikan Produk/Layanan</label>
                        <textarea name="output_perbaikan" rows="3" class="form-control">{{ old('output_perbaikan', $rTM->output_perbaikan) }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Kebutuhan Sumber Daya</label>
                        <textarea name="output_sumber_daya" rows="3" class="form-control">{{ old('output_sumber_daya', $rTM->output_sumber_daya) }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Kesimpulan / Keputusan Manajemen Lainnya</label>
                        <textarea name="keputusan_manajemen" rows="4" class="form-control">{{ old('keputusan_manajemen', $rTM->keputusan_manajemen) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
