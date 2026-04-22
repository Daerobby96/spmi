<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_id', 'pembuat_id', 'kode_dokumen',
        'judul', 'unit_pemilik', 'versi', 'tanggal_terbit',
        'tanggal_kadaluarsa', 'file_path', 'file_size', 'file_type',
        'status', 'keterangan', 'download_count',
    ];

    protected $casts = [
        'tanggal_terbit'      => 'date',
        'tanggal_kadaluarsa'  => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriDokumen::class);
    }

    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class);
    }

    public function standars(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Standar::class, 'dokumen_standar');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '-';
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow   = floor(log($this->file_size) / log(1024));
        return round($this->file_size / pow(1024, $pow), 2) . ' ' . $units[$pow];
    }
}