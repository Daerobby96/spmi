<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin',  'display_name' => 'Super Admin',   'description' => 'Akses penuh ke semua fitur sistem'],
            ['name' => 'auditor',      'display_name' => 'Auditor',        'description' => 'Melaksanakan Audit Mutu Internal'],
            ['name' => 'auditee',      'display_name' => 'Auditee',        'description' => 'Unit yang diaudit, input tindak lanjut'],
            ['name' => 'pimpinan',     'display_name' => 'Pimpinan',       'description' => 'Akses dashboard dan laporan (read-only)'],
            ['name' => 'staf_dokumen', 'display_name' => 'Staf Dokumen',   'description' => 'Mengelola dokumen dan standar mutu'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}