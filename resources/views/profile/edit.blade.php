@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Ubah data pribadi Anda')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil Saya</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-pencil me-2 text-primary"></i>
                <h6 class="mb-0">Edit Data Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" name="unit_kerja"
                                class="form-control @error('unit_kerja') is-invalid @enderror"
                                value="{{ old('unit_kerja', $user->unit_kerja) }}" placeholder="Fakultas/Prodi/Unit">
                            @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                value="{{ old('jabatan', $user->jabatan) }}" placeholder="Kepala, Staf, dll">
                            @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Profil</label>
                            @if($user->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $user->foto) }}" 
                                         class="rounded-circle" width="60" height="60" style="object-fit:cover">
                                </div>
                            @endif
                            <input type="file" name="foto"
                                class="form-control @error('foto') is-invalid @enderror"
                                accept="image/jpeg,image/png">
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPG/PNG, max 2MB. Kosongkan jika tidak ingin mengubah.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-top mt-4">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="col-lg-4">
        <div class="card card-custom">
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
                        <th>Status</th>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Terdaftar</th>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card card-custom mt-3">
            <div class="card-header-custom">
                <i class="bi bi-key me-2 text-primary"></i>
                <h6 class="mb-0">Keamanan</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Ubah password akun Anda untuk menjaga keamanan.</p>
                <a href="{{ route('profile.settings') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-gear me-1"></i>Pengaturan Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection