<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndikatorKinerja extends Model
{
    protected $fillable = [
        'kode', 'nama', 'unit_pengukuran', 'target_deskripsi',
        'target_nilai', 'unit_kerja', 'standar_id', 'is_aktif',
    ];

    protected $casts = [
        'target_nilai' => 'decimal:2',
        'is_aktif'     => 'boolean',
    ];

    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'indikator_id');
    }
}