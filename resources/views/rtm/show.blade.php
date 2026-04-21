@extends('layouts.app')

@section('title', 'Detail RTM')
@section('page-title', 'Detail RTM')
@section('page-subtitle', $rTM->judul_rapat)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rtm.index') }}">RTM</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Utama --}}
    <div class="col-lg-4">
        <div class="card card-custom sticky-top" style="top: 80px;">
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-1 text-primary"></i>Info Umum</h6>
                @if($rTM->status === 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-warning text-dark">Draft</span>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0 detail-table">
                    <tr>
                        <th width="120">Tanggal</th>
                        <td>{{ $rTM->tanggal_rapat->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Absensi</th>
                        <td>
                            @if($rTM->file_absensi)
                                <a href="{{ asset('storage/' . $rTM->file_absensi) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Lihat Absensi
                                </a>
                            @else
                                <span class="text-muted small italic">Tidak ada file</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <hr>
                <div class="mb-3">
                    <strong class="small text-muted d-block mb-1 text-uppercase">Agenda Utama:</strong>
                    <div class="p-2 bg-light rounded small">{{ $rTM->agenda ?? '-' }}</div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold small text-uppercase mb-3"><i class="bi bi-bar-chart me-1"></i>Status Temuan Periode Ini</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Belum Selesai (Open)</span>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $findingStats['open'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Dalam Proses</span>
                        <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $findingStats['in_progress'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">Sudah Selesai/Closed</span>
                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $findingStats['closed'] }}</span>
                    </div>
                </div>
                
                <div class="d-grid mt-4 gap-2">
                    <a href="{{ route('rtm.pdf', $rTM) }}" class="btn btn-outline-danger" target="_blank">
                        <i class="bi bi-file-pdf me-1"></i>Cetak Laporan RTM
                    </a>
                    <a href="{{ route('rtm.edit', $rTM) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Notulensi & Keputusan --}}
    <div class="col-lg-8">
        {{-- Section 1: Inputs --}}
        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-arrow-down me-2 text-primary"></i>I. Masukan Tinjauan (Inputs)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="30%">Kategori Tinjauan</th>
                                <th>Uraian / Hasil Pembahasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold small">1. Hasil Audit Internal</td>
                                <td class="small">{{ $rTM->input_audit_internal ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold small">2. Umpan Balik Pelanggan</td>
                                <td class="small">{{ $rTM->input_umpan_balik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold small">3. Kinerja Proses & Kesesuaian</td>
                                <td class="small">{{ $rTM->input_kinerja_proses ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold small">4. Status Tindakan Perbaikan</td>
                                <td class="small">{{ $rTM->input_status_tindakan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold small">5. Perubahan Sistem Pengelolaan</td>
                                <td class="small">{{ $rTM->input_perubahan_sistem ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold small">6. Rekomendasi Peningkatan</td>
                                <td class="small">{{ $rTM->input_rekomendasi ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Section 2: Outputs --}}
        <div class="card card-custom">
            <div class="card-header-custom bg-success-subtle border-success-subtle">
                <h6 class="mb-0 fw-bold text-success"><i class="bi bi-journal-arrow-up me-2"></i>II. Keputusan & Hasil (Outputs)</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="fw-bold small text-uppercase text-muted border-bottom pb-2">Notulensi Rapat</h6>
                    <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $rTM->notulensi ?? 'Tidak ada catatan.' }}</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-bold small text-primary"><i class="bi bi-shield-check me-1"></i>Keefektifan SPMI</h6>
                            <p class="mb-0 small text-muted">{{ $rTM->output_keefektifan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-bold small text-success"><i class="bi bi-box-seam me-1"></i>Perbaikan Layanan</h6>
                            <p class="mb-0 small text-muted">{{ $rTM->output_perbaikan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-bold small text-warning text-dark"><i class="bi bi-people me-1"></i>Sumber Daya</h6>
                            <p class="mb-0 small text-muted">{{ $rTM->output_sumber_daya ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-primary-subtle text-primary border border-primary-subtle rounded">
                    <h6 class="fw-bold small text-uppercase mb-2"><i class="bi bi-patch-check me-1"></i>Kesimpulan & Keputusan Manajemen</h6>
                    <div style="white-space: pre-wrap;">{{ $rTM->keputusan_manajemen ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 text-center">
        <a href="{{ route('rtm.index') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar RTM
        </a>
    </div>
</div>
@endsection
