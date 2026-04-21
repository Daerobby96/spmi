<?php
namespace App\Http\Controllers;

use App\Models\IndikatorKinerja;
use App\Models\Standar;
use App\Imports\IndikatorImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IndikatorKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = IndikatorKinerja::with('standar');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('standar_id')) {
            $query->where('standar_id', $request->standar_id);
        }

        $indikators = $query->orderBy('kode')->paginate(10)->withQueryString();
        $standars = Standar::where('is_aktif', true)->orderBy('kode')->get();

        return view('indikator-kinerja.index', compact('indikators', 'standars'));
    }

    public function create()
    {
        $standars = Standar::where('is_aktif', true)->orderBy('kode')->get();
        return view('indikator-kinerja.create', compact('standars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'             => 'required|string|max:20|unique:indikator_kinerjas,kode',
            'nama'             => 'required|string|max:255',
            'unit_pengukuran'  => 'required|string|max:50',
            'target_nilai'     => 'nullable|numeric|min:0',
            'target_deskripsi' => 'nullable|string',
            'unit_kerja'       => 'required|string|max:100',
            'standar_id'       => 'nullable|exists:standars,id',
            'is_aktif'         => 'boolean',
        ]);

        IndikatorKinerja::create([
            'kode'             => strtoupper($request->kode),
            'nama'             => $request->nama,
            'unit_pengukuran'  => $request->unit_pengukuran,
            'target_nilai'     => $request->target_nilai,
            'target_deskripsi' => $request->target_deskripsi ?: $request->target_nilai,
            'unit_kerja'       => $request->unit_kerja,
            'standar_id'       => $request->standar_id,
            'is_aktif'         => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('indikator-kinerja.index')
            ->with('success', 'Indikator kinerja berhasil ditambahkan.');
    }

    public function show(IndikatorKinerja $indikator_kinerja)
    {
        $indikator_kinerja->load(['standar', 'monitorings.evaluasi']);
        return view('indikator-kinerja.show', ['indikator' => $indikator_kinerja]);
    }

    public function edit(IndikatorKinerja $indikator_kinerja)
    {
        $standars = Standar::where('is_aktif', true)->orderBy('kode')->get();
        return view('indikator-kinerja.edit', ['indikator' => $indikator_kinerja, 'standars' => $standars]);
    }

    public function update(Request $request, IndikatorKinerja $indikator_kinerja)
    {
        $request->validate([
            'kode'             => 'required|string|max:20|unique:indikator_kinerjas,kode,' . $indikator_kinerja->id,
            'nama'             => 'required|string|max:255',
            'unit_pengukuran'  => 'required|string|max:50',
            'target_nilai'     => 'nullable|numeric|min:0',
            'target_deskripsi' => 'nullable|string',
            'unit_kerja'       => 'required|string|max:100',
            'standar_id'       => 'nullable|exists:standars,id',
            'is_aktif'         => 'boolean',
        ]);

        $indikator_kinerja->update([
            'kode'             => strtoupper($request->kode),
            'nama'             => $request->nama,
            'unit_pengukuran'  => $request->unit_pengukuran,
            'target_nilai'     => $request->target_nilai,
            'target_deskripsi' => $request->target_deskripsi ?: $request->target_nilai,
            'unit_kerja'       => $request->unit_kerja,
            'standar_id'       => $request->standar_id,
            'is_aktif'         => $request->boolean('is_aktif'),
        ]);

        return redirect()->route('indikator-kinerja.index')
            ->with('success', 'Indikator kinerja berhasil diperbarui.');
    }

    public function destroy(IndikatorKinerja $indikator_kinerja)
    {
        if ($indikator_kinerja->monitorings()->count() > 0) {
            return back()->with('error', 'Indikator tidak dapat dihapus karena sudah memiliki data monitoring.');
        }

        $indikator_kinerja->delete();
        return redirect()->route('indikator-kinerja.index')
            ->with('success', 'Indikator kinerja berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:2048']);
        try {
            Excel::import(new \App\Imports\IndikatorImport, $request->file('file'));
            return back()->with('success', 'Data indikator kinerja berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['kode', 'nama', 'unit_pengukuran', 'target_nilai', 'unit_kerja', 'kode_standar'];
        return Excel::download(new \App\Exports\TemplateExport($headings, 'Template Indikator'), 'template-indikator.xlsx');
    }
}
