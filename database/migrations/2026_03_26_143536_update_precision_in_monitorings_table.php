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
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->decimal('target_nilai', 16, 2)->change();
        });

        Schema::table('monitorings', function (Blueprint $table) {
            $table->decimal('nilai_capaian', 16, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->decimal('target_nilai', 8, 2)->change();
        });

        Schema::table('monitorings', function (Blueprint $table) {
            $table->decimal('nilai_capaian', 8, 2)->change();
        });
    }
};
