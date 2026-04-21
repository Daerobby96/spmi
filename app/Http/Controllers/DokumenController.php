<?php
namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\Standar;
use App\Imports\DokumenImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::with(['kategori', 'standar', 'pembuat'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_dokumen', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_pemilik', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('standar_id')) {
            $query->where('standar_id', $request->standar_id);
        }

        $dokumens   = $query->paginate(12)->withQueryString();
        $kategoris  = KategoriDokumen::orderBy('nama')->get();
        $standars   = Standar::where('is_aktif', true)->orderBy('nama')->get();

        $stats = [
            'total'      => Dokumen::count(),
            'approved'   => Dokumen::where('status', 'approved')->count(),
            'draft'      => Dokumen::where('status', 'draft')->count(),
            'review'     => Dokumen::where('status', 'review')->count(),
            'kadaluarsa' => Dokumen::where('tanggal_kadaluarsa', '<=', now())
                                   ->where('status', 'approved')->count(),
        ];

        return view('dokumen.index', compact('dokumens', 'kategoris', 'standars', 'stats'));
    }

    public function create()
    {
        $kategoris = KategoriDokumen::orderBy('nama')->get();
        $standars  = Standar::where('is_aktif', true)->orderBy('nama')->get();
        return view('dokumen.create', compact('kategoris', 'standars'));
    }

    public function store(Request $request)
    {
        if (empty($request->all()) && $request->server('CONTENT_LENGTH') > 0) {
            return back()->with('error', 'File terlalu besar. Maksimal ukuran file adalah 20MB.');
        }

        $request->validate([
            'kategori_id'        => 'required|exists:kategori_dokumens,id',
            'standar_id'         => 'nullable|exists:standars,id',
            'judul'              => 'required|string|max:255',
            'unit_pemilik'       => 'required|string|max:255',
            'versi'              => 'required|string|max:20',
            'tanggal_terbit'     => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after:tanggal_terbit',
            'status'             => 'required|in:draft,review,approved,obsolete',
            'keterangan'         => 'nullable|string',
            'file'               => [
                'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:20480',
                function ($attribute, $value, $fail) {
                    $allowedMimes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    ];
                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('Tipe file tidak valid. Hanya file PDF, Word, Excel, dan PowerPoint yang diizinkan.');
                    }
                },
            ],
        ]);

        try {
            $kategori = KategoriDokumen::find($request->kategori_id);
            $unitKode = strtoupper(substr(str_replace(' ', '', $request->unit_pemilik), 0, 4));
            $tahun    = now()->year;
            $count    = Dokumen::where('kategori_id', $request->kategori_id)
                               ->whereYear('created_at', $tahun)->count() + 1;
            
            $kodeDokumen = sprintf('%s/%s/%s/%03d', $kategori->kode, $unitKode, $tahun, $count);

            $filePath = $fileSize = $fileType = null;
            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $filePath = $file->store('dokumen', 'public');
                $fileSize = $file->getSize();
                $fileType = $file->getClientOriginalExtension();
            }

            Dokumen::create([
                'kategori_id'        => $request->kategori_id,
                'standar_id'         => $request->standar_id,
                'pembuat_id'         => Auth::id(),
                'kode_dokumen'       => $kodeDokumen,
                'judul'              => $request->judul,
                'unit_pemilik'       => $request->unit_pemilik,
                'versi'              => $request->versi,
                'tanggal_terbit'     => $request->tanggal_terbit,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'status'             => $request->status,
                'keterangan'         => $request->keterangan,
                'file_path'          => $filePath,
                'file_size'          => $fileSize,
                'file_type'          => $fileType,
            ]);

            return redirect()->route('dokumen.index')
                ->with('success', 'Dokumen "' . $request->judul . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan dokumen: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Dokumen $dokumen)
    {
        if (!Auth::check() && $dokumen->status !== 'approved') {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumen ini.');
        }
        $dokumen->load(['kategori', 'standar', 'pembuat']);
        return view('dokumen.show', compact('dokumen'));
    }

    public function edit(Dokumen $dokumen)
    {
        $kategoris = KategoriDokumen::orderBy('nama')->get();
        $standars  = Standar::where('is_aktif', true)->orderBy('nama')->get();
        return view('dokumen.edit', compact('dokumen', 'kategoris', 'standars'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        if (empty($request->all()) && $request->server('CONTENT_LENGTH') > 0) {
            return back()->with('error', 'File terlalu besar. Maksimal ukuran file adalah 20MB.');
        }

        $request->validate([
            'kategori_id'        => 'required|exists:kategori_dokumens,id',
            'standar_id'         => 'nullable|exists:standars,id',
            'judul'              => 'required|string|max:255',
            'unit_pemilik'       => 'required|string|max:255',
            'versi'              => 'required|string|max:20',
            'tanggal_terbit'     => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after:tanggal_terbit',
            'status'             => 'required|in:draft,review,approved,obsolete',
            'keterangan'         => 'nullable|string',
            'file'               => [
                'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:20480',
                function ($attribute, $value, $fail) {
                    $allowedMimes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    ];
                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('Tipe file tidak valid. Hanya file PDF, Word, Excel, dan PowerPoint yang diizinkan.');
                    }
                },
            ],
        ]);

        try {
            $data = $request->only([
                'kategori_id', 'standar_id', 'judul', 'unit_pemilik',
                'versi', 'tanggal_terbit', 'tanggal_kadaluarsa',
                'status', 'keterangan',
            ]);

            if ($request->hasFile('file')) {
                if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                    Storage::disk('public')->delete($dokumen->file_path);
                }
                $file             = $request->file('file');
                $data['file_path'] = $file->store('dokumen', 'public');
                $data['file_size'] = $file->getSize();
                $data['file_type'] = $file->getClientOriginalExtension();
            }

            $dokumen->update($data);

            return redirect()->route('dokumen.index')
                ->with('success', 'Dokumen "' . $dokumen->judul . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui dokumen: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $dokumen->delete();
        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Dokumen $dokumen)
    {
        if (!Auth::check() && $dokumen->status !== 'approved') {
            abort(403);
        }

        if (!$dokumen->file_path || !Storage::disk('public')->exists($dokumen->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $dokumen->increment('download_count');
        
        $extension = strtolower($dokumen->file_type ?? pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
        $safeFilename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $dokumen->kode_dokumen . '_' . $dokumen->judul) . '.' . $extension;

        return Storage::disk('public')->download($dokumen->file_path, $safeFilename);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:2048']);
        try {
            Excel::import(new \App\Imports\DokumenImport, $request->file('file'));
            return back()->with('success', 'Metadata dokumen berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['kode', 'nama', 'versi', 'tahun', 'kategori', 'kode_standar', 'deskripsi'];
        return Excel::download(new \App\Exports\TemplateExport($headings, 'Template Dokumen'), 'template-dokumen.xlsx');
    }
}