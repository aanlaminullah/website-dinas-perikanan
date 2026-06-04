<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublikasiDataController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\LensaKegiatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublikasiDokumenController;
use App\Http\Controllers\BeritaController;


// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [LandingController::class, 'index'])->name('landing');


Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index')->middleware('modul:modul_pengumuman');
Route::get('/pengumuman/{id}', [AnnouncementController::class, 'show'])->name('announcements.show')->middleware('modul:modul_pengumuman');
Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi.index')->middleware('modul:modul_struktur_organisasi');
Route::get('/visi-misi', [VisiMisiController::class, 'index'])->name('visi-misi.index')->middleware('modul:modul_visi_misi');
Route::get('/publikasi-data', [PublikasiDataController::class, 'index'])->name('publikasi-data.index')->middleware('modul:modul_publikasi_data');
Route::get('/lensa-kegiatan', [LensaKegiatanController::class, 'index'])->name('lensa-kegiatan.index');
Route::get('/publikasi-dokumen', [PublikasiDokumenController::class, 'index'])->name('publikasi-dokumen.index')->middleware('modul:modul_publikasi_dokumen');
Route::get('/publikasi-dokumen/{publikasiDokumen:slug}/download', [PublikasiDokumenController::class, 'download'])->name('publikasi-dokumen.download')->middleware('modul:modul_publikasi_dokumen');
Route::post('/publikasi-dokumen/{publikasiDokumen:slug}/share', [PublikasiDokumenController::class, 'share'])->name('publikasi-dokumen.share')->middleware('modul:modul_publikasi_dokumen');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index')->middleware('modul:modul_berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show')->middleware('modul:modul_berita');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('modul:modul_publikasi_data')->group(function () {
        Route::get('publikasi-data',                            [PublikasiDataController::class, 'adminIndex'])->name('publikasi-data.index');
        Route::get('publikasi-data/create',                     [PublikasiDataController::class, 'create'])->name('publikasi-data.create');
        Route::post('publikasi-data',                           [PublikasiDataController::class, 'store'])->name('publikasi-data.store');
        Route::get('publikasi-data/{produksiBudidaya}/edit',    [PublikasiDataController::class, 'edit'])->name('publikasi-data.edit');
        Route::put('publikasi-data/{produksiBudidaya}',         [PublikasiDataController::class, 'update'])->name('publikasi-data.update');
        Route::delete('publikasi-data/{produksiBudidaya}',      [PublikasiDataController::class, 'destroy'])->name('publikasi-data.destroy');
        Route::get('publikasi-data/import',                     [PublikasiDataController::class, 'importForm'])->name('publikasi-data.import.form');
        Route::post('publikasi-data/import',                    [PublikasiDataController::class, 'importProcess'])->name('publikasi-data.import.process');
        Route::get('publikasi-data/template',                   [PublikasiDataController::class, 'downloadTemplate'])->name('publikasi-data.template');
    });

    Route::middleware('modul:modul_pengumuman')->group(function () {
        Route::get('announcements',                     [AnnouncementController::class, 'adminIndex'])->name('announcements.index');
        Route::get('announcements/create',              [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements',                    [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}',      [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}/remove-attachment', [AnnouncementController::class, 'removeAttachment'])->name('announcements.remove-attachment');
        Route::delete('announcements/{announcement}',   [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    Route::middleware('modul:modul_struktur_organisasi')->group(function () {
        Route::resource('pejabat', \App\Http\Controllers\Admin\PejabatController::class)->names('pejabat');
    });

    Route::middleware('modul:modul_visi_misi')->group(function () {
        Route::get('visi-misi',         [\App\Http\Controllers\Admin\VisiMisiController::class, 'index'])->name('visi-misi.index');
        Route::get('visi-misi/edit',    [\App\Http\Controllers\Admin\VisiMisiController::class, 'edit'])->name('visi-misi.edit');
        Route::put('visi-misi',         [\App\Http\Controllers\Admin\VisiMisiController::class, 'update'])->name('visi-misi.update');
    });

    Route::resource('lensa-kegiatan', \App\Http\Controllers\Admin\LensaKegiatanController::class)
        ->names('lensa-kegiatan');

    Route::get('publikasi-dokumen',                                     [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'index'])->name('publikasi-dokumen.index');
    Route::get('publikasi-dokumen/create',                              [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'create'])->name('publikasi-dokumen.create');
    Route::post('publikasi-dokumen',                                    [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'store'])->name('publikasi-dokumen.store');
    Route::get('publikasi-dokumen/{publikasiDokumen}/edit',             [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'edit'])->name('publikasi-dokumen.edit');
    Route::put('publikasi-dokumen/{publikasiDokumen}',                  [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'update'])->name('publikasi-dokumen.update');
    Route::delete('publikasi-dokumen/{publikasiDokumen}',               [\App\Http\Controllers\Admin\PublikasiDokumenController::class, 'destroy'])->name('publikasi-dokumen.destroy');

    Route::post('instansi-terkait/reorder', [\App\Http\Controllers\Admin\InstansiTerkaitController::class, 'reorder'])->name('instansi-terkait.reorder');
    Route::resource('instansi-terkait', \App\Http\Controllers\Admin\InstansiTerkaitController::class)->names('instansi-terkait');

    Route::post('carousel/reorder', [\App\Http\Controllers\Admin\CarouselController::class, 'reorder'])->name('carousel.reorder');
    Route::resource('carousel', \App\Http\Controllers\Admin\CarouselController::class)->names('carousel');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('settings',  [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('settings.index');
        Route::put('settings',  [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('users');
    });
});

// SSO Routes
Route::get('/auth/sso', [SsoController::class, 'redirect'])->name('sso.redirect')->middleware('guest');
Route::get('/auth/callback', [SsoController::class, 'callback'])->name('sso.callback');
Route::post('/auth/sso/logout', [SsoController::class, 'logout'])->name('sso.logout')->middleware('auth');
Route::post('/auth/backchannel-logout', [SsoController::class, 'backchannelLogout'])->name('sso.backchannel-logout');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [SsoController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Debug route — HAPUS setelah masalah selesai
Route::get('/debug/session', function (\Illuminate\Http\Request $request) {
    $testKey = 'debug_test_' . time();
    $request->session()->put($testKey, 'works');

    return response()->json([
        'timestamp' => now()->toDateTimeString(),
        'session' => [
            'driver' => config('session.driver'),
            'id' => $request->session()->getId(),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'same_site' => config('session.same_site'),
            'path' => config('session.path'),
            'cookie_name' => config('session.cookie'),
            'test_key_written' => $testKey,
            'all_keys' => array_keys($request->session()->all()),
        ],
        'request' => [
            'is_secure' => $request->isSecure(),
            'scheme' => $request->getScheme(),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
            'host' => $request->getHost(),
            'ip' => $request->ip(),
        ],
        'headers' => [
            'x-forwarded-proto' => $request->header('X-Forwarded-Proto'),
            'x-forwarded-for' => $request->header('X-Forwarded-For'),
            'x-forwarded-host' => $request->header('X-Forwarded-Host'),
            'host' => $request->header('Host'),
        ],
        'app' => [
            'url' => config('app.url'),
            'env' => config('app.env'),
            'debug' => config('app.debug'),
        ],
        'cookies_received' => array_keys($request->cookies->all()),
    ]);
});

// Debug route 2 — cek apakah session persist antara request
Route::get('/debug/session-check', function (\Illuminate\Http\Request $request) {
    $allKeys = array_keys($request->session()->all());
    $debugKeys = array_filter($allKeys, fn($k) => str_starts_with($k, 'debug_test_'));

    return response()->json([
        'timestamp' => now()->toDateTimeString(),
        'session_id' => $request->session()->getId(),
        'session_persisted' => count($debugKeys) > 0,
        'debug_keys_found' => array_values($debugKeys),
        'all_session_keys' => $allKeys,
        'cookies_received' => array_keys($request->cookies->all()),
    ]);
});

