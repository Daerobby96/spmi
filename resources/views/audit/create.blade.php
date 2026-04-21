@extends('layouts.app')

@section('title', 'Buat Audit Baru')
@section('page-title', 'Buat Audit Baru')
@section('page-subtitle', 'Inisiasi pelaksanaan audit mutu internal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clipboard2-plus me-2 text-primary"></i>
                <h6 class="mb-0">Form Pembuatan Audit</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('audit.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Info Dasar --}}
                        <div class="col-12">
                            <h6 class="text-muted mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Dasar</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kode Audit</label>
                            <input type="text" class="form-control" value="{{ $kodeAudit }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" class="form-select @error('periode_id') is-invalid @enderror" required>
                                <option value="">Pilih Periode</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="Draft" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Audit <span class="text-danger">*</span></label>
                            <input type="text" name="nama_audit"
                                class="form-control @error('nama_audit') is-invalid @enderror"
                                value="{{ old('nama_audit') }}"
                                placeholder="Contoh: Audit Internal Proses Pendidikan" required>
                            @error('nama_audit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Unit yang Diaudit <span class="text-danger">*</span></label>
                            <input type="text" name="unit_yang_diaudit"
                                class="form-control @error('unit_yang_diaudit') is-invalid @enderror"
                                value="{{ old('unit_yang_diaudit') }}"
                                placeholder="Contoh: Fakultas Teknik" required>
                            @error('unit_yang_diaudit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tim Auditor --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-people me-2"></i>Tim Auditor</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ketua Auditor <span class="text-danger">*</span></label>
                            <select name="ketua_auditor_id" class="form-select @error('ketua_auditor_id') is-invalid @enderror" required>
                                <option value="">Pilih Ketua Auditor</option>
                                @foreach($auditors as $a)
                                    <option value="{{ $a->id }}" {{ old('ketua_auditor_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->name }} ({{ $a->unit_kerja }})
                                    </option>
                                @endforeach
                            </select>
                            @error('ketua_auditor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Anggota Auditor</label>
                            <select name="anggota_auditor[]" class="form-select" multiple>
                                @foreach($auditors as $a)
                                    <option value="{{ $a->id }}" {{ in_array($a->id, old('anggota_auditor', [])) ? 'selected' : '' }}>
                                        {{ $a->name }} ({{ $a->unit_kerja }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Tahan Ctrl untuk memilih lebih dari satu</div>
                        </div>

                        {{-- Jadwal --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-calendar3 me-2"></i>Jadwal Audit</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_audit"
                                class="form-control @error('tanggal_audit') is-invalid @enderror"
                                value="{{ old('tanggal_audit') }}" required>
                            @error('tanggal_audit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Detail Tambahan --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-file-text me-2"></i>Detail Tambahan</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lingkup Audit</label>
                            <textarea name="lingkup_audit" rows="2" class="form-control"
                                placeholder="Jelaskan lingkup audit...">{{ old('lingkup_audit') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tujuan Audit</label>
                            <textarea name="tujuan_audit" rows="2" class="form-control"
                                placeholder="Jelaskan tujuan audit...">{{ old('tujuan_audit') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" rows="2" class="form-control"
                                placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Audit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection