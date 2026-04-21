@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('title', 'Detail Dokumen')

@section('page-title', $dokumen->judul)
@section('page-subtitle', $dokumen->kode_dokumen)

@section('page-actions')
    @auth
    <div class="d-flex gap-2">
        @if($dokumen->file_path)
        <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-success">
            <i class="bi bi-download me-1"></i>Download
        </a>
        @endif
        <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
    @endauth
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
@guest
<section class="py-5 bg-light border-bottom mb-4">
    <div class="container mt-4">
        <h1 class="fw-800 mb-2">{{ $dokumen->judul }}</h1>
        <p class="text-muted lead">{{ $dokumen->kode_dokumen }}</p>
    </div>
</section>
@endguest

<div class="{{ auth()->check() ? '' : 'container pb-5' }}">
<div class="row g-4">

    {{-- ── Detail Utama ── --}}
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                <h6 class="mb-0">Informasi Dokumen</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table mb-0">
                    <tr>
                        <th>Kode Dokumen</th>
                        <td><code class="fs-6">{{ $dokumen->kode_dokumen }}</code></td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td class="fw-semibold">{{ $dokumen->judul }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            <span class="badge bg-{{ $dokumen->kategori->warna ?? 'secondary' }}">
                                {{ $dokumen->kategori->kode }}
                            </span>
                            {{ $dokumen->kategori->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>Standar Mutu</th>
                        <td>
                            @if($dokumen->standar)
                                <span class="badge bg-info text-dark">{{ $dokumen->standar->kode }}</span>
                                {{ $dokumen->standar->nama }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Unit Pemilik</th>
                        <td>{{ $dokumen->unit_pemilik }}</td>
                    </tr>
                    <tr>
                        <th>Versi</th>
                        <td><span class="badge bg-light text-dark border">v{{ $dokumen->versi }}</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $statusMap = ['draft'=>['secondary','Draft'],'review'=>['warning','Review'],
                                    'approved'=>['success','Approved'],'obsolete'=>['dark','Obsolete']];
                                $s = $statusMap[$dokumen->status] ?? ['secondary',$dokumen->status];
                            @endphp
                            <span class="badge bg-{{ $s[0] }} px-3">{{ $s[1] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Terbit</th>
                        <td>{{ $dokumen->tanggal_terbit->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Kadaluarsa</th>
                        <td>
                            @if($dokumen->tanggal_kadaluarsa)
                                <span class="{{ $dokumen->tanggal_kadaluarsa <= now() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $dokumen->tanggal_kadaluarsa->translatedFormat('d F Y') }}
                                    @if($dokumen->tanggal_kadaluarsa <= now())
                                        <span class="badge bg-danger ms-1">Kadaluarsa</span>
                                    @elseif($dokumen->tanggal_kadaluarsa <= now()->addDays(30))
                                        <span class="badge bg-warning text-dark ms-1">Segera</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Tidak ada masa berlaku</span>
                            @endif
                        </td>
                    </tr>
                    @if($dokumen->keterangan)
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $dokumen->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ── Sidebar Info ── --}}
    <div class="col-lg-4">

        {{-- File --}}
        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <i class="bi bi-paperclip me-2 text-primary"></i>
                <h6 class="mb-0">File Dokumen</h6>
            </div>
            <div class="card-body text-center py-4">
                @if($dokumen->file_path)
                    <div class="file-big-icon mb-3">
                        @switch($dokumen->file_type)
                            @case('pdf')   <i class="bi bi-file-earmark-pdf text-danger"></i>  @break
                            @case('docx')
                            @case('doc')   <i class="bi bi-file-earmark-word text-primary"></i> @break
                            @case('xlsx')
                            @case('xls')   <i class="bi bi-file-earmark-excel text-success"></i>@break
                            @default       <i class="bi bi-file-earmark text-secondary"></i>
                        @endswitch
                    </div>
                    <p class="fw-semibold mb-1">{{ strtoupper($dokumen->file_type) }} File</p>
                    <p class="text-muted small mb-3">{{ $dokumen->file_size_formatted }}</p>
                    <a href="{{ route('dokumen.download', $dokumen) }}"
                       class="btn btn-success w-100">
                        <i class="bi bi-download me-1"></i>Download File
                    </a>
                    <p class="text-muted small mt-2 mb-0">
                        Diunduh {{ $dokumen->download_count }}x
                    </p>
                @else
                    <i class="bi bi-file-earmark-x text-muted" style="font-size:2.5rem"></i>
                    <p class="text-muted mt-2 mb-0 small">Belum ada file terupload</p>
                    <a href="{{ route('dokumen.edit', $dokumen) }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bi bi-upload me-1"></i>Upload File
                    </a>
                @endif
            </div>
        </div>

        {{-- Card QR Code Premium --}}
        <div class="card card-custom mb-4 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);">
            <div class="card-header-custom bg-transparent border-0 pb-0">
                <i class="bi bi-patch-check-fill me-2 text-primary"></i>
                <h6 class="mb-0 fw-bold">Verifikasi Digital</h6>
            </div>
            <div class="card-body text-center py-4 position-relative">
                <div class="qr-container mb-3">
                    <div class="qr-code-wrapper p-3 bg-white rounded-4 shadow-sm d-inline-block border">
                        @php
                            $qr = QrCode::size(160)
                                ->format('svg')
                                ->style('round')
                                ->eye('circle')
                                ->color(30, 27, 75); // Midnight Blue / Indigo

                            if(isset($appSettings['logo']) && $appSettings['logo']) {
                                // Note: SimpleQRCode merge requires physical path or absolute URL
                                // Using style and color is safer if merging complicates things on some servers
                                // But let's try a clean stylized QR first
                            }
                        @endphp
                        {!! $qr->generate(route('dokumen.show', $dokumen)) !!}
                    </div>
                </div>
                
                <div class="verification-badge mb-3">
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                        <i class="bi bi-shield-lock-fill me-1"></i> Official Document
                    </span>
                </div>
                
                <p class="text-muted small px-3 mb-0">Pindai segel ini untuk memverifikasi keaslian dokumen secara <i>real-time</i>.</p>
                
                <div class="d-grid mt-4 px-3">
                    <button onclick="window.print()" class="btn btn-outline-primary border-2 fw-bold">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Label Validasi
                    </button>
                </div>
            </div>
            {{-- Hiasan aksen gradien di pojok --}}
            <div style="position: absolute; top: -10px; right: -10px; width: 50px; height: 50px; background: rgba(99, 102, 241, 0.05); border-radius: 50%;"></div>
        </div>

        {{-- Meta Info --}}
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clock-history me-2 text-primary"></i>
                <h6 class="mb-0">Riwayat</h6>
            </div>
            <div class="card-body">
                <div class="meta-row">
                    <span class="text-muted small">Dibuat oleh</span>
                    <span class="fw-medium small">{{ $dokumen->pembuat->name }}</span>
                </div>
                <div class="meta-row">
                    <span class="text-muted small">Tanggal dibuat</span>
                    <span class="small">{{ $dokumen->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div class="meta-row">
                    <span class="text-muted small">Terakhir diperbarui</span>
                    <span class="small">{{ $dokumen->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection