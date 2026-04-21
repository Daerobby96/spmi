<?php

namespace App\Services;

use App\Models\DosenKinerja;
use App\Models\Periode;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;

class EdomImportService
{
    public function importFromHtml($html)
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        
        // Find all "title" divs to split by lecturer pages
        $pages = $xpath->query("//div[@class='title' and contains(text(), 'Laporan EDOM')]");
        
        $importedCount = 0;

        foreach ($pages as $pageTitleNode) {
            // Get the container or follow the siblings to get metadata and tables
            $parent = $pageTitleNode->parentNode;
            
            // 1. Extract Metadata Table (Period, NIP, Dosen Name, etc)
            $metadataTable = $xpath->query("following-sibling::table[1]", $pageTitleNode)->item(0);
            if (!$metadataTable) continue;

            $metaData = $this->extractMetadata($xpath, $metadataTable);
            if (!$metaData['nip'] || !$metaData['nama']) continue;

            // 2. Get Periode
            $periode = $this->getOrCreatePeriode($metaData['periode_raw']);

            // 3. Extract Category Scores Table
            $categoryTable = $xpath->query("following-sibling::table[@class='tb_data border'][1]", $pageTitleNode)->item(0);
            $kategoriScores = $this->extractCategoryScores($xpath, $categoryTable);

            // 4. Extract Class Scores Table
            $classTable = $xpath->query("following-sibling::table[@class='tb_data border'][2]", $pageTitleNode)->item(0);
            $classScores = $this->extractClassScores($xpath, $classTable);
            
            // 5. Total Average
            $totalRerata = $this->extractTotalRerata($xpath, $classTable);

            // 6. Save to Database
            DosenKinerja::updateOrCreate(
                [
                    'periode_id' => $periode->id,
                    'dosen_nip' => $metaData['nip'],
                ],
                [
                    'dosen_name' => $metaData['nama'],
                    'homebase' => $metaData['homebase'],
                    'total_rerata' => $totalRerata,
                    'kategori_scores' => $kategoriScores,
                    'mata_kuliah_scores' => $classScores,
                ]
            );

            $importedCount++;
        }

        return $importedCount;
    }

    private function extractMetadata($xpath, $table)
    {
        $cells = $xpath->query(".//td", $table);
        $data = [
            'periode_raw' => '',
            'nip' => '',
            'homebase' => '',
            'nama' => ''
        ];

        foreach ($cells as $cell) {
            $text = trim($cell->textContent);
            if (str_starts_with($text, ':')) {
                $value = trim(substr($text, 1));
                // Previous cell tells us what this is
                $labelNode = $xpath->query("preceding-sibling::td[1]", $cell)->item(0);
                if (!$labelNode) continue;
                $label = strtolower(trim($labelNode->textContent));

                if (str_contains($label, 'periode')) $data['periode_raw'] = $value;
                if (str_contains($label, 'nip')) $data['nip'] = $value;
                if (str_contains($label, 'homebase')) $data['homebase'] = $value;
                if (str_contains($label, 'nama dosen')) $data['nama'] = $value;
            }
        }

        return $data;
    }

    private function extractCategoryScores($xpath, $table)
    {
        if (!$table) return [];
        $rows = $xpath->query(".//tr", $table);
        $scores = [];
        
        foreach ($rows as $row) {
            $cols = $xpath->query("td", $row);
            if ($cols->length >= 3) {
                $kategori = trim($cols->item(1)->textContent);
                $skor = trim($cols->item(2)->textContent);
                if (is_numeric(str_replace(',', '.', $skor))) {
                    $scores[] = [
                        'kategori' => $kategori,
                        'skor' => (float)str_replace(',', '.', $skor)
                    ];
                }
            }
        }
        return $scores;
    }

    private function extractClassScores($xpath, $table)
    {
        if (!$table) return [];
        $rows = $xpath->query(".//tr", $table);
        $scores = [];

        foreach ($rows as $row) {
            $cols = $xpath->query("td", $row);
            // MK rows usually have 9 columns
            if ($cols->length == 9) {
                $no = trim($cols->item(0)->textContent);
                if (is_numeric($no)) {
                    $scores[] = [
                        'kode' => trim($cols->item(1)->textContent),
                        'mk' => trim($cols->item(2)->textContent),
                        'kelas' => trim($cols->item(4)->textContent),
                        'prodi' => trim($cols->item(5)->textContent),
                        'responden' => trim($cols->item(6)->textContent),
                        'skor' => (float)str_replace(',', '.', trim($cols->item(8)->textContent))
                    ];
                }
            }
        }
        return $scores;
    }

    private function extractTotalRerata($xpath, $table)
    {
        if (!$table) return 0;
        $totalRow = $xpath->query(".//tr[last()-1]", $table)->item(0);
        if ($totalRow) {
            $val = trim($totalRow->textContent);
            preg_match('/(\d+,\d+)/', $val, $matches);
            if (isset($matches[1])) {
                return (float)str_replace(',', '.', $matches[1]);
            }
        }
        return 0;
    }

    private function getOrCreatePeriode($judul)
    {
        preg_match('/(\d{4})/', $judul, $matches);
        $tahun = $matches[1] ?? date('Y');
        $isGanjil = str_contains(strtolower($judul), 'ganjil');
        $semester = $isGanjil ? 'ganjil' : 'genap';

        // 1. Try to find existing period by year and semester
        $existing = Periode::where('tahun', $tahun)
            ->where('semester', $semester)
            ->first();

        if ($existing) return $existing;

        // 2. Create if not found
        $nama = ($isGanjil ? 'Ganjil ' : 'Genap ') . $tahun;
        return Periode::create([
            'nama' => $nama,
            'tahun' => $tahun,
            'semester' => $semester,
            'tanggal_mulai' => $isGanjil ? "$tahun-07-01" : "$tahun-01-01",
            'tanggal_selesai' => $isGanjil ? "$tahun-12-31" : "$tahun-06-30",
            'is_aktif' => false
        ]);
    }
}
