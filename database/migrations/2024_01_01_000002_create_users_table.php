<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->string('name');
            $table->string('nip')->unique()->nullable();        // Nomor Induk Pegawai
            $table->string('email')->unique();
            $table->string('unit_kerja')->nullable();           // Prodi / Fakultas / Unit
            $table->string('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
