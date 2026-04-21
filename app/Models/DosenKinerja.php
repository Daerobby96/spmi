<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenKinerja extends Model
{
    protected $guarded = [];

    protected $casts = [
        'kategori_scores' => 'array',
        'mata_kuliah_scores' => 'array',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
