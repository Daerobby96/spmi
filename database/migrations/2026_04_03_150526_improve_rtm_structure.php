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
        Schema::table('r_t_m_s', function (Blueprint $table) {
            // Inputs (ISO/SPMI Standard)
            $table->text('input_audit_internal')->nullable()->after('agenda');
            $table->text('input_umpan_balik')->nullable()->after('input_audit_internal');
            $table->text('input_kinerja_proses')->nullable()->after('input_umpan_balik');
            $table->text('input_status_tindakan')->nullable()->after('input_kinerja_proses');
            $table->text('input_perubahan_sistem')->nullable()->after('input_status_tindakan');
            $table->text('input_rekomendasi')->nullable()->after('input_perubahan_sistem');
            
            // Outputs (Keputusan)
            $table->text('output_keefektifan')->nullable()->after('notulensi');
            $table->text('output_perbaikan')->nullable()->after('output_keefektifan');
            $table->text('output_sumber_daya')->nullable()->after('output_perbaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r_t_m_s', function (Blueprint $table) {
            $table->dropColumn([
                'input_audit_internal', 'input_umpan_balik', 'input_kinerja_proses',
                'input_status_tindakan', 'input_perubahan_sistem', 'input_rekomendasi',
                'output_keefektifan', 'output_perbaikan', 'output_sumber_daya'
            ]);
        });
    }
};
