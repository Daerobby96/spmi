@extends('layouts.app')

@section('title', 'Tambah Dokumen')
@section('page-title', 'Tambah Dokumen Baru')
@section('page-subtitle', 'Isi form berikut untuk menambahkan dokumen mutu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Tambah Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">

<form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
    @csrf
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

        {{-- ── Informasi Dokumen ── --}}
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    <h6 class="mb-0">Informasi Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
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
                                    <option value="{{ $s->id }}" {{ is_array(old('standar_ids')) && in_array($s->id, old('standar_ids')) ? 'selected' : '' }}>
                                        [{{ $s->kode }}] {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('standar_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Anda bisa memilih lebih dari satu standar.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul') }}"
                                placeholder="Contoh: SOP Penerimaan Mahasiswa Baru"
                                required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="unit_pemilik"
                                class="form-control @error('unit_pemilik') is-invalid @enderror"
                                value="{{ old('unit_pemilik') }}"
                                placeholder="Contoh: Prodi Teknik Informatika"
                                required>
                            @error('unit_pemilik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Digunakan untuk membuat kode dokumen otomatis</div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Versi <span class="text-danger">*</span></label>
                            <input type="text" name="versi"
                                class="form-control @error('versi') is-invalid @enderror"
                                value="{{ old('versi', '1.0') }}"
                                placeholder="1.0"
                                required>
                            @error('versi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft"    {{ old('status','draft') == 'draft'    ? 'selected' : '' }}>Draft</option>
                                <option value="review"   {{ old('status') == 'review'   ? 'selected' : '' }}>Review</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="obsolete" {{ old('status') == 'obsolete' ? 'selected' : '' }}>Obsolete</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_terbit"
                                class="form-control @error('tanggal_terbit') is-invalid @enderror"
                                value="{{ old('tanggal_terbit', now()->format('Y-m-d')) }}"
                                required>
                            @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa"
                                class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror"
                                value="{{ old('tanggal_kadaluarsa') }}">
                            @error('tanggal_kadaluarsa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Kosongkan jika tidak ada masa berlaku</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Deskripsi singkat dokumen...">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Upload File ── --}}
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="bi bi-paperclip me-2 text-primary"></i>
                    <h6 class="mb-0">Upload File Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="upload-area" id="uploadArea">
                        <input type="file" name="file" id="fileInput"
                            class="@error('file') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                            style="display:none">
                        <div id="uploadPlaceholder" class="text-center py-3">
                            <i class="bi bi-cloud-arrow-up text-muted" style="font-size:2.5rem"></i>
                            <p class="mb-1 fw-medium mt-2">Klik atau drag & drop file di sini</p>
                            <p class="text-muted small mb-3">PDF, Word, Excel, PowerPoint — maks. 20MB</p>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-folder2-open me-1"></i>Pilih File
                            </button>
                        </div>
                        <div id="filePreview" class="d-none">
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded">
                                <div class="file-preview-icon text-primary fs-3" id="fileIcon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" id="fileName">-</div>
                                    <div class="text-muted small" id="fileSize">-</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="clearFile()">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan Dokumen
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
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('uploadArea');
    const uploadForm = document.getElementById('uploadForm');
    const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    const ALLOWED_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (validateFile(file)) {
                showPreview(file);
            } else {
                this.value = '';
            }
        }
    });

    // Drag & drop
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && validateFile(file)) {
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
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

    function showPreview(file) {
        document.getElementById('uploadPlaceholder').classList.add('d-none');
        document.getElementById('filePreview').classList.remove('d-none');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatSize(file.size);

        const ext = file.name.split('.').pop().toLowerCase();
        const icons = { pdf: 'bi-file-earmark-pdf text-danger', docx: 'bi-file-earmark-word text-primary',
                        doc: 'bi-file-earmark-word text-primary', xlsx: 'bi-file-earmark-excel text-success',
                        xls: 'bi-file-earmark-excel text-success', pptx: 'bi-file-earmark-slides text-warning',
                        ppt: 'bi-file-earmark-slides text-warning' };
        document.getElementById('fileIcon').innerHTML =
            `<i class="bi ${icons[ext] || 'bi-file-earmark'}"></i>`;
    }

    function clearFile() {
        fileInput.value = '';
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('filePreview').classList.add('d-none');
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
            
            // Simulasi progress (karena form submit biasa tidak bisa track progress)
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                    progressBar.textContent = progress + '%';
                }
            }, 200);
            
            // Stop interval setelah 10 detik (timeout)
            setTimeout(() => {
                clearInterval(interval);
            }, 10000);
        }
    });
</script>
@endpush