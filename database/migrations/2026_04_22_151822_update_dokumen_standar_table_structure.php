<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Recreate the table with correct columns if it exists, or just ensure columns exist
        Schema::dropIfExists('dokumen_standar');
        
        Schema::create('dokumen_standar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->onDelete('cascade');
            $table->foreignId('standar_id')->constrained('standars')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing data
        $existingDokumens = DB::table('dokumens')->whereNotNull('standar_id')->get();
        foreach ($existingDokumens as $dokumen) {
            DB::table('dokumen_standar')->insert([
                'dokumen_id' => $dokumen->id,
                'standar_id' => $dokumen->standar_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_standar');
    }
};
