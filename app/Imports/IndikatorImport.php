<?php

namespace App\Imports;

use App\Models\IndikatorKinerja;
use App\Models\Standar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\WithMapping;

class IndikatorImport implements ToModel, WithHeadingRow, WithValidation, WithMapping
{
    public function map($row): array
    {
        if (isset($row['target_nilai']) || isset($row['target_deskripsi'])) {
            $rawNilai = isset($row['target_nilai']) ? trim((string) $row['target_nilai']) : '';
            $rawDeskripsi = isset($row['target_deskripsi']) ? trim((string) $row['target_deskripsi']) : '';
            
            // Prioritaskan target_deskripsi dari kolom excel, jika kosong ambil dari target_nilai
            $row['target_deskripsi'] = $rawDeskripsi ?: $rawNilai;

            // Jika nilai target_nilai di excel adalah deskripsi (seperti "≥ 80%"), 
            // kita tetap proses agar field numeric target_nilai mendapatkan angka murninya
            $valueForNumeric = $rawNilai;
            
            // Handle common empty/invalid placeholders for the numeric field
            if ($valueForNumeric === '-' || $valueForNumeric === '' || strtolower($valueForNumeric) === 'n/a' || strtolower($valueForNumeric) === 'tbd') {
                $row['target_nilai'] = null;
            } else {
                // Normalisasi untuk pengambilan angka
                $cleanValue = $valueForNumeric;
                if (strpos($cleanValue, ',') !== false && strpos($cleanValue, '.') !== false) {
                    $cleanValue = str_replace('.', '', $cleanValue);
                    $cleanValue = str_replace(',', '.', $cleanValue);
                } 
                elseif (strpos($cleanValue, '.') !== false && strpos($cleanValue, ',') === false) {
                    if (preg_match_all('/\.\d{3}(?!\d)/', $cleanValue) === substr_count($cleanValue, '.')) {
                        $cleanValue = str_replace('.', '', $cleanValue);
                    }
                }
                elseif (strpos($cleanValue, ',') !== false) {
                    $cleanValue = str_replace(',', '.', $cleanValue);
                }
                
                $cleaned = preg_replace('/[^0-9\.-]/', '', $cleanValue);
                
                if (substr_count($cleaned, '.') > 1) {
                    $parts = explode('.', $cleaned);
                    $first = array_shift($parts);
                    $cleaned = $first . '.' . implode('', $parts);
                }

                if ($cleaned === '' || $cleaned === '.' || $cleaned === '-') {
                    $cleaned = null;
                }
                
                $row['target_nilai'] = $cleaned;
            }
        }
        
        return $row;
    }

    public function model(array $row)
    {
        // Use the same fallback logic for standar as before
        $kodeStandar = $row['kode_standar'] ?? null;
        $standar = null;
        if ($kodeStandar) {
            $standar = Standar::where('kode', $kodeStandar)
                            ->orWhere('nama', 'like', '%' . $kodeStandar . '%')
                            ->first();
        }

        return IndikatorKinerja::updateOrCreate(
            ['kode' => strtoupper($row['kode'])],
            [
                'nama'             => $row['nama'],
                'unit_pengukuran'  => $row['unit_pengukuran'],
                'target_deskripsi' => $row['target_deskripsi'],
                'target_nilai'     => $row['target_nilai'],
                'unit_kerja'       => $row['unit_kerja'],
                'standar_id'       => $standar?->id,
                'is_aktif'         => true,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'kode'             => 'required|string|max:20',
            'nama'             => 'required|string|max:255',
            'unit_pengukuran'  => 'required|string|max:50',
            'target_deskripsi' => 'required|string', 
            'target_nilai'     => 'nullable|numeric',
            'unit_kerja'       => 'required|string|max:100',
            'kode_standar'     => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'target_deskripsi.required' => 'Kolom Target Nilai tidak boleh kosong.',
        ];
    }
}
