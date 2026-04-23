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
            $deskripsi = "Import dari Siakad - Unit: " . ($metadata['unit'] ?? 'Semua');

            // Hapus SEMUA kuesioner lama jika sudah pernah diimport berkali-kali sebelumnya
            $existings = Kuesioner::where('judul', $metadata['judul'])
                ->where('periode_id', $periode->id)
                ->where('deskripsi', $deskripsi)
                ->get();
                
            foreach ($existings as $oldData) {
                $oldData->delete(); // Menghapus data lama secara cascade
            }

            // 3. Create Kuesioner Baru
            $kuesioner = Kuesioner::create([
                'judul' => $metadata['judul'],
                'periode_id' => $periode->id,
                'deskripsi' => $deskripsi,
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
            // ==========================================
            // AUTO-SCORING: Integrasi ke Indikator Kinerja
            // ==========================================
            // Hitung rata-rata keseluruhan (Grand Average)
            $totalSkor = \App\Models\KuesionerJawabanDetail::whereHas('pertanyaan', function($q) use ($kuesioner) {
                $q->where('kuesioner_id', $kuesioner->id)->where('tipe', 'likert');
            })->sum('skor');

            $totalJawaban = \App\Models\KuesionerJawabanDetail::whereHas('pertanyaan', function($q) use ($kuesioner) {
                $q->where('kuesioner_id', $kuesioner->id)->where('tipe', 'likert');
            })->count();

            $rataRata = $totalJawaban > 0 ? round($totalSkor / $totalJawaban, 2) : 0;

            if ($rataRata > 0) {
                // Cari Indikator Kepuasan Layanan / Mahasiswa yang aktif
                $indikator = \App\Models\IndikatorKinerja::where('is_aktif', true)
                    ->where(function($q) {
                        $q->where('nama', 'like', '%kepuasan%')
                          ->orWhere('nama', 'like', '%layanan%');
                    })
                    ->first();

                // Jika tidak ada, buatkan otomatis sebagai template dasar
                if (!$indikator) {
                    $standar = \App\Models\Standar::first();
                    if ($standar) {
                        $indikator = \App\Models\IndikatorKinerja::create([
                            'kode' => 'IKU-KPSN',
                            'nama' => 'Tingkat Kepuasan Layanan Mahasiswa',
                            'unit_pengukuran' => 'Skala 5.0', // Kuesioner Siakad 1-5
                            'target_nilai' => 3.50,
                            'unit_kerja' => 'Semua Unit',
                            'standar_id' => $standar->id,
                            'is_aktif' => true
                        ]);
                    }
                }

                if ($indikator) {
                    // Sinkronisasi otomatis ke tabel Monitoring IKU/IKT
                    \App\Models\Monitoring::updateOrCreate(
                        [
                            'periode_id' => $periode->id,
                            'indikator_id' => $indikator->id,
                        ],
                        [
                            'pelapor_id' => auth()->id() ?? \App\Models\User::first()->id ?? 1,
                            'nilai_capaian' => $rataRata,
                            'keterangan' => 'Auto-Scoring hasil import otomatis dari Kuesioner: ' . $metadata['judul'],
                            'tanggal_input' => now(),
                            'status' => 'verified' // Langsung terverifikasi karena dari sistem Siakad
                        ]
                    );
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
        // 1. Coba cari periode yang namanya disebutkan persis di dalam judul kuesioner
        // Contoh judul: "Laporan Kuesioner Kepuasan Mahasiswa Semester Genap 2025/2026"
        $periodes = Periode::all();
        foreach ($periodes as $p) {
            if (stripos($judul, $p->nama) !== false) {
                return $p;
            }
        }

        // 2. Coba tebak berdasarkan format tahun miring (2025/2026) dan kata Ganjil/Genap
        if (preg_match('/(\d{4})\/(\d{4})/', $judul, $matches)) {
            $tahunAwal = $matches[1];
            $tahunAkhir = $matches[2];
            $semester = str_contains(strtolower($judul), 'ganjil') ? 'ganjil' : 'genap';
            
            // Sesuai konvensi: Ganjil = tahun awal, Genap = tahun akhir
            $tahun = $semester == 'ganjil' ? $tahunAwal : $tahunAkhir;

            $existing = Periode::where('tahun', $tahun)
                ->where('semester', $semester)
                ->first();

            if ($existing) return $existing;
            
        } elseif (preg_match('/(\d{4})/', $judul, $matches)) {
            // 2b. Coba tebak dari 1 tahun tunggal (misal: "2024 Genap")
            // Di Siakad, "2024 Genap" biasanya merujuk pada Tahun Ajaran 2024/2025.
            // Sesuai konvensi database Anda, "Semester Genap 2024/2025" disimpan dengan Tahun = 2025.
            $tahunMasehi = intval($matches[1]);
            $semester = str_contains(strtolower($judul), 'ganjil') ? 'ganjil' : 'genap';
            
            // Jika Genap, tahun aktual di kalender biasanya bergeser +1 dari tahun awal ajaran
            $tahun = $semester == 'genap' ? ($tahunMasehi + 1) : $tahunMasehi;
            
            $existing = Periode::where('tahun', $tahun)
                ->where('semester', $semester)
                ->first();

            if ($existing) return $existing;
        }

        // 3. Fallback ke periode yang sedang aktif saat ini
        $activePeriode = Periode::where('is_aktif', true)->first();
        if ($activePeriode) {
            return $activePeriode;
        }

        // 4. Batalkan proses daripada membuat periode sampah yang merusak database
        throw new \Exception("Gagal menentukan Periode secara otomatis dari judul: '$judul'. Pastikan nama Periode di sistem sama dengan judul Siakad, atau aktifkan salah satu Periode di menu Manajemen Periode terlebih dahulu.");
    }
}
