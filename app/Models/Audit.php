<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use SoftDeletes, \App\Traits\Loggable;

    protected $fillable = [
        'periode_id', 'kode_audit', 'nama_audit', 'unit_yang_diaudit',
        'ketua_auditor_id', 'tanggal_audit', 'tanggal_selesai',
        'status', 'lingkup_audit', 'tujuan_audit', 'catatan',
        'opening_meeting', 'closing_meeting', 'ai_summary',
    ];

    protected $casts = [
        'tanggal_audit'     => 'date',
        'tanggal_selesai'   => 'date',
        'opening_meeting'   => 'datetime',
        'closing_meeting'   => 'datetime',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function ketuaAuditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_auditor_id');
    }

    public function auditors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'audit_auditors')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    public function temuans(): HasMany
    {
        return $this->hasMany(Temuan::class);
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(AuditChecklist::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'   => '<span class="badge bg-secondary">Draft</span>',
            'aktif'   => '<span class="badge bg-primary">Aktif</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditutup' => '<span class="badge bg-dark">Ditutup</span>',
            default   => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public static function generateKode(): string
    {
        $tahun = now()->year;
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return sprintf('AMI/%d/%03d', $tahun, $count);
    }
}