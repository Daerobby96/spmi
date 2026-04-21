<?php

namespace App\Observers;

use App\Models\Dokumen;
use Illuminate\Support\Facades\Cache;

class DokumenObserver
{
    /**
     * Clear cache when Dokumen is created.
     */
    public function created(Dokumen $dokumen): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Dokumen is updated.
     */
    public function updated(Dokumen $dokumen): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Dokumen is deleted.
     */
    public function deleted(Dokumen $dokumen): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Dokumen is restored.
     */
    public function restored(Dokumen $dokumen): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Dokumen is force deleted.
     */
    public function forceDeleted(Dokumen $dokumen): void
    {
        $this->clearCache();
    }

    /**
     * Clear all dokumen-related cache.
     */
    protected function clearCache(): void
    {
        Cache::forget('navbar_notifications');
    }
}
