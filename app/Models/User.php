<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, \App\Traits\Loggable;

    protected $fillable = [
        'role_id', 'name', 'nip', 'email', 'unit_kerja',
        'jabatan', 'no_hp', 'foto', 'is_active', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'ketua_auditor_id');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'pembuat_id');
    }

    // ─── Role Helpers ──────────────────────────────────────────────
    public function hasRole(string $role): bool
    {
        return $this->role->name === $role;
    }

    public function isSuperAdmin(): bool  { return $this->hasRole(Role::SUPER_ADMIN); }
    public function isAuditor(): bool     { return $this->hasRole(Role::AUDITOR); }
    public function isAuditee(): bool     { return $this->hasRole(Role::AUDITEE); }
    public function isPimpinan(): bool    { return $this->hasRole(Role::PIMPINAN); }
    public function isStafDokumen(): bool { return $this->hasRole(Role::STAF_DOKUMEN); }

    public function canManageAudit(): bool
    {
        return in_array($this->role->name, [Role::SUPER_ADMIN, Role::AUDITOR]);
    }

    public function canManageDokumen(): bool
    {
        return in_array($this->role->name, [Role::SUPER_ADMIN, Role::STAF_DOKUMEN]);
    }

    // ─── Accessor ──────────────────────────────────────────────────
    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }
}