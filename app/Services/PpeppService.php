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
     * Pastikan IKU Tracer Study tersedia di database
     */
    private function ensureTracerIndicatorsExist()
    {
        $standar = \App\Models\Standar::first();
        $standarId = $standar ? $standar->id : null;

        $defaults = [
            'TRC-01' => ['nama' => 'Persentase Lulusan Mendapat Pekerjaan', 'satuan' => '%', 'target' => 80, 'deskripsi' => '≥ 80% Lulusan Bekerja'],
            'TRC-02' => ['nama' => 'Rerata Waktu Tunggu Lulusan', 'satuan' => 'Bulan', 'target' => 6, 'deskripsi' => '< 6 Bulan'],
            'TRC-03' => ['nama' => 'Rerata Pendapatan Lulusan', 'satuan' => 'Rp', 'target' => 5000000, 'deskripsi' => '≥ 1.2x UMP'],
        ];

        foreach ($defaults as $kode => $data) {
            IndikatorKinerja::firstOrCreate(
                ['kode' => $kode],
                [
                    'nama' => $data['nama'],
                    'unit_pengukuran' => $data['satuan'],
                    'target_nilai' => $data['target'],
                    'target_deskripsi' => $data['deskripsi'],
                    'unit_kerja' => 'Pusat Karir / Alumni',
                    'standar_id' => $standarId,
                    'is_aktif' => true,
                ]
            );
        }
    }

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

        $syncedCount = 0;
        
        // Cari Indikator terkait Pekerjaan / Keterserapan
        $indikatorKerja = IndikatorKinerja::where(function($q) {
            $q->where('nama', 'ilike', '%kerja%')
              ->orWhere('nama', 'ilike', '%pekerjaan%')
              ->orWhere('nama', 'ilike', '%terserap%');
        })->where('nama', 'ilike', '%lulusan%')->first();
        
        if ($indikatorKerja) {
            $this->saveMonitoring($periode->id, $indikatorKerja->id, $employedRate);
            $syncedCount++;
        }

        // Cari Indikator terkait Waktu Tunggu
        $indikatorTunggu = IndikatorKinerja::where('nama', 'ilike', '%waktu tunggu%')
            ->where('nama', 'ilike', '%lulusan%')->first();
            
        if ($indikatorTunggu) {
            $this->saveMonitoring($periode->id, $indikatorTunggu->id, $stats['avg_tunggu']);
            $syncedCount++;
        }

        // Cari Indikator terkait Pendapatan / Gaji
        $indikatorGaji = IndikatorKinerja::where(function($q) {
            $q->where('nama', 'ilike', '%pendapatan%')
              ->orWhere('nama', 'ilike', '%gaji%')
              ->orWhere('nama', 'ilike', '%ump%');
        })->where('nama', 'ilike', '%lulusan%')->first();
        
        if ($indikatorGaji) {
            $this->saveMonitoring($periode->id, $indikatorGaji->id, $stats['avg_gaji']);
            $syncedCount++;
        }

        return [
            'status' => 'success', 
            'message' => "Berhasil menyinkronkan $syncedCount indikator ke siklus PPEPP (Evaluasi)."
        ];
    }

    private function saveMonitoring($periodeId, $indikatorId, $nilai)
    {
        Monitoring::updateOrCreate(
            [
                'periode_id' => $periodeId,
                'indikator_id' => $indikatorId,
            ],
            [
                'nilai_capaian' => $nilai,
                'tanggal_input' => now(),
                'pelapor_id' => Auth::id() ?? \App\Models\User::first()->id ?? 1,
                'status' => 'verified',
                'keterangan' => 'Sistem Otomatis: Sync data Tracer Study ' . now()->format('Y-m-d H:i')
            ]
        );
    }

    /**
     * Ambil data perbandingan Target vs Capaian untuk Dashboard Tracer
     */
    public function getComparisonData()
    {
        $periode = Periode::where('is_aktif', true)->first();
        if (!$periode) return [];

        $results = [];

        // Ambil semua indikator yang mengandung kata lulusan
        $indikators = IndikatorKinerja::where('nama', 'ilike', '%lulusan%')
            ->where(function($q) {
                $q->where('nama', 'ilike', '%kerja%')
                  ->orWhere('nama', 'ilike', '%pekerjaan%')
                  ->orWhere('nama', 'ilike', '%waktu tunggu%')
                  ->orWhere('nama', 'ilike', '%pendapatan%')
                  ->orWhere('nama', 'ilike', '%gaji%');
            })->get();

        foreach ($indikators as $indikator) {
            $monitoring = Monitoring::where('periode_id', $periode->id)
                ->where('indikator_id', $indikator->id)
                ->first();
            
            // Evaluasi capaian: Jika Waktu Tunggu, semakin KECIL semakin TERCAPAI.
            $isTercapai = false;
            if ($monitoring) {
                if (stripos($indikator->nama, 'waktu tunggu') !== false) {
                    $isTercapai = $monitoring->nilai_capaian <= $indikator->target_nilai;
                } else {
                    $isTercapai = $monitoring->nilai_capaian >= $indikator->target_nilai;
                }
            }

            $results[] = [
                'nama' => $indikator->nama,
                'target' => $indikator->target_nilai,
                'capaian' => $monitoring ? $monitoring->nilai_capaian : 0,
                'satuan' => $indikator->unit_pengukuran,
                'status' => $monitoring ? ($isTercapai ? 'Tercapai' : 'Di Bawah Target') : 'Belum Sinkron'
            ];
        }

        return $results;
    }
}
