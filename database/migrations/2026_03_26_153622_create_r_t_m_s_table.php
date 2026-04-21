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
        Schema::create('r_t_m_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained()->cascadeOnDelete();
            $table->string('judul_rapat');
            $table->date('tanggal_rapat');
            $table->text('agenda')->nullable();
            $table->text('notulensi')->nullable();
            $table->text('keputusan_manajemen')->nullable();
            $table->string('file_absensi')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_t_m_s');
    }
};
