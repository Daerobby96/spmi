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
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('prodi')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            
            // PDDIKTI Essential Fields
            $table->string('status_kerja')->nullable(); // Bekerja, Wirausaha, Lanjut Studi, dll
            $table->string('perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->integer('gaji')->nullable();
            $table->integer('waktu_tunggu_bulan')->nullable();
            $table->string('tingkat_instansi')->nullable(); // Lokal, Nasional, Internasional
            $table->string('keselarasan_horisontal')->nullable();
            $table->string('keselarasan_vertikal')->nullable();
            
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
