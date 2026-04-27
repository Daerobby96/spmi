<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Monitoring extends Model
{
    protected $fillable = [
        'periode_id', 'indikator_id', 'pelapor_id',
        'nilai_capaian', 'tanggal_input', 'keterangan',
        'bukti_dokumen', 'status',
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'nilai_capaian' => 'decimal:2',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function evaluasi(): HasOne
    {
        return $this->hasOne(Evaluasi::class);
    }

    public function getPersentaseCapaianAttribute(): float
    {
        if (!$this->indikator || !$this->indikator->target_nilai) return 0;
        return round(($this->nilai_capaian / $this->indikator->target_nilai) * 100, 2);
    }

    public function getIsTercapaiAttribute(): bool
    {
        if (!$this->indikator) return false;
        
        $target = $this->indikator->target_nilai;
        $capaian = $this->nilai_capaian;
        
        // Cek jika indikator waktu tunggu (semakin kecil semakin baik)
        if (stripos($this->indikator->nama, 'waktu tunggu') !== false) {
            return $capaian <= $target;
        }
        
        // Untuk target lain, capaian harus lebih besar atau sama dengan target
        return $capaian >= $target;
    }

    public function getStatusKinerjaAttribute(): string
    {
        return $this->is_tercapai ? 'Tercapai' : 'Tidak Tercapai';
    }
}