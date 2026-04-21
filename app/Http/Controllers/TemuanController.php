<?php
namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Temuan::with(['audit', 'auditor', 'tindakLanjuts'])->latest();

        if ($request->filled('search')) {
            $query->where('uraian_temuan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_temuan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $temuans = $query->paginate(10)->withQueryString();
        return view('audit.temuan.index', compact('temuans'));
    }

    public function create(Audit $audit, Request $request)
    {
        $checklistId = $request->query('checklist_id');
        $checklist = null;
        if ($checklistId) {
            $checklist = \App\Models\AuditChecklist::find($checklistId);
        }
        return view('audit.temuan.create', compact('audit', 'checklist'));
    }

    public function store(Request $request, Audit $audit)
    {
        $request->validate([
            'audit_checklist_id'   => 'nullable|exists:audit_checklists,id',
            'kategori'             => 'required|in:KTS_Mayor,KTS_Minor,OB,Rekomendasi',
            'klausul_standar'      => 'nullable|string|max:100',
            'uraian_temuan'        => 'required|string',
            'bukti_objektif'       => 'nullable|string',
            'batas_tindak_lanjut'  => 'nullable|date|after:today',
            'file_bukti'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $fileBukti = null;
        if ($request->hasFile('file_bukti')) {
            $fileBukti = $request->file('file_bukti')->store('temuan', 'public');
        }

        $temuan = Temuan::create([
            'audit_id'            => $audit->id,
            'audit_checklist_id'  => $request->audit_checklist_id,
            'auditor_id'          => Auth::id(),
            'kode_temuan'         => Temuan::generateKode(),
            'kategori'            => $request->kategori,
            'klausul_standar'     => $request->klausul_standar,
            'uraian_temuan'       => $request->uraian_temuan,
            'bukti_objektif'      => $request->bukti_objektif,
            'file_bukti'          => $fileBukti,
            'status'              => 'open',
            'batas_tindak_lanjut' => $request->batas_tindak_lanjut,
        ]);

        // Kirim Notifikasi ke Auditee
        $auditees = \App\Models\User::where('unit_kerja', $audit->unit_yang_diaudit)
            ->whereHas('role', fn($q) => $q->where('name', \App\Models\Role::AUDITEE))
            ->get();
            
        foreach ($auditees as $auditee) {
            $auditee->notify(new \App\Notifications\NewFindingNotification($temuan));
        }

        return redirect()->route('audit.show', $audit)
            ->with('success', 'Temuan berhasil ditambahkan dan notifikasi telah dikirim ke unit terkait.');
    }

    public function show(Audit $audit, Temuan $temuan)
    {
        $temuan->load(['audit', 'auditor', 'tindakLanjuts.penanggungJawab', 'tindakLanjuts.verifikator']);
        return view('audit.temuan.show', compact('audit', 'temuan'));
    }

    public function edit(Audit $audit, Temuan $temuan)
    {
        return view('audit.temuan.edit', compact('audit', 'temuan'));
    }

    public function update(Request $request, Audit $audit, Temuan $temuan)
    {
        $request->validate([
            'kategori'            => 'required|in:KTS_Mayor,KTS_Minor,OB,Rekomendasi',
            'klausul_standar'     => 'nullable|string|max:100',
            'uraian_temuan'       => 'required|string',
            'bukti_objektif'      => 'nullable|string',
            'status'              => 'required|in:open,in_progress,closed,verified',
            'batas_tindak_lanjut' => 'nullable|date',
        ]);

        $temuan->update($request->only([
            'kategori', 'klausul_standar', 'uraian_temuan',
            'bukti_objektif', 'status', 'batas_tindak_lanjut',
        ]));

        return redirect()->route('audit.show', $audit)
            ->with('success', 'Temuan berhasil diperbarui.');
    }

    public function destroy(Audit $audit, Temuan $temuan)
    {
        $temuan->delete();
        return redirect()->route('audit.show', $audit)
            ->with('success', 'Temuan berhasil dihapus.');
    }

    public function verifikasi(Request $request, Temuan $temuan)
    {
        $request->validate([
            'hasil_verifikasi'   => 'required|in:diterima,ditolak',
            'verifikasi_auditor' => 'required|string',
        ]);

        // Update status tindak lanjut terkait
        $temuan->tindakLanjuts()->latest()->first()?->update([
            'hasil_verifikasi'   => $request->hasil_verifikasi,
            'verifikasi_auditor' => $request->verifikasi_auditor,
            'verifikator_id'     => Auth::id(),
            'tanggal_verifikasi' => now(),
            'status'             => $request->hasil_verifikasi === 'diterima' ? 'selesai' : 'proses',
        ]);

        // Tutup temuan jika diterima
        if ($request->hasil_verifikasi === 'diterima') {
            $temuan->update(['status' => 'verified']);
        }

        return back()->with('success', 'Verifikasi berhasil disimpan.');
    }
}