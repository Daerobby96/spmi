<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\Dokumen;
use App\Models\IndikatorKinerja;
use App\Models\Monitoring;
use App\Models\Periode;

class PublicController extends Controller
{
    public function index()
    {
        $periode = Periode::where('is_aktif', true)->first();
        $stats = [];
        
        // Statistik Dokumen
        $stats['total_dokumen'] = Dokumen::where('status', 'approved')->where('is_public', true)->count();
        
        // Statistik Audit
        $auditSelesai = Audit::where('status', 'selesai')->count();
        $totalAudit = Audit::count();
        $stats['audit_progress'] = $totalAudit > 0 ? round(($auditSelesai / $totalAudit) * 100) : 0;
        
        // Capaian IKU (Indikator Kinerja Utama)
        $monitorings = Monitoring::where('periode_id', $periode?->id)->get();
        $avgCapaian = $monitorings->avg(function($m) {
            return $m->persentase_capaian;
        }) ?? 0;
        $stats['avg_capaian'] = round($avgCapaian, 1);
        
        // Data untuk Chart Publik (Radar)
        $capaianPerStandar = [];
        if ($periode) {
            $capaianPerStandar = Monitoring::where('periode_id', $periode->id)
                ->with('indikator.standar')
                ->get()
                ->groupBy('indikator.standar.nama')
                ->map(function ($group) {
                    return round($group->avg(fn($m) => $m->persentase_capaian), 1);
                });
        }
        
        // Rerata Kepuasan Mahasiswa (EDOM)
        $stats['avg_edom'] = round(\App\Models\DosenKinerja::where('periode_id', $periode?->id)->avg('total_rerata') ?? 0, 2);
        
        // Kuesioner Aktif untuk Publik (Yang ditandai is_public)
        $publicKuesioners = \App\Models\Kuesioner::where('status', 'aktif')
            ->where('is_public', true)
            ->latest()
            ->take(3)
            ->get();
            
        // Ganti placeholder {periode} pada judul kuesioner dan perbaiki typo umum
        if ($periode) {
            foreach ($publicKuesioners as $k) {
                $k->judul = str_replace('{periode}', $periode->nama, $k->judul);
                // Perbaiki typo umum jika ada di data
                $k->judul = str_ireplace(['surver', 'ganji '], ['Survei', 'Ganjil '], $k->judul);
            }
        }
        
        return view('public.index', compact('stats', 'periode', 'capaianPerStandar', 'publicKuesioners'));
    }

    public function documents(Request $request)
    {
        $query = Dokumen::where('status', 'approved')
            ->where('is_public', true)
            ->with('kategori', 'standar');
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('kode_dokumen', 'like', '%' . $request->search . '%');
        }
        $documents = $query->latest()->paginate(12);
        
        return view('public.documents', compact('documents'));
    }
}