@extends('layouts.app')

@section('title', 'Isi Kuesioner: ' . $kuesioner->judul)

@section('page-title', 'Form Kuesioner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4 bg-white border-0 shadow-sm mb-4" data-aos="fade-down">
            <div class="badge bg-primary-subtle text-primary border-0 px-3 rounded-pill mb-2">Periode {{ $kuesioner->periode->nama }}</div>
            <h4 class="fw-800 text-dark mb-2">{{ $kuesioner->full_judul }}</h4>
            <p class="text-muted mb-0">{{ $kuesioner->deskripsi }}</p>
        </div>

        <form action="{{ route('user-kuesioner.submit', $kuesioner) }}" method="POST">
            @csrf
            
            <div class="d-flex flex-column gap-4">
                @foreach($kuesioner->pertanyaans as $p)
                <div class="premium-card p-5 bg-white border-0 shadow-sm" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <h6 class="fw-bold text-dark mb-4 lh-base">
                        <span class="text-primary me-2">{{ $loop->iteration }}.</span> {{ $p->pertanyaan }}
                    </h6>
                    
                    @if($p->tipe == 'likert')
                    <div class="likert-container">
                        <div class="row g-2">
                            @php
                                $options = [
                                    5 => ['Sangat Puas / Setuju', 'success'],
                                    4 => ['Puas / Setuju', 'primary'],
                                    3 => ['Cukup ', 'info'],
                                    2 => ['Tidak Puas / Kurang Setuju', 'warning'],
                                    1 => ['Sangat Tidak Puas', 'danger']
                                ];
                            @endphp
                            @foreach($options as $val => $opt)
                            <div class="col-md">
                                <input type="radio" class="btn-check" name="jawaban[{{ $p->id }}]" id="q{{ $p->id }}v{{ $val }}" value="{{ $val }}" required>
                                <label class="btn btn-outline-{{ $opt[1] }} w-100 py-3 rounded-4" for="q{{ $p->id }}v{{ $val }}">
                                    <div class="fw-800 fs-5 mb-1">{{ $val }}</div>
                                    <div class="small fw-bold lh-1">{{ $opt[0] }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <textarea name="jawaban[{{ $p->id }}]" class="form-control rounded-4 p-3" rows="3" required placeholder="Tuliskan jawaban atau masukan Anda di sini..."></textarea>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-5 mb-100 text-center">
                <div class="alert alert-warning border-0 shadow-sm mb-4 rounded-4">
                    <i class="bi bi-info-circle me-2"></i>Pastikan seluruh pertanyaan telah dijawab sebelum mengirimkan formulir ini.
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-100 py-3 rounded-pill fw-800 shadow-lg">
                    <i class="bi bi-send-fill me-2"></i>Kirim Jawaban Anda
                </button>
                <div class="mt-3">
                    <a href="{{ route('user-kuesioner.index') }}" class="text-muted text-decoration-none small">Batal dan Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-check:checked + .btn-outline-success { background-color: #dcfce7; color: #166534; border-color: #166534; }
    .btn-check:checked + .btn-outline-primary { background-color: #eff6ff; color: #1e40af; border-color: #1e40af; }
    .btn-check:checked + .btn-outline-warning { background-color: #fef9c3; color: #854d0e; border-color: #854d0e; }
    .btn-check:checked + .btn-outline-danger { background-color: #fee2e2; color: #991b1b; border-color: #991b1b; }
    
    .px-100 { padding-left: 6rem; padding-right: 6rem; }
    .mb-100 { margin-bottom: 100px; }
</style>
@endpush
