<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTM extends Model
{
    protected $table = 'r_t_m_s';

    protected $fillable = [
        'periode_id', 'judul_rapat', 'tanggal_rapat', 'agenda',
        'input_audit_internal', 'input_umpan_balik', 'input_kinerja_proses',
        'input_status_tindakan', 'input_perubahan_sistem', 'input_rekomendasi',
        'notulensi', 'output_keefektifan', 'output_perbaikan', 'output_sumber_daya',
        'keputusan_manajemen', 'file_absensi', 'status',
    ];

    protected $casts = [
        'tanggal_rapat' => 'date',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
