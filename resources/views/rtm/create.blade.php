@extends('layouts.app')

@section('title', 'Buat RTM Baru')
@section('page-title', 'Buat RTM Baru')
@section('page-subtitle', 'Input agenda dan tinjauan manajemen sesuai standar SPMI')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rtm.index') }}">RTM</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<form action="{{ route('rtm.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        {{-- Sisi Kiri: Info Umum --}}
        <div class="col-lg-4">
            <div class="card card-custom sticky-top" style="top: 80px;">
                <div class="card-header-custom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Header Rapat</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Rapat <span class="text-danger">*</span></label>
                        <input type="text" name="judul_rapat" class="form-control @error('judul_rapat') is-invalid @enderror" 
                               placeholder="Misal: RTM Semester Genap 2024" required value="{{ old('judul_rapat') }}">
                        @error('judul_rapat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rapat" class="form-control @error('tanggal_rapat') is-invalid @enderror" 
                               required value="{{ old('tanggal_rapat', date('Y-m-d')) }}">
                        @error('tanggal_rapat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Absensi (PDF/JPG/PNG)</label>
                        <input type="file" name="file_absensi" class="form-control @error('file_absensi') is-invalid @enderror">
                        <div class="form-text small">Maksimal 5MB. Bukti fisik kehadiran.</div>
                        @error('file_absensi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Agenda RTM
                        </button>
                        <a href="{{ route('rtm.index') }}" class="btn btn-outline-secondary">Batal</a>
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
                        <textarea name="agenda" rows="3" class="form-control" placeholder="Garis besar agenda yang dibahas...">{{ old('agenda') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">1. Hasil Audit Internal</label>
                            <textarea name="input_audit_internal" rows="4" class="form-control" placeholder="Ringkasan temuan dan efektivitas audit...">{{ old('input_audit_internal') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">2. Umpan Balik Pelanggan</label>
                            <textarea name="input_umpan_balik" rows="4" class="form-control" placeholder="Keluhan pelanggan, hasil survei kepuasan...">{{ old('input_umpan_balik') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">3. Kinerja Proses</label>
                            <textarea name="input_kinerja_proses" rows="4" class="form-control" placeholder="Capaian IKU/IKT, kesesuaian layanan...">{{ old('input_kinerja_proses') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">4. Status Tindakan Perbaikan</label>
                            <textarea name="input_status_tindakan" rows="4" class="form-control" placeholder="Status tindak lanjut dari RTM sebelumnya...">{{ old('input_status_tindakan') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">5. Perubahan Sistem Pengelolaan</label>
                            <textarea name="input_perubahan_sistem" rows="4" class="form-control" placeholder="Perubahan internal/eksternal yang berdampak pada mutu...">{{ old('input_perubahan_sistem') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="form-label fw-bold small text-uppercase text-muted">6. Rekomendasi Peningkatan</label>
                            <textarea name="input_rekomendasi" rows="4" class="form-control" placeholder="Saran-saran untuk perbaikan berkelanjutan...">{{ old('input_rekomendasi') }}</textarea>
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
                        <textarea name="notulensi" rows="5" class="form-control" placeholder="Catatan jalannya rapat...">{{ old('notulensi') }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Keputusan Terkait Keefektifan SPMI</label>
                        <textarea name="output_keefektifan" rows="3" class="form-control" placeholder="Keputusan untuk meningkatkan efektivitas sistem...">{{ old('output_keefektifan') }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Keputusan Perbaikan Produk/Layanan</label>
                        <textarea name="output_perbaikan" rows="3" class="form-control" placeholder="Rencana perbaikan layanan kepada stakeholder...">{{ old('output_perbaikan') }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Kebutuhan Sumber Daya</label>
                        <textarea name="output_sumber_daya" rows="3" class="form-control" placeholder="Alokasi SDM, anggaran, atau sarana prasarana baru...">{{ old('output_sumber_daya') }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="form-label fw-bold">Kesimpulan / Keputusan Manajemen Lainnya</label>
                        <textarea name="keputusan_manajemen" rows="4" class="form-control" placeholder="Resume keputusan strategis lainnya...">{{ old('keputusan_manajemen') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
