@extends('layouts.app')

@section('title', 'Edit Audit')
@section('page-title', 'Edit Audit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clipboard2-check me-2 text-primary"></i>
                <h6 class="mb-0">Edit Audit - {{ $audit->kode_audit }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('audit.update', $audit) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        {{-- Info Dasar --}}
                        <div class="col-12">
                            <h6 class="text-muted mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Dasar</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kode Audit</label>
                            <input type="text" class="form-control" value="{{ $audit->kode_audit }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" class="form-select @error('periode_id') is-invalid @enderror" required>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('periode_id', $audit->periode_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $audit->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ old('status', $audit->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status', $audit->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditutup" {{ old('status', $audit->status) === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Audit <span class="text-danger">*</span></label>
                            <input type="text" name="nama_audit"
                                class="form-control @error('nama_audit') is-invalid @enderror"
                                value="{{ old('nama_audit', $audit->nama_audit) }}" required>
                            @error('nama_audit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Unit yang Diaudit <span class="text-danger">*</span></label>
                            <input type="text" name="unit_yang_diaudit"
                                class="form-control @error('unit_yang_diaudit') is-invalid @enderror"
                                value="{{ old('unit_yang_diaudit', $audit->unit_yang_diaudit) }}" required>
                            @error('unit_yang_diaudit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tim Auditor --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-people me-2"></i>Tim Auditor</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ketua Auditor <span class="text-danger">*</span></label>
                            <select name="ketua_auditor_id" class="form-select @error('ketua_auditor_id') is-invalid @enderror" required>
                                @foreach($auditors as $a)
                                    <option value="{{ $a->id }}" {{ old('ketua_auditor_id', $audit->ketua_auditor_id) == $a->id ? 'selected' : '' }}>
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
                                    <option value="{{ $a->id }}" {{ in_array($a->id, old('anggota_auditor', $selectedAnggota)) ? 'selected' : '' }}>
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
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_audit"
                                class="form-control @error('tanggal_audit') is-invalid @enderror"
                                value="{{ old('tanggal_audit', $audit->tanggal_audit->format('Y-m-d')) }}" required>
                            @error('tanggal_audit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Opening Meeting</label>
                            <input type="datetime-local" name="opening_meeting"
                                class="form-control @error('opening_meeting') is-invalid @enderror"
                                value="{{ old('opening_meeting', $audit->opening_meeting?->format('Y-m-d\TH:i')) }}">
                            @error('opening_meeting') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Closing Meeting</label>
                            <input type="datetime-local" name="closing_meeting"
                                class="form-control @error('closing_meeting') is-invalid @enderror"
                                value="{{ old('closing_meeting', $audit->closing_meeting?->format('Y-m-d\TH:i')) }}">
                            @error('closing_meeting') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $audit->tanggal_selesai?->format('Y-m-d')) }}">
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Detail Tambahan --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-file-text me-2"></i>Detail Tambahan</h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lingkup Audit</label>
                            <textarea name="lingkup_audit" rows="2" class="form-control">{{ old('lingkup_audit', $audit->lingkup_audit) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tujuan Audit</label>
                            <textarea name="tujuan_audit" rows="2" class="form-control">{{ old('tujuan_audit', $audit->tujuan_audit) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" rows="2" class="form-control">{{ old('catatan', $audit->catatan) }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('audit.show', $audit) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Perbarui Audit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection