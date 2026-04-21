<?php

namespace App\Services;

use App\Models\TracerStudy;
use App\Models\IndikatorKinerja;
use App\Models\Monitoring;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;

class PpeppService
{
    /**
     * Sinkronisasi data Tracer Study ke Monitoring (Evaluasi PPEPP)
     */
    public function syncTracerToMonitoring()
    {
        $periode = Periode::where('is_aktif', true)->first();
        if (!$periode) return ['status' => 'error', 'message' => 'Tidak ada periode aktif.'];

        // 1. Hitung Metrik Terkini
        $stats = [
            'total' => TracerStudy::count(),
            'bekerja' => TracerStudy::where(function($q) {
                $q->where('status_kerja', 'like', 'Bekerja%')->orWhere('status_kerja', '1');
            })->count(),
            'avg_gaji' => TracerStudy::where('gaji', '>', 0)->avg('gaji') ?? 0,
            'avg_tunggu' => TracerStudy::where('waktu_tunggu_bulan', '>', 0)->avg('waktu_tunggu_bulan') ?? 0,
        ];

        if ($stats['total'] == 0) return ['status' => 'error', 'message' => 'Data Tracer Study kosong.'];

        $employedRate = ($stats['bekerja'] / $stats['total']) * 100;

        // 2. Mapping ke Indikator Kinerja (Nama/Kode yang biasanya dipakai)
        // Kita cari yang relevan atau asumsikan kode tertentu
        $mappings = [
            'TRC-01' => ['name' => 'Persentase Lulusan Mendapat Pekerjaan', 'value' => $employedRate],
            'TRC-02' => ['name' => 'Rerata Waktu Tunggu Lulusan', 'value' => $stats['avg_tunggu']],
            'TRC-03' => ['name' => 'Rerata Pendapatan Lulusan', 'value' => $stats['avg_gaji']],
        ];

        $syncedCount = 0;
        foreach ($mappings as $kode => $map) {
            $indikator = IndikatorKinerja::where('kode', $kode)
                ->orWhere('nama', 'like', '%' . $map['name'] . '%')
                ->first();

            if ($indikator) {
                Monitoring::updateOrCreate(
                    [
                        'periode_id' => $periode->id,
                        'indikator_id' => $indikator->id,
                    ],
                    [
                        'nilai_capaian' => $map['value'],
                        'tanggal_input' => now(),
                        'pelapor_id' => Auth::id() ?? 1,
                        'status' => 'submitted',
                        'keterangan' => 'Automated sync from Tracer Study Data on ' . now()->format('Y-m-d H:i')
                    ]
                );
                $syncedCount++;
            }
        }

        return [
            'status' => 'success', 
            'message' => "Berhasil menyinkronkan $syncedCount indikator ke siklus PPEPP (Evaluasi)."
        ];
    }

    /**
     * Ambil data perbandingan Target vs Capaian untuk Dashboard Tracer
     */
    public function getComparisonData()
    {
        $periode = Periode::where('is_aktif', true)->first();
        if (!$periode) return [];

        $targetKodes = ['TRC-01', 'TRC-02', 'TRC-03'];
        $results = [];

        foreach ($targetKodes as $kode) {
            $indikator = IndikatorKinerja::where('kode', $kode)->first();
            if ($indikator) {
                $monitoring = Monitoring::where('periode_id', $periode->id)
                    ->where('indikator_id', $indikator->id)
                    ->first();
                
                $results[] = [
                    'nama' => $indikator->nama,
                    'target' => $indikator->target_nilai,
                    'capaian' => $monitoring ? $monitoring->nilai_capaian : 0,
                    'satuan' => $indikator->unit_pengukuran,
                    'status' => $monitoring ? ($monitoring->nilai_capaian >= $indikator->target_nilai ? 'Tercapai' : 'Di Bawah Target') : 'Belum Sinkron'
                ];
            }
        }

        return $results;
    }
}
