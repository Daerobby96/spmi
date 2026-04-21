<?php

namespace App\Http\Controllers;

use App\Models\RTM;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class RtmController extends Controller
{
    public function index()
    {
        $periodeId = session('active_periode_id');
        
        // Fallback ke periode yang sedang aktif jika session kosong
        if (!$periodeId) {
            $aktif = \App\Models\Periode::where('is_aktif', true)->first();
            $periodeId = $aktif ? $aktif->id : null;
        }

        $rtms = RTM::where('periode_id', $periodeId)->latest()->get();
        
        $stats = [
            'total_temuan' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->count(),
            'kts_mayor' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'KTS_Mayor')->count(),
            'kts_minor' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'KTS_Minor')->count(),
            'observasi' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'OB')->count(),
            'indikator_tercapai' => \App\Models\Evaluasi::whereHas('monitoring', fn($q) => $q->where('periode_id', $periodeId))->where('hasil', 'tercapai')->count(),
            'indikator_total' => \App\Models\Monitoring::where('periode_id', $periodeId)->count(),
        ];
        
        return view('rtm.index', compact('rtms', 'stats'));
    }

    public function create()
    {
        return view('rtm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'agenda' => 'nullable|string',
            'input_audit_internal' => 'nullable|string',
            'input_umpan_balik' => 'nullable|string',
            'input_kinerja_proses' => 'nullable|string',
            'input_status_tindakan' => 'nullable|string',
            'input_perubahan_sistem' => 'nullable|string',
            'input_rekomendasi' => 'nullable|string',
            'notulensi' => 'nullable|string',
            'output_keefektifan' => 'nullable|string',
            'output_perbaikan' => 'nullable|string',
            'output_sumber_daya' => 'nullable|string',
            'keputusan_manajemen' => 'nullable|string',
            'file_absensi' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $aktif = \App\Models\Periode::where('is_aktif', true)->first();
        $periodeId = session('active_periode_id') ?? ($aktif ? $aktif->id : null);
        
        $data = $request->except('file_absensi');
        $data['periode_id'] = $periodeId;

        if ($request->hasFile('file_absensi')) {
            $data['file_absensi'] = $request->file('file_absensi')->store('rtm/absensi', 'public');
        }

        RTM::create($data);

        return redirect()->route('rtm.index')->with('success', 'RTM berhasil dibuat.');
    }

    public function show(RTM $rTM)
    {
        $periodeId = $rTM->periode_id;
        $findingStats = [
            'open' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('status', 'open')->count(),
            'in_progress' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('status', 'in_progress')->count(),
            'closed' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->whereIn('status', ['closed', 'verified'])->count(),
        ];

        return view('rtm.show', compact('rTM', 'findingStats'));
    }

    public function edit(RTM $rTM)
    {
        return view('rtm.edit', compact('rTM'));
    }

    public function update(Request $request, RTM $rTM)
    {
        $request->validate([
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'agenda' => 'nullable|string',
            'input_audit_internal' => 'nullable|string',
            'input_umpan_balik' => 'nullable|string',
            'input_kinerja_proses' => 'nullable|string',
            'input_status_tindakan' => 'nullable|string',
            'input_perubahan_sistem' => 'nullable|string',
            'input_rekomendasi' => 'nullable|string',
            'notulensi' => 'nullable|string',
            'output_keefektifan' => 'nullable|string',
            'output_perbaikan' => 'nullable|string',
            'output_sumber_daya' => 'nullable|string',
            'keputusan_manajemen' => 'nullable|string',
            'file_absensi' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'status' => 'required|in:draft,selesai',
        ]);

        $data = $request->except('file_absensi');

        if ($request->hasFile('file_absensi')) {
            if ($rTM->file_absensi) Storage::disk('public')->delete($rTM->file_absensi);
            $data['file_absensi'] = $request->file('file_absensi')->store('rtm/absensi', 'public');
        }

        $rTM->update($data);

        return redirect()->route('rtm.index')->with('success', 'RTM berhasil diperbarui.');
    }

    public function destroy(RTM $rTM)
    {
        if ($rTM->file_absensi) Storage::disk('public')->delete($rTM->file_absensi);
        $rTM->delete();
        return redirect()->route('rtm.index')->with('success', 'RTM berhasil dihapus.');
    }

    public function exportPdf(RTM $rtm)
    {
        $rtm->load('periode');
        
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

        $findingStats = [
            'open' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->where('status', 'open')->count(),
            'in_progress' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->where('status', 'in_progress')->count(),
            'closed' => \App\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->whereIn('status', ['closed', 'verified'])->count(),
        ];

        $data = [
            'rtm' => $rtm,
            'findingStats' => $findingStats,
            'setting' => $setting,
            'ketua_spmi' => $ketuaSpmi,
            'kepala_institusi' => $kepalaInstitusi,
        ];

        $pdf = Pdf::setOptions([
            'margin-top' => 75,
            'margin-left' => 75,
            'margin-right' => 75,
            'margin-bottom' => 75,
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ])->loadView('rtm.pdf', $data);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan-RTM-' . str_replace(' ', '-', $rtm->judul_rapat) . '.pdf';
        return $pdf->stream($filename);
    }
}
