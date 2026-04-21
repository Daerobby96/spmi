@extends('layouts.app')

@section('title', 'Evaluasi Monitoring')
@section('page-title', 'Evaluasi Monitoring')
@section('page-subtitle', 'Evaluasi hasil capaian indikator kinerja')

@section('page-actions')
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="periode_id" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ $periodeSel->id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @if(request('hasil'))
                <input type="hidden" name="hasil" value="{{ request('hasil') }}">
            @endif
        </form>
        <a href="{{ route('evaluasi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Buat Evaluasi
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Evaluasi Monitoring</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-success-subtle text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['tercapai'] }}</div>
                <div class="mini-stat-label">Tercapai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-danger-subtle text-danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['tidak_tercapai'] }}</div>
                <div class="mini-stat-label">Tidak Tercapai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-warning-subtle text-warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['perlu_perhatian'] }}</div>
                <div class="mini-stat-label">Perlu Perhatian</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-secondary-subtle text-secondary">
                <i class="bi bi-clock"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['belum_eval'] }}</div>
                <div class="mini-stat-label">Belum Evaluasi</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card card-custom mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="hasil" class="form-select">
                        <option value="">Semua Hasil</option>
                        <option value="tercapai" {{ request('hasil') === 'tercapai' ? 'selected' : '' }}>Tercapai</option>
                        <option value="tidak_tercapai" {{ request('hasil') === 'tidak_tercapai' ? 'selected' : '' }}>Tidak Tercapai</option>
                        <option value="perlu_perhatian" {{ request('hasil') === 'perlu_perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-filter me-1"></i>Filter</button>
                    <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
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
                        <th>Indikator</th>
                        <th class="text-center">Capaian</th>
                        <th style="width: 300px;">Analisa Auditor</th>
                        <th class="text-center">Hasil</th>
                        <th>Pelapor</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitorings as $m)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $m->indikator->nama }}</div>
                            <div class="text-muted small">{{ $m->indikator->kode }}</div>
                            <div class="text-muted small mt-1">
                                Target: {{ $m->indikator->target_nilai + 0 }} {{ $m->indikator->unit_pengukuran }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="fw-bold">{{ $m->nilai_capaian + 0 }}</div>
                            @php $persen = $m->persentase_capaian; @endphp
                            <span class="badge {{ $persen >= 100 ? 'bg-success' : ($persen >= 80 ? 'bg-warning text-dark' : 'bg-danger') }} small">
                                {{ number_format($persen, 1) }}%
                            </span>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm inline-eval" 
                                      data-m-id="{{ $m->id }}" data-field="analisa"
                                      rows="2" placeholder="Tulis analisa di sini...">{{ $m->evaluasi->analisa ?? '' }}</textarea>
                        </td>
                        <td class="text-center">
                            <select class="form-select form-select-sm inline-eval" 
                                    data-m-id="{{ $m->id }}" data-field="hasil"
                                    style="width: 140px; margin: 0 auto;">
                                <option value="">- Pilih Hasil -</option>
                                <option value="tercapai" {{ ($m->evaluasi && $m->evaluasi->hasil === 'tercapai') ? 'selected' : '' }}>Tercapai</option>
                                <option value="tidak_tercapai" {{ ($m->evaluasi && $m->evaluasi->hasil === 'tidak_tercapai') ? 'selected' : '' }}>Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ ($m->evaluasi && $m->evaluasi->hasil === 'perlu_perhatian') ? 'selected' : '' }}>Perlu Perhatian</option>
                            </select>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ $m->pelapor->name ?? '-' }}</div>
                            <div class="text-muted small">{{ $m->tanggal_input->format('d/m/y') }}</div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                @if($m->evaluasi)
                                <a href="{{ route('evaluasi.show', $m->evaluasi) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                                <a href="{{ route('monitoring.show', $m) }}"
                                   class="btn btn-sm btn-outline-info" title="Detail Monitoring">
                                    <i class="bi bi-search"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-clipboard-check d-block fs-2 mb-2"></i>
                            Belum ada data monitoring yang perlu dievaluasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($monitorings->count() > 0)
    <div class="pagination-wrapper mt-3">
        <div class="pagination-info">
            Menampilkan {{ $monitorings->count() }} data monitoring yang perlu dievaluasi
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.inline-eval').forEach(el => {
    el.addEventListener('change', function() {
        const m_id = this.dataset.mId;
        const field = this.dataset.field;
        const value = this.value;
        
        // Visual feedback
        this.style.opacity = '0.5';

        fetch("{{ route('evaluasi.update-inline') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ monitoring_id: m_id, field, value })
        })
        .then(response => response.json())
        .then(data => {
            this.style.opacity = '1';
            if (data.success) {
                this.classList.add('text-success');
                setTimeout(() => this.classList.remove('text-success'), 1000);
            } else {
                alert(data.message || 'Gagal menyimpan evaluasi.');
            }
        })
        .catch(err => {
            this.style.opacity = '1';
            alert('Terjadi kesalahan sistem.');
            console.error(err);
        });
    });
});
</script>
<style>
.inline-eval:focus {
    background-color: #fff !important;
    border: 1px solid #1a73e8 !important;
}
</style>
@endpush