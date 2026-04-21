@extends('layouts.app')

@section('title', 'Manajemen Kuesioner')

@section('page-title', 'Manajemen Kuesioner')
@section('page-subtitle', 'Kelola survei kepuasan dan evaluasi diri institusi.')

@section('page-actions')
    @if(auth()->user()->isSuperAdmin())
    <button type="button" class="btn btn-outline-primary px-4 shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#importSiakadModal">
        <i class="bi bi-cloud-arrow-up me-2"></i>Import dari Siakad
    </button>
    <a href="{{ route('kuesioner.create') }}" class="btn btn-primary px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Buat Kuesioner Baru
    </a>
    @endif
@endsection

@section('content')
<div class="card card-custom border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Kuesioner</th>
                        <th>Periode</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th class="text-center">Responden</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuesioners as $k)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $k->full_judul }}</div>
                            <small class="text-muted">{{ Str::limit($k->deskripsi, 50) }}</small>
                        </td>
                        <td>{{ $k->periode->nama }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 rounded-pill">
                                {{ $k->target_role ? ucfirst($k->target_role) : 'Semua Unit' }}
                            </span>
                        </td>
                        <td>
                            @if($k->status == 'aktif')
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                </span>
                            @elseif($k->status == 'draft')
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                    <i class="bi bi-pencil-square me-1"></i>Draft
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                    <i class="bi bi-stop-circle me-1"></i>Selesai
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="fw-800 fs-5 text-primary">{{ $k->jawabans_count }}</div>
                            <small class="text-muted">Total Pengisi</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('kuesioner.show', $k) }}" class="btn btn-sm btn-light border-0 shadow-sm text-primary" title="Lihat Hasil">
                                    <i class="bi bi-bar-chart-fill"></i>
                                </a>
                                @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('kuesioner.edit', $k) }}" class="btn btn-sm btn-light border-0 shadow-sm mx-1" title="Edit / Pertanyaan">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('kuesioner.destroy', $k) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border-0 shadow-sm text-danger" title="Hapus Kuesioner" onclick="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini? Seluruh data jawaban responden juga akan terhapus.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-clipboard-x display-4 text-muted opacity-25"></i>
                            <p class="mt-3 text-muted">Belum ada kuesioner yang dibuat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($kuesioners->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $kuesioners->links() }}
    </div>
    @endif
</div>
@endsection

@if(auth()->user()->isSuperAdmin())
<!-- Modal Import Siakad -->
<div class="modal fade" id="importSiakadModal" tabindex="-1" aria-labelledby="importSiakadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importSiakadModalLabel">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Import Kuesioner Siakad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kuesioner.import-siakad') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Pilih file <strong>Laporan Rekap Kuesioner</strong> (.xls) yang diunduh langsung dari Siakad.
                    </div>
                    
                    <div class="mb-3">
                        <label for="siakad_file" class="form-label fw-bold">Pilih File XLS Siakad</label>
                        <input type="file" name="file" class="form-control" id="siakad_file" required>
                        <div class="form-text mt-2">
                            Sistem akan otomatis mendeteksi periode, judul, dan hasil kuesioner dari file tersebut.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
