<?php

namespace App\Providers;

use App\Models\Dokumen;
use App\Models\Periode;
use App\Models\Setting;
use App\Models\Temuan;
use App\Observers\DokumenObserver;
use App\Observers\PeriodeObserver;
use App\Observers\TemuanObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();

        // Register Observers for cache invalidation
        Temuan::observe(TemuanObserver::class);
        Dokumen::observe(DokumenObserver::class);
        Periode::observe(PeriodeObserver::class);

        // View Composer untuk layout App & Public (settings & theme)
        View::composer(['layouts.app', 'layouts.public', 'auth.login'], function ($view) {
            $appSettings = Cache::remember('app_settings', 3600, function () {
                return [
                    'appName' => Setting::get('app_name', 'SPMI'),
                    'themePrimary' => Setting::get('theme_primary', '#2563eb'),
                    'themeSidebar' => Setting::get('theme_sidebar', 'dark'),
                    'logo' => Setting::get('logo'),
                    'favicon' => Setting::get('favicon'),
                ];
            });
            $view->with('appSettings', $appSettings);
        });

        // View Composer untuk Sidebar
        View::composer('layouts.sidebar', function ($view) {
            $user = auth()->user();
            
            // Cache settings untuk sidebar
            $sidebarSettings = Cache::remember('sidebar_settings', 3600, function () {
                return [
                    'appName' => Setting::get('app_name', 'SPMI'),
                    'appTagline' => Setting::get('app_tagline', 'Penjaminan Mutu'),
                    'logo' => Setting::get('logo'),
                ];
            });
            
            // Cache periode data
            $periodeData = Cache::remember('sidebar_periode', 300, function () {
                return [
                    'aktif' => Periode::aktif(),
                    'all' => Periode::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get(),
                ];
            });
            
            // Cache jumlah temuan open (hanya jika user login)
            $openTemuanCount = 0;
            if ($user) {
                $openTemuanCount = Cache::remember('sidebar_open_temuan', 60, function () {
                    return Temuan::where('status', 'open')->count();
                });
            }
            
            $view->with([
                'sidebarSettings' => $sidebarSettings,
                'periodeData' => $periodeData,
                'openTemuanCount' => $openTemuanCount,
            ]);
        });

        // View Composer untuk Navbar
        View::composer('layouts.navbar', function ($view) {
            $user = auth()->user();
            if (!$user) {
                $view->with('notifications', [
                    'deadlineTemuan' => 0,
                    'overdueTemuan' => 0,
                    'pendingDokumen' => 0,
                    'dbNotifications' => collect(),
                    'total' => 0,
                ]);
                return;
            }

            // Cache per user (menggunakan ID user di kunci cache)
            $notifications = Cache::remember("navbar_notifications_{$user->id}", 60, function () use ($user) {
                $unit = $user->unit_kerja;
                
                // Filter berdasarkan unit jika auditee
                $queryTemuan = Temuan::where('status', 'open');
                if ($user->isAuditee()) {
                    $queryTemuan->whereHas('audit', fn($q) => $q->where('unit_yang_diaudit', $unit));
                }

                $deadlineTemuan = (clone $queryTemuan)->where('batas_tindak_lanjut', '<=', now()->addDays(7))
                    ->where('batas_tindak_lanjut', '>=', now())
                    ->count();
                $overdueTemuan = (clone $queryTemuan)->where('batas_tindak_lanjut', '<', now())
                    ->count();
                
                $pendingDokumen = 0;
                if ($user->canManageDokumen()) {
                    $pendingDokumen = Dokumen::where('status', 'review')->count();
                }
                
                // Get real database notifications
                $dbNotifications = $user->unreadNotifications()->latest()->limit(5)->get();
                
                return [
                    'deadlineTemuan'  => $deadlineTemuan,
                    'overdueTemuan'   => $overdueTemuan,
                    'pendingDokumen'  => $pendingDokumen,
                    'dbNotifications' => $dbNotifications,
                    'total'           => $deadlineTemuan + $overdueTemuan + $pendingDokumen + $user->unreadNotifications()->count(),
                ];
            });
            
            $view->with('notifications', $notifications);
        });
    }
}
