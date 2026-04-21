<?php

namespace App\Imports;

use App\Models\KuesionerPertanyaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KuesionerPertanyaanImport implements ToModel, WithHeadingRow
{
    protected $kuesioner_id;
    protected $count = 0;

    public function __construct($kuesioner_id)
    {
        $this->kuesioner_id = $kuesioner_id;
        $this->count = KuesionerPertanyaan::where('kuesioner_id', $kuesioner_id)->count();
    }

    public function model(array $row)
    {
        // Skip jika pertanyaan kosong
        if (empty($row['pertanyaan'])) {
            return null;
        }

        $this->count++;

        return new KuesionerPertanyaan([
            'kuesioner_id' => $this->kuesioner_id,
            'pertanyaan'   => $row['pertanyaan'],
            'tipe'         => strtolower($row['tipe'] ?? 'likert') == 'text' ? 'text' : 'likert',
            'urutan'       => $this->count,
        ]);
    }
}
