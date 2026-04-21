@extends('layouts.app')

@section('title', 'Rapat Tinjauan Manajemen (RTM)')
@section('page-title', 'Rapat Tinjauan Manajemen (RTM)')
@section('page-subtitle', 'Monitoring hasil audit dan keputusan manajemen')

@section('page-actions')
    <a href="{{ route('rtm.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Buat RTM Baru
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">RTM</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-3">
        <div class="card card-custom h-100 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-1">Rapat Tinjauan (RTM)</h6>
                        <h2 class="mb-0 fw-bold">{{ $rtms->count() }}</h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-calendar-event"></i></div>
                </div>
                <div class="mt-2 text-white-50 small">Total agenda rapat periode ini</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom h-100 border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">KTS Mayor</h6>
                        <h2 class="mb-0 fw-bold text-danger">{{ $stats['kts_mayor'] }}</h2>
                    </div>
                    <div class="fs-1 text-danger-subtle"><i class="bi bi-exclamation-octagon"></i></div>
                </div>
                <div class="mt-2 text-muted small">Ketidaksesuaian Mayor periode ini</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">KTS Minor & OB</h6>
                        <h2 class="mb-0 fw-bold text-warning">{{ $stats['kts_minor'] + $stats['observasi'] }}</h2>
                    </div>
                    <div class="fs-1 text-warning-subtle"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
                <div class="mt-2 text-muted small">Total temuan kategori lain</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card card-custom h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Capaian Indikator</h6>
                        <h2 class="mb-0 fw-bold text-success">
                            {{ $stats['indikator_tercapai'] }} <span class="text-muted fs-6">/ {{ $stats['indikator_total'] }}</span>
                        </h2>
                    </div>
                    <div class="fs-1 text-success-subtle"><i class="bi bi-graph-up-arrow"></i></div>
                </div>
                <div class="mt-2 text-muted small">Capaian target kinerja institusi</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Judul Rapat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rtms as $rtm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-primary">{{ $rtm->judul_rapat }}</div>
                            <small class="text-muted">{{ Str::limit($rtm->agenda, 50) }}</small>
                        </td>
                        <td>{{ $rtm->tanggal_rapat->format('d M Y') }}</td>
                        <td>
                            @if($rtm->status === 'draft')
                                <span class="badge bg-warning text-dark">Draft</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('rtm.pdf', $rtm) }}" class="btn btn-sm btn-outline-danger" title="Cetak PDF" target="_blank">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('rtm.show', $rtm) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('rtm.edit', $rtm) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('rtm.destroy', $rtm) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus RTM ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x d-block fs-1 mb-2"></i>
                            Belum ada agenda RTM untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
