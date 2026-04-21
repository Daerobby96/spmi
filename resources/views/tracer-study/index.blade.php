@extends('layouts.app')

@section('title', 'Tracer Study (Alumni)')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h5 class="fw-800 text-dark mb-1">Tracer Study (Alumni)</h5>
            <p class="text-muted small mb-0">Manajemen data lulusan dan penyerapan kerja sesuai standar PDDIKTI.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
            <a href="{{ route('tracer-study.template') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-download me-2"></i> Download Template
            </a>
            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-upload me-2"></i> Import PDDIKTI Template
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-primary-subtle text-primary rounded-4">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-0">Total Alumni</h6>
                        <h4 class="fw-800 mb-0">{{ $stats['total'] }}</h4>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Responden</span>
                        <span>100%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-success-subtle text-success rounded-4">
                        <i class="bi bi-briefcase fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-0">Telah Bekerja</h6>
                        <h4 class="fw-800 mb-0">{{ $stats['bekerja_persen'] }}%</h4>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>{{ $stats['bekerja'] }} Orang</span>
                        <span>Target: 80%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['bekerja_persen'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-warning-subtle text-warning rounded-4">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-0">Rerata Tunggu</h6>
                        <h4 class="fw-800 mb-0">{{ number_format($stats['avg_tunggu'], 1) }} <small class="fs-6 fw-normal">Bulan</small></h4>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Standar: < 6 Bln</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $waitPercent = max(0, min(100, (6 / max(1, $stats['avg_tunggu'])) * 100)); @endphp
                        <div class="progress-bar bg-warning" style="width: {{ $stats['avg_tunggu'] > 0 ? 100 - $waitPercent : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-info-subtle text-info rounded-4">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small mb-0">Rerata Gaji</h6>
                        <h4 class="fw-800 mb-0">{{ number_format($stats['avg_gaji'] / 1000000, 1) }} <small class="fs-6 fw-normal">jt</small></h4>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>UMP: Rp 2.8jt</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $salaryPercent = max(0, min(100, ($stats['avg_gaji'] / 5000000) * 100)); @endphp
                        <div class="progress-bar bg-info" style="width: {{ $salaryPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Analysis & Distribution -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center gap-2">
                    <div class="p-2 bg-purple-subtle text-purple rounded-3">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h6 class="fw-bold mb-0">AI Smart Insight</h6>
                </div>
                <div class="card-body bg-light-soft">
                    <div class="ai-content text-dark">
                        {!! $aiInsight !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 bg-danger-subtle text-danger rounded-3">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Integrasi PPEPP (Evaluasi)</h6>
                    </div>
                    <form action="{{ route('tracer-study.sync-ppepp') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="bi bi-sync me-1"></i> Sinkronkan
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    @if(count($ppeppData) > 0)
                        <div class="list-group list-group-flush small">
                            @foreach($ppeppData as $item)
                                <div class="list-group-item px-0 border-0 mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">{{ $item['nama'] }}</span>
                                        <span class="badge {{ $item['status'] == 'Tercapai' ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                            {{ $item['status'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-dark">
                                            {{ number_format($item['capaian'], 1) }} / {{ number_format($item['target'], 1) }} {{ $item['satuan'] }}
                                        </div>
                                        <div class="progress w-50" style="height: 4px;">
                                            @php $perc = min(100, ($item['capaian'] / max(1, $item['target'])) * 100); @endphp
                                            <div class="progress-bar {{ $perc >= 100 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $perc }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-exclamation-triangle d-block mb-2"></i>
                            Indikator Kinerja (TRC) belum didefinisikan.
                            <br>Pastikan Kode IKU 'TRC-01', 'TRC-02', 'TRC-03' ada di modul Standar.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution & Job Status -->
    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Sebaran Status Lulusan</h6>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <canvas id="statusChart" style="max-height: 150px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold mb-0">Data Lulusan</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">NIM / Nama</th>
                        <th>Prodi / Lulus</th>
                        <th>Status Kerja</th>
                        <th>Perusahaan / Jabatan</th>
                        <th>Gaji / Tunggu</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracerData as $data)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $data->nama }}</div>
                            <div class="text-muted small">{{ $data->nim }}</div>
                        </td>
                        <td>
                            <div>{{ $data->prodi ?? '-' }}</div>
                            <span class="badge bg-light text-dark fw-normal border">TA {{ $data->tahun_lulus }}</span>
                        </td>
                        <td>
                            @php
                                $sKerja = strtolower($data->status_kerja ?? '');
                            @endphp
                            @if(str_starts_with($sKerja, 'bekerja') || $sKerja == '1')
                                <span class="badge bg-success-subtle text-success">Bekerja</span>
                            @elseif(str_contains($sKerja, 'wirausaha') || $sKerja == '2')
                                <span class="badge bg-info-subtle text-info">Wirausaha</span>
                            @elseif(str_contains($sKerja, 'melanjutkan') || $sKerja == '3')
                                <span class="badge bg-primary-subtle text-primary">Lanjut Studi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">{{ $data->status_kerja ?? '-' }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark fw-500">{{ $data->perusahaan ?? '-' }}</div>
                            <div class="small text-muted">{{ $data->jabatan }}</div>
                        </td>
                        <td>
                            <div class="text-primary fw-bold">Rp {{ number_format($data->gaji) }}</div>
                            <div class="small text-muted">{{ $data->waktu_tunggu_bulan }} Bulan</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" class="btn btn-link text-primary p-0" data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}" title="Lihat Detail Lengkap">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <form action="{{ route('tracer-study.destroy', $data) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Hapus Data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-800">Detail Tracer Study Alumni</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body py-4">
                                            <div class="row g-4">
                                                <!-- Identitas Alumni -->
                                                <div class="col-md-12">
                                                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Identitas Alumni</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Nama Lengkap</div>
                                                            <div class="fw-500">{{ $data->nama }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">NIM</div>
                                                            <div class="fw-500">{{ $data->nim }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Program Studi</div>
                                                            <div class="fw-500">{{ $data->prodi ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Tahun Lulus</div>
                                                            <div class="fw-500">{{ $data->tahun_lulus ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Telepon & Email</div>
                                                            <div class="fw-500">{{ $data->telepon ?? '-' }} <br> <span class="text-muted small">{{ $data->email ?? '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Pekerjaan -->
                                                <div class="col-md-12 mt-4">
                                                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Informasi Pekerjaan (F5)</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <div class="small text-muted mb-1">Status Pekerjaan</div>
                                                            <div class="fw-bold text-dark">{{ $data->status_kerja ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Instansi / Perusahaan</div>
                                                            <div class="fw-500">{{ $data->perusahaan ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Jabatan</div>
                                                            <div class="fw-500">{{ $data->jabatan ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Tingkat Instansi</div>
                                                            <div class="fw-500">{{ $data->tingkat_instansi ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Rata-rata Pendapatan / Gaji</div>
                                                            <div class="fw-500 text-success">Rp {{ number_format($data->gaji ?? 0, 0, ',', '.') }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="small text-muted mb-1">Waktu Tunggu (Bulan)</div>
                                                            <div class="fw-500">{{ $data->waktu_tunggu_bulan ?? 0 }} Bulan</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Keselarasan -->
                                                <div class="col-md-12 mt-4">
                                                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Keselarasan Evaluasi Lulusan</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Keselarasan Horisontal (F14)</div>
                                                            <div class="fw-500">{{ $data->keselarasan_horisontal ?? 'Sangat Erat / Erat / Cukup Erat / Kurang Erat / Tidak Sama Sekali' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Keselarasan Vertikal (F15)</div>
                                                            <div class="fw-500">{{ $data->keselarasan_vertikal ?? 'Setingkat Lebih Tinggi / Tingkat Sama / Setingkat Lebih Rendah / Tidak Perlu Pendidikan Tinggi' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data tracer study. Silakan import file Excel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tracerData->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $tracerData->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tracer-study.import') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-800">Import Data Alumni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="p-4 bg-light rounded-4 border-dashed text-center mb-3">
                    <i class="bi bi-file-earmark-spreadsheet fs-1 text-primary mb-3 d-block"></i>
                    <p class="small text-muted mb-3">Pilih file Excel Template PDDIKTI (.xlsx, .xls)</p>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="alert alert-info border-0 rounded-4 small">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Sistem akan otomatis mendeteksi kolom seperti NIM, Nama, Gaji, dan Status Kerja dari header file Anda.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Mulai Import</button>
            </div>
        </form>
    </div>
</div>

<style>
    .border-dashed { border: 2px dashed #dee2e6; }
    .fw-800 { font-weight: 800; }
    .fw-500 { font-weight: 500; }
    .bg-purple-subtle { background-color: rgba(124, 58, 237, 0.1); }
    .text-purple { color: #7c3aed; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statusChart').getContext('2d');
        const data = @json($statusDist);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: [
                        '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#6b7280'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { size: 10 } } }
                }
            }
        });
    });
</script>
@endpush
@endsection
