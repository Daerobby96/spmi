<?php

namespace App\Imports;

use App\Models\TracerStudy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;

class AlumniTracerImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // 1. Identitas Utama (Strict to PDDIKTI Template Keys)
        $nim = $row['nomor_mhs'] ?? $row['nim'] ?? null;
        if (!$nim) return null;

        // 2. Mapping Status Kerja (f8)
        $statusMap = [
            '1' => 'Bekerja (Full Time/Part Time)',
            '2' => 'Wirausaha',
            '3' => 'Melanjutkan Pendidikan',
            '4' => 'Tidak bekerja tetapi sedang mencari kerja',
            '5' => 'Belum Bekerja'
        ];
        $statusRaw = $row['f8'] ?? null;
        $statusKerja = $statusMap[$statusRaw] ?? ($row['status_kerja'] ?? $statusRaw);

        // 3. Mapping Data Pekerjaan (Strict to PDDIKTI f-codes)
        // f5b  = Nama Instansi
        // f5c  = Jabatan
        // f505 = Gaji (berdasarkan screenshot user)
        // f301 = Waktu Tunggu
        
        $perusahaan = $row['f5b'] ?? $row['f503'] ?? $row['instansi'] ?? '-';
        $jabatan = $row['f5c'] ?? $row['f506'] ?? '-';
        $gaji = (int) filter_var($row['f505'] ?? $row['f504'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        $waktuTunggu = (int) ($row['f301'] ?? 0);

        // 4. Update or Create
        return TracerStudy::updateOrCreate(
            ['nim' => $nim],
            [
                'nama'                  => $row['nama'] ?? 'Tanpa Nama',
                'prodi'                 => $row['kode_prodi'] ?? $row['prodi'] ?? '-',
                'tahun_lulus'           => $row['tahun_lulus'] ?? null,
                'telepon'               => $row['hp'] ?? $row['telepon'] ?? null,
                'email'                 => $row['email'] ?? null,
                'status_kerja'          => $statusKerja,
                'perusahaan'            => $perusahaan,
                'jabatan'               => $jabatan,
                'gaji'                  => $gaji,
                'waktu_tunggu_bulan'    => $waktuTunggu,
                'tingkat_instansi'      => $row['f502'] ?? null,
                'keselarasan_horisontal' => $row['f14'] ?? null,
                'keselarasan_vertikal'   => $row['f15'] ?? null,
                'raw_data'              => $row,
            ]
        );
    }

    public function headingRow(): int
    {
        return 1;
    }
}
