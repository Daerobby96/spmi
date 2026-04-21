<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\IndikatorKinerja;
use App\Models\Monitoring;
use App\Models\Periode;
use Carbon\Carbon;

class SiakadService
{
    /**
     * Simulate fetching data from SIAKAD API for specific performance indicators.
     * In a real world scenario, this would use Http::get() with API Keys.
     */
    public function syncAcademicIndicators()
    {
        $periode = Periode::where('is_aktif', true)->first();
        if (!$periode) return ['success' => false, 'message' => 'No active period found.'];

        // Mock response data from SIAKAD
        $mockData = [
            'IKU-001' => [
                'name' => 'Rasio Dosen Tingkat Pendidikan S3',
                'value' => 45.5, // 45.5%
                'unit' => 'Persen',
                'standar_id' => 1
            ],
            'IKU-002' => [
                'name' => 'IPK Rata-rata Lulusan',
                'value' => 3.42,
                'unit' => 'Skala 4.0',
                'standar_id' => 1
            ],
            'IKU-003' => [
                'name' => 'Rata-rata Masa Studi (Tahun)',
                'value' => 4.1,
                'unit' => 'Tahun',
                'standar_id' => 2
            ],
            'IKU-004' => [
                'name' => 'Persentase Kelulusan Tepat Waktu',
                'value' => 78.2,
                'unit' => 'Persen',
                'standar_id' => 2
            ]
        ];

        $syncedCount = 0;

        foreach ($mockData as $kode => $data) {
            // Find or Create Indicator
            $indicator = IndikatorKinerja::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama' => $data['name'],
                    'unit_pengukuran' => $data['unit'],
                    'standar_id' => $data['standar_id'],
                    'unit_kerja' => 'Universitas',
                    'is_aktif' => true,
                    // Typically target is set manually, but we define default if new
                    'target_nilai' => $indicator->target_nilai ?? 80 
                ]
            );

            // Record Monitoring for current period
            Monitoring::updateOrCreate(
                [
                    'periode_id' => $periode->id,
                    'indikator_id' => $indicator->id
                ],
                [
                    'nilai_capaian' => $data['value'],
                    'tanggal_input' => Carbon::now(),
                    'keterangan' => 'Automated sync from SIAKAD API',
                    'status' => 'verified'
                ]
            );

            $syncedCount++;
        }

        return [
            'success' => true, 
            'message' => "Successfully synced {$syncedCount} indicators from SIAKAD."
        ];
    }
}
