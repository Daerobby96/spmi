<?php

namespace App\Services;

use App\Models\Kuesioner;
use App\Models\KuesionerPertanyaan;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerJawabanDetail;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiakadImportService
{
    public function importFromHtml($htmlContent)
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        // Force UTF-8 encoding
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent);
        $xpath = new \DOMXPath($dom);

        // 1. Extract Metadata
        $metadata = $this->extractMetadata($xpath);
        if (!$metadata['judul']) {
            throw new \Exception("Gagal menemukan judul kuesioner dalam file.");
        }

        // 2. Find or Create Periode
        $periode = $this->getOrCreatePeriode($metadata['judul']);

        return DB::transaction(function () use ($xpath, $metadata, $periode) {
            // 3. Create Kuesioner
            $kuesioner = Kuesioner::create([
                'judul' => $metadata['judul'],
                'periode_id' => $periode->id,
                'deskripsi' => "Import dari Siakad - Unit: " . ($metadata['unit'] ?? 'Semua'),
                'target_role' => 'mahasiswa', // Default for Siakad kuesioner layanan
                'status' => 'selesai',
            ]);

            // 4. Extract Questions and Results
            $rows = $xpath->query("//table[@class='border']/tbody/tr");
            $currentCategory = "Layanan";
            $respondentCount = intval($metadata['responden'] ?? 0);
            
            // Create dummy users to represent respondents if needed, 
            // but for aggregated data, we can just create 'N' anonymous answers.
            // To make it easy, we'll create one KuesionerJawaban per respondent.
            $jawabanIds = [];
            for ($i = 0; $i < $respondentCount; $i++) {
                $j = KuesionerJawaban::create([
                    'kuesioner_id' => $kuesioner->id,
                    'user_id' => null, // Anonymous/Dummy
                    'filled_at' => now(),
                ]);
                $jawabanIds[] = $j->id;
            }

            foreach ($rows as $row) {
                $cols = $xpath->query("td", $row);
                
                // Category row detection (Look for italic/bold or colspan=7)
                $firstCell = $cols->item(0);
                $isCategory = false;
                
                if ($cols->length == 1) {
                    $style = $row->getAttribute('style') . $firstCell->getAttribute('style');
                    if (str_contains($style, 'font-style:italic') || 
                        str_contains($style, 'font-weight:bold') || 
                        $firstCell->getAttribute('colspan') == '7') {
                        $isCategory = true;
                    }
                }

                if ($isCategory) {
                    $categoryText = trim($firstCell->textContent);
                    // Skip if it's just meta info or average rows
                    if ($categoryText && !is_numeric($categoryText) && 
                        !Str::contains($categoryText, ['Rerata', 'Kuesioner', 'Unit Kerja', 'Jumlah Responden'])) {
                        $currentCategory = $categoryText;
                    }
                    continue;
                }

                // Question row (first col should be a number)
                if ($cols->length >= 7) {
                    $noText = trim($cols->item(0)->textContent);
                    if (is_numeric($noText)) {
                        $pertanyaanText = trim($cols->item(1)->textContent);
                        
                        // Create Pertanyaan
                        $pertanyaan = $kuesioner->pertanyaans()->create([
                            'kategori' => $currentCategory,
                            'pertanyaan' => $pertanyaanText,
                            'tipe' => 'likert',
                            'urutan' => intval($noText)
                        ]);

                        // Scores distribution (index 2 to 6: STS, TS, N, S, SS)
                        $distributions = [];
                        for ($score = 1; $score <= 5; $score++) {
                            $cellText = $cols->item($score + 1)->textContent;
                            preg_match('/\((.*) Responden\)/', $cellText, $matches);
                            $count = isset($matches[1]) ? intval($matches[1]) : 0;
                            $distributions[$score] = $count;
                        }

                        // Fill Detail Jawabans
                        $currentRespondentIdx = 0;
                        foreach ($distributions as $score => $count) {
                            for ($k = 0; $k < $count; $k++) {
                                if (isset($jawabanIds[$currentRespondentIdx])) {
                                    KuesionerJawabanDetail::create([
                                        'jawaban_id' => $jawabanIds[$currentRespondentIdx],
                                        'pertanyaan_id' => $pertanyaan->id,
                                        'skor' => $score
                                    ]);
                                    $currentRespondentIdx++;
                                }
                            }
                        }
                    }
                }
            }

            return $kuesioner;
        });
    }

    private function extractMetadata($xpath)
    {
        $meta = [
            'judul' => '',
            'unit' => '',
            'responden' => 0
        ];

        $cells = $xpath->query("//table/tr/td");
        foreach ($cells as $i => $cell) {
            $text = trim($cell->textContent);
            if ($text == 'Kuesioner') {
                $meta['judul'] = trim($cells->item($i + 2)->textContent);
            } elseif ($text == 'Unit Kerja') {
                $meta['unit'] = trim($cells->item($i + 2)->textContent);
            } elseif ($text == 'Jumlah Responden') {
                $val = trim($cells->item($i + 2)->textContent);
                if (preg_match('/(\d+) dari/', $val, $matches)) {
                    $meta['responden'] = $matches[1];
                }
            }
        }

        return $meta;
    }

    private function getOrCreatePeriode($judul)
    {
        // Extract year from title (e.g. "2025 Ganjil")
        preg_match('/(\d{4})/', $judul, $matches);
        $tahun = $matches[1] ?? date('Y');
        $semester = str_contains(strtolower($judul), 'ganjil') ? 'ganjil' : 'genap';

        // 1. Try to find existing period by year and semester
        $existing = Periode::where('tahun', $tahun)
            ->where('semester', $semester)
            ->first();

        if ($existing) return $existing;

        // 2. Create if not found
        $nama = ($semester == 'ganjil' ? 'Ganjil ' : 'Genap ') . $tahun;
        $tanggalMulai = $semester == 'ganjil' ? "$tahun-07-01" : "$tahun-01-01";
        $tanggalSelesai = $semester == 'ganjil' ? "$tahun-12-31" : "$tahun-06-30";

        return Periode::create([
            'nama' => $nama,
            'tahun' => $tahun, 
            'semester' => $semester, 
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'is_aktif' => false
        ]);
    }
}
