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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, color, image, boolean
            $table->string('group')->default('general'); // general, theme, logo
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'SPMI', 'type' => 'text', 'group' => 'general', 'label' => 'Nama Aplikasi'],
            ['key' => 'app_tagline', 'value' => 'Sistem Penjaminan Mutu Internal', 'type' => 'text', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'theme_primary', 'value' => '#4e73df', 'type' => 'color', 'group' => 'theme', 'label' => 'Warna Utama'],
            ['key' => 'theme_sidebar', 'value' => 'dark', 'type' => 'text', 'group' => 'theme', 'label' => 'Tema Sidebar (dark/light)'],
            ['key' => 'logo', 'value' => null, 'type' => 'image', 'group' => 'logo', 'label' => 'Logo'],
            ['key' => 'favicon', 'value' => null, 'type' => 'image', 'group' => 'logo', 'label' => 'Favicon'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
