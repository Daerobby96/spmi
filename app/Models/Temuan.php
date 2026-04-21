<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temuan extends Model
{
    use SoftDeletes, \App\Traits\Loggable;

    protected $fillable = [
        'audit_id', 'audit_checklist_id', 'auditor_id', 'kode_temuan', 'kategori',
        'klausul_standar', 'uraian_temuan', 'bukti_objektif',
        'status', 'batas_tindak_lanjut', 'file_bukti',
    ];

    protected $casts = [
        'batas_tindak_lanjut' => 'date',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(AuditChecklist::class, 'audit_checklist_id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function tindakLanjuts(): HasMany
    {
        return $this->hasMany(TindakLanjut::class);
    }

    public function getKategoriBadgeAttribute(): string
    {
        return match ($this->kategori) {
            'KTS_Mayor'   => '<span class="badge bg-danger">KTS Mayor</span>',
            'KTS_Minor'   => '<span class="badge bg-warning text-dark">KTS Minor</span>',
            'OB'          => '<span class="badge bg-info text-dark">Observasi</span>',
            'Rekomendasi' => '<span class="badge bg-success">Rekomendasi</span>',
            default       => '<span class="badge bg-secondary">-</span>',
        };
    }

    public static function generateKode(): string
    {
        $tahun = now()->year;
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return sprintf('TMN/%d/%03d', $tahun, $count);
    }
}