<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakLanjut extends Model
{
    use \App\Traits\Loggable;

    protected $fillable = [
        'temuan_id', 'penanggung_jawab_id', 'analisa_penyebab',
        'rencana_tindakan', 'target_selesai', 'tanggal_realisasi',
        'bukti_tindakan', 'status', 'verifikasi_auditor',
        'hasil_verifikasi', 'verifikator_id', 'tanggal_verifikasi',
    ];

    protected $casts = [
        'target_selesai'     => 'date',
        'tanggal_realisasi'  => 'date',
        'tanggal_verifikasi' => 'date',
    ];

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(Temuan::class);
    }

    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}