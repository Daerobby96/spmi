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
        
        // Hitung Keselarasan / Kesesuaian (Status 1, 2, 3 = Erat/Sesuai)
        $bekerjaValid = TracerStudy::whereNotNull('keselarasan_horisontal')->count();
        $selaras = TracerStudy::whereIn('keselarasan_horisontal', ['1', '2', '3', 'Sangat Erat', 'Erat', 'Cukup Erat'])->count();
        $kesesuaianRate = $bekerjaValid > 0 ? ($selaras / $bekerjaValid) * 100 : 0;

        $syncedCount = 0;
        
        // Cari Indikator terkait Kesesuaian / Keselarasan
        $indikatorKesesuaianList = IndikatorKinerja::where('nama', 'ilike', '%lulusan%')
            ->where(function($q) {
                $q->where('nama', 'ilike', '%sesuai%')
                  ->orWhere('nama', 'ilike', '%selaras%')
                  ->orWhere('nama', 'ilike', '%relevan%');
            })->get();
            
        $excludeIds = [];
        foreach ($indikatorKesesuaianList as $indikatorKesesuaian) {
            $this->saveMonitoring($periode->id, $indikatorKesesuaian->id, $kesesuaianRate);
            $excludeIds[] = $indikatorKesesuaian->id;
            $syncedCount++;
        }
        
        // Cari Indikator terkait Pekerjaan / Keterserapan (Kecuali yang Kesesuaian)
        $indikatorKerjaList = IndikatorKinerja::where('nama', 'ilike', '%lulusan%')
            ->where(function($q) {
                $q->where('nama', 'ilike', '%kerja%')
                  ->orWhere('nama', 'ilike', '%pekerjaan%')
                  ->orWhere('nama', 'ilike', '%terserap%');
            })
            ->whereNotIn('id', $excludeIds)
            ->get();
        
        foreach ($indikatorKerjaList as $indikatorKerja) {
            $this->saveMonitoring($periode->id, $indikatorKerja->id, $employedRate);
            $syncedCount++;
        }

        // Cari Indikator terkait Waktu Tunggu
        $indikatorTungguList = IndikatorKinerja::where('nama', 'ilike', '%waktu tunggu%')
            ->where('nama', 'ilike', '%lulusan%')->get();
            
        foreach ($indikatorTungguList as $indikatorTunggu) {
            $this->saveMonitoring($periode->id, $indikatorTunggu->id, $stats['avg_tunggu']);
            $syncedCount++;
        }

        // Cari Indikator terkait Pendapatan / Gaji
        $indikatorGajiList = IndikatorKinerja::where(function($q) {
            $q->where('nama', 'ilike', '%pendapatan%')
              ->orWhere('nama', 'ilike', '%gaji%')
              ->orWhere('nama', 'ilike', '%ump%');
        })->where('nama', 'ilike', '%lulusan%')->get();
        
        foreach ($indikatorGajiList as $indikatorGaji) {
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

        // Ambil semua indikator yang relevan
        $indikators = IndikatorKinerja::where('nama', 'ilike', '%lulusan%')
            ->where(function($q) {
                $q->where('nama', 'ilike', '%kerja%')
                  ->orWhere('nama', 'ilike', '%pekerjaan%')
                  ->orWhere('nama', 'ilike', '%waktu tunggu%')
                  ->orWhere('nama', 'ilike', '%pendapatan%')
                  ->orWhere('nama', 'ilike', '%gaji%')
                  ->orWhere('nama', 'ilike', '%sesuai%')
                  ->orWhere('nama', 'ilike', '%selaras%')
                  ->orWhere('nama', 'ilike', '%relevan%');
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
