<?php

namespace App\Imports;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\Standar;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DokumenImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $kategori = KategoriDokumen::where('nama', $row['kategori'])->first();
        $standar  = Standar::where('kode', $row['kode_standar'] ?? null)->first();

        return new Dokumen([
            'kode'                => strtoupper($row['kode'] ?? 'DOC-' . uniqid()),
            'nama'                => $row['nama'],
            'versi'               => $row['versi'] ?? '1.0',
            'tahun'               => $row['tahun'] ?? date('Y'),
            'kategori_dokumen_id' => $kategori?->id,
            'standar_id'          => $standar?->id,
            'pembuat_id'          => Auth::id(),
            'deskripsi'           => $row['deskripsi'] ?? '-',
            'status'              => 'Draft',
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|string|exists:kategori_dokumens,nama',
        ];
    }
}
