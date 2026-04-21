@extends('layouts.app')

@section('title', 'Edit Temuan')
@section('page-title', 'Edit Temuan')
@section('page-subtitle', '{{ $temuan->kode_temuan }}')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Edit Temuan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-exclamation-triangle me-2 text-primary"></i>
                <h6 class="mb-0">Edit Temuan - {{ $temuan->kode_temuan }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('audit.temuan.update', [$audit, $temuan]) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori Temuan <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="KTS_Mayor" {{ old('kategori', $temuan->kategori) === 'KTS_Mayor' ? 'selected' : '' }}>KTS Mayor</option>
                                <option value="KTS_Minor" {{ old('kategori', $temuan->kategori) === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor</option>
                                <option value="OB" {{ old('kategori', $temuan->kategori) === 'OB' ? 'selected' : '' }}>Observasi</option>
                                <option value="Rekomendasi" {{ old('kategori', $temuan->kategori) === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', $temuan->status) === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $temuan->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="closed" {{ old('status', $temuan->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="verified" {{ old('status', $temuan->status) === 'verified' ? 'selected' : '' }}>Verified</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Klausul Standar</label>
                            <input type="text" name="klausul_standar"
                                class="form-control @error('klausul_standar') is-invalid @enderror"
                                value="{{ old('klausul_standar', $temuan->klausul_standar) }}">
                            @error('klausul_standar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batas Tindak Lanjut</label>
                            <input type="date" name="batas_tindak_lanjut"
                                class="form-control @error('batas_tindak_lanjut') is-invalid @enderror"
                                value="{{ old('batas_tindak_lanjut', $temuan->batas_tindak_lanjut?->format('Y-m-d')) }}">
                            @error('batas_tindak_lanjut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Uraian Temuan <span class="text-danger">*</span></span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 btn-voice" data-target="uraian_temuan" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill me-1"></i>Suara
                                </button>
                            </label>
                            <textarea name="uraian_temuan" rows="4" class="form-control @error('uraian_temuan') is-invalid @enderror"
                                required>{{ old('uraian_temuan', $temuan->uraian_temuan) }}</textarea>
                            @error('uraian_temuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Bukti Objektif</span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 btn-voice" data-target="bukti_objektif" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill me-1"></i>Suara
                                </button>
                            </label>
                            <textarea name="bukti_objektif" rows="3" class="form-control">{{ old('bukti_objektif', $temuan->bukti_objektif) }}</textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3">
                            <a href="{{ route('audit.temuan.show', [$audit, $temuan]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Perbarui Temuan
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
    document.addEventListener('DOMContentLoaded', function() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        
        if (!SpeechRecognition) {
            document.querySelectorAll('.btn-voice').forEach(btn => btn.style.display = 'none');
            console.warn('Browser tidak mendukung Speech Recognition.');
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        let activeTarget = null;
        let activeBtn = null;

        document.querySelectorAll('.btn-voice').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetName = this.getAttribute('data-target');
                activeTarget = document.querySelector(`[name="${targetName}"]`);
                activeBtn = this;

                if (activeBtn.classList.contains('btn-danger')) {
                    recognition.stop();
                    return;
                }

                try {
                    recognition.start();
                } catch (e) {
                    recognition.stop();
                    setTimeout(() => recognition.start(), 100);
                }
            });
        });

        recognition.onstart = function() {
            if (activeBtn) {
                activeBtn.classList.remove('btn-outline-primary');
                activeBtn.classList.add('btn-danger');
                activeBtn.innerHTML = '<i class="bi bi-record-fill me-1"></i>Mendengarkan...';
            }
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            if (activeTarget) {
                const startPos = activeTarget.selectionStart;
                const endPos = activeTarget.selectionEnd;
                const value = activeTarget.value;
                
                // Cek apakah textarea kosong atau butuh spasi
                const prefix = (value.length > 0 && startPos > 0 && value[startPos-1] !== ' ') ? ' ' : '';
                
                activeTarget.value = value.substring(0, startPos) + prefix + transcript + value.substring(endPos);
                
                // Geser kursor ke akhir teks yang baru dimasukkan
                const newPos = startPos + prefix.length + transcript.length;
                activeTarget.setSelectionRange(newPos, newPos);
                activeTarget.focus();
            }
        };

        recognition.onend = function() {
            if (activeBtn) {
                activeBtn.classList.remove('btn-danger');
                activeBtn.classList.add('btn-outline-primary');
                activeBtn.innerHTML = '<i class="bi bi-mic-fill me-1"></i>Suara';
            }
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            if (activeBtn) {
                activeBtn.classList.remove('btn-danger');
                activeBtn.classList.add('btn-outline-primary');
                activeBtn.innerHTML = '<i class="bi bi-mic-fill me-1"></i>Suara';
            }
            if (event.error === 'not-allowed') {
                alert('Izin mikrofon ditolak. Harap izinkan akses mikrofon di browser Anda.');
            }
        };
    });
</script>
@endpush