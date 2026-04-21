<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert settings for laporan audit
        $settings = [
            ['key' => 'nama_institusi', 'value' => 'NAMA PERGURUAN TINGGI', 'type' => 'text', 'group' => 'institusi', 'label' => 'Nama Institusi'],
            ['key' => 'alamat_institusi', 'value' => 'Alamat Lengkap Institusi', 'type' => 'text', 'group' => 'institusi', 'label' => 'Alamat Institusi'],
            ['key' => 'kota_institusi', 'value' => 'Kota', 'type' => 'text', 'group' => 'institusi', 'label' => 'Kota Institusi'],
            ['key' => 'logo_institusi', 'value' => null, 'type' => 'image', 'group' => 'institusi', 'label' => 'Logo Institusi'],
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete the settings
        $keys = [
            'nama_institusi',
            'alamat_institusi',
            'kota_institusi',
            'logo_institusi',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
