<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\KuesionerPertanyaan;
use App\Models\Periode;
use App\Imports\KuesionerPertanyaanImport;
use App\Services\SiakadImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KuesionerController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::with(['periode'])->withCount('jawabans')->latest()->paginate(10);
        return view('kuesioner.index', compact('kuesioners'));
    }

    public function create()
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        return view('kuesioner.create', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'periode_id' => 'required|exists:periodes,id',
            'target_role' => 'nullable|string',
        ]);

        $kuesioner = Kuesioner::create($request->all());

        return redirect()->route('kuesioner.edit', $kuesioner)->with('success', 'Kuesioner berhasil dibuat. Silakan tambahkan pertanyaan!');
    }

    public function show(Kuesioner $kuesioner)
    {
        $kuesioner->load(['pertanyaans.detailJawabans', 'jawabans.user']);
        
        // Group questions by category
        $groupedQuestions = $kuesioner->pertanyaans->groupBy('kategori');

        // Perhitungan hasil
        $results = [];
        $analysisList = [];
        foreach ($kuesioner->pertanyaans as $p) {
            if ($p->tipe == 'likert') {
                $avg = round($p->detailJawabans->avg('skor'), 2);
                $distribution = $p->detailJawabans->groupBy('skor')->map->count();
                $res = [
                    'id' => $p->id,
                    'pertanyaan' => $p->pertanyaan,
                    'avg' => $avg,
                    'total' => $p->detailJawabans->count(),
                    'dist' => $distribution
                ];
                $results[$p->id] = $res;
                $analysisList[] = $res;
            } else {
                $results[$p->id] = [
                    'answers' => $p->detailJawabans->pluck('jawaban_text')->filter()->take(10)
                ];
            }
        }

        // Sorting for Insights
        $topThree = collect($analysisList)->sortByDesc('avg')->take(3);
        $bottomThree = collect($analysisList)->where('total', '>', 0)->sortBy('avg')->take(3);

        return view('kuesioner.show', compact('kuesioner', 'results', 'groupedQuestions', 'topThree', 'bottomThree'));
    }

    public function edit(Kuesioner $kuesioner)
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        return view('kuesioner.edit', compact('kuesioner', 'periodes'));
    }

    public function update(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'judul' => 'required',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        $kuesioner->update($request->all());
        return redirect()->route('kuesioner.index')->with('success', 'Kuesioner diperbarui.');
    }

    public function destroy(Kuesioner $kuesioner)
    {
        $kuesioner->delete();
        return redirect()->route('kuesioner.index')->with('success', 'Kuesioner berhasil dihapus.');
    }

    // Question Management
    public function addQuestion(Request $request, Kuesioner $kuesioner)
    {
        $request->validate(['pertanyaan' => 'required', 'tipe' => 'required']);
        
        $kuesioner->pertanyaans()->create([
            'pertanyaan' => $request->pertanyaan,
            'tipe' => $request->tipe,
            'urutan' => $kuesioner->pertanyaans()->count() + 1
        ]);

        return back()->with('success', 'Pertanyaan ditambahkan.');
    }

    public function deleteQuestion(KuesionerPertanyaan $pertanyaan)
    {
        $pertanyaan->delete();
        return back()->with('success', 'Pertanyaan dihapus.');
    }

    public function downloadTemplate()
    {
        $headers = ['Pertanyaan', 'Tipe (likert/text)'];
        $data = [
            ['Contoh: Sejauh mana Anda puas dengan layanan kami?', 'likert'],
            ['Contoh: Berikan saran untuk perbaikan.', 'text'],
        ];

        return response()->streamDownload(function() use ($headers, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'template_pertanyaan_kuesioner.csv');
    }

    public function import(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048'
        ]);

        Excel::import(new KuesionerPertanyaanImport($kuesioner->id), $request->file('file'));

        return back()->with('success', 'Pertanyaan berhasil diimport.');
    }

    public function importSiakad(Request $request, SiakadImportService $service)
    {
        $request->validate([
            'file' => 'required|max:5120' // Max 5MB, any extension allowed but expected xls/html
        ]);

        try {
            $content = file_get_contents($request->file('file')->getRealPath());
            $kuesioner = $service->importFromHtml($content);
            
            return redirect()->route('kuesioner.show', $kuesioner)
                ->with('success', "Berhasil mengimport data Siakad: {$kuesioner->judul}");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
