<?php
namespace App\Http\Controllers;

use App\Models\Standar;
use App\Imports\StandarImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StandarController extends Controller
{
    public function index(Request $request)
    {
        $query = Standar::withCount('dokumens');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        $standars = $query->latest()->paginate(10)->withQueryString();

        return view('standar.index', compact('standars'));
    }

    public function create()
    {
        return view('standar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:50|unique:standars,kode',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_aktif'  => 'boolean',
        ]);

        Standar::create([
            'kode'      => strtoupper($request->kode),
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('standar.index')
            ->with('success', 'Standar "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function show(Standar $standar)
    {
        $standar->load(['dokumens.kategori', 'indikators']);
        return view('standar.show', compact('standar'));
    }

    public function edit(Standar $standar)
    {
        return view('standar.edit', compact('standar'));
    }

    public function update(Request $request, Standar $standar)
    {
        $request->validate([
            'kode'      => 'required|string|max:50|unique:standars,kode,' . $standar->id,
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_aktif'  => 'boolean',
        ]);

        $standar->update([
            'kode'      => strtoupper($request->kode),
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => $request->boolean('is_aktif'),
        ]);

        return redirect()->route('standar.index')
            ->with('success', 'Standar "' . $standar->nama . '" berhasil diperbarui.');
    }

    public function destroy(Standar $standar)
    {
        if ($standar->dokumens()->count() > 0) {
            return back()->with('error', 'Standar tidak dapat dihapus karena masih memiliki dokumen terkait.');
        }

        $standar->delete();
        return redirect()->route('standar.index')
            ->with('success', 'Standar berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StandarImport, $request->file('file'));
            return back()->with('success', 'Data standar berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['kode', 'nama', 'deskripsi'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TemplateExport($headings, 'Template Standar'), 'template-standar.xlsx');
    }
}
