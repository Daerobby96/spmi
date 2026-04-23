@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Informasi akun dan data pribadi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profil Saya</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card card-custom text-center">
            <div class="card-body py-4">
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" 
                         class="rounded-circle mb-3" width="120" height="120" 
                         style="object-fit:cover">
                @else
                    <div class="avatar-circle-large bg-primary text-white mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->jabatan ?? '-' }}</p>
                <span class="badge bg-{{ $user->role->name === 'super_admin' ? 'danger' : ($user->role->name === 'auditor' ? 'primary' : ($user->role->name === 'auditee' ? 'success' : 'secondary')) }} mb-2">
                    {{ $user->role->display_name }}
                </span>
                <div class="mt-2">
                    @if($user->is_active)
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                    @else
                        <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pb-4">
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit Profil
                    </a>
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-1"></i>Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header-custom">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Pribadi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless detail-table">
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>NIP</th>
                                <td><code>{{ $user->nip ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td>{{ $user->no_hp ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless detail-table">
                            <tr>
                                <th>Unit Kerja</th>
                                <td>{{ $user->unit_kerja ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $user->jabatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $user->role->display_name }}</td>
                            </tr>
                            <tr>
                                <th>Terdaftar</th>
                                <td>{{ $user->created_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 48px;
}
</style>
@endpush
@endsection