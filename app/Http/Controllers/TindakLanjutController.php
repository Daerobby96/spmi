<?php
namespace App\Http\Controllers;

use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TindakLanjutController extends Controller
{
    public function index(Request $request)
    {
        $query = Temuan::with(['audit', 'auditor', 'tindakLanjuts'])
            ->whereIn('status', ['open', 'in_progress']);

        if ($request->filled('search')) {
            $query->where('uraian_temuan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_temuan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('deadline')) {
            $query->where('batas_tindak_lanjut', '<=', now()->addDays((int)$request->deadline));
        }

        // Auditee hanya lihat temuan dari unit-nya
        if (auth()->user()->isAuditee()) {
            $query->whereHas('audit', fn($q) =>
                $q->where('unit_yang_diaudit', auth()->user()->unit_kerja)
            );
        }

        $temuans = $query->orderBy('batas_tindak_lanjut')->paginate(10)->withQueryString();
        
        $stats = [
            'open'        => Temuan::where('status', 'open')->count(),
            'in_progress' => Temuan::where('status', 'in_progress')->count(),
            'closed'      => Temuan::where('status', 'closed')->count(),
            'overdue'     => Temuan::where('status', 'open')
                                   ->where('batas_tindak_lanjut', '<', now())->count(),
        ];

        return view('tindak-lanjut.index', compact('temuans', 'stats'));
    }

    public function create(Request $request)
    {
        $temuan  = Temuan::with('audit')->findOrFail($request->temuan_id);
        $petugas = User::where('is_active', true)->orderBy('name')->get();
        return view('tindak-lanjut.create', compact('temuan', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'temuan_id'           => 'required|exists:temuans,id',
            'penanggung_jawab_id' => 'required|exists:users,id',
            'analisa_penyebab'    => 'required|string',
            'rencana_tindakan'    => 'required|string',
            'target_selesai'      => 'required|date|after:today',
            'bukti_tindakan'      => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_tindakan')) {
            $buktiPath = $request->file('bukti_tindakan')->store('bukti-tindak-lanjut', 'public');
        }

        TindakLanjut::create([
            'temuan_id'           => $request->temuan_id,
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'analisa_penyebab'    => $request->analisa_penyebab,
            'rencana_tindakan'    => $request->rencana_tindakan,
            'target_selesai'      => $request->target_selesai,
            'bukti_tindakan'      => $buktiPath,
            'status'              => 'proses',
        ]);

        // Update status temuan menjadi in_progress
        Temuan::find($request->temuan_id)->update(['status' => 'in_progress']);

        return redirect()->route('tindak-lanjut.index')
            ->with('success', 'Tindak lanjut berhasil disimpan.');
    }

    public function show(TindakLanjut $tindakLanjut)
    {
        $tindakLanjut->load(['temuan.audit', 'penanggungJawab', 'verifikator']);
        return view('tindak-lanjut.show', compact('tindakLanjut'));
    }

    public function edit(TindakLanjut $tindakLanjut)
    {
        $petugas = User::where('is_active', true)->orderBy('name')->get();
        $tindakLanjut->load('temuan.audit');
        return view('tindak-lanjut.edit', compact('tindakLanjut', 'petugas'));
    }

    public function update(Request $request, TindakLanjut $tindakLanjut)
    {
        $request->validate([
            'penanggung_jawab_id' => 'required|exists:users,id',
            'analisa_penyebab'    => 'required|string',
            'rencana_tindakan'    => 'required|string',
            'target_selesai'      => 'required|date',
            'tanggal_realisasi'   => 'nullable|date',
            'status'              => 'required|in:pending,proses,selesai',
            'bukti_tindakan'      => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $data = $request->only([
            'penanggung_jawab_id', 'analisa_penyebab', 'rencana_tindakan',
            'target_selesai', 'tanggal_realisasi', 'status',
        ]);

        if ($request->hasFile('bukti_tindakan')) {
            if ($tindakLanjut->bukti_tindakan) {
                Storage::disk('public')->delete($tindakLanjut->bukti_tindakan);
            }
            $data['bukti_tindakan'] = $request->file('bukti_tindakan')
                ->store('bukti-tindak-lanjut', 'public');
        }

        $tindakLanjut->update($data);

        return redirect()->route('tindak-lanjut.index')
            ->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    public function destroy(TindakLanjut $tindakLanjut)
    {
        if ($tindakLanjut->bukti_tindakan) {
            Storage::disk('public')->delete($tindakLanjut->bukti_tindakan);
        }
        $tindakLanjut->delete();
        return back()->with('success', 'Tindak lanjut berhasil dihapus.');
    }
}