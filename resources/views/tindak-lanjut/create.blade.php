@extends('layouts.app')

@section('title', 'Buat Tindak Lanjut')
@section('page-title', 'Buat Tindak Lanjut')
@section('page-subtitle', 'Temuan: ' . $temuan->kode_temuan)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tindak-lanjut.index') }}">Tindak Lanjut</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Temuan --}}
    <div class="col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-exclamation-triangle me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Temuan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Kode</th>
                        <td><code class="text-primary">{{ $temuan->kode_temuan }}</code></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{!! $temuan->kategori_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Audit</th>
                        <td>{{ $temuan->audit->kode_audit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Unit</th>
                        <td>{{ $temuan->audit->unit_yang_diaudit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Batas TL</th>
                        <td>{{ $temuan->batas_tindak_lanjut?->translatedFormat('d F Y') ?? '-' }}</td>
                    </tr>
                </table>
                <hr>
                <div>
                    <strong>Uraian Temuan:</strong>
                    <p class="mb-0 mt-1 small">{{ $temuan->uraian_temuan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Tindak Lanjut --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                <h6 class="mb-0">Form Tindak Lanjut</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tindak-lanjut.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="temuan_id" value="{{ $temuan->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                            <select name="penanggung_jawab_id" class="form-select @error('penanggung_jawab_id') is-invalid @enderror" required>
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach($petugas as $p)
                                    <option value="{{ $p->id }}" {{ old('penanggung_jawab_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->unit_kerja }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penanggung_jawab_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="target_selesai"
                                class="form-control @error('target_selesai') is-invalid @enderror"
                                value="{{ old('target_selesai') }}" required>
                            @error('target_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Analisa Penyebab <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-outline-primary ai-btn" 
                                    onclick="analyzeAI('root-cause', 'analisa_penyebab')">
                                    <i class="bi bi-robot me-1"></i>Analisa AI
                                </button>
                            </div>
                            <textarea name="analisa_penyebab" id="analisa_penyebab" rows="3" 
                                class="form-control @error('analisa_penyebab') is-invalid @enderror"
                                placeholder="Analisa penyebab terjadinya temuan..." required>{{ old('analisa_penyebab') }}</textarea>
                            @error('analisa_penyebab') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Rencana Tindakan <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-outline-primary ai-btn" 
                                    onclick="analyzeAI('recommendation', 'rencana_tindakan')">
                                    <i class="bi bi-robot me-1"></i>Sugesti AI
                                </button>
                            </div>
                            <textarea name="rencana_tindakan" id="rencana_tindakan" rows="3" 
                                class="form-control @error('rencana_tindakan') is-invalid @enderror"
                                placeholder="Rencana tindakan yang akan dilakukan..." required>{{ old('rencana_tindakan') }}</textarea>
                            @error('rencana_tindakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bukti Tindakan</label>
                            <input type="file" name="bukti_tindakan"
                                class="form-control @error('bukti_tindakan') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.png">
                            <div class="form-text">Format: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</div>
                            @error('bukti_tindakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Tindak Lanjut
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function analyzeAI(type, targetId) {
    const btn = event.currentTarget;
    const target = document.getElementById(targetId);
    const uraianTemuan = "{{ $temuan->uraian_temuan }}";
    
    // UI Feedback
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Berpikir...';
    btn.disabled = true;
    
    try {
        const endpoint = type === 'root-cause' ? '{{ route("ai.analyze-root-cause") }}' : '{{ route("ai.suggest-recommendation") }}';
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ text: uraianTemuan })
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            target.value = result.data;
            // Auto resize textarea if needed
            target.style.height = 'auto';
            target.style.height = target.scrollHeight + 'px';
        } else {
            alert(result.message || 'Gagal memproses permintaan AI');
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