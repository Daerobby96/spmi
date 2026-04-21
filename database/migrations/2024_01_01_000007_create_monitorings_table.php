<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('unit_pengukuran');         // %, nilai, jumlah
            $table->decimal('target_nilai', 8, 2);
            $table->string('unit_kerja');
            $table->foreignId('standar_id')->nullable()->constrained('standars');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes');
            $table->foreignId('indikator_id')->constrained('indikator_kinerjas');
            $table->foreignId('pelapor_id')->constrained('users');
            $table->decimal('nilai_capaian', 8, 2);
            $table->date('tanggal_input');
            $table->text('keterangan')->nullable();
            $table->string('bukti_dokumen')->nullable();
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            $table->timestamps();
        });

        Schema::create('evaluasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained('monitorings')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users');
            $table->text('analisa');
            $table->text('rekomendasi')->nullable();
            $table->enum('hasil', ['tercapai', 'tidak_tercapai', 'perlu_perhatian']);
            $table->date('tanggal_evaluasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasis');
        Schema::dropIfExists('monitorings');
        Schema::dropIfExists('indikator_kinerjas');
    }
};
