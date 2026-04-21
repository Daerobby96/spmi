<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete unused settings (now using users table instead)
        $keys = [
            'kepala_institusi',
            'nip_kepala_institusi',
            'jabatan_kepala_institusi',
            'kepala_spmi',
            'nip_kepala_spmi',
            'jabatan_kepala_spmi',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-insert the settings if needed
        $settings = [
            ['key' => 'kepala_institusi', 'value' => 'Nama Kepala Institusi', 'type' => 'text', 'group' => 'institusi', 'label' => 'Nama Kepala/Rektor'],
            ['key' => 'nip_kepala_institusi', 'value' => '-', 'type' => 'text', 'group' => 'institusi', 'label' => 'NIP Kepala Institusi'],
            ['key' => 'jabatan_kepala_institusi', 'value' => 'Kepala Perguruan Tinggi', 'type' => 'text', 'group' => 'institusi', 'label' => 'Jabatan Kepala Institusi'],
            ['key' => 'kepala_spmi', 'value' => 'Nama Ketua SPMI', 'type' => 'text', 'group' => 'institusi', 'label' => 'Nama Ketua SPMI'],
            ['key' => 'nip_kepala_spmi', 'value' => '-', 'type' => 'text', 'group' => 'institusi', 'label' => 'NIP Ketua SPMI'],
            ['key' => 'jabatan_kepala_spmi', 'value' => 'Ketua SPMI', 'type' => 'text', 'group' => 'institusi', 'label' => 'Jabatan Ketua SPMI'],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }
};
