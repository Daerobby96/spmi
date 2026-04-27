@extends('layouts.app')

@section('title', 'Laporan Monitoring')
@section('page-title', 'Laporan Monitoring & Evaluasi')
@section('page-subtitle', 'Rekapitulasi pelaksanaan dan capaian indikator kinerja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Monitoring</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Filter --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select">
                            <option value="">Semua Periode</option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->tahun }} - {{ $p->semester ?? 'Semester ' . $p->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('laporan.monitoring') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Statistik Hasil Evaluasi --}}
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-graph-pie me-2 text-primary"></i>
                <h6 class="mb-0">Hasil Evaluasi</h6>
            </div>
            <div class="card-body">
                @if($hasilEvaluasi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        @foreach($hasilEvaluasi as $hasil => $total)
                        <tr>
                            <td>
                                @if($hasil === 'tercapai')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tercapai</span>
                                @elseif($hasil === 'tidak_tercapai')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Tidak Tercapai</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>{{ ucfirst($hasil) }}</span>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ $total }}</strong></td>
                            <td style="width: 100px;">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $hasil === 'tercapai' ? 'success' : ($hasil === 'tidak_tercapai' ? 'danger' : 'warning') }}"
                                         style="width: {{ ($total / $hasilEvaluasi->sum()) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="border-top">
                            <td><strong>Total Dievaluasi</strong></td>
                            <td class="text-end"><strong>{{ $hasilEvaluasi->sum() }}</strong></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">Belum ada data evaluasi</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart me-2 text-primary"></i>
                <h6 class="mb-0">Ringkasan Monitoring</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $monitorings->count() }}</h3>
                            <small class="text-muted">Total Monitoring</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-success">{{ $hasilEvaluasi->get('tercapai', 0) }}</h3>
                            <small class="text-muted">Tercapai</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-danger">{{ $hasilEvaluasi->get('tidak_tercapai', 0) }}</h3>
                            <small class="text-muted">Tidak Tercapai</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-warning">{{ $monitorings->count() - $hasilEvaluasi->sum() }}</h3>
                            <small class="text-muted">Belum Dievaluasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Monitoring --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    <h6 class="mb-0 d-inline">Daftar Monitoring ({{ $monitorings->count() }})</h6>
                </div>
                <a href="{{ route('laporan.export.pdf', ['type' => 'monitoring', 'periode_id' => $periodeId]) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Indikator</th>
                                <th>Target</th>
                                <th>Capaian</th>
                                <th>Periode</th>
                                <th>Kinerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monitorings as $monitoring)
                            <tr>
                                <td><code class="text-primary">{{ $monitoring->kode_monitoring }}</code></td>
                                <td>
                                    @if($monitoring->indikator)
                                        {{ Str::limit($monitoring->indikator->nama_indikator, 40) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $monitoring->target }} {{ $monitoring->satuan }}</td>
                                <td>
                                    @if($monitoring->capaian !== null)
                                        {{ $monitoring->capaian }} {{ $monitoring->satuan }}
                                        @php
                                            $persen = $monitoring->target > 0 ? ($monitoring->capaian / $monitoring->target) * 100 : 0;
                                        @endphp
                                        <br><small class="text-{{ $persen >= 100 ? 'success' : ($persen >= 80 ? 'warning' : 'danger') }}">{{ number_format($persen, 1) }}%</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $monitoring->periode->tahun ?? '-' }}</td>
                                <td>
                                    @if($monitoring->is_tercapai)
                                        <span class="badge bg-success">Tercapai</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Tercapai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada data monitoring
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