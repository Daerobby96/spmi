<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KuesionerJawaban extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kuesioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(KuesionerJawabanDetail::class, 'jawaban_id');
    }
}
