<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditChecklist extends Model
{
    protected $fillable = [
        'audit_id', 'indikator_id', 'pertanyaan',
        'status', 'catatan', 'bukti_objektif',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class, 'indikator_id');
    }

    public function temuans(): HasMany
    {
        return $this->hasMany(Temuan::class, 'audit_checklist_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'sesuai'        => '<span class="badge bg-success">Sesuai</span>',
            'tidak_sesuai'  => '<span class="badge bg-danger">KTS</span>',
            'observasi'     => '<span class="badge bg-info">OB</span>',
            'tidak_terkait' => '<span class="badge bg-secondary">N/A</span>',
            default         => '<span class="badge bg-light text-dark">Belum Diisi</span>',
        };
    }
}
