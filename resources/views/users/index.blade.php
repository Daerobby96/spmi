@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola pengguna dan hak akses sistem')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Statistik --}}
    <div class="col-md-4">
        <div class="card card-custom bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total User</h6>
                        <h2 class="mb-0">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">User Aktif</h6>
                        <h2 class="mb-0">{{ $stats['aktif'] }}</h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-person-check"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">User Nonaktif</h6>
                        <h2 class="mb-0">{{ $stats['nonaktif'] }}</h2>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-person-x"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Daftar User --}}
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-people me-2"></i>Daftar User</h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
                    </button>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Filter --}}
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari nama, email, NIP..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="role_id" class="form-select">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>

                {{-- Tabel User --}}
                <div class="table-responsive">
                    <table class="table table-hover table-custom">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Unit Kerja</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" 
                                                 class="rounded-circle" width="36" height="36" 
                                                 style="object-fit:cover">
                                        @else
                                            <div class="avatar-circle bg-primary text-white">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->jabatan }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><code>{{ $user->nip ?? '-' }}</code></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role->name === 'super_admin' ? 'danger' : ($user->role->name === 'auditor' ? 'primary' : ($user->role->name === 'auditee' ? 'success' : 'secondary')) }}">
                                        {{ $user->role->display_name }}
                                    </span>
                                </td>
                                <td>{{ $user->unit_kerja ?? '-' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="bi bi-{{ $user->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada data user
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} data
                    </div>
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-excel me-2"></i>Import User via Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Pastikan file Excel memiliki heading: <strong>nama, email, role, nip, unit_kerja, jabatan</strong>.
                        <br><small>Role: auditor, auditee, pimpinan, staf_dokumen</small>
                    </div>
                    <div class='mb-3 text-center'>
                        <label class='form-label fw-bold d-block mb-2'>Format File Excel</label>
                        <a href="{{ route('users.template') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download Template User
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File (.xlsx / .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text mt-2 text-muted">
                            Hanya mendukung file format .xlsx, .xls, atau .csv.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}
</style>
@endpush
@endsection