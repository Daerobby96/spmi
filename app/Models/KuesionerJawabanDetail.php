<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuesionerJawabanDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jawaban(): BelongsTo
    {
        return $this->belongsTo(KuesionerJawaban::class, 'jawaban_id');
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(KuesionerPertanyaan::class, 'pertanyaan_id');
    }
}
