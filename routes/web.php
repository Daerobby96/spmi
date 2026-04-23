<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\StandarController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RtmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\IndikatorKinerjaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\DosenKinerjaController;
use App\Http\Controllers\UserKuesionerController;
use App\Http\Controllers\TracerStudyController;
use Illuminate\Support\Facades\Route;

// ─── Public Dashboard ──────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/documents', [PublicController::class, 'documents'])->name('home.documents');

// ─── Survey (Public Access) ──────────────────────────────────────
Route::get('/survei/aktif', [UserKuesionerController::class, 'activeSurvey'])->name('user-kuesioner.active');
Route::get('/survei', [UserKuesionerController::class, 'index'])->name('user-kuesioner.index');
Route::get('/survei/{kuesioner}', [UserKuesionerController::class, 'fill'])->name('user-kuesioner.fill');
Route::post('/survei/{kuesioner}', [UserKuesionerController::class, 'submit'])->name('user-kuesioner.submit');

// ─── Auth ──────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/set-periode', [DashboardController::class, 'setPeriode'])->name('set-periode');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');

    // ── Profile & Settings ───────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // ── Audit Mutu Internal ────────────────────────────────────────
    Route::middleware('role:super_admin,auditor')->group(function () {
        Route::post('audit/checklist-inline', [AuditController::class, 'updateChecklistInline'])->name('audit.checklist-inline');
        Route::resource('audit', AuditController::class);
        Route::post('audit/{audit}/generate-checklist', [AuditController::class, 'generateChecklist'])
            ->name('audit.generate-checklist');
        Route::put('audit/{audit}/checklist/{checklist}', [AuditController::class, 'updateChecklist'])
            ->name('audit.update-checklist');
        Route::resource('audit.temuan', TemuanController::class);
        Route::resource('rtm', RtmController::class);
        Route::get('rtm/{rtm}/pdf', [RtmController::class, 'exportPdf'])->name('rtm.pdf');
        Route::post('temuan/{temuan}/verifikasi', [TemuanController::class, 'verifikasi'])
            ->name('temuan.verifikasi');
    });

    // Tindak lanjut (auditee juga bisa input)
    Route::middleware('role:super_admin,auditor,auditee')->group(function () {
        Route::resource('tindak-lanjut', TindakLanjutController::class);
    });

    // ── Dokumen & Standar (Management) ──────────────────────────────
    Route::middleware('role:super_admin,staf_dokumen')->group(function () {
        Route::get('dokumen/template', [DokumenController::class, 'downloadTemplate'])->name('dokumen.template');
        Route::post('dokumen/import', [DokumenController::class, 'import'])->name('dokumen.import');
        Route::resource('dokumen', DokumenController::class)->except(['show'])->parameter('dokumen', 'dokumen');
        Route::get('standar/template', [StandarController::class, 'downloadTemplate'])->name('standar.template');
        Route::post('standar/import', [StandarController::class, 'import'])->name('standar.import');
        Route::resource('standar', StandarController::class);
        Route::resource('kategori-dokumen', KategoriController::class);
    });

    // ── Monitoring & Evaluasi ──────────────────────────────────────
    Route::middleware('role:super_admin,auditor,auditee')->group(function () {
        Route::post('monitoring/sync-siakad', [MonitoringController::class, 'syncSiakad'])->name('monitoring.sync-siakad');
        Route::get('monitoring/template', [MonitoringController::class, 'downloadTemplate'])->name('monitoring.template');
        Route::post('monitoring/import', [MonitoringController::class, 'import'])->name('monitoring.import');
        Route::post('monitoring/inline', [MonitoringController::class, 'updateInline'])->name('monitoring.update-inline');
        Route::resource('monitoring', MonitoringController::class);
    });
    Route::middleware('role:super_admin,auditor')->group(function () {
        Route::post('evaluasi/inline', [EvaluasiController::class, 'updateInline'])->name('evaluasi.update-inline');
        Route::resource('evaluasi', EvaluasiController::class);
    });
    Route::middleware('role:super_admin')->group(function () {
        Route::get('indikator-kinerja/template', [IndikatorKinerjaController::class, 'downloadTemplate'])->name('indikator-kinerja.template');
        Route::post('indikator-kinerja/import', [IndikatorKinerjaController::class, 'import'])->name('indikator-kinerja.import');
        Route::resource('indikator-kinerja', IndikatorKinerjaController::class);
    });

    // ── Kuesioner (Management) ──────────────────────────────────
    Route::middleware('role:super_admin,auditor')->group(function () {
        Route::get('kuesioner/template', [KuesionerController::class, 'downloadTemplate'])->name('kuesioner.template');
        Route::post('kuesioner/import-siakad', [KuesionerController::class, 'importSiakad'])->name('kuesioner.import-siakad');
        Route::post('kuesioner/{kuesioner}/import', [KuesionerController::class, 'import'])->name('kuesioner.import');
        Route::resource('kuesioner', KuesionerController::class);
        Route::post('kuesioner/{kuesioner}/add-question', [KuesionerController::class, 'addQuestion'])->name('kuesioner.add-question');
        Route::delete('kuesioner-pertanyaan/{pertanyaan}', [KuesionerController::class, 'deleteQuestion'])->name('kuesioner.delete-question');
        
        // ── Kinerja Dosen ──────────────────────────────────────────
        Route::get('kinerja-dosen', [DosenKinerjaController::class, 'index'])->name('kinerja-dosen.index');
        Route::post('kinerja-dosen/import-edom', [DosenKinerjaController::class, 'importEdom'])->name('kinerja-dosen.import-edom');
        Route::get('kinerja-dosen/{kinerja}', [DosenKinerjaController::class, 'show'])->name('kinerja-dosen.show');
        Route::get('kinerja-dosen/{kinerja}/export-pdf', [DosenKinerjaController::class, 'exportIndividualPdf'])->name('kinerja-dosen.export-pdf');

        // ── Tracer Study (Alumni) ──────────────────────────────────
        Route::get('tracer-study', [TracerStudyController::class, 'index'])->name('tracer-study.index');
        Route::get('tracer-study/template', [TracerStudyController::class, 'downloadTemplate'])->name('tracer-study.template');
        Route::post('tracer-study/import', [TracerStudyController::class, 'import'])->name('tracer-study.import');
        Route::post('tracer-study/sync-ppepp', [TracerStudyController::class, 'syncPpepp'])->name('tracer-study.sync-ppepp');
        Route::delete('tracer-study/{tracerStudy}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');
    });

    // ── Laporan ────────────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/',                            [LaporanController::class, 'index'])->name('index');
        Route::get('/audit',                       [LaporanController::class, 'audit'])->name('audit');
        Route::get('/dokumen',                     [LaporanController::class, 'dokumen'])->name('dokumen');
        Route::get('/monitoring',                  [LaporanController::class, 'monitoring'])->name('monitoring');
        Route::get('/export/pdf/{type}',           [LaporanController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel/{type}',         [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/audit/{audit}/pdf',    [LaporanController::class, 'exportAuditIndividual'])->name('export.audit.individual');
        Route::post('/audit/{audit}/update-ai-summary', [AuditController::class, 'updateAiSummary'])->name('audit.update-ai-summary');
    });

    // ── Manajemen User (Super Admin only) ─────────────────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
        Route::resource('periode', PeriodeController::class);
        Route::post('periode/{periode}/activate', [PeriodeController::class, 'activate'])
            ->name('periode.activate');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');
        
        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    // ── AI Smart Features ───────────────────────────────────────────
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('/analyze-root-cause', [App\Http\Controllers\AiController::class, 'analyzeRootCause'])->name('analyze-root-cause');
        Route::post('/suggest-recommendation', [App\Http\Controllers\AiController::class, 'suggestRecommendation'])->name('suggest-recommendation');
        Route::post('/summarize', [App\Http\Controllers\AiController::class, 'summarize'])->name('summarize');
        Route::post('/audit-summary', [App\Http\Controllers\AiController::class, 'generateAuditSummary'])->name('audit-summary');
    });
});

// Public Document Access (Placed after management to avoid conflict with /create)
Route::get('dokumen/{dokumen}', [DokumenController::class, 'show'])->name('dokumen.show');
Route::get('dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');