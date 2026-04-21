@extends('layouts.app')

@section('title', 'Tambah Temuan')
@section('page-title', 'Tambah Temuan Baru')
@section('page-subtitle', 'Audit: {{ $audit->nama_audit }}')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Tambah Temuan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-exclamation-triangle me-2 text-primary"></i>
                <h6 class="mb-0">Form Tambah Temuan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('audit.temuan.store', $audit) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($checklist))
                        <input type="hidden" name="audit_checklist_id" value="{{ $checklist->id }}">
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori Temuan <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select form-select-lg @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="KTS_Mayor" {{ (old('kategori') === 'KTS_Mayor' || (isset($checklist) && $checklist->status == 'tidak_sesuai')) ? 'selected' : '' }}>KTS Mayor</option>
                                <option value="KTS_Minor" {{ old('kategori') === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor</option>
                                <option value="OB" {{ (old('kategori') === 'OB' || (isset($checklist) && $checklist->status == 'observasi')) ? 'selected' : '' }}>Observasi</option>
                                <option value="Rekomendasi" {{ old('kategori') === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Klausul Standar</label>
                            <input type="text" name="klausul_standar"
                                class="form-control form-control-lg @error('klausul_standar') is-invalid @enderror"
                                value="{{ old('klausul_standar', $checklist->indikator->standar->kode ?? '') }}"
                                placeholder="Contoh: 7.1.1">
                            @error('klausul_standar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Uraian Temuan <span class="text-danger">*</span></span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 btn-voice" data-target="uraian_temuan" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill me-1"></i>Suara
                                </button>
                            </label>
                            <textarea name="uraian_temuan" rows="4" class="form-control form-control-lg @error('uraian_temuan') is-invalid @enderror"
                                placeholder="Jelaskan temuan secara detail..." required>{{ old('uraian_temuan', $checklist->catatan ?? '') }}</textarea>
                            @error('uraian_temuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Bukti Objektif (Keterangan)</span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 btn-voice" data-target="bukti_objektif" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill me-1"></i>Suara
                                </button>
                            </label>
                            <textarea name="bukti_objektif" rows="3" class="form-control form-control-lg"
                                placeholder="Bukti-bukti yang mendukung temuan...">{{ old('bukti_objektif', $checklist->bukti_objektif ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lampiran Bukti Foto/Dokumen</label>
                            <div class="upload-zone p-3 border rounded-3 bg-light text-center">
                                <input type="file" name="file_bukti" class="form-control" id="file_bukti" 
                                    accept="image/*,application/pdf" capture="environment">
                                <div class="form-text mt-2">
                                    <i class="bi bi-camera me-1"></i> Klik untuk ambil foto atau pilih file (Max: 5MB)
                                </div>
                            </div>
                            @error('file_bukti') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Batas Tindak Lanjut</label>
                            <input type="date" name="batas_tindak_lanjut"
                                class="form-control form-control-lg @error('batas_tindak_lanjut') is-invalid @enderror"
                                value="{{ old('batas_tindak_lanjut') }}">
                            @error('batas_tindak_lanjut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Tanggal batas waktu penyelesaian temuan</div>
                        </div>
                        <div class="col-12 d-grid d-md-flex gap-2 justify-content-md-end pt-3">
                            <a href="{{ route('audit.show', $audit) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Temuan
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