<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuesioners', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('status'); // Default publik agar tidak "ribet"
        });

        Schema::table('kuesioner_jawabans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change(); // User ID boleh kosong (anonim)
        });
    }

    public function down(): void
    {
        Schema::table('kuesioners', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });

        Schema::table('kuesioner_jawabans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
