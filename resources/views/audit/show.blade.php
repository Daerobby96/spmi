@extends('layouts.app')

@section('title', 'Detail Audit')
@section('title', 'Detail Audit')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.css"/>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.js"></script>
@endpush

@section('page-title', 'Detail Audit')
@section('page-subtitle', $audit->nama_audit)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">{{ $audit->kode_audit }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Tabs Navigation --}}
    <div class="col-12">
        <ul class="nav nav-pills gap-2 bg-white p-2 rounded shadow-sm border" id="auditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="bi bi-info-circle me-1"></i>Informasi & Tim
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold d-flex align-items-center" id="checklist-tab" data-bs-toggle="tab" data-bs-target="#checklist" type="button" role="tab">
                    <i class="bi bi-list-check me-1"></i>Checklist Audit
                    @if($statsChecklist['belum'] > 0)
                        <span class="badge bg-danger ms-2 px-1" style="font-size: 0.6rem;">{{ $statsChecklist['belum'] }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="temuan-tab" data-bs-toggle="tab" data-bs-target="#temuan" type="button" role="tab">
                    <i class="bi bi-exclamation-triangle me-1"></i>Daftar Temuan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold d-flex align-items-center" id="ai-insight-tab" data-bs-toggle="tab" data-bs-target="#ai-insight" type="button" role="tab">
                    <i class="bi bi-robot me-1"></i>Insight AI
                    @if(!$audit->ai_summary)
                        <span class="ms-1" style="width: 8px; height: 8px; background-color: #0d6efd; border-radius: 50%;"></span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    <div class="col-12">
        <div class="tab-content" id="auditTabsContent">
            {{-- Tab 1: Informasi & Tim --}}
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card card-custom h-100">
                            <div class="card-header-custom"><h6 class="mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Audit</h6></div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table mb-0">
                                    <tr><th>Kode</th><td><code class="text-primary">{{ $audit->kode_audit }}</code></td></tr>
                                    <tr><th>Nama</th><td class="fw-semibold">{{ $audit->nama_audit }}</td></tr>
                                    <tr><th>Unit Diaudit</th><td><span class="badge bg-indigo-subtle text-indigo">{{ $audit->unit_yang_diaudit }}</span></td></tr>
                                    <tr><th>Ketua Auditor</th><td>{{ $audit->ketuaAuditor->name ?? '-' }}</td></tr>
                                    <tr><th>Tgl Audit</th><td>{{ $audit->tanggal_audit->translatedFormat('d F Y') }}</td></tr>
                                    <tr><th>Opening</th><td><small class="text-muted">{{ $audit->opening_meeting ? $audit->opening_meeting->format('d/m/y H:i') : 'Belum Atur' }}</small></td></tr>
                                    <tr><th>Closing</th><td><small class="text-muted">{{ $audit->closing_meeting ? $audit->closing_meeting->format('d/m/y H:i') : 'Belum Atur' }}</small></td></tr>
                                    <tr><th>Status</th><td>{!! $audit->status_badge !!}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-custom h-100">
                            <div class="card-header-custom"><h6 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Tim Auditor</h6></div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach($audit->auditors as $auditor)
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <div class="user-avatar-sm">{{ strtoupper(substr($auditor->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="fw-semibold">{{ $auditor->name }}</div>
                                            <div class="small text-muted">{{ $auditor->pivot->peran === 'ketua' ? 'Ketua Auditor' : 'Anggota' }}</div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-custom h-100">
                            <div class="card-header-custom"><h6 class="mb-0"><i class="bi bi-gear me-2 text-primary"></i>Aksi Cepat</h6></div>
                            <div class="card-body d-grid gap-2">
                                @if($audit->status === 'draft')
                                <form action="{{ route('audit.update', $audit) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="aktif">
                                    <input type="hidden" name="nama_audit" value="{{ $audit->nama_audit }}">
                                    <input type="hidden" name="periode_id" value="{{ $audit->periode_id }}">
                                    <input type="hidden" name="unit_yang_diaudit" value="{{ $audit->unit_yang_diaudit }}">
                                    <input type="hidden" name="ketua_auditor_id" value="{{ $audit->ketua_auditor_id }}">
                                    <input type="hidden" name="tanggal_audit" value="{{ $audit->tanggal_audit->format('Y-m-d') }}">
                                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-play-circle me-1"></i>Mulai Audit</button>
                                </form>
                                @endif
                                <a href="{{ route('laporan.export.audit.individual', $audit) }}" class="btn btn-danger" target="_blank"><i class="bi bi-file-pdf me-1"></i>Export Laporan PDF</a>
                                <a href="{{ route('audit.edit', $audit) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit Konfigurasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Checklist Audit --}}
            <div class="tab-pane fade" id="checklist" role="tabpanel">
                <div class="card card-custom">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Checklist Instrumen Audit</h6>
                            <small class="text-muted">Berdasarkan indikator kinerja unit {{ $audit->unit_yang_diaudit }}</small>
                        </div>
                        @if($audit->checklists->isEmpty())
                        <form action="{{ route('audit.generate-checklist', $audit) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-magic me-1"></i>Generate Checklist</button>
                        </form>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Instrumen / Pertanyaan</th>
                                        <th width="120">Standar</th>
                                        <th width="150">Status</th>
                                        <th>Catatan / Bukti Objektif</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($indikators as $ind)
                                    @php 
                                        $item = $audit->checklists->where('indikator_id', $ind->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $ind->nama }}</div>
                                            <small class="text-muted">{{ $ind->kode }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $ind->standar->kode ?? '-' }}</span></td>
                                        <td>
                                            <select class="form-select form-select-sm inline-audit-checklist" 
                                                    data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="status"
                                                    style="width: 140px;">
                                                <option value="belum_diisi" {{ (!$item || $item->status == 'belum_diisi') ? 'selected' : '' }}>Belum Diisi</option>
                                                <option value="sesuai" {{ ($item && $item->status == 'sesuai') ? 'selected' : '' }}>Sesuai (Compliant)</option>
                                                <option value="tidak_sesuai" {{ ($item && $item->status == 'tidak_sesuai') ? 'selected' : '' }}>Tidak Sesuai / KTS</option>
                                                <option value="observasi" {{ ($item && $item->status == 'observasi') ? 'selected' : '' }}>Observasi (OB)</option>
                                                <option value="tidak_terkait" {{ ($item && $item->status == 'tidak_terkait') ? 'selected' : '' }}>Tidak Terkait (N/A)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <textarea class="form-control form-control-sm inline-audit-checklist" 
                                                          data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="catatan"
                                                          rows="1" placeholder="Catatan audit...">{{ $item->catatan ?? '' }}</textarea>
                                            </div>
                                            <input type="text" class="form-control form-control-sm inline-audit-checklist" 
                                                   data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="bukti_objektif"
                                                   placeholder="Bukti objektif/link..." value="{{ $item->bukti_objektif ?? '' }}">
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($item && in_array($item->status, ['tidak_sesuai', 'observasi']))
                                                    @if($item->temuans->isEmpty())
                                                    <a href="{{ route('audit.temuan.create', [$audit, 'checklist_id' => $item->id]) }}" class="btn btn-sm btn-outline-danger" title="Buat Temuan Formal">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                    </a>
                                                    @else
                                                    <span class="badge bg-success py-2"><i class="bi bi-check-circle"></i></span>
                                                    @endif
                                                @endif
                                                <a href="{{ route('indikator-kinerja.show', $ind) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Detail Indikator">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada indikator aktif untuk unit ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Daftar Temuan --}}
            <div class="tab-pane fade" id="temuan" role="tabpanel">
                <div class="card card-custom">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2 text-primary"></i>Ringkasan Temuan Audit</h6>
                        <a href="{{ route('audit.temuan.create', $audit) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Input Temuan (KTS/OB)</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi Temuan</th>
                                        <th>Klausul/Standar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($audit->temuans as $temuan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{!! $temuan->kategori_badge !!}</td>
                                        <td class="fw-semibold">{{ Str::limit($temuan->uraian_temuan, 100) }}</td>
                                        <td><span class="small text-muted">{{ $temuan->klausul_standar ?? '-' }}</span></td>
                                        <td class="text-center">
                                            @if($temuan->status === 'open') <span class="badge bg-danger">Open</span>
                                            @elseif($temuan->status === 'in_progress') <span class="badge bg-warning text-dark">In Progress</span>
                                            @else <span class="badge bg-success">Closed</span> @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('audit.temuan.show', [$audit, $temuan]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada temuan formal yang dicatat.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: AI Insight --}}
            <div class="tab-pane fade" id="ai-insight" role="tabpanel">
                <div class="card card-custom border-primary-subtle">
                    <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-bold"><i class="bi bi-robot me-2"></i>Executive Briefing (Generasi AI)</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnEditAi" onclick="toggleAiEdit()" style="display: {{ $audit->ai_summary ? 'block' : 'none' }}">
                                <i class="bi bi-pencil me-1"></i>Edit Manual
                            </button>
                            <button type="button" class="btn btn-sm btn-success" id="btnSaveAi" onclick="saveAiSummary()" style="display: none">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="generateAuditSummary()">
                                <i class="bi bi-magic me-1"></i>Generate Ringkasan Baru
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <textarea id="aiSummaryEditor" style="display: none;"></textarea>
                        <div id="aiSummaryContent" class="p-4">
                            @if($audit->ai_summary)
                                <div class="ai-report-text">
                                    {!! $audit->ai_summary !!}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-robot display-4 text-muted mb-3 d-block"></i>
                                    <h5>Belum Ada Analisa AI</h5>
                                    <p class="text-muted">Klik "Generate Ringkasan" untuk membuat rangkasan eksekutif otomatis berdasarkan temuan audit.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-light small text-muted text-center pt-2">
                        <i class="bi bi-info-circle me-1"></i>Analisa ini dibuat secara otomatis oleh kecerdasan buatan (Groq LLM). Mohon verifikasi kembali hasilnya.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab Persistence
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = localStorage.getItem('auditActiveTab');
        if (activeTab) {
            const tabEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }

        const tabBtns = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabBtns.forEach(btn => {
            btn.addEventListener('shown.bs.tab', (e) => {
                localStorage.setItem('auditActiveTab', e.target.getAttribute('data-bs-target'));
            });
        });
    });

    let joditAi = null;

    function toggleAiEdit() {
        const contentDiv = document.getElementById('aiSummaryContent');
        const editorWrapper = document.querySelector('.jodit-container') || document.getElementById('aiSummaryEditor');
        const btnEdit = document.getElementById('btnEditAi');
        const btnSave = document.getElementById('btnSaveAi');

        if (contentDiv.style.display !== 'none') {
            // Start Editing
            contentDiv.style.display = 'none';
            btnEdit.innerHTML = '<i class="bi bi-x-lg me-1"></i>Batal';
            btnSave.style.display = 'block';

            if (!joditAi) {
                joditAi = new Jodit('#aiSummaryEditor', {
                    height: 400,
                    toolbarAdaptive: false,
                    buttons: 'bold,italic,underline,ul,ol,eraser,undo,redo',
                });
            }
            
            // Load current content
            const currentHtml = contentDiv.querySelector('.ai-report-text')?.innerHTML || '';
            joditAi.value = currentHtml;
            document.querySelector('.jodit-container').style.display = 'block';
        } else {
            // Cancel Editing
            contentDiv.style.display = 'block';
            document.querySelector('.jodit-container').style.display = 'none';
            btnEdit.innerHTML = '<i class="bi bi-pencil me-1"></i>Edit Manual';
            btnSave.style.display = 'none';
        }
    }

    async function saveAiSummary() {
        if (!joditAi) return;
        
        const btn = document.getElementById('btnSaveAi');
        const html = joditAi.value;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        try {
            const response = await fetch('{{ route("laporan.audit.update-ai-summary", $audit) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ai_summary: html })
            });
            
            const result = await response.json();
            if (result.success) {
                // Update display
                document.getElementById('aiSummaryContent').innerHTML = `<div class="ai-report-text">${html}</div>`;
                toggleAiEdit();
                alert('Analisa AI berhasil diperbarui.');
            }
        } catch (e) {
            console.error(e);
            alert('Gagal menyimpan perubahan.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Perubahan';
        }
    }

    async function generateAuditSummary() {
        const btn = event.currentTarget;
        const container = document.getElementById('aiSummaryContent');
        const auditId = '{{ $audit->id }}';

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menganalisa...';
        
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-grow text-primary mb-3" role="status"></div>
                <h5>Menganalisa {{ $audit->temuans->count() }} Temuan...</h5>
                <p class="text-muted">Proses ini membutuhkan waktu beberapa detik.</p>
            </div>
        `;

        try {
            const response = await fetch('{{ route("ai.audit-summary") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ audit_id: auditId })
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                container.innerHTML = `<div class="ai-report-text">${result.data}</div>`;
                document.getElementById('btnEditAi').style.display = 'block';
            } else {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>${result.message || 'Gagal generate rangkasan.'}
                    </div>
                `;
            }
        } catch (e) {
            console.error(e);
            container.innerHTML = `<div class="alert alert-danger">Gagal menghubungi server AI.</div>`;
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Ringkasan Baru';
        }
    }

    // Inline Audit Checklist
    document.querySelectorAll('.inline-audit-checklist').forEach(el => {
        el.addEventListener('change', async function() {
            const audit_id = this.dataset.auditId;
            const indikator_id = this.dataset.indId;
            const field = this.dataset.field;
            const value = this.value;
            
            this.style.opacity = '0.5';

            try {
                const response = await fetch('{{ route("audit.checklist-inline") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ audit_id, indikator_id, field, value })
                });
                
                const result = await response.json();
                if (result.success) {
                    this.classList.add('text-success');
                    setTimeout(() => {
                        this.classList.remove('text-success');
                        if (field === 'status') location.reload(); // Reload to show Temuan button if status changes
                    }, 1000);
                }
            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan perubahan.');
            } finally {
                this.style.opacity = '1';
            }
        });
    });
</script>
<style>
    .ai-report-text {
        font-family: inherit;
        line-height: 1.8;
        color: #2c3e50;
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #0d6efd;
        white-space: pre-wrap;
    }
</style>
@endpush
@endsection