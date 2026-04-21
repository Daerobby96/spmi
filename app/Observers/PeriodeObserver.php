<?php

namespace App\Observers;

use App\Models\Periode;
use Illuminate\Support\Facades\Cache;

class PeriodeObserver
{
    /**
     * Clear cache when Periode is created.
     */
    public function created(Periode $periode): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Periode is updated.
     */
    public function updated(Periode $periode): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Periode is deleted.
     */
    public function deleted(Periode $periode): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Periode is restored.
     */
    public function restored(Periode $periode): void
    {
        $this->clearCache();
    }

    /**
     * Clear cache when Periode is force deleted.
     */
    public function forceDeleted(Periode $periode): void
    {
        $this->clearCache();
    }

    /**
     * Clear all periode-related cache.
     */
    protected function clearCache(): void
    {
        Cache::forget('sidebar_periode');
    }
}
