@extends('layouts.app')

@section('title', 'Tindak Lanjut Temuan')
@section('page-title', 'Tindak Lanjut Temuan')
@section('page-subtitle', 'Kelola tindak lanjut temuan audit')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tindak Lanjut Temuan</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-danger-subtle text-danger">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['open'] }}</div>
                <div class="mini-stat-label">Open</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-warning-subtle text-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['in_progress'] }}</div>
                <div class="mini-stat-label">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-success-subtle text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['closed'] }}</div>
                <div class="mini-stat-label">Closed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-danger-subtle text-danger">
                <i class="bi bi-calendar-x"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['overdue'] }}</div>
                <div class="mini-stat-label">Overdue</div>
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
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="KTS_Mayor" {{ request('kategori') === 'KTS_Mayor' ? 'selected' : '' }}>KTS Mayor</option>
                        <option value="KTS_Minor" {{ request('kategori') === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor</option>
                        <option value="OB" {{ request('kategori') === 'OB' ? 'selected' : '' }}>Observasi</option>
                        <option value="Rekomendasi" {{ request('kategori') === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="deadline" class="form-select">
                        <option value="">Semua Deadline</option>
                        <option value="7" {{ request('deadline') === '7' ? 'selected' : '' }}>≤ 7 hari</option>
                        <option value="14" {{ request('deadline') === '14' ? 'selected' : '' }}>≤ 14 hari</option>
                        <option value="30" {{ request('deadline') === '30' ? 'selected' : '' }}>≤ 30 hari</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari temuan..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
                    <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
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
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Uraian Temuan</th>
                        <th>Unit Diaudit</th>
                        <th>Batas TL</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $temuan)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><code class="text-primary">{{ $temuan->kode_temuan }}</code></td>
                        <td>{!! $temuan->kategori_badge !!}</td>
                        <td class="fw-semibold">{{ Str::limit($temuan->uraian_temuan, 60) }}</td>
                        <td>{{ $temuan->audit->unit_yang_diaudit ?? '-' }}</td>
                        <td>
                            @if($temuan->batas_tindak_lanjut)
                                @php $isOverdue = $temuan->batas_tindak_lanjut->isPast(); @endphp
                                <span class="{{ $isOverdue ? 'text-danger fw-semibold' : '' }}">
                                    {{ $temuan->batas_tindak_lanjut->format('d M Y') }}
                                    @if($isOverdue)
                                        <i class="bi bi-exclamation-triangle"></i>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($temuan->status === 'open')
                                <span class="badge bg-danger">Open</span>
                            @elseif($temuan->status === 'in_progress')
                                <span class="badge bg-warning text-dark">In Progress</span>
                            @else
                                <span class="badge bg-success">Closed</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                @if($temuan->tindakLanjuts->count() === 0)
                                <a href="{{ route('tindak-lanjut.create', ['temuan_id' => $temuan->id]) }}"
                                   class="btn btn-sm btn-primary" title="Buat Tindak Lanjut">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                @else
                                <a href="{{ route('tindak-lanjut.show', $temuan->tindakLanjuts->first()) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Lihat TL">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-check-circle d-block fs-2 mb-2 text-success"></i>
                            Tidak ada temuan yang memerlukan tindak lanjut
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($temuans->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $temuans->firstItem() }}–{{ $temuans->lastItem() }} dari {{ $temuans->total() }} data
        </div>
        {{ $temuans->links() }}
    </div>
    @endif
</div>
@endsection