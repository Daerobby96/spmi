@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Akun')
@section('page-subtitle', 'Kelola keamanan akun Anda')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil Saya</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-key me-2 text-primary"></i>
                <h6 class="mb-0">Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="form-control" required>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-shield-check me-2 text-primary"></i>
                <h6 class="mb-0">Tips Keamanan</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle text-success mt-1"></i>
                        <span>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle text-success mt-1"></i>
                        <span>Hindari menggunakan informasi pribadi seperti tanggal lahir atau nama</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle text-success mt-1"></i>
                        <span>Jangan gunakan password yang sama dengan akun lain</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle text-success mt-1"></i>
                        <span>Ubah password secara berkala untuk menjaga keamanan</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle text-success mt-1"></i>
                        <span>Jangan bagikan password kepada siapapun</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-custom mt-3">
            <div class="card-header-custom">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                <h6 class="mb-0">Info Akun</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ $user->role->display_name }}</td>
                    </tr>
                    <tr>
                        <th>Login Terakhir</th>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection