<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->foreignId('auditor_id')->constrained('users');
            $table->string('kode_temuan')->unique();          // TMN/2024/001
            $table->enum('kategori', ['KTS_Mayor', 'KTS_Minor', 'OB', 'Rekomendasi']);
            // KTS_Mayor = Ketidaksesuaian Mayor
            // KTS_Minor = Ketidaksesuaian Minor
            // OB        = Observasi
            $table->string('klausul_standar')->nullable();    // Pasal/klausul terkait
            $table->text('uraian_temuan');
            $table->text('bukti_objektif')->nullable();
            $table->enum('status', ['open', 'in_progress', 'closed', 'verified'])->default('open');
            $table->date('batas_tindak_lanjut')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temuan_id')->constrained('temuans')->onDelete('cascade');
            $table->foreignId('penanggung_jawab_id')->constrained('users');
            $table->text('analisa_penyebab');
            $table->text('rencana_tindakan');
            $table->date('target_selesai');
            $table->date('tanggal_realisasi')->nullable();
            $table->text('bukti_tindakan')->nullable();     // path file
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->text('verifikasi_auditor')->nullable();
            $table->enum('hasil_verifikasi', ['diterima', 'ditolak'])->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->date('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
        Schema::dropIfExists('temuans');
    }
};
