<?php
namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Dokumen;
use App\Models\Evaluasi;
use App\Models\Monitoring;
use App\Models\Periode;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $periode = Periode::aktif();
        $ringkasan = [
            'audit' => [
                'total' => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))->count(),
                'selesai' => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))->where('status', 'selesai')->count(),
            ],
            'temuan' => [
                'total' => Temuan::count(),
                'open' => Temuan::where('status', 'open')->count(),
                'closed' => Temuan::where('status', 'closed')->count(),
            ],
            'dokumen' => [
                'total' => Dokumen::count(),
                'approved' => Dokumen::where('status', 'approved')->count(),
            ],
            'monitoring' => [
                'total' => Monitoring::when($periode, fn($q) => $q->where('periode_id', $periode->id))->count(),
                'tercapai' => Evaluasi::where('hasil', 'tercapai')->count(),
            ],
            'edom' => [
                'total' => \App\Models\DosenKinerja::when($periode, fn($q) => $q->where('periode_id', $periode->id))->count(),
                'avg' => \App\Models\DosenKinerja::when($periode, fn($q) => $q->where('periode_id', $periode->id))->avg('total_rerata') ?? 0,
            ],
        ];
        $periodes = Periode::orderByDesc('tahun')->get();
        return view('laporan.index', compact('ringkasan', 'periodes', 'periode'));
    }

    public function audit(Request $request)
    {
        $periodeId = $request->periode_id ?? Periode::aktif()?->id;
        $audits = Audit::with(['periode', 'ketuaAuditor', 'temuans'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->get();

        $temuanPerKategori = Temuan::whereHas(
            'audit',
            fn($q) =>
            $q->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId))
        )->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $periodes = Periode::orderByDesc('tahun')->get();
        return view('laporan.audit', compact('audits', 'temuanPerKategori', 'periodes', 'periodeId'));
    }

    public function dokumen(Request $request)
    {
        $dokumens = Dokumen::with(['kategori', 'standar'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        $perKategori = $dokumens->groupBy('kategori.nama')->map(fn($g) => $g->count());
        $perStatus = $dokumens->groupBy('status')->map(fn($g) => $g->count());

        return view('laporan.dokumen', compact('dokumens', 'perKategori', 'perStatus'));
    }

    public function monitoring(Request $request)
    {
        $periodeId = $request->periode_id ?? Periode::aktif()?->id;
        $monitorings = Monitoring::with(['indikator', 'evaluasi'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->get();

        $periodes = Periode::orderByDesc('tahun')->get();
        $hasilEvaluasi = $monitorings->filter(fn($m) => $m->evaluasi)
            ->groupBy('evaluasi.hasil')
            ->map(fn($g) => $g->count());

        return view('laporan.monitoring', compact('monitorings', 'periodes', 'periodeId', 'hasilEvaluasi'));
    }

    public function exportPdf(Request $request, string $type)
    {
        $data = [];
        $view = '';
        $filename = '';

        switch ($type) {
            case 'audit':
                $periodeId = $request->periode_id ?? Periode::aktif()?->id;
                $data['audits'] = Audit::with(['periode', 'ketuaAuditor', 'temuans'])
                    ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                    ->get();
                $data['temuanPerKategori'] = Temuan::whereHas(
                    'audit',
                    fn($q) =>
                    $q->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId))
                )->selectRaw('kategori, COUNT(*) as total')
                    ->groupBy('kategori')
                    ->pluck('total', 'kategori');
                $data['periode'] = Periode::find($periodeId);
                $view = 'laporan.pdf.audit';
                $filename = 'laporan-audit-' . date('Y-m-d') . '.pdf';
                break;

            case 'dokumen':
                $data['dokumens'] = Dokumen::with(['kategori', 'standar'])
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->get();
                $data['perKategori'] = $data['dokumens']->groupBy('kategori.nama')->map(fn($g) => $g->count());
                $data['perStatus'] = $data['dokumens']->groupBy('status')->map(fn($g) => $g->count());
                $data['periode'] = Periode::aktif();

                Setting::clearCache();
                $data['setting'] = [
                    'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
                    'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
                    'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
                    'logo_institusi' => Setting::get('logo_institusi', null),
                ];

                $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
                $data['ketua_spmi'] = $superAdminRole ? User::where('role_id', $superAdminRole->id)->where('is_active', true)->first() : null;

                $data['kepala_institusi'] = User::where('is_active', true)
                    ->where(function ($q) {
                        $q->where('jabatan', 'like', '%Kepala%')
                            ->orWhere('jabatan', 'like', '%Rektor%')
                            ->orWhere('jabatan', 'like', '%Direktur%');
                    })->first();

                $stafDokumenRole = Role::where('name', Role::STAF_DOKUMEN)->first();
                $data['koordinator_dokumen'] = $stafDokumenRole ? User::where('role_id', $stafDokumenRole->id)->where('is_active', true)->first() : null;

                $view = 'laporan.pdf.dokumen';
                $filename = 'laporan-dokumen-' . date('Y-m-d') . '.pdf';
                break;

            case 'monitoring':
                $periodeId = $request->periode_id ?? Periode::aktif()?->id;
                $data['monitorings'] = Monitoring::with(['indikator', 'evaluasi'])
                    ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                    ->get();
                $data['periode'] = Periode::find($periodeId);
                $data['hasilEvaluasi'] = $data['monitorings']->filter(fn($m) => $m->evaluasi)
                    ->groupBy('evaluasi.hasil')
                    ->map(fn($g) => $g->count());

                Setting::clearCache();
                $data['setting'] = [
                    'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
                    'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
                    'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
                    'logo_institusi' => Setting::get('logo_institusi', null),
                ];

                $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
                $data['ketua_spmi'] = $superAdminRole ? User::where('role_id', $superAdminRole->id)->where('is_active', true)->first() : null;

                $data['kepala_institusi'] = User::where('is_active', true)
                    ->where(function ($q) {
                        $q->where('jabatan', 'like', '%Kepala%')
                            ->orWhere('jabatan', 'like', '%Rektor%')
                            ->orWhere('jabatan', 'like', '%Direktur%');
                    })->first();

                $auditeeRole = Role::where('name', Role::AUDITEE)->first();
                $data['koordinator_monitoring'] = $auditeeRole ? User::where('role_id', $auditeeRole->id)->where('is_active', true)->first() : null;

                $data['stats'] = [
                    'total' => $data['monitorings']->count(),
                    'tercapai' => $data['hasilEvaluasi']->get('tercapai', 0),
                    'tidak_tercapai' => $data['hasilEvaluasi']->get('tidak_tercapai', 0),
                    'perlu_perhatian' => $data['hasilEvaluasi']->get('perlu_perhatian', 0),
                    'draft' => $data['monitorings']->where('status', 'draft')->count(),
                    'submitted' => $data['monitorings']->where('status', 'submitted')->count(),
                    'verified' => $data['monitorings']->where('status', 'verified')->count(),
                ];

                $view = 'laporan.pdf.monitoring';
                $filename = 'laporan-monitoring-' . date('Y-m-d') . '.pdf';
                break;

            case 'edom':
                $periodeId = $request->periode_id ?? Periode::aktif()?->id;
                $data['kinerjas'] = \App\Models\DosenKinerja::where('periode_id', $periodeId)
                    ->orderBy('total_rerata', 'desc')
                    ->get();
                $data['periode'] = Periode::find($periodeId);
                
                Setting::clearCache();
                $data['setting'] = [
                    'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
                    'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
                    'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
                    'logo_institusi' => Setting::get('logo_institusi', null),
                ];

                $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
                $data['ketua_spmi'] = $superAdminRole ? User::where('role_id', $superAdminRole->id)->where('is_active', true)->first() : null;

                $data['kepala_institusi'] = User::where('is_active', true)
                    ->where(function ($q) {
                        $q->where('jabatan', 'like', '%Kepala%')
                            ->orWhere('jabatan', 'like', '%Rektor%')
                            ->orWhere('jabatan', 'like', '%Direktur%');
                    })->first();

                $view = 'laporan.pdf.edom';
                $filename = 'laporan-edom-' . date('Y-m-d') . '.pdf';
                break;

            case 'buku-ami':
                $periodeId = $request->periode_id ?? Periode::aktif()?->id;
                $data['periode'] = Periode::find($periodeId);
                
                // P: Penetapan
                $data['dokumens'] = Dokumen::where('status', 'approved')->get();
                $data['standars'] = \App\Models\Standar::with('indikators')->where('is_aktif', true)->get();
                
                // P: Pelaksanaan
                $data['monitorings'] = Monitoring::with('indikator')
                    ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                    ->get();
                $data['kuesioners'] = \App\Models\Kuesioner::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->get();
                
                // E: Evaluasi (Audit & Temuan)
                $data['audits'] = Audit::with(['ketuaAuditor', 'temuans'])
                    ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                    ->get();
                $data['temuanPerKategori'] = Temuan::whereHas('audit', fn($q) => $q->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId)))
                    ->selectRaw('kategori, COUNT(*) as total')->groupBy('kategori')->pluck('total', 'kategori');
                
                // P: Pengendalian (Tindak Lanjut)
                $data['tindakLanjuts'] = \App\Models\TindakLanjut::with(['temuan.audit', 'penanggungJawab'])
                    ->whereHas('temuan.audit', fn($q) => $q->when($periodeId, fn($q2) => $q2->where('periode_id', $periodeId)))
                    ->get();
                
                // P: Peningkatan (RTM)
                $data['rtms'] = \App\Models\RTM::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->get();

                Setting::clearCache();
                $data['setting'] = [
                    'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
                    'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
                    'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
                    'logo_institusi' => Setting::get('logo_institusi', null),
                ];

                $kepalaInstitusi = User::where('is_active', true)
                    ->where(function ($q) {
                        $q->where('jabatan', 'like', '%Kepala%')
                            ->orWhere('jabatan', 'like', '%Rektor%')
                            ->orWhere('jabatan', 'like', '%Direktur%');
                    })->first();
                $data['kepala_institusi'] = $kepalaInstitusi;
                
                $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
                $data['ketua_spmi'] = $superAdminRole ? User::where('role_id', $superAdminRole->id)->where('is_active', true)->first() : null;

                $view = 'laporan.pdf.buku-ami';
                $filename = 'Buku-Laporan-AMI-' . ($data['periode'] ? $data['periode']->tahun : date('Y')) . '.pdf';
                break;

            default:
                return back()->with('error', 'Tipe laporan tidak valid.');
        }

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('A4', 'portrait');

        if ($type === 'monitoring' || $type === 'dokumen') {
            $pdf->setOptions([
                'margin-top' => 25,
                'margin-left' => 25,
                'margin-right' => 25,
                'margin-bottom' => 25,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'defaultFont' => 'DejaVu Sans',
                'chroot' => [storage_path('app/public'), public_path(), base_path()],
            ]);
        } else {
            $pdf->setOption('margin-top', '30mm');
            $pdf->setOption('margin-left', '30mm');
            $pdf->setOption('margin-right', '30mm');
            $pdf->setOption('margin-bottom', '30mm');
            $pdf->setOption('isRemoteEnabled', true);
        }

        return $pdf->stream($filename);
    }

    public function exportExcel(Request $request, string $type)
    {
        return back()->with('error', 'Fitur export Excel membutuhkan package maatwebsite/excel. Jalankan: composer require maatwebsite/excel');
    }

    /**
     * Export Laporan Hasil Audit Individual (per unit) dalam format PDF
     */
    public function exportAuditIndividual(Request $request, Audit $audit)
    {
        $audit->load(['periode', 'ketuaAuditor', 'auditors', 'temuans.tindakLanjuts']);
        $stats = [
            'total' => $audit->temuans->count(),
            'kts_mayor' => $audit->temuans->where('kategori', 'KTS_Mayor')->count(),
            'kts_minor' => $audit->temuans->where('kategori', 'KTS_Minor')->count(),
            'observasi' => $audit->temuans->where('kategori', 'OB')->count(),
            'rekomendasi' => $audit->temuans->where('kategori', 'Rekomendasi')->count(),
            'open' => $audit->temuans->where('status', 'open')->count(),
            'in_progress' => $audit->temuans->where('status', 'in_progress')->count(),
            'closed' => $audit->temuans->where('status', 'closed')->count(),
            'verified' => $audit->temuans->where('status', 'verified')->count(),
        ];

        Setting::clearCache();
        $setting = [
            'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
            'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
            'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
            'logo_institusi' => Setting::get('logo_institusi', null),
        ];

        $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();
        $ketuaSpmi = $superAdminRole ? User::where('role_id', $superAdminRole->id)->where('is_active', true)->first() : null;

        $kepalaInstitusi = User::where('is_active', true)
            ->where(function ($q) {
                $q->where('jabatan', 'like', '%Kepala%')
                    ->orWhere('jabatan', 'like', '%Rektor%')
                    ->orWhere('jabatan', 'like', '%Direktur%');
            })->first();

        $data = [
            'audit' => $audit,
            'stats' => $stats,
            'setting' => $setting,
            'ketua_spmi' => $ketuaSpmi,
            'kepala_institusi' => $kepalaInstitusi,
        ];

        $filename = 'Laporan-Audit-' . str_replace(['/', '\\'], '-', $audit->kode_audit) . '-' . date('Y-m-d') . '.pdf';

        $pdf = Pdf::setOptions([
            'margin-top' => 75,
            'margin-left' => 75,
            'margin-right' => 75,
            'margin-bottom' => 75,
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'chroot' => [storage_path('app/public'), public_path(), base_path()],
        ])->loadView('laporan.pdf.audit-individual', $data);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }
}