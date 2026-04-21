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
        Schema::create('audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->foreignId('indikator_id')->nullable()->constrained('indikator_kinerjas')->onDelete('set null');
            $table->text('pertanyaan');
            $table->enum('status', ['sesuai', 'tidak_sesuai', 'observasi', 'tidak_terkait', 'belum_diisi'])->default('belum_diisi');
            $table->text('catatan')->nullable();
            $table->text('bukti_objektif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_checklists');
    }
};
