@extends('layouts.app')

@section('title', 'Edit Evaluasi')
@section('page-title', 'Edit Evaluasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('scripts')
<script>
async function summarizeAI() {
    const btn = event.currentTarget;
    const target = document.querySelector('textarea[name="analisa"]');
    const text = target.value;
    
    if (!text || text.length < 20) {
        alert('Silakan tulis narasi analisa terlebih dahulu (min 20 karakter) sebelum diringkas.');
        return;
    }
    
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Meringkas...';
    btn.disabled = true;
    
    try {
        const response = await fetch('{{ route("ai.summarize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ text: text })
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            target.value = result.data;
        } else {
            alert(result.message || 'Gagal meringkas teks');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan koneksi');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
</script>
<style>
    .btn-xs { padding: .2rem .5rem; font-size: .75rem; }
</style>
@endpush

@section('content')
<div class="row g-4">
    {{-- Info Monitoring --}}
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                <h6 class="mb-0">Data Monitoring</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Indikator</th>
                        <td>{{ $evaluasi->monitoring->indikator->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <td>{{ $evaluasi->monitoring->indikator->unit_kerja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Target</th>
                        <td>{{ $evaluasi->monitoring->indikator->target_nilai ?? '-' }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Capaian</th>
                        <td>{{ $evaluasi->monitoring->nilai_capaian }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Persentase</th>
                        <td>
                            @php $persen = $evaluasi->monitoring->persentase_capaian; @endphp
                            <span class="badge {{ $persen >= 100 ? 'bg-success' : ($persen >= 80 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ number_format($persen, 1) }}%
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Form Edit --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clipboard-check me-2 text-primary"></i>
                <h6 class="mb-0">Edit Evaluasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluasi.update', $evaluasi) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_evaluasi"
                                class="form-control @error('tanggal_evaluasi') is-invalid @enderror"
                                value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi->format('Y-m-d')) }}" required>
                            @error('tanggal_evaluasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hasil Evaluasi <span class="text-danger">*</span></label>
                            <select name="hasil" class="form-select @error('hasil') is-invalid @enderror" required>
                                <option value="tercapai" {{ old('hasil', $evaluasi->hasil) === 'tercapai' ? 'selected' : '' }}>Tercapai</option>
                                <option value="tidak_tercapai" {{ old('hasil', $evaluasi->hasil) === 'tidak_tercapai' ? 'selected' : '' }}>Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ old('hasil', $evaluasi->hasil) === 'perlu_perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                            </select>
                            @error('hasil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Analisa <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="summarizeAI()">
                                    <i class="bi bi-robot me-1"></i>Ringkas AI
                                </button>
                            </div>
                            <textarea name="analisa" rows="4" class="form-control @error('analisa') is-invalid @enderror"
                                required>{{ old('analisa', $evaluasi->analisa) }}</textarea>
                            @error('analisa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Rekomendasi</label>
                            <textarea name="rekomendasi" rows="3" class="form-control">{{ old('rekomendasi', $evaluasi->rekomendasi) }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Perbarui
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection