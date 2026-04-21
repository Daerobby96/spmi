<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes');
            $table->string('kode_audit')->unique();         // AMI/2024/001
            $table->string('nama_audit');
            $table->string('unit_yang_diaudit');
            $table->foreignId('ketua_auditor_id')->constrained('users');
            $table->date('tanggal_audit');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'aktif', 'selesai', 'ditutup'])->default('draft');
            $table->text('lingkup_audit')->nullable();
            $table->text('tujuan_audit')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel pivot auditor (bisa lebih dari 1 auditor per audit)
        Schema::create('audit_auditors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('peran')->default('anggota'); // ketua / anggota
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_auditors');
        Schema::dropIfExists('audits');
    }
};
