@extends('layouts.app')

@section('title', 'Manajemen Periode')
@section('page-title', 'Manajemen Periode')
@section('page-subtitle', 'Kelola periode/semester untuk data SPMI')

@section('page-actions')
    <a href="{{ route('periode.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Periode
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Periode</li>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Periode</th>
                        <th>Tahun</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $periode)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $periode->nama }}</td>
                        <td>{{ $periode->tahun }}</td>
                        <td>
                            <span class="badge {{ $periode->semester === 'ganjil' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($periode->semester) }}
                            </span>
                        </td>
                        <td>{{ $periode->tanggal_mulai->translatedFormat('d F Y') }}</td>
                        <td>{{ $periode->tanggal_selesai->translatedFormat('d F Y') }}</td>
                        <td class="text-center">
                            @if($periode->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                @if(!$periode->is_aktif)
                                <form action="{{ route('periode.activate', $periode) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            title="Aktifkan periode ini">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('periode.edit', $periode) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('periode.destroy', $periode) }}" method="POST"
                                      onsubmit="return confirm('Hapus periode ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            title="Hapus" {{ $periode->is_aktif ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
                            Belum ada data periode
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($periodes->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $periodes->firstItem() }}-{{ $periodes->lastItem() }} dari {{ $periodes->total() }} data
        </div>
        {{ $periodes->links() }}
    </div>
    @endif
</div>
@endsection
