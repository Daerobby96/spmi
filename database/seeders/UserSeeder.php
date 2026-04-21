<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole  = Role::where('name', 'super_admin')->first();
        $auditorRole     = Role::where('name', 'auditor')->first();
        $auditeeRole     = Role::where('name', 'auditee')->first();
        $pimpinanRole    = Role::where('name', 'pimpinan')->first();
        $stafDokumenRole = Role::where('name', 'staf_dokumen')->first();

        $users = [
            [
                'role_id'    => $superAdminRole->id,
                'name'       => 'Super Admin',
                'nip'        => '199001010001',
                'email'      => 'admin@spmi.ac.id',
                'unit_kerja' => 'Lembaga Penjaminan Mutu',
                'jabatan'    => 'Kepala LPM',
                'password'   => Hash::make('password'),
            ],
            [
                'role_id'    => $auditorRole->id,
                'name'       => 'Dr. Budi Santoso',
                'nip'        => '198501010002',
                'email'      => 'auditor@spmi.ac.id',
                'unit_kerja' => 'Lembaga Penjaminan Mutu',
                'jabatan'    => 'Auditor Internal',
                'password'   => Hash::make('password'),
            ],
            [
                'role_id'    => $auditeeRole->id,
                'name'       => 'Siti Rahayu, M.T.',
                'nip'        => '199201010003',
                'email'      => 'auditee@spmi.ac.id',
                'unit_kerja' => 'Prodi Teknik Informatika',
                'jabatan'    => 'Kaprodi',
                'password'   => Hash::make('password'),
            ],
            [
                'role_id'    => $pimpinanRole->id,
                'name'       => 'Prof. Dr. Ahmad Fauzi',
                'nip'        => '197001010004',
                'email'      => 'rektor@spmi.ac.id',
                'unit_kerja' => 'Rektorat',
                'jabatan'    => 'Rektor',
                'password'   => Hash::make('password'),
            ],
            [
                'role_id'    => $stafDokumenRole->id,
                'name'       => 'Dewi Lestari',
                'nip'        => '199501010005',
                'email'      => 'dokumen@spmi.ac.id',
                'unit_kerja' => 'Lembaga Penjaminan Mutu',
                'jabatan'    => 'Staf Dokumen',
                'password'   => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }
    }
}