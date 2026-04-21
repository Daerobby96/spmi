@extends('layouts.public')

@section('title', 'E-Repositori Dokumen Mutu — SPMI Digital')

@section('content')
<!-- Header Premium -->
<section class="py-100 bg-dark text-white position-relative overflow-hidden">
    <div class="hero-backdrop opacity-50"></div>
    <div class="container position-relative z-2 pt-5">
        <div class="row">
            <div class="col-lg-7" data-aos="fade-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Repositori</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-800 mb-3">E-Repositori <span class="text-gradient-cyan">Mutu</span></h1>
                <p class="text-white-50 lead mb-0">Akses transparansi seluruh dokumen standar, manual, dan prosedur operasional mutu institusi dalam satu platform terintegrasi.</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-80 mt-n5 position-relative z-3">
    <div class="row g-4">
        <!-- Sidebar Filter Premium -->
        <div class="col-lg-3">
            <div class="premium-card p-4 bg-white sticky-top shadow-soft border-0" style="top: 100px;">
                <form method="GET">
                    <div class="mb-4">
                        <label class="form-label fw-800 small text-uppercase tracking-widest text-muted mb-3">Pencarian</label>
                        <div class="input-group search-input-group shadow-sm rounded-4 overflow-hidden">
                            <span class="input-group-text bg-white border-0 ps-3">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 py-3 shadow-none" 
                                placeholder="Judul / Kode..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-glow w-100 mb-3 py-3">Cari Dokumen</button>
                    
                    @if(request()->anyFilled(['search']))
                        <a href="{{ route('home.documents') }}" class="btn btn-light-soft w-100 py-3 text-dark fw-700 text-decoration-none rounded-4">
                            Reset Filter
                        </a>
                    @endif
                </form>
                
                <div class="mt-5 pt-4 border-top border-light">
                    <h6 class="fw-800 small text-uppercase tracking-widest text-muted mb-4 text-center">Bantuan Akses</h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <span class="small text-muted">Akses terbatas untuk publik</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <span class="small text-muted">Hanya dokumen "Approved"</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Document Grid) -->
        <div class="col-lg-9">
            <div class="row g-4" id="documentGrid">
                @forelse($documents as $doc)
                <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 100 }}">
                    <div class="premium-card h-100 bg-white p-4 d-flex flex-column hover-lift">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="doc-icon-wrapper bg-indigo-soft text-primary">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div class="badge-glass-primary">v{{ $doc->versi }}</div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-800 text-dark mb-2 text-line-clamp-2" style="min-height: 2.8rem;">{{ $doc->judul }}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-primary border-0 rounded-pill px-3">{{ $doc->kode_dokumen }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-auto pt-4 border-top border-light d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-xs bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-tag text-muted small"></i>
                                </div>
                                <span class="small fw-700 text-muted">{{ $doc->kategori->nama }}</span>
                            </div>
                            <a href="{{ route('dokumen.show', $doc) }}" class="btn-circular-arrow" title="Lihat Detail">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 py-100 text-center">
                    <div class="p-5 bg-white rounded-5 shadow-soft border-dashed border-2 d-inline-block">
                        <i class="bi bi-search display-1 text-muted opacity-10"></i>
                        <h4 class="mt-4 text-dark fw-800">Dokumen Tidak Ditemukan</h4>
                        <p class="text-muted">Gunakan kata kunci atau kode dokumen yang lain.</p>
                        <a href="{{ route('home.documents') }}" class="btn btn-primary rounded-pill px-4 mt-3">Tampilkan Semua</a>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-80 d-flex justify-content-center">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Newsletter/Disclaimer Section -->
<section class="py-80 bg-light-soft mt-5">
    <div class="container text-center" data-aos="zoom-in">
        <div class="p-4 p-md-5 bg-white rounded-5 shadow-soft mx-auto" style="max-width: 800px;">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                <i class="bi bi-lock text-primary fs-3"></i>
                <h4 class="fw-800 mb-0">Akses Terkelola</h4>
            </div>
            <p class="text-muted">Seluruh dokumen yang dipublikasikan adalah dokumen resmi institusi. Penyalahgunaan atau penggandaan tanpa izin tertulis dari Kantor Penjaminan Mutu dapat dikenakan sanksi sesuai regulasi yang berlaku.</p>
            <div class="mt-4">
                <span class="badge bg-primary px-4 py-2 rounded-pill fw-bold">Budaya Mutu, Tanggung Jawab Kita</span>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .py-80 { padding: 80px 0; }
    .mt-80 { margin-top: 80px; }
    
    .bg-dark { background-color: #0f172a !important; }
    
    .search-input-group {
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .search-input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.1) !important;
    }
    
    .doc-icon-wrapper {
        width: 54px; height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge-glass-primary {
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
        font-weight: 800;
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 100px;
    }
    
    .btn-circular-arrow {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-circular-arrow:hover {
        background: var(--primary-dark);
        transform: rotate(-45deg);
        color: white;
    }
    
    .text-line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    
    .avatar-xs { width: 28px; height: 28px; }
    
    .border-dashed { border-style: dashed !important; }
    
    @media (max-width: 991px) {
        .py-80 { padding: 50px 0; }
        .sticky-top { position: relative !important; top: 0 !important; margin-bottom: 2rem; }
    }
</style>
@endpush
