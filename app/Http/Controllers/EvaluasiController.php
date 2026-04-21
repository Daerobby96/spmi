<?php
namespace App\Http\Controllers;

use App\Models\Evaluasi;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $periodeSel = \App\Models\Periode::find($request->periode_id) ?? \App\Models\Periode::aktif();
        
        $query = Monitoring::where('periode_id', $periodeSel?->id)
                            ->with(['indikator.standar', 'evaluasi', 'pelapor'])
                            ->whereIn('status', ['submitted', 'verified'])
                            ->latest();

        if ($request->filled('hasil')) {
            $query->whereHas('evaluasi', fn($q) => $q->where('hasil', $request->hasil));
        }

        $monitorings = $query->get();
        $periodes    = \App\Models\Periode::orderByDesc('tahun')->get();

        $stats = [
            'tercapai'        => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'tercapai')->count(),
            'tidak_tercapai'  => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'tidak_tercapai')->count(),
            'perlu_perhatian' => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'perlu_perhatian')->count(),
            'belum_eval'      => $monitorings->filter(fn($m) => !$m->evaluasi)->count(),
        ];

        return view('evaluasi.index', compact('monitorings', 'stats', 'periodes', 'periodeSel'));
    }

    public function create(Request $request)
    {
        $monitorings = Monitoring::with(['indikator', 'periode'])
            ->doesntHave('evaluasi')
            ->where('status', 'submitted')
            ->orderBy('tanggal_input', 'desc')
            ->get();

        $selected = $request->filled('monitoring_id')
            ? Monitoring::with('indikator')->find($request->monitoring_id)
            : null;

        return view('evaluasi.create', compact('monitorings', 'selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'monitoring_id'   => 'required|exists:monitorings,id',
            'analisa'         => 'required|string',
            'rekomendasi'     => 'nullable|string',
            'hasil'           => 'required|in:tercapai,tidak_tercapai,perlu_perhatian',
            'tanggal_evaluasi'=> 'required|date',
        ]);

        if (Evaluasi::where('monitoring_id', $request->monitoring_id)->exists()) {
            return back()->with('error', 'Monitoring ini sudah memiliki evaluasi.');
        }

        Evaluasi::create([
            'monitoring_id'    => $request->monitoring_id,
            'evaluator_id'     => Auth::id(),
            'analisa'          => $request->analisa,
            'rekomendasi'      => $request->rekomendasi,
            'hasil'            => $request->hasil,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
        ]);

        Monitoring::find($request->monitoring_id)->update(['status' => 'verified']);

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil disimpan.');
    }

    public function show(Evaluasi $evaluasi)
    {
        $evaluasi->load(['monitoring.indikator.standar', 'monitoring.periode', 'evaluator']);
        return view('evaluasi.show', compact('evaluasi'));
    }

    public function edit(Evaluasi $evaluasi)
    {
        $evaluasi->load('monitoring.indikator');
        return view('evaluasi.edit', compact('evaluasi'));
    }

    public function update(Request $request, Evaluasi $evaluasi)
    {
        $request->validate([
            'analisa'          => 'required|string',
            'rekomendasi'      => 'nullable|string',
            'hasil'            => 'required|in:tercapai,tidak_tercapai,perlu_perhatian',
            'tanggal_evaluasi' => 'required|date',
        ]);

        $evaluasi->update($request->only([
            'analisa', 'rekomendasi', 'hasil', 'tanggal_evaluasi',
        ]));

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    public function destroy(Evaluasi $evaluasi)
    {
        $evaluasi->monitoring->update(['status' => 'submitted']);
        $evaluasi->delete();
        return back()->with('success', 'Evaluasi berhasil dihapus.');
    }

    public function updateInline(Request $request)
    {
        $request->validate([
            'monitoring_id' => 'required|exists:monitorings,id',
            'field'         => 'required|in:analisa,hasil',
            'value'         => 'required',
        ]);

        $evaluasi = Evaluasi::updateOrCreate(
            ['monitoring_id' => $request->monitoring_id],
            [
                'evaluator_id'     => Auth::id(),
                'tanggal_evaluasi' => now(), 
                $request->field    => $request->value,
            ]
        );

        $evaluasi->monitoring->update(['status' => 'verified']);

        return response()->json([
            'success' => true,
            'message' => 'Evaluasi berhasil diperbarui.',
        ]);
    }
}