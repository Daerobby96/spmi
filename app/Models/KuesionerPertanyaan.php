<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KuesionerPertanyaan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kuesioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function detailJawabans(): HasMany
    {
        return $this->hasMany(KuesionerJawabanDetail::class, 'pertanyaan_id');
    }
}
