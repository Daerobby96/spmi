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
            $table->text('target_deskripsi')->nullable()->after('unit_pengukuran');
            $table->decimal('target_nilai', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->dropColumn('target_deskripsi');
            $table->decimal('target_nilai', 8, 2)->nullable(false)->change();
        });
    }
};
