@extends('layouts.app')

@section('title', 'Atur Kuesioner & Pertanyaan')

@section('page-title', 'Konfigurasi Kuesioner')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kuesioner.index') }}">Kuesioner</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Pengaturan Utama -->
    <div class="col-lg-4">
        <div class="card card-custom border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4">
                <h6 class="fw-bold mb-0">Pengaturan Utama</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kuesioner.update', $kuesioner) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul</label>
                        <input type="text" name="judul" class="form-control form-control-sm" value="{{ $kuesioner->judul }}" required>
                        <small class="text-muted" style="font-size: .65rem">Gunakan tag <code>{periode}</code> untuk menyisipkan nama periode otomatis.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Publikasi</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" {{ $kuesioner->status == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                            <option value="aktif" {{ $kuesioner->status == 'aktif' ? 'selected' : '' }}>Aktif (Bisa Diisi)</option>
                            <option value="selesai" {{ $kuesioner->status == 'selesai' ? 'selected' : '' }}>Selesai (Tutup Pengisian)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_public" value="0">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic" {{ $kuesioner->is_public ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold" for="isPublic">Akses Publik (Tanpa Login)</label>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: .7rem">Jika aktif, siapa pun yang memiliki link dapat mengisi tanpa perlu akun.</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Simpan Perubahan</button>
                    
                    @if($kuesioner->is_public && $kuesioner->status == 'aktif')
                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <label class="small fw-bold d-block mb-1 text-primary"><i class="bi bi-qr-code"></i> Link Statis (Selalunya Aktif):</label>
                        <input type="text" class="form-control form-control-sm bg-white mb-2" value="{{ route('user-kuesioner.active') }}" readonly onclick="this.select()">
                        
                        <label class="small fw-bold d-block mb-1 text-muted"><i class="bi bi-link-45deg"></i> Link Spesifik (ID: {{ $kuesioner->id }}):</label>
                        <input type="text" class="form-control form-control-sm bg-white" value="{{ route('user-kuesioner.fill', $kuesioner) }}" readonly onclick="this.select()">
                        <small class="text-muted mt-2 d-block" style="font-size: .65rem">Disarankan menggunakan <strong>Link Statis</strong> agar kode QR Anda tidak perlu diganti setiap periode.</small>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card card-custom border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-4">
                <h2 class="fw-800 mb-0">{{ $kuesioner->jawabans()->count() }}</h2>
                <p class="small opacity-75 mb-0">Responden saat ini</p>
                <hr class="opacity-25 my-3">
                <p class="small mb-0">Pastikan status <strong>Aktif</strong> agar responden target dapat melihat kuesioner ini di dashboard mereka.</p>
            </div>
        </div>
    </div>

    <!-- Question Builder -->
    <div class="col-lg-8">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold mb-0">Daftar Pertanyaan ({{ $kuesioner->pertanyaans()->count() }})</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#importQuestionModal">
                        <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
                    </button>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Pertanyaan
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" width="50">#</th>
                                <th>Pertanyaan</th>
                                <th>Tipe</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kuesioner->pertanyaans as $index => $q)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>{{ $q->pertanyaan }}</td>
                                <td>
                                    <span class="badge {{ $q->tipe == 'likert' ? 'bg-primary' : 'bg-info' }} rounded-pill px-3">
                                        {{ ucfirst($q->tipe) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('kuesioner.delete-question', $q) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('Hapus pertanyaan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-100">
                                    <p class="text-muted mb-0">Belum ada pertanyaan. Silakan klik tombol "Tambah Pertanyaan".</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pertanyaan -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold px-3 pt-3">Tambah Pertanyaan Baru</h5>
                <button type="button" class="btn-close me-2" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kuesioner.add-question', $kuesioner) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Isi Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="3" required placeholder="Contoh: Apakah Anda puas dengan layanan akademik?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Jawaban</label>
                        <select name="tipe" class="form-select" required>
                            <option value="likert">Pilihan Skala (1-5 / Likert)</option>
                            <option value="text">Esai / Jawaban Singkat</option>
                        </select>
                        <small class="text-muted">Tipe Likert mendukung perhitungan statistik otomatis.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Simpan Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Pertanyaan -->
<div class="modal fade" id="importQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold px-3 pt-3">Import Pertanyaan dari Excel/CSV</h5>
                <button type="button" class="btn-close me-2" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kuesioner.import', $kuesioner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih File</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx, .xls, .csv">
                        <div class="mt-2 text-muted" style="font-size: .75rem">
                            Format file yang didukung: <strong>.xlsx, .xls, .csv</strong>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-light rounded-4">
                        <h6 class="small fw-bold mb-2">Panduan Import:</h6>
                        <ul class="small text-muted mb-3 ps-3">
                            <li>Pastikan kolom pertama diberi judul <strong>Pertanyaan</strong>.</li>
                            <li>Kolom kedua diberi judul <strong>Tipe</strong> (isi dengan <code>likert</code> atau <code>text</code>).</li>
                            <li>Pertanyaan akan ditambahkan di bawah pertanyaan yang sudah ada.</li>
                        </ul>
                        <a href="{{ route('kuesioner.template') }}" class="btn btn-sm btn-outline-secondary w-100 fw-bold">
                            <i class="bi bi-download me-1"></i>Unduh Template CSV
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-upload me-2"></i>Proses Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
