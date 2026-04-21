<?php
namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with(['periode', 'ketuaAuditor'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_audit', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_audit', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_yang_diaudit', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        $audits   = $query->paginate(10)->withQueryString();
        $periodes = Periode::orderByDesc('tahun')->get();

        $stats = [
            'total'   => Audit::count(),
            'draft'   => Audit::where('status', 'draft')->count(),
            'aktif'   => Audit::where('status', 'aktif')->count(),
            'selesai' => Audit::where('status', 'selesai')->count(),
        ];

        return view('audit.index', compact('audits', 'periodes', 'stats'));
    }

    public function create()
    {
        $periodes  = Periode::orderByDesc('tahun')->get();
        $auditors  = User::whereHas('role', fn($q) => $q->whereIn('name', ['auditor', 'super_admin']))
                         ->where('is_active', true)->orderBy('name')->get();
        $kodeAudit = Audit::generateKode();

        return view('audit.create', compact('periodes', 'auditors', 'kodeAudit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id'         => 'required|exists:periodes,id',
            'nama_audit'         => 'required|string|max:255',
            'unit_yang_diaudit'  => 'required|string|max:255',
            'ketua_auditor_id'   => 'required|exists:users,id',
            'tanggal_audit'      => 'required|date',
            'opening_meeting'    => 'nullable|date',
            'closing_meeting'    => 'nullable|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_audit',
            'lingkup_audit'      => 'nullable|string',
            'tujuan_audit'       => 'nullable|string',
            'catatan'            => 'nullable|string',
            'anggota_auditor'    => 'nullable|array',
            'anggota_auditor.*'  => 'exists:users,id',
        ]);

        $audit = Audit::create([
            'periode_id'        => $request->periode_id,
            'kode_audit'        => Audit::generateKode(),
            'nama_audit'        => $request->nama_audit,
            'unit_yang_diaudit' => $request->unit_yang_diaudit,
            'ketua_auditor_id'  => $request->ketua_auditor_id,
            'tanggal_audit'     => $request->tanggal_audit,
            'opening_meeting'   => $request->opening_meeting,
            'closing_meeting'   => $request->closing_meeting,
            'tanggal_selesai'   => $request->tanggal_selesai,
            'status'            => 'draft',
            'lingkup_audit'     => $request->lingkup_audit,
            'tujuan_audit'      => $request->tujuan_audit,
            'catatan'           => $request->catatan,
        ]);

        // Simpan anggota auditor
        $auditorIds = collect($request->anggota_auditor ?? [])
            ->mapWithKeys(fn($id) => [$id => ['peran' => 'anggota']]);

        // Tambahkan ketua
        $auditorIds[$request->ketua_auditor_id] = ['peran' => 'ketua'];
        $audit->auditors()->sync($auditorIds);

        return redirect()->route('audit.show', $audit)
            ->with('success', 'Audit "' . $audit->nama_audit . '" berhasil dibuat.');
    }

    public function show(Audit $audit)
    {
        $audit->load(['periode', 'ketuaAuditor', 'auditors', 'temuans.tindakLanjuts', 'checklists.indikator.standar']);

        // Ambil indikator yang relevan dengan unit kerja ini
        $indikators = \App\Models\IndikatorKinerja::where('unit_kerja', $audit->unit_yang_diaudit)
                                                ->where('is_aktif', true)
                                                ->with('standar')
                                                ->orderBy('kode')
                                                ->get();

        if ($indikators->isEmpty()) {
            $indikators = \App\Models\IndikatorKinerja::where('is_aktif', true)
                                                    ->with('standar')
                                                    ->orderBy('kode')
                                                    ->get();
        }

        $statsTemuan = [
            'total'       => $audit->temuans->count(),
            'kts_mayor'   => $audit->temuans->where('kategori', 'KTS_Mayor')->count(),
            'kts_minor'   => $audit->temuans->where('kategori', 'KTS_Minor')->count(),
            'observasi'   => $audit->temuans->where('kategori', 'OB')->count(),
            'rekomendasi' => $audit->temuans->where('kategori', 'Rekomendasi')->count(),
            'open'        => $audit->temuans->where('status', 'open')->count(),
            'closed'      => $audit->temuans->where('status', 'closed')->count(),
        ];

        // Stats Checklist
        $statsChecklist = [
            'total' => $audit->checklists->count(),
            'sesuai' => $audit->checklists->where('status', 'sesuai')->count(),
            'tidak_sesuai' => $audit->checklists->where('status', 'tidak_sesuai')->count(),
            'belum' => $audit->checklists->where('status', 'belum_diisi')->count(),
        ];

        return view('audit.show', compact('audit', 'statsTemuan', 'statsChecklist', 'indikators'));
    }

    public function updateChecklistInline(Request $request)
    {
        $request->validate([
            'audit_id'     => 'required|exists:audits,id',
            'indikator_id' => 'required|exists:indikator_kinerjas,id',
            'field'        => 'required|in:status,catatan,bukti_objektif',
            'value'        => 'required',
        ]);

        $indikator = \App\Models\IndikatorKinerja::find($request->indikator_id);
        
        $checklist = \App\Models\AuditChecklist::updateOrCreate(
            [
                'audit_id'     => $request->audit_id,
                'indikator_id' => $request->indikator_id,
            ],
            [
                'pertanyaan'    => $indikator->nama,
                $request->field => $request->value,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Checklist diperbarui.',
        ]);
    }

    public function updateChecklist(Request $request, Audit $audit, \App\Models\AuditChecklist $checklist)
    {
        if ($checklist->audit_id !== $audit->id) abort(403);

        $request->validate([
            'status' => 'required|in:sesuai,tidak_sesuai,observasi,tidak_terkait,belum_diisi',
            'catatan' => 'nullable|string',
            'bukti_objektif' => 'nullable|string',
        ]);

        $checklist->update($request->only(['status', 'catatan', 'bukti_objektif']));

        return back()->with('success', 'Checklist berhasil diupdate.');
    }

    public function edit(Audit $audit)
    {
        $periodes = Periode::orderByDesc('tahun')->get();
        $auditors = User::whereHas('role', fn($q) => $q->whereIn('name', ['auditor', 'super_admin']))
                        ->where('is_active', true)->orderBy('name')->get();
        $selectedAnggota = $audit->auditors()->wherePivot('peran', 'anggota')->pluck('users.id')->toArray();

        return view('audit.edit', compact('audit', 'periodes', 'auditors', 'selectedAnggota'));
    }

    public function update(Request $request, Audit $audit)
    {
        $request->validate([
            'periode_id'        => 'required|exists:periodes,id',
            'nama_audit'        => 'required|string|max:255',
            'unit_yang_diaudit' => 'required|string|max:255',
            'ketua_auditor_id'  => 'required|exists:users,id',
            'tanggal_audit'     => 'required|date',
            'opening_meeting'   => 'nullable|date',
            'closing_meeting'   => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_audit',
            'status'            => 'required|in:draft,aktif,selesai,ditutup',
            'lingkup_audit'     => 'nullable|string',
            'tujuan_audit'      => 'nullable|string',
            'catatan'           => 'nullable|string',
            'anggota_auditor'   => 'nullable|array',
            'anggota_auditor.*' => 'exists:users,id',
        ]);

        $audit->update($request->only([
            'periode_id', 'nama_audit', 'unit_yang_diaudit', 'ketua_auditor_id',
            'tanggal_audit', 'tanggal_selesai', 'opening_meeting', 'closing_meeting', 'status',
            'lingkup_audit', 'tujuan_audit', 'catatan',
        ]));

        $auditorIds = collect($request->anggota_auditor ?? [])
            ->mapWithKeys(fn($id) => [$id => ['peran' => 'anggota']]);
        $auditorIds[$request->ketua_auditor_id] = ['peran' => 'ketua'];
        $audit->auditors()->sync($auditorIds);

        return redirect()->route('audit.show', $audit)
            ->with('success', 'Audit berhasil diperbarui.');
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audit.index')
            ->with('success', 'Audit berhasil dihapus.');
    }

    public function updateAiSummary(Request $request, Audit $audit)
    {
        $audit->update(['ai_summary' => $request->ai_summary]);
        return response()->json(['success' => true]);
    }
}