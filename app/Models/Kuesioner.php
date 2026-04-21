<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kuesioner extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke Periode
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    /**
     * Judul dengan placeholder yang diproses
     * Contoh: "Survei {periode}" -> "Survei Ganjil 2024"
     */
    public function getFullJudulAttribute(): string
    {
        return str_replace('{periode}', $this->periode->nama, $this->judul);
    }

    /**
     * Relasi ke Daftar Pertanyaan
     */
    public function pertanyaans(): HasMany
    {
        return $this->hasMany(KuesionerPertanyaan::class)->orderBy('urutan');
    }

    /**
     * Relasi ke Jawaban User
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(KuesionerJawaban::class);
    }

    /**
     * Cek apakah user sudah mengisi
     */
    public function isFilledBy($userId): bool
    {
        return $this->jawabans()->where('user_id', $userId)->exists();
    }

    /**
     * Hitung Nilai Rata-rata (Indeks Kepuasan)
     */
    public function calculateIndex(): float
    {
        // Ambil semua detail jawaban bertipe likert untuk kuesioner ini
        $totalSkor = KuesionerJawabanDetail::whereHas('jawaban', function($q) {
            $q->where('kuesioner_id', $this->id);
        })->whereHas('pertanyaan', function($q) {
            $q->where('tipe', 'likert');
        })->sum('skor');

        $totalResponder = KuesionerJawabanDetail::whereHas('jawaban', function($q) {
            $q->where('kuesioner_id', $this->id);
        })->whereHas('pertanyaan', function($q) {
            $q->where('tipe', 'likert');
        })->count();

        return $totalResponder > 0 ? round($totalSkor / $totalResponder, 2) : 0;
    }
}
