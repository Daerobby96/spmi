<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Standar extends Model
{
    use \App\Traits\Loggable;

    protected $fillable = ['kode', 'nama', 'deskripsi', 'is_aktif'];

    protected $casts = ['is_aktif' => 'boolean'];

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    public function dokumens_many(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Dokumen::class, 'dokumen_standar');
    }

    public function indikators(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class);
    }
}