<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dosen_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained()->onDelete('cascade');
            $table->string('dosen_nip');
            $table->string('dosen_name');
            $table->string('homebase')->nullable();
            $table->decimal('total_rerata', 5, 2);
            $table->string('predikat')->nullable();
            $table->json('kategori_scores'); // [{kategori: '...', skor: 4.3}]
            $table->json('mata_kuliah_scores'); // [{kode: '...', mk: '...', kelas: '...', skor: 4.3}]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_kinerjas');
    }
};
