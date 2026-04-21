<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $fillable = [
        'nama', 'tahun', 'semester',
        'tanggal_mulai', 'tanggal_selesai',
        'is_aktif', 'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif'        => 'boolean',
    ];

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public static function aktif(): ?self
    {
        return self::where('is_aktif', true)->first();
    }
}