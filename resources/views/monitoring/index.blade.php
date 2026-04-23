@extends('layouts.app')

@section('title', 'Monitoring IKU/IKT')
@section('page-title', 'Monitoring IKU/IKT')
@section('page-subtitle', 'Pantau capaian indikator kinerja unit kerja')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
        </button>
        <form action="{{ route('monitoring.sync-siakad') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Sync SIAKAD
            </button>
        </form>
        <a href="{{ route('monitoring.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>Input Data Baru
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Monitoring IKU/IKT</li>
@endsection

@section('content')

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
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari indikator..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
                    <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-primary-subtle text-primary">
                <i class="bi bi-bar-chart-line"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['total'] }}</div>
                <div class="mini-stat-label">Total Data</div>
            </div>
        </div>
    </div>
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
                <div class="mini-stat-value">{{ $stats['tidak'] }}</div>
                <div class="mini-stat-label">Tidak Tercapai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mini-stat">
            <div class="mini-stat-icon bg-warning-subtle text-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div>
                <div class="mini-stat-value">{{ $stats['belum_eval'] }}</div>
                <div class="mini-stat-label">Belum Evaluasi</div>
            </div>
        </div>
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
                        <th>Tanggal</th>
                        <th>Indikator</th>
                        <th>Unit Kerja</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Capaian</th>
                        <th class="text-center">%</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indikators as $i)
                    @php 
                        $m = $i->monitorings->first(); 
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $m ? $m->tanggal_input->translatedFormat('d F Y') : '-' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $i->nama }}</div>
                            <div class="text-muted small">{{ $i->kode }}</div>
                        </td>
                        <td>{{ $i->unit_kerja }}</td>
                        <td class="text-center">{{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }}</td>
                        <td class="text-center">
                            <input type="number" step="any" 
                                   class="form-control form-control-sm text-center fw-bold inline-edit" 
                                   data-indikator-id="{{ $i->id }}" data-periode-id="{{ $periodeSel->id }}" data-field="nilai_capaian"
                                   value="{{ $m ? ($m->nilai_capaian + 0) : '' }}"
                                   placeholder="..."
                                   style="width: 100px; margin: 0 auto;">
                        </td>
                        <td class="text-center" id="persen-{{ $i->id }}">
                            @if($m)
                                @php $persen = $m->persentase_capaian; @endphp
                                <span class="badge {{ $persen >= 100 ? 'bg-success' : ($persen >= 80 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ number_format($persen, 1) }}%
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <select class="form-select form-select-sm border-0 bg-transparent fw-semibold inline-edit" 
                                    data-indikator-id="{{ $i->id }}" data-periode-id="{{ $periodeSel->id }}" data-field="status"
                                    style="width: 110px; margin: 0 auto; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                                <option value="draft" {{ ($m && $m->status === 'draft') ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ ($m && $m->status === 'submitted') ? 'selected' : '' }}>Submitted</option>
                                <option value="verified" {{ ($m && $m->status === 'verified') ? 'selected' : '' }}>Verified</option>
                            </select>
                        </td>
                        <td class="text-center">
                            @if($m)
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('monitoring.show', $m) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('monitoring.edit', $m) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('monitoring.destroy', $m) }}" method="POST"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-muted small">Belum terisi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-bar-chart-x d-block fs-2 mb-2"></i>
                            Belum ada data indikator aktif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($indikators->count() > 0)
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan semua data (Total: {{ $indikators->count() }} indikator)
        </div>
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content border-0 shadow'>
            <form action='{{ route('monitoring.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-primary text-white border-0'>
                    <h5 class='modal-title' id='importModalLabel'><i class='bi bi-file-earmark-excel me-2'></i>Import Capaian</h5>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 shadow-sm small'>
                        <i class='bi bi-info-circle-fill me-2'></i>
                        Gunakan fitur ini untuk update capaian indikator secara massal pada <strong>Periode Aktif</strong>.
                        <br><small>Heading: <strong>kode_indikator, capaian_nilai, analisis, kendala, tindakan</strong></small>
                    </div>
                    <div class='mb-3 text-center'>
                        <label class='form-label fw-bold d-block mb-2'>Format File Excel</label>
                        <a href="{{ route('monitoring.template') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download Template Capaian
                        </a>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label fw-semibold'>Pilih File (.xlsx / .csv)</label>
                        <input type='file' name='file' class='form-control px-3 py-2' accept='.xlsx,.xls,.csv' required>
                    </div>
                </div>
                <div class='modal-footer bg-light border-0'>
                    <button type='button' class='btn btn-secondary px-3' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='btn btn-primary px-4'>Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.inline-edit').forEach(el => {
    el.addEventListener('change', function() {
        const indikator_id = this.dataset.indikatorId;
        const periode_id = this.dataset.periodeId;
        const field = this.dataset.field;
        const value = this.value;
        
        // Visual feedback
        this.style.opacity = '0.5';

        fetch("{{ route('monitoring.update-inline') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ indikator_id, periode_id, field, value })
        })
        .then(response => response.json())
        .then(data => {
            this.style.opacity = '1';
            if (data.success) {
                // Flash success color
                this.classList.add('text-success');
                setTimeout(() => this.classList.remove('text-success'), 1000);
                
                // Update percentage cell if needed
                if (data.persentase) {
                    const persenCell = document.getElementById(`persen-${indikator_id}`);
                    if (persenCell) {
                        const pValue = parseFloat(data.persentase);
                        const badgeClass = pValue >= 100 ? 'bg-success' : (pValue >= 80 ? 'bg-warning text-dark' : 'bg-danger');
                        persenCell.innerHTML = `<span class="badge ${badgeClass}">${data.persentase}</span>`;
                    }
                }
            } else {
                alert(data.message || 'Gagal memperbarui data.');
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
.inline-edit:focus {
    background-color: #fff !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: none !important;
}
.inline-edit {
    cursor: pointer;
    transition: all 0.2s;
}
.inline-edit:hover {
    background-color: rgba(0,0,0,0.03) !important;
}
</style>
@endpush
