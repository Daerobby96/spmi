<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kuesioner (Main Header)
        Schema::create('kuesioners', function (Blueprint $blade) {
            $blade->id();
            $blade->string('judul');
            $blade->text('deskripsi')->nullable();
            $blade->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $blade->string('target_role')->nullable(); // misal: 'mahasiswa', 'dosen', 'all'
            $blade->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $blade->timestamps();
        });

        // 2. Pertanyaan
        Schema::create('kuesioner_pertanyaans', function (Blueprint $blade) {
            $blade->id();
            $blade->foreignId('kuesioner_id')->constrained('kuesioners')->onDelete('cascade');
            $blade->text('pertanyaan');
            $blade->enum('tipe', ['likert', 'text'])->default('likert'); // likert = 1-4/5, text = esai
            $blade->integer('urutan')->default(0);
            $blade->timestamps();
        });

        // 3. Jawaban Header (Satu kali pengisian per user)
        Schema::create('kuesioner_jawabans', function (Blueprint $blade) {
            $blade->id();
            $blade->foreignId('kuesioner_id')->constrained('kuesioners')->onDelete('cascade');
            $blade->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blade->timestamp('filled_at')->useCurrent();
            $blade->timestamps();
            
            $blade->unique(['kuesioner_id', 'user_id']); // User hanya bisa mengisi 1 kali per kuesioner
        });

        // 4. Jawaban Detail
        Schema::create('kuesioner_jawaban_details', function (Blueprint $blade) {
            $blade->id();
            $blade->foreignId('jawaban_id')->constrained('kuesioner_jawabans')->onDelete('cascade');
            $blade->foreignId('pertanyaan_id')->constrained('kuesioner_pertanyaans')->onDelete('cascade');
            $blade->integer('skor')->nullable(); // Untuk tipe likert (1-4)
            $blade->text('jawaban_text')->nullable(); // Untuk tipe esai
            $blade->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioner_jawaban_details');
        Schema::dropIfExists('kuesioner_jawabans');
        Schema::dropIfExists('kuesioner_pertanyaans');
        Schema::dropIfExists('kuesioners');
    }
};
