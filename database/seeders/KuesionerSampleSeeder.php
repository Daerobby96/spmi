<?php

namespace Database\Seeders;

use App\Models\Kuesioner;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerJawabanDetail;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;

class KuesionerSampleSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::where('is_aktif', true)->first() ?? Periode::first();
        
        if (!$periode) {
            $periode = Periode::create([
                'nama' => 'Ganjil 2024/2025',
                'tahun' => 2024,
                'semester' => 'ganjil',
                'is_aktif' => true
            ]);
        }

        // 1. Create Questionnaire
        $kuesioner = Kuesioner::create([
            'judul' => 'Survei Kepuasan Pemangku Kepentingan 2024',
            'deskripsi' => 'Survei ini bertujuan untuk mengukur tingkat kepuasan civitas akademika terhadap implementasi Sistem Penjaminan Mutu Internal (SPMI) di institusi kita.',
            'periode_id' => $periode->id,
            'target_role' => 'all',
            'status' => 'aktif',
            'is_public' => true
        ]);

        // 2. Add Questions
        $questions = [
            ['pertanyaan' => 'Sejauh mana Anda puas dengan kemudahan akses dokumen mutu di sistem ini?', 'tipe' => 'likert'],
            ['pertanyaan' => 'Apakah sosialisasi mengenai standar mutu institusi sudah dilakukan secara merata?', 'tipe' => 'likert'],
            ['pertanyaan' => 'Bagaimana efektivitas koordinasi antar unit dalam penjaminan mutu?', 'tipe' => 'likert'],
            ['pertanyaan' => 'Sejauh mana efektivitas Audit Mutu Internal (AMI) dalam mendorong perbaikan unit kerja?', 'tipe' => 'likert'],
            ['pertanyaan' => 'Berikan saran atau masukan Anda untuk peningkatan sistem penjaminan mutu ke depan.', 'tipe' => 'text'],
        ];

        foreach ($questions as $index => $q) {
            $kuesioner->pertanyaans()->create([
                'pertanyaan' => $q['pertanyaan'],
                'tipe' => $q['tipe'],
                'urutan' => $index + 1
            ]);
        }

        // 3. Generate Fake Responses (Optional but helpful for testing)
        $users = User::take(5)->get();
        foreach ($users as $user) {
            $jawaban = KuesionerJawaban::create([
                'kuesioner_id' => $kuesioner->id,
                'user_id' => $user->id,
                'filled_at' => now()
            ]);

            foreach ($kuesioner->pertanyaans as $p) {
                KuesionerJawabanDetail::create([
                    'jawaban_id' => $jawaban->id,
                    'pertanyaan_id' => $p->id,
                    'skor' => $p->tipe == 'likert' ? rand(3, 5) : null,
                    'jawaban_text' => $p->tipe == 'text' ? 'Contoh saran perbaikan dari user ' . $user->name : null,
                ]);
            }
        }
    }
}
