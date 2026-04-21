<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();      // SOP, SK, PM, IK, FR
            $table->string('warna')->nullable();   // untuk badge UI
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('standars', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();      // SNDikti, ISO9001, dll
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_dokumens');
            $table->foreignId('standar_id')->nullable()->constrained('standars');
            $table->foreignId('pembuat_id')->constrained('users');
            $table->string('kode_dokumen')->unique();     // SOP/HRD/001
            $table->string('judul');
            $table->string('unit_pemilik');
            $table->string('versi')->default('1.0');
            $table->date('tanggal_terbit');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->enum('status', ['draft', 'review', 'approved', 'obsolete'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
        Schema::dropIfExists('standars');
        Schema::dropIfExists('kategori_dokumens');
    }
};
