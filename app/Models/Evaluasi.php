<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluasi extends Model
{
    protected $fillable = [
        'monitoring_id', 'evaluator_id', 'analisa',
        'rekomendasi', 'hasil', 'tanggal_evaluasi',
    ];

    protected $casts = [
        'tanggal_evaluasi' => 'date',
    ];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}