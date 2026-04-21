<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $roleName = str_replace(' ', '_', strtolower($row['role'] ?? Role::AUDITEE));
        $role = Role::where('name', $roleName)
                    ->orWhere('display_name', 'like', '%' . $row['role'] . '%')
                    ->first();

        return new User([
            'name'       => $row['nama'],
            'email'      => $row['email'],
            'nip'        => $row['nip'] ?? null,
            'unit_kerja' => $row['unit_kerja'] ?? '-',
            'jabatan'    => $row['jabatan'] ?? '-',
            'role_id'    => $role?->id ?? Role::where('name', Role::AUDITEE)->first()->id,
            'password'   => Hash::make($row['password'] ?? 'password123'),
            'is_active'  => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'required|string',
            'nip'        => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'jabatan'    => 'nullable|string',
        ];
    }
}
