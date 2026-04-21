<?php

namespace App\Observers;

use App\Models\Temuan;
use Illuminate\Support\Facades\Cache;

class TemuanObserver
{
    protected $aiService;

    public function __construct(\App\Services\AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Clear cache and trigger AI Analysis when Temuan is created.
     */
    public function created(Temuan $temuan): void
    {
        $this->clearCache();

        // Otomatisasi AI: Analisa Akar Masalah & Rekomendasi Tindakan
        // Hanya dilakukan jika uraian_temuan ada dan kategori bukan Rekomendasi (yang biasanya sudah berupa saran)
        if ($temuan->uraian_temuan && $temuan->kategori !== 'Rekomendasi') {
            
            // 1. Dapatkan Analisa Akar Masalah (Root Cause)
            $rootCauseResult = $this->aiService->analyzeRootCause($temuan->uraian_temuan);
            $analisa = $rootCauseResult['status'] === 'success' ? $rootCauseResult['data'] : 'AI gagal memberikan analisa.';

            // 2. Dapatkan Saran Tindakan Koreksi
            $actionResult = $this->aiService->suggestCorrectiveAction($temuan->uraian_temuan);
            $rencana = $actionResult['status'] === 'success' ? $actionResult['data'] : 'AI gagal memberikan saran.';

            // 3. Buat draf Tindak Lanjut otomatis agar Auditee punya titik awal
            // Kita cari user dari unit yang diaudit untuk jadi penanggung jawab draf
            $auditee = \App\Models\User::where('unit_kerja', $temuan->audit->unit_yang_diaudit)
                ->whereHas('role', fn($q) => $q->where('name', \App\Models\Role::AUDITEE))
                ->first();

            if ($auditee) {
                \App\Models\TindakLanjut::create([
                    'temuan_id'           => $temuan->id,
                    'penanggung_jawab_id' => $auditee->id,
                    'analisa_penyebab'    => "[AI SUGGESTION]\n" . $analisa,
                    'rencana_tindakan'    => "[AI SUGGESTION]\n" . $rencana,
                    'target_selesai'      => now()->addDays(14), // Default 2 minggu
                    'status'              => 'pending'
                ]);
            }
        }
    }

    /**
     * Clear cache when Temuan is updated.
     */
    public function updated(Temuan $temuan): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Temuan is deleted.
     */
    public function deleted(Temuan $temuan): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Temuan is restored.
     */
    public function restored(Temuan $temuan): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Temuan is force deleted.
     */
    public function forceDeleted(Temuan $temuan): void
    {
        $this->clearCache();
    }

    /**
     * Clear all temuan-related cache.
     */
    protected function clearCache(): void
    {
        Cache::forget('navbar_notifications');
        Cache::forget('sidebar_open_temuan');
    }
}
