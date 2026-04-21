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
        if (isset($row['target_nilai'])) {
            $value = trim((string) $row['target_nilai']);
            
            // Simpan teks asli ke kolom baru
            $row['target_deskripsi'] = $value;

            // Handle common empty/invalid placeholders for the numeric field
            if ($value === '-' || $value === '' || strtolower($value) === 'n/a' || strtolower($value) === 'tbd') {
                $row['target_nilai'] = null;
                return $row;
            }

            // Normalisasi untuk pengambilan angka (Logic tetap dipertahankan untuk target_nilai numeric)
            $cleanValue = $value;
            // If there's a comma and dots, it's likely Indonesian 1.000.000,50
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
            
            // Ambil karakter angka, titik pertama, dan minus
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

        return new IndikatorKinerja([
            'kode'             => strtoupper($row['kode']),
            'nama'             => $row['nama'],
            'unit_pengukuran'  => $row['unit_pengukuran'],
            'target_deskripsi' => $row['target_deskripsi'],
            'target_nilai'     => $row['target_nilai'],
            'unit_kerja'       => $row['unit_kerja'],
            'standar_id'       => $standar?->id,
            'is_aktif'         => true,
        ]);
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
