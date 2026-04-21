@extends('layouts.app')

@section('title', 'Edit Data Monitoring')
@section('page-title', 'Edit Data Monitoring')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring IKU/IKT</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                <h6 class="mb-0">Edit Data Monitoring</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('monitoring.update', $monitoring) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" class="form-select @error('periode_id') is-invalid @enderror" required>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('periode_id', $monitoring->periode_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input"
                                class="form-control @error('tanggal_input') is-invalid @enderror"
                                value="{{ old('tanggal_input', $monitoring->tanggal_input->format('Y-m-d')) }}" required>
                            @error('tanggal_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Indikator Kinerja <span class="text-danger">*</span></label>
                            <select name="indikator_id" class="form-select @error('indikator_id') is-invalid @enderror" required>
                                @foreach($indikators as $i)
                                    <option value="{{ $i->id }}" {{ old('indikator_id', $monitoring->indikator_id) == $i->id ? 'selected' : '' }}>
                                        {{ $i->kode }} - {{ $i->nama }} (Target: {{ $i->target_nilai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('indikator_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai Capaian <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_capaian" step="0.01" min="0"
                                class="form-control @error('nilai_capaian') is-invalid @enderror"
                                value="{{ old('nilai_capaian', $monitoring->nilai_capaian) }}" required>
                            @error('nilai_capaian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $monitoring->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ old('status', $monitoring->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="verified" {{ old('status', $monitoring->status) === 'verified' ? 'selected' : '' }}>Verified</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bukti Dokumen</label>
                            @if($monitoring->bukti_dokumen)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $monitoring->bukti_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-file-earmark me-1"></i>Lihat Bukti Saat Ini
                                </a>
                            </div>
                            @endif
                            <input type="file" name="bukti_dokumen"
                                class="form-control @error('bukti_dokumen') is-invalid @enderror"
                                accept=".pdf,.jpg,.png,.docx">
                            <div class="form-text">Format: PDF, JPG, PNG, DOCX (Max: 10MB). Kosongkan jika tidak ingin mengubah.</div>
                            @error('bukti_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"
                                placeholder="Catatan tambahan...">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary">
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