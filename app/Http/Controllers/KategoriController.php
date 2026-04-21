<?php
namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriDokumen::withCount('dokumens')->orderBy('nama')->get();
        return view('kategori-dokumen.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori-dokumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'required|string|max:10|unique:kategori_dokumens,kode',
            'warna'      => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        KategoriDokumen::create([
            'nama'       => $request->nama,
            'kode'       => strtoupper($request->kode),
            'warna'      => $request->warna ?? 'primary',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function edit(KategoriDokumen $kategori_dokuman)
    {
        return view('kategori-dokumen.edit', ['kategoriDokumen' => $kategori_dokuman]);
    }

    public function update(Request $request, KategoriDokumen $kategori_dokuman)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'required|string|max:10|unique:kategori_dokumens,kode,' . $kategori_dokuman->id,
            'warna'      => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $kategori_dokuman->update([
            'nama'       => $request->nama,
            'kode'       => strtoupper($request->kode),
            'warna'      => $request->warna,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriDokumen $kategori_dokuman)
    {
        if ($kategori_dokuman->dokumens()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki dokumen.');
        }

        $kategori_dokuman->delete();
        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
