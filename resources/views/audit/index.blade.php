@extends('layouts.app')

@section('title', 'Pelaksanaan Audit')
@section('page-title', 'Pelaksanaan Audit')
@section('page-subtitle', 'Kelola audit mutu internal')

@section('page-actions')
    <a href="{{ route('audit.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Buat Audit Baru
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Pelaksanaan Audit</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-clipboard2-check"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['total'] }}</div>
                <div class="mini-stat-label">Total Audit</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-secondary-subtle text-secondary">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['draft'] }}</div>
                <div class="mini-stat-label">Draft</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-warning-subtle text-warning">
                <i class="bi bi-play-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['aktif'] }}</div>
                <div class="mini-stat-label">Sedang Berjalan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-success-subtle text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['selesai'] }}</div>
                <div class="mini-stat-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="periode_id" class="form-select">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama, kode, atau unit..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
                    <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Audit</th>
                        <th>Nama Audit</th>
                        <th>Unit Diaudit</th>
                        <th>Ketua Auditor</th>
                        <th>Tgl Audit</th>
                        <th class="text-center">Temuan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><code class="text-primary">{{ $audit->kode_audit }}</code></td>
                        <td class="fw-semibold">{{ $audit->nama_audit }}</td>
                        <td>{{ $audit->unit_yang_diaudit }}</td>
                        <td>{{ $audit->ketuaAuditor->name ?? '-' }}</td>
                        <td>{{ $audit->tanggal_audit->translatedFormat('d F Y') }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                {{ $audit->temuans()->count() }} temuan
                            </span>
                        </td>
                        <td class="text-center">{!! $audit->status_badge !!}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('audit.show', $audit) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('audit.edit', $audit) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('audit.destroy', $audit) }}" method="POST"
                                      onsubmit="return confirm('Hapus audit ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-clipboard2-x d-block fs-2 mb-2"></i>
                            Belum ada data audit
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($audits->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $audits->firstItem() }}–{{ $audits->lastItem() }} dari {{ $audits->total() }} data
        </div>
        {{ $audits->links() }}
    </div>
    @endif
</div>
@endsection