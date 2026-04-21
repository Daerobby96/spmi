@extends('layouts.app')

@section('title', 'Buat Evaluasi')
@section('page-title', 'Buat Evaluasi Monitoring')
@section('page-subtitle', 'Evaluasi data monitoring yang telah disubmit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Buat Evaluasi</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Form Evaluasi --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clipboard-check me-2 text-primary"></i>
                <h6 class="mb-0">Form Evaluasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluasi.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Data Monitoring <span class="text-danger">*</span></label>
                            <select name="monitoring_id" id="monitoringSelect" class="form-select @error('monitoring_id') is-invalid @enderror" required>
                                <option value="">Pilih Data Monitoring</option>
                                @foreach($monitorings as $m)
                                    <option value="{{ $m->id }}" 
                                        data-target="{{ $m->indikator->target_nilai ?? 0 }}"
                                        data-capaian="{{ $m->nilai_capaian }}"
                                        data-unit="{{ $m->indikator->unit_pengukuran ?? '' }}"
                                        {{ $selected && $selected->id == $m->id ? 'selected' : '' }}>
                                        {{ $m->indikator->kode }} - {{ $m->indikator->nama }} 
                                        ({{ $m->tanggal_input->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('monitoring_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_evaluasi"
                                class="form-control @error('tanggal_evaluasi') is-invalid @enderror"
                                value="{{ old('tanggal_evaluasi', date('Y-m-d')) }}" required>
                            @error('tanggal_evaluasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Hasil Evaluasi <span class="text-danger">*</span></label>
                            <select name="hasil" class="form-select @error('hasil') is-invalid @enderror" required>
                                <option value="">Pilih Hasil</option>
                                <option value="tercapai" {{ old('hasil') === 'tercapai' ? 'selected' : '' }}>Tercapai</option>
                                <option value="tidak_tercapai" {{ old('hasil') === 'tidak_tercapai' ? 'selected' : '' }}>Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ old('hasil') === 'perlu_perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
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
                                placeholder="Analisa pencapaian indikator..." required>{{ old('analisa') }}</textarea>
                            @error('analisa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Rekomendasi</label>
                            <textarea name="rekomendasi" rows="3" class="form-control"
                                placeholder="Rekomendasi tindak lanjut (opsional)">{{ old('rekomendasi') }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Evaluasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Monitoring --}}
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                <h6 class="mb-0">Info Monitoring</h6>
            </div>
            <div class="card-body" id="monitoringInfo">
                @if($selected)
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Indikator</th>
                        <td>{{ $selected->indikator->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <td>{{ $selected->indikator->unit_kerja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Target</th>
                        <td>{{ $selected->indikator->target_nilai ?? '-' }} {{ $selected->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Capaian</th>
                        <td>{{ $selected->nilai_capaian }} {{ $selected->indikator->unit_pengukuran ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Persentase</th>
                        <td>
                            @php $persen = $selected->persentase_capaian; @endphp
                            <span class="badge {{ $persen >= 100 ? 'bg-success' : ($persen >= 80 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ number_format($persen, 1) }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Input</th>
                        <td>{{ $selected->tanggal_input->format('d M Y') }}</td>
                    </tr>
                </table>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-arrow-left-circle d-block fs-3 mb-2"></i>
                    Pilih data monitoring untuk melihat info
                </div>
                @endif
            </div>
        </div>

        @if($monitorings->isEmpty())
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Tidak ada data monitoring yang perlu dievaluasi. 
            Pastikan ada data monitoring dengan status "submitted".
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('monitoringSelect')?.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const target = option.dataset.target || 0;
        const capaian = option.dataset.capaian || 0;
        const unit = option.dataset.unit || '';
        
        if (this.value) {
            const persen = target > 0 ? ((capaian / target) * 100).toFixed(1) : 0;
            const badgeClass = persen >= 100 ? 'bg-success' : (persen >= 80 ? 'bg-warning text-dark' : 'bg-danger');
            
            document.getElementById('monitoringInfo').innerHTML = `
                <table class="table table-borderless detail-table mb-0">
                    <tr><th>Target</th><td>${target} ${unit}</td></tr>
                    <tr><th>Capaian</th><td>${capaian} ${unit}</td></tr>
                    <tr><th>Persentase</th><td><span class="badge ${badgeClass}">${persen}%</span></td></tr>
                </table>
            `;
        }
    });

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