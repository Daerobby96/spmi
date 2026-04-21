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
        // Sanitize target_nilai for Indonesian format: 
        // 1.000.000,50 -> 1000000.50
        if (isset($row['target_nilai'])) {
            $value = (string) $row['target_nilai'];
            // If there's a comma and dots, it's likely Indonesian 1.000.000,50
            if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                $value = str_replace('.', '', $value); // Remove thousand dots
                $value = str_replace(',', '.', $value); // Change decimal comma to dot
            } 
            // If only dots, could be 1.000.000 or 1.5 (English)
            // But in many contexts in Indo, 1.000 is 1000.
            elseif (strpos($value, '.') !== false && strpos($value, ',') === false) {
                // If it looks like a thousand separator (3 digits after), assume it's one.
                // Or just remove All dots if we assume no decimal was needed.
                // More robust: if it's 1.000 we want 1000.
                if (preg_match('/\.\d{3}($|\D)/', $value)) {
                    $value = str_replace('.', '', $value);
                }
            }
            // If only comma, it's likely decimal 14,5
            elseif (strpos($value, ',') !== false) {
                $value = str_replace(',', '.', $value);
            }
            
            $row['target_nilai'] = preg_replace('/[^0-9\.-]/', '', $value);
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
            'kode'            => strtoupper($row['kode']),
            'nama'            => $row['nama'],
            'unit_pengukuran' => $row['unit_pengukuran'],
            'target_nilai'    => (float) $row['target_nilai'],
            'unit_kerja'      => $row['unit_kerja'],
            'standar_id'      => $standar?->id,
            'is_aktif'        => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode'            => 'required|string|max:20',
            'nama'            => 'required|string|max:255',
            'unit_pengukuran' => 'required|string|max:50',
            'target_nilai'    => 'required|numeric',
            'unit_kerja'      => 'required|string|max:100',
            'kode_standar'    => 'nullable|string',
        ];
    }
}
