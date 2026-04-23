@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Ubah data pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-pencil me-2 text-primary"></i>
                <h6 class="mb-0">Edit User: {{ $user->name }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        {{-- Data Akun --}}
                        <div class="col-12">
                            <h6 class="text-muted mb-3"><i class="bi bi-key me-2"></i>Data Akun</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="form-control">
                        </div>

                        {{-- Data Pribadi --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
                        </div>
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
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
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
                <h6 class="mb-0">Info User</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
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
                        <td>{{ $user->created_at->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Update Terakhir</th>
                        <td>{{ $user->updated_at->translatedFormat('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($user->id !== auth()->id())
        <div class="card card-custom border-danger mt-3">
            <div class="card-header-custom text-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <h6 class="mb-0">Zona Bahaya</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Hapus user ini secara permanen. Aksi ini tidak dapat dibatalkan.</p>
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i>Hapus User
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection