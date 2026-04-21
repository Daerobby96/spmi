<?php
namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->paginate(10);
        return view('periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'tahun'           => 'required|integer|min:2020|max:2100',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        Periode::create([
            'nama'            => $request->nama,
            'tahun'           => $request->tahun,
            'semester'        => $request->semester,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_aktif'        => false,
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->route('periode.index')
            ->with('success', 'Periode "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function edit(Periode $periode)
    {
        return view('periode.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'tahun'           => 'required|integer|min:2020|max:2100',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        $periode->update([
            'nama'            => $request->nama,
            'tahun'           => $request->tahun,
            'semester'        => $request->semester,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_aktif) {
            return back()->with('error', 'Periode aktif tidak dapat dihapus.');
        }

        if ($periode->audits()->count() > 0 || $periode->monitorings()->count() > 0) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah memiliki data terkait.');
        }

        $periode->delete();
        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dihapus.');
    }

    public function activate(Periode $periode)
    {
        Periode::query()->update(['is_aktif' => false]);
        $periode->update(['is_aktif' => true]);
        return redirect()->route('periode.index')
            ->with('success', 'Periode "' . $periode->nama . '" telah diaktifkan.');
    }
}