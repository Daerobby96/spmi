@extends('layouts.app')

@section('title', 'Laporan Audit')
@section('page-title', 'Laporan Audit Internal')
@section('page-subtitle', 'Rekapitulasi hasil audit dan temuan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Audit</li>
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
                        <a href="{{ route('laporan.audit') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Statistik Temuan per Kategori --}}
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-pie-chart me-2 text-primary"></i>
                <h6 class="mb-0">Temuan per Kategori</h6>
            </div>
            <div class="card-body">
                @if($temuanPerKategori->count() > 0)
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        @foreach($temuanPerKategori as $kategori => $total)
                        <tr>
                            <td>
                                @if($kategori === 'KTS_Mayor')
                                    <span class="badge bg-danger">KTS Mayor</span>
                                @elseif($kategori === 'KTS_Minor')
                                    <span class="badge bg-warning text-dark">KTS Minor</span>
                                @elseif($kategori === 'OB')
                                    <span class="badge bg-info">Observasi</span>
                                @elseif($kategori === 'Rekomendasi')
                                    <span class="badge bg-success">Rekomendasi</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($kategori) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong>{{ $total }}</strong> temuan
                            </td>
                            <td style="width: 100px;">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $kategori === 'KTS_Mayor' ? 'danger' : ($kategori === 'KTS_Minor' ? 'warning' : ($kategori === 'OB' ? 'info' : 'success')) }}"
                                         style="width: {{ ($total / $temuanPerKategori->sum()) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="border-top">
                            <td><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ $temuanPerKategori->sum() }}</strong></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">Tidak ada data temuan</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Daftar Audit --}}
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-clipboard-check me-2 text-primary"></i>
                    <h6 class="mb-0 d-inline">Daftar Audit ({{ $audits->count() }})</h6>
                </div>
                <a href="{{ route('laporan.export.pdf', ['type' => 'audit', 'periode_id' => $periodeId]) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i>Export Ringkasan PDF
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Audit</th>
                                <th>Unit</th>
                                <th>Tanggal</th>
                                <th class="text-center">Temuan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($audits as $audit)
                            <tr>
                                <td><code class="text-primary">{{ $audit->kode_audit }}</code></td>
                                <td>{{ $audit->nama_audit }}</td>
                                <td>{{ $audit->unit_yang_diaudit }}</td>
                                <td>{{ $audit->tanggal_audit?->translatedFormat('d F Y') ?? '-' }}</td>
                                <td class="text-center">
                                    @php
                                        $major = $audit->temuans->where('kategori', 'KTS_Mayor')->count();
                                        $minor = $audit->temuans->where('kategori', 'KTS_Minor')->count();
                                        $obs = $audit->temuans->where('kategori', 'OB')->count();
                                    @endphp
                                    @if($major > 0)<span class="badge bg-danger me-1">{{ $major }} M</span>@endif
                                    @if($minor > 0)<span class="badge bg-warning text-dark me-1">{{ $minor }} m</span>@endif
                                    @if($obs > 0)<span class="badge bg-info">{{ $obs }} O</span>@endif
                                    @if($major + $minor + $obs === 0)<span class="text-muted">-</span>@endif
                                </td>
                                <td>
                                    @if($audit->status === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($audit->status === 'aktif')
                                        <span class="badge bg-warning text-dark">Berlangsung</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('laporan.export.audit.individual', $audit) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Export Laporan PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada data audit
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Temuan per Audit --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-list-check me-2 text-primary"></i>
                <h6 class="mb-0">Detail Temuan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Audit</th>
                                <th>Kategori</th>
                                <th>Uraian Temuan</th>
                                <th>Status</th>
                                <th>Batas TL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allTemuan = collect();
                                foreach($audits as $audit) {
                                    foreach($audit->temuans as $temuan) {
                                        $allTemuan->push($temuan);
                                    }
                                }
                            @endphp
                            @forelse($allTemuan as $temuan)
                            <tr>
                                <td><code class="text-primary">{{ $temuan->kode_temuan }}</code></td>
                                <td>{{ $temuan->audit->kode_audit }}</td>
                                <td>{!! $temuan->kategori_badge !!}</td>
                                <td>{{ Str::limit($temuan->uraian_temuan, 60) }}</td>
                                <td>
                                    @if($temuan->status === 'open')
                                        <span class="badge bg-danger">Open</span>
                                    @elseif($temuan->status === 'in_progress')
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @else
                                        <span class="badge bg-success">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($temuan->batas_tindak_lanjut)
                                        @if($temuan->batas_tindak_lanjut->isPast() && $temuan->status !== 'closed')
                                            <span class="text-danger">{{ $temuan->batas_tindak_lanjut->translatedFormat('d F Y') }}</span>
                                        @else
                                            {{ $temuan->batas_tindak_lanjut->translatedFormat('d F Y') }}
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada data temuan
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