<?php

namespace App\Imports;

use App\Models\Standar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StandarImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Standar([
            'kode'      => strtoupper($row['kode']),
            'nama'      => $row['nama'],
            'deskripsi' => $row['deskripsi'] ?? null,
            'is_aktif'  => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:20|unique:standars,kode',
            'nama' => 'required|string|max:255',
        ];
    }
}
