@extends('layouts.app')

@section('title', 'Buat Kuesioner Baru')

@section('page-title', 'Buat Kuesioner')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kuesioner.index') }}">Kuesioner</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('kuesioner.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Kuesioner</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Survei Kepuasan {periode}" required>
                        <small class="text-muted">Gunakan <code>{periode}</code> agar nama periode muncul otomatis.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Berikan penjelasan singkat mengenai kuesioner ini..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Periode</label>
                            <select name="periode_id" class="form-select" required>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ $p->is_aktif ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Target Responden (Internal)</label>
                            <select name="target_role" class="form-select">
                                <option value="all">Semua Pemegang Akun</option>
                                <option value="auditor">Khusus Auditor</option>
                                <option value="auditee">Khusus Auditee / Unit Kerja</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Aksesibilitas</label>
                            <select name="is_public" class="form-select text-primary fw-bold">
                                <option value="1">Publik (Bisa Tanpa Login)</option>
                                <option value="0">Private (Hanya Internal/Login)</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="status" value="draft">

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('kuesioner.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-5">Simpan & Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="alert alert-info border-0 shadow-sm p-4 rounded-4">
            <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
            <p class="small mb-0">Setelah menyimpan data utama kuesioner, Anda akan diarahkan ke halaman pengaturan pertanyaan untuk menambahkan soal-soal survei.</p>
        </div>
    </div>
</div>
@endsection
