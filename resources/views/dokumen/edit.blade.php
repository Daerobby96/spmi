@extends('layouts.app')

@section('title', 'Edit Dokumen')
@section('page-title', 'Edit Dokumen')
@section('page-subtitle', $dokumen->kode_dokumen . ' — ' . $dokumen->judul)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">

<form action="{{ route('dokumen.update', $dokumen) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
    @csrf
    @method('PUT')
    <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
    
    {{-- Progress bar untuk upload --}}
    <div id="uploadProgress" class="d-none mb-3">
        <div class="progress" style="height: 25px;">
            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                 role="progressbar" style="width: 0%">0%</div>
        </div>
        <small class="text-muted">Mengupload file...</small>
    </div>

    <div class="row g-4">
        {{-- Info Kode (read only) --}}
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    Kode Dokumen: <strong>{{ $dokumen->kode_dokumen }}</strong>
                    &nbsp;·&nbsp; Dibuat oleh: <strong>{{ $dokumen->pembuat->name }}</strong>
                    &nbsp;·&nbsp; Diunduh: <strong>{{ $dokumen->download_count }}x</strong>
                </div>
            </div>
        </div>

        {{-- Informasi Dokumen --}}
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    <h6 class="mb-0">Edit Informasi Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}"
                                        {{ (old('kategori_id', $dokumen->kategori_id) == $k->id) ? 'selected' : '' }}>
                                        [{{ $k->kode }}] {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Standar Mutu Terkait</label>
                            <select name="standar_ids[]" id="standar_ids" class="form-select @error('standar_ids') is-invalid @enderror" multiple placeholder="Pilih satu atau lebih standar...">
                                @foreach($standars as $s)
                                    <option value="{{ $s->id }}" 
                                        {{ in_array($s->id, old('standar_ids', $dokumen->standars->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        [{{ $s->kode }}] {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('standar_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $dokumen->judul) }}" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="unit_pemilik"
                                class="form-control @error('unit_pemilik') is-invalid @enderror"
                                value="{{ old('unit_pemilik', $dokumen->unit_pemilik) }}" required>
                            @error('unit_pemilik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Versi <span class="text-danger">*</span></label>
                            <input type="text" name="versi"
                                class="form-control @error('versi') is-invalid @enderror"
                                value="{{ old('versi', $dokumen->versi) }}" required>
                            @error('versi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                @foreach(['draft','review','approved','obsolete'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $dokumen->status) == $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $dokumen->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_public">Akses Publik</label>
                                <div class="form-text small">Dapat diakses tanpa login</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_terbit"
                                class="form-control @error('tanggal_terbit') is-invalid @enderror"
                                value="{{ old('tanggal_terbit', $dokumen->tanggal_terbit->format('Y-m-d')) }}" required>
                            @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa"
                                class="form-control"
                                value="{{ old('tanggal_kadaluarsa', $dokumen->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"
                                placeholder="Deskripsi singkat...">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upload File --}}
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-paperclip me-2 text-primary"></i>
                    <h6 class="mb-0">File Dokumen</h6>
                </div>
                <div class="card-body">
                    @if($dokumen->file_path)
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-3">
                        <i class="bi bi-file-earmark-check text-success fs-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">File saat ini</div>
                            <div class="text-muted small">
                                {{ strtoupper($dokumen->file_type) }} &nbsp;·&nbsp; {{ $dokumen->file_size_formatted }}
                            </div>
                        </div>
                        <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i>Download
                        </a>
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Upload file baru di bawah untuk mengganti file yang ada. Kosongkan jika tidak ingin mengubah.
                    </p>
                    @endif

                    <input type="file" name="file" class="form-control"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    <div class="form-text">PDF, Word, Excel, PowerPoint — maks. 20MB</div>
                    @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Perbarui Dokumen
                </button>
            </div>
        </div>
    </div>
</form>
</div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .ts-wrapper.multi .ts-control > div {
        background-color: var(--primary-color);
        color: #fff;
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#standar_ids",{
        plugins: ['remove_button'],
        maxItems: null,
    });
</script>
<script>
    const fileInput = document.querySelector('input[name="file"]');
    const uploadForm = document.getElementById('uploadForm');
    const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    const ALLOWED_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (!validateFile(file)) {
                this.value = '';
            }
        }
    });

    function validateFile(file) {
        // Check file size
        if (file.size > MAX_FILE_SIZE) {
            alert('Ukuran file terlalu besar! Maksimal 20MB.\n\nUkuran file Anda: ' + formatSize(file.size));
            return false;
        }

        // Check file extension
        const ext = file.name.split('.').pop().toLowerCase();
        if (!ALLOWED_TYPES.includes(ext)) {
            alert('Tipe file tidak diizinkan!\n\nTipe yang diizinkan: PDF, Word, Excel, PowerPoint');
            return false;
        }

        return true;
    }

    function formatSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + units[i];
    }

    // Form submit dengan progress
    uploadForm.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (file && file.size > MAX_FILE_SIZE) {
            e.preventDefault();
            alert('Ukuran file terlalu besar! Maksimal 20MB.\n\nUkuran file Anda: ' + formatSize(file.size));
            return false;
        }
        
        // Tampilkan progress bar jika ada file
        if (file) {
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            progressDiv.classList.remove('d-none');
            
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                    progressBar.textContent = progress + '%';
                }
            }, 200);
            
            setTimeout(() => {
                clearInterval(interval);
            }, 10000);
        }
    });
</script>
@endpush