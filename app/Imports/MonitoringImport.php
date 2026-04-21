<?php

namespace App\Imports;

use App\Models\IndikatorKinerja;
use App\Models\Monitoring;
use App\Models\Periode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\WithMapping;

class MonitoringImport implements ToCollection, WithHeadingRow, WithValidation, WithMapping
{
    protected $periode_id;

    public function __construct()
    {
        $this->periode_id = Periode::where('is_aktif', true)->first()?->id;
    }

    public function map($row): array
    {
        if (isset($row['capaian_nilai'])) {
            $value = (string) $row['capaian_nilai'];
            // 1.000.000,50 -> 1000000.50
            if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } 
            elseif (strpos($value, '.') !== false && strpos($value, ',') === false) {
                // If 1.000, it's 1000
                if (preg_match('/\.\d{3}($|\D)/', $value)) {
                    $value = str_replace('.', '', $value);
                }
            }
            elseif (strpos($value, ',') !== false) {
                $value = str_replace(',', '.', $value);
            }
            $row['capaian_nilai'] = preg_replace('/[^0-9\.-]/', '', $value);
        }
        return $row;
    }

    public function collection(Collection $rows)
    {
        // ... rest of the code as before
        foreach ($rows as $row) {
            $indikator = IndikatorKinerja::where('kode', $row['kode_indikator'])->first();

            if ($indikator && $this->periode_id) {
                Monitoring::updateOrCreate(
                    [
                        'indikator_id' => $indikator->id,
                        'periode_id'   => $this->periode_id,
                    ],
                    [
                        'nilai_capaian' => (float) $row['capaian_nilai'], // Ensure correct field name
                        'analisis'      => $row['analisis'] ?? '-',
                        'kendala'       => $row['kendala'] ?? '-',
                        'tindakan'      => $row['tindakan'] ?? '-',
                        'tanggal_input' => now(),
                        'pelapor_id'    => auth()->id(),
                        'status'        => 'submitted'
                    ]
                );
            }
        }
    }

    public function rules(): array
    {
        return [
            'kode_indikator' => 'required|string|exists:indikator_kinerjas,kode',
            'capaian_nilai'  => 'required|numeric',
        ];
    }
}
