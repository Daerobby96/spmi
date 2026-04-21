<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriDokumen extends Model
{
    protected $fillable = ['nama', 'kode', 'warna', 'keterangan'];

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'kategori_id');
    }
}