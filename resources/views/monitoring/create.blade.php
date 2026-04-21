@extends('layouts.app')

@section('title', 'Input Data Monitoring')
@section('page-title', 'Input Data Monitoring')
@section('page-subtitle', 'Catat capaian indikator kinerja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring IKU/IKT</a></li>
    <li class="breadcrumb-item active">Input Data</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                <h6 class="mb-0">Form Input Data Monitoring</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input"
                                class="form-control @error('tanggal_input') is-invalid @enderror"
                                value="{{ old('tanggal_input', date('Y-m-d')) }}" required>
                            @error('tanggal_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Indikator Kinerja <span class="text-danger">*</span></label>
                            <select name="indikator_id" class="form-select @error('indikator_id') is-invalid @enderror" required>
                                <option value="">Pilih Indikator</option>
                                @foreach($indikators as $i)
                                    <option value="{{ $i->id }}" {{ old('indikator_id') == $i->id ? 'selected' : '' }}>
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
                                value="{{ old('nilai_capaian') }}" placeholder="Masukkan nilai capaian" required>
                            @error('nilai_capaian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bukti Dokumen</label>
                            <input type="file" name="bukti_dokumen"
                                class="form-control @error('bukti_dokumen') is-invalid @enderror"
                                accept=".pdf,.jpg,.png,.docx">
                            <div class="form-text">Format: PDF, JPG, PNG, DOCX (Max: 10MB)</div>
                            @error('bukti_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"
                                placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary">
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