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
        Schema::table('audits', function (Blueprint $table) {
            $table->dateTime('opening_meeting')->nullable()->after('tanggal_audit');
            $table->dateTime('closing_meeting')->nullable()->after('opening_meeting');
            $table->text('ai_summary')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn(['opening_meeting', 'closing_meeting', 'ai_summary']);
        });
    }
};
