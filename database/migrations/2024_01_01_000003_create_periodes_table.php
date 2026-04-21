<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');           // contoh: "Semester Ganjil 2024/2025"
            $table->year('tahun');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_aktif')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
