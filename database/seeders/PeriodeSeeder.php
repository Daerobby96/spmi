<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        Periode::updateOrCreate(
            ['tahun' => 2024, 'semester' => 'ganjil'],
            [
                'nama'            => 'Semester Ganjil 2024/2025',
                'tahun'           => 2024,
                'semester'        => 'ganjil',
                'tanggal_mulai'   => '2024-09-01',
                'tanggal_selesai' => '2025-01-31',
                'is_aktif'        => true,
                'keterangan'      => 'Periode aktif saat ini',
            ]
        );
    }
}