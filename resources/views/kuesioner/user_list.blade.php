@extends('layouts.app')

@section('title', 'Survei & Kuesioner Mutu')

@section('page-title', 'Survei & Kuesioner')
@section('page-subtitle', 'Partisipasi Anda sangat penting untuk peningkatan mutu institusi.')

@section('content')
<div class="row g-4">
    @forelse($kuesioners as $k)
    <div class="col-lg-4 col-md-6">
        <div class="premium-card h-100 bg-white p-4 d-flex flex-column hover-lift">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="doc-icon-wrapper bg-blue-50 text-blue-600">
                    <i class="bi bi-clipboard2-data fs-3"></i>
                </div>
                @if($k->jawabans_count > 0 || $k->is_filled_via_cookie)
                    <span class="badge bg-success-subtle text-success border-0 px-3 rounded-pill py-2">
                        <i class="bi bi-check-lg me-1"></i>Sudah Diisi
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning border-0 px-3 rounded-pill py-2">
                        <i class="bi bi-clock me-1"></i>Belum Diisi
                    </span>
                @endif
            </div>
            
            <h5 class="fw-800 text-dark mb-2">{{ $k->full_judul }}</h5>
            <p class="text-muted small mb-4 flex-grow-1">{{ $k->deskripsi }}</p>
            
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="badge bg-light text-muted border-0 small px-3">Periode {{ $k->periode->nama }}</div>
            </div>

            <div class="mt-auto pt-4 border-top">
                @if($k->jawabans_count > 0 || $k->is_filled_via_cookie)
                    <button class="btn btn-light-soft w-100 py-3 disabled opacity-75">
                        <i class="bi bi-check2-all me-1"></i>Terima Kasih atas Partisipasi Anda
                    </button>
                @else
                    <a href="{{ route('user-kuesioner.fill', $k) }}" class="btn btn-primary w-100 py-3 fw-bold">
                        Mulai Pengisian <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-100 text-center">
        <i class="bi bi-clipboard-check display-1 text-muted opacity-10"></i>
        <h4 class="mt-4 text-dark fw-bold">Tidak Ada Survei Aktif</h4>
        <p class="text-muted">Semua kewajiban survei Anda telah terpenuhi atau belum ada survei baru.</p>
    </div>
    @endforelse
</div>
@endsection
