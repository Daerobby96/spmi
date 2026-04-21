<?php
namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\IndikatorKinerja;
use App\Models\Periode;
use App\Services\SiakadService;
use App\Imports\MonitoringImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MonitoringController extends Controller
{
    public function syncSiakad(SiakadService $siakadService)
    {
        $result = $siakadService->syncAcademicIndicators();
        
        if ($result['success']) {
            return redirect()->route('monitoring.index')
                ->with('success', $result['message']);
        }
        
        return redirect()->route('monitoring.index')
            ->with('error', $result['message']);
    }

    public function index(Request $request)
    {
        $periodeSel = Periode::find($request->periode_id) ?? Periode::aktif();
        
        $query = IndikatorKinerja::leftJoin('standars', 'indikator_kinerjas.standar_id', '=', 'standars.id')
                                ->select('indikator_kinerjas.*')
                                ->where('indikator_kinerjas.is_aktif', true)
                                ->with(['monitorings' => function($q) use ($periodeSel) {
                                    if ($periodeSel) {
                                        $q->where('periode_id', $periodeSel->id);
                                    }
                                }, 'monitorings.evaluasi', 'standar'])
                                ->orderBy('standars.kode')
                                ->orderBy('indikator_kinerjas.kode');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        // Auditee hanya lihat data unit-nya
        if (auth()->user()->isAuditee()) {
            $query->where('unit_kerja', auth()->user()->unit_kerja);
        }

        $indikators = $query->get();
        $periodes   = Periode::orderByDesc('tahun')->get();

        // Statistik capaian (Live berdasarkan data di tabel)
        $stats = [
            'total'      => $indikators->count(),
            'tercapai'   => $indikators->filter(fn($i) => 
                $i->monitorings->isNotEmpty() && ($i->monitorings->first()->nilai_capaian >= $i->target_nilai)
            )->count(),
            'tidak'      => $indikators->filter(fn($i) => 
                $i->monitorings->isNotEmpty() && ($i->monitorings->first()->nilai_capaian < $i->target_nilai)
            )->count(),
            'belum_eval' => $indikators->filter(fn($i) => $i->monitorings->isEmpty())->count(),
        ];

        return view('monitoring.index', compact('indikators', 'periodes', 'stats', 'periodeSel'));
    }

    public function create()
    {
        $indikators = IndikatorKinerja::where('is_aktif', true)->orderBy('nama')->get();
        $periodes   = Periode::orderByDesc('tahun')->get();
        return view('monitoring.create', compact('indikators', 'periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id'     => 'required|exists:periodes,id',
            'indikator_id'   => 'required|exists:indikator_kinerjas,id',
            'nilai_capaian'  => 'required|numeric|min:0',
            'tanggal_input'  => 'required|date',
            'keterangan'     => 'nullable|string',
            'bukti_dokumen'  => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_dokumen')) {
            $buktiPath = $request->file('bukti_dokumen')->store('bukti-monitoring', 'public');
        }

        Monitoring::create([
            'periode_id'    => $request->periode_id,
            'indikator_id'  => $request->indikator_id,
            'pelapor_id'    => Auth::id(),
            'nilai_capaian' => $request->nilai_capaian,
            'tanggal_input' => $request->tanggal_input,
            'keterangan'    => $request->keterangan,
            'bukti_dokumen' => $buktiPath,
            'status'        => 'submitted',
        ]);

        return redirect()->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil disimpan.');
    }

    public function show(Monitoring $monitoring)
    {
        $monitoring->load(['periode', 'indikator.standar', 'pelapor', 'evaluasi.evaluator']);
        return view('monitoring.show', compact('monitoring'));
    }

    public function edit(Monitoring $monitoring)
    {
        $indikators = IndikatorKinerja::where('is_aktif', true)->orderBy('nama')->get();
        $periodes   = Periode::orderByDesc('tahun')->get();
        return view('monitoring.edit', compact('monitoring', 'indikators', 'periodes'));
    }

    public function update(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'periode_id'    => 'required|exists:periodes,id',
            'indikator_id'  => 'required|exists:indikator_kinerjas,id',
            'nilai_capaian' => 'required|numeric|min:0',
            'tanggal_input' => 'required|date',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|in:draft,submitted,verified',
            'bukti_dokumen' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        $data = $request->only([
            'periode_id', 'indikator_id', 'nilai_capaian',
            'tanggal_input', 'keterangan', 'status',
        ]);

        if ($request->hasFile('bukti_dokumen')) {
            if ($monitoring->bukti_dokumen) {
                Storage::disk('public')->delete($monitoring->bukti_dokumen);
            }
            $data['bukti_dokumen'] = $request->file('bukti_dokumen')
                ->store('bukti-monitoring', 'public');
        }

        $monitoring->update($data);

        return redirect()->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil diperbarui.');
    }

    public function destroy(Monitoring $monitoring)
    {
        if ($monitoring->bukti_dokumen) {
            Storage::disk('public')->delete($monitoring->bukti_dokumen);
        }
        $monitoring->delete();

        return back()->with('success', 'Data monitoring berhasil dihapus.');
    }

    public function updateInline(Request $request)
    {
        $request->validate([
            'indikator_id' => 'required|exists:indikator_kinerjas,id',
            'periode_id'   => 'required|exists:periodes,id',
            'field'        => 'required|in:nilai_capaian,status',
            'value'        => 'required',
        ]);

        $field = $request->field;
        $value = $request->value;

        if ($field === 'nilai_capaian' && !is_numeric($value)) {
            return response()->json(['success' => false, 'message' => 'Nilai harus angka.'], 422);
        }

        $monitoring = Monitoring::updateOrCreate(
            [
                'indikator_id' => $request->indikator_id,
                'periode_id'   => $request->periode_id,
            ],
            [
                'pelapor_id'    => Auth::id(),
                'tanggal_input' => now(), // Default if creating
                $field          => $value,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Berhasil diperbarui.',
            'persentase' => number_format($monitoring->persentase_capaian, 1) . '%'
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new MonitoringImport, $request->file('file'));
            return back()->with('success', 'Data monitoring berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['kode_indikator', 'capaian_nilai', 'analisis', 'kendala', 'tindakan'];
        return Excel::download(new \App\Exports\TemplateExport($headings, 'Template Capaian'), 'template-capaian.xlsx');
    }
}
