<?php
namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Dokumen;
use App\Models\Monitoring;
use App\Models\Temuan;
use App\Models\Periode;
use App\Models\User;
use App\Models\Standar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $periode = Periode::aktif();
        $unit = $user->unit_kerja;

        // ─── Statistik ────────────────────────────────────────────────
        $stats = [
            'total_audit'         => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->count(),
            'audit_selesai'       => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->where('status', 'selesai')->count(),
            'audit_aktif'         => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->where('status', 'aktif')->count(),
            'total_temuan'        => Temuan::whereHas('audit', function($q) use ($periode, $user, $unit) {
                                            $q->when($periode, fn($q2) => $q2->where('periode_id', $periode->id))
                                              ->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit));
                                        })->count(),
            'temuan_open'         => Temuan::where('status', 'open')
                                        ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
                                        ->count(),
            'total_dokumen'       => Dokumen::where('status', 'approved')
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
                                        ->count(),
            'dokumen_kadaluarsa'  => Dokumen::where('tanggal_kadaluarsa', '<=', now()->addMonths(1))
                                        ->where('status', 'approved')
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
                                        ->count(),
            'total_monitoring'    => Monitoring::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_kerja', $unit))
                                        ->count(),
            'total_user'          => User::where('is_active', true)->count(),
        ];

        // ─── Status PPEPP ─────────────────────────────────────────────
        $ppeppStatus = [
            'penetapan'   => false,
            'pelaksanaan' => false,
            'evaluasi'    => false,
            'pengendalian'=> false,
            'peningkatan' => false,
        ];

        if ($periode) {
            $ppeppStatus['penetapan']   = Standar::count() > 0 || \App\Models\IndikatorKinerja::where('periode_id', $periode->id)->count() > 0;
            $ppeppStatus['pelaksanaan'] = Monitoring::where('periode_id', $periode->id)->count() > 0;
            $ppeppStatus['evaluasi']    = Audit::where('periode_id', $periode->id)->whereIn('status', ['aktif', 'selesai'])->count() > 0;
            $ppeppStatus['pengendalian']= \App\Models\TindakLanjut::whereHas('temuan.audit', fn($q) => $q->where('periode_id', $periode->id))->count() > 0;
            $ppeppStatus['peningkatan'] = \App\Models\RTM::where('periode_id', $periode->id)->count() > 0;
        }

        // ─── Data Periode & Radar Chart ──────────────────────────────
        $lastPeriodes = Periode::orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->limit(5)
            ->get()
            ->reverse();

        $standars = Standar::with(['indikators.monitorings' => function($q) use ($periode) {
            $q->when($periode, fn($q2) => $q2->where('periode_id', $periode->id));
        }])->get();

        $radarLabels = [];
        $radarData = [];
        $standarProgress = [];

        foreach ($standars as $s) {
            $radarLabels[] = $s->kode;
            $totalPersen = 0;
            $countIndikator = 0;
            foreach ($s->indikators as $ind) {
                $m = $ind->monitorings->first();
                if ($m) {
                    $totalPersen += $m->persentase_capaian;
                    $countIndikator++;
                }
            }
            $avgAchievement = $countIndikator > 0 ? round($totalPersen / $countIndikator, 1) : 0;
            $radarData[] = $avgAchievement;

            // Progress Dokumen per Standar
            $totalDocs = $s->dokumens()->count();
            $approvedDocs = $s->dokumens()->where('status', 'approved')->count();
            $docPercent = $totalDocs > 0 ? round(($approvedDocs / $totalDocs) * 100) : 0;
            $standarProgress[] = [
                'nama' => $s->nama,
                'kode' => $s->kode,
                'total' => $totalDocs,
                'approved' => $approvedDocs,
                'percent' => $docPercent
            ];
        }

        // ─── Graf Tren (Temuan & Performa) ───────────────────────────
        $trenLabels = $lastPeriodes->pluck('nama')->toArray();
        $trenData = []; // Tren Temuan
        $perfTrendData = []; // Tren Performa (Achievement %)

        foreach ($lastPeriodes as $p) {
            // Temuan
            $trenData[] = Temuan::whereHas('audit', function($q) use ($p, $user, $unit) {
                $q->where('periode_id', $p->id)
                  ->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit));
            })->count();

            // Performa
            $avgPerf = Monitoring::where('periode_id', $p->id)
                ->get()
                ->avg(function($m) {
                    return $m->persentase_capaian;
                });
            $perfTrendData[] = round($avgPerf ?? 0, 1);
        }

        // ─── Temuan per Kategori ─────────────────────────────────────
        $temuanPerKategori = Temuan::selectRaw('kategori, COUNT(*) as total')
            ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        // ─── Data List Terkait ───────────────────────────────────────
        $auditTerbaru = Audit::with(['periode', 'ketuaAuditor'])
            ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
            ->latest()
            ->limit(5)
            ->get();

        $temuanDeadline = Temuan::with(['audit'])
            ->where('status', 'open')
            ->whereNotNull('batas_tindak_lanjut')
            ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
            ->orderBy('batas_tindak_lanjut')
            ->limit(5)
            ->get();

        $listDokumenKadaluarsa = Dokumen::where('status', 'approved')
            ->where('tanggal_kadaluarsa', '<=', now()->addMonths(3))
            ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'periode', 'stats', 'temuanPerKategori', 'auditTerbaru', 
            'temuanDeadline', 'trenLabels', 'trenData', 'perfTrendData',
            'listDokumenKadaluarsa', 'radarLabels', 'radarData', 'standarProgress',
            'ppeppStatus'
        ));
    }

    public function setPeriode(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
        ]);

        Periode::query()->update(['is_aktif' => false]);
        Periode::where('id', $request->periode_id)->update(['is_aktif' => true]);

        return redirect()->back()->with('success', 'Periode berhasil diubah.');
    }
}