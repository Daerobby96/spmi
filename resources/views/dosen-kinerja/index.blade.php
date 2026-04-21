@extends('layouts.app')

@section('title', 'Kinerja Dosen (EDOM)')

@section('page-title', 'Analisis Kinerja Dosen')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.export.pdf', ['type' => 'edom', 'periode_id' => $selectedPeriodeId]) }}" class="btn btn-outline-dark d-flex align-items-center gap-2" target="_blank">
            <i class="bi bi-file-pdf"></i>
            <span>Cetak Laporan Resmi</span>
        </a>
        <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up"></i>
            <span>Import Nilai EDOM</span>
        </button>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Kinerja Dosen</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body p-3">
                <form action="{{ route('kinerja-dosen.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1">PILIH PERIODE</label>
                        <select name="periode_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <div class="d-flex justify-content-md-end gap-4 mt-3 mt-md-0">
                            <div>
                                <div class="text-muted smaller fw-bold text-uppercase">Total Dosen</div>
                                <div class="fs-4 fw-800 text-primary">{{ $kinerjas->count() }}</div>
                            </div>
                            <div class="border-start ps-4">
                                <div class="text-muted smaller fw-bold text-uppercase">Rerata Institusi</div>
                                <div class="fs-4 fw-800 text-success">{{ number_format($kinerjas->avg('total_rerata'), 2) }}</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 60px">Rank</th>
                            <th>Nama Dosen</th>
                            <th class="text-center">NIP</th>
                            <th>Homebase</th>
                            <th class="text-center">Skor Rerata</th>
                            <th class="text-center">Predikat</th>
                            <th class="text-center pe-4" style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kinerjas as $index => $k)
                        <tr>
                            <td class="ps-4">
                                <div class="rank-badge {{ $index < 3 ? 'rank-top-'.$index+1 : '' }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $k->dosen_name }}</div>
                            </td>
                            <td class="text-center small text-muted">{{ $k->dosen_nip }}</td>
                            <td><span class="small text-muted">{{ $k->homebase }}</span></td>
                            <td class="text-center">
                                <div class="fw-800 fs-5 {{ $k->total_rerata >= 4 ? 'text-success' : ($k->total_rerata >= 3 ? 'text-primary' : 'text-danger') }}">
                                    {{ $k->total_rerata }}
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $score = $k->total_rerata;
                                    $label = 'Cukup'; $class = 'bg-warning';
                                    if($score >= 4.5) { $label = 'Sangat Baik'; $class = 'bg-success'; }
                                    elseif($score >= 3.75) { $label = 'Baik'; $class = 'bg-primary'; }
                                    elseif($score < 3) { $label = 'Kurang'; $class = 'bg-danger'; }
                                @endphp
                                <span class="badge {{ $class }} rounded-pill px-3">{{ $label }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('kinerja-dosen.show', $k->id) }}" class="btn btn-sm btn-light border-0 rounded-pill px-3">
                                    Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data kinerja dosen untuk periode ini.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Import Nilai EDOM (Siakad)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kinerja-dosen.import-edom') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted small mb-4">Pilih file Laporan EDOM (format .xls dari Siakad) untuk memproses data kinerja secara massal.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">FILE LAPORAN (.XLS)</label>
                        <input type="file" name="file" class="form-control" accept=".xls" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rank-badge {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f3f7;
        font-weight: 800;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .rank-top-1 { background: #ffd700; color: #fff; }
    .rank-top-2 { background: #c0c0c0; color: #fff; }
    .rank-top-3 { background: #cd7f32; color: #fff; }
</style>
@endsection
