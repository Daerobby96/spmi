@extends('layouts.app')

@section('title', 'Laporan Dokumen')
@section('page-title', 'Laporan Dokumen Mutu')
@section('page-subtitle', 'Rekapitulasi dokumen mutu berdasarkan kategori dan status')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Dokumen</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Filter --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('laporan.dokumen') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-folder me-2 text-primary"></i>
                <h6 class="mb-0">Dokumen per Kategori</h6>
            </div>
            <div class="card-body">
                @if($perKategori->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        @foreach($perKategori as $kategori => $total)
                        <tr>
                            <td>{{ $kategori ?? 'Tanpa Kategori' }}</td>
                            <td class="text-end"><strong>{{ $total }}</strong></td>
                            <td style="width: 100px;">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary"
                                         style="width: {{ ($total / $perKategori->sum()) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="border-top">
                            <td><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ $perKategori->sum() }}</strong></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">Tidak ada data</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-check-circle me-2 text-primary"></i>
                <h6 class="mb-0">Dokumen per Status</h6>
            </div>
            <div class="card-body">
                @if($perStatus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        @foreach($perStatus as $status => $total)
                        <tr>
                            <td>
                                @if($status === 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($status === 'review')
                                    <span class="badge bg-warning text-dark">Review</span>
                                @elseif($status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ $total }}</strong></td>
                            <td style="width: 100px;">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $status === 'approved' ? 'success' : ($status === 'review' ? 'warning' : ($status === 'rejected' ? 'danger' : 'secondary')) }}"
                                         style="width: {{ ($total / $perStatus->sum()) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="border-top">
                            <td><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ $perStatus->sum() }}</strong></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">Tidak ada data</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Daftar Dokumen --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                    <h6 class="mb-0 d-inline">Daftar Dokumen ({{ $dokumens->count() }})</h6>
                </div>
                <a href="{{ route('laporan.export.pdf', ['type' => 'dokumen', 'status' => request('status')]) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Standar</th>
                                <th>Versi</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumens as $dokumen)
                            <tr>
                                <td><code class="text-primary">{{ $dokumen->kode_dokumen }}</code></td>
                                <td>
                                    <a href="{{ route('dokumen.show', $dokumen) }}">
                                        {{ Str::limit($dokumen->judul, 40) }}
                                    </a>
                                </td>
                                <td>
                                    @if($dokumen->kategori)
                                        <span class="badge bg-{{ $dokumen->kategori->warna ?? 'secondary' }}">
                                            {{ $dokumen->kategori->kode }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $dokumen->standar->nama_standar ?? '-' }}</td>
                                <td>v{{ $dokumen->versi }}</td>
                                <td>
                                    @if($dokumen->status === 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($dokumen->status === 'review')
                                        <span class="badge bg-warning text-dark">Review</span>
                                    @elseif($dokumen->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($dokumen->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($dokumen->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $dokumen->tanggal_berlaku?->translatedFormat('d F Y') ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada data dokumen
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Laporan
        </a>
    </div>
</div>
@endsection