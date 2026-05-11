<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanMagangController;

Route::get('/', function () {
    return view('home.home', [
        'kampus_list' => [],
        'fakultas_list' => [],
        'jurusan_list' => []
    ]);
});

Route::get('auth/register', [AuthController::class, 'showRegistrationForm']);
Route::post('auth/register', [AuthController::class, 'register']);

Route::get('auth/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');

// Presensi Login (mobile-style) — sesi terpisah dari web utama
Route::get('presensi/login', [AuthController::class, 'showPresensiLoginForm'])->name('presensi.login');
Route::post('presensi/login', [AuthController::class, 'loginPresensi'])->name('presensi.login.post');
Route::post('presensi/login-sistem', [AuthController::class, 'loginPresensiSistem'])->name('presensi.login_sistem');
Route::post('presensi/logout', [AuthController::class, 'logoutPresensi'])->name('presensi.logout');

// Route presensi — dilindungi middleware presensi.auth (bukan auth web)
Route::middleware('presensi.auth')->group(function () {
    Route::get('/peserta/presensi', [App\Http\Controllers\PesertaController::class, 'presensi'])->name('peserta.presensi');
});

Route::get('auth/setup-password/{id}', [AuthController::class, 'showSetupPasswordForm'])->name('setup_password.show');
Route::post('auth/setup-password/{id}', [AuthController::class, 'setupPassword']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/manajemen-admin', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'index'])->name('manajemen_admin.index');
    Route::get('/manajemen-admin/create', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'create'])->name('manajemen_admin.create');
    Route::post('/manajemen-admin', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'store'])->name('manajemen_admin.store');
    Route::get('/manajemen-admin/{id}/edit', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'edit'])->name('manajemen_admin.edit');
    Route::put('/manajemen-admin/{id}', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'update'])->name('manajemen_admin.update');
    Route::delete('/manajemen-admin/{id}', [App\Http\Controllers\Admin\ManajemenAdminController::class, 'destroy'])->name('manajemen_admin.destroy');

    Route::get('/manajemen-peserta', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'index'])->name('manajemen_peserta.index');
        Route::get('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'show'])->name('manajemen_peserta.show');
        Route::get('/manajemen-peserta/{id}/edit', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'edit'])->name('manajemen_peserta.edit');
        Route::put('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'update'])->name('manajemen_peserta.update');
        Route::delete('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'destroy'])->name('manajemen_peserta.destroy');

        Route::resource('/peserta-magang-aktif', App\Http\Controllers\Admin\PesertaMagangAktifController::class)->except(['create', 'store'])->names('peserta_magang_aktif');
        Route::get('/pengajuan-magang', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'index'])->name('pengajuan_magang.index');
        Route::post('/pengajuan-magang/{id}/terima', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'terima'])->name('pengajuan_magang.terima');
        Route::post('/pengajuan-magang/{id}/tolak', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'tolak'])->name('pengajuan_magang.tolak');
        Route::get('/pengajuan-magang/{id}/cetak-surat', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'cetakSurat'])->name('pengajuan_magang.cetak_surat');
        Route::get('/log-aktivitas', [App\Http\Controllers\Admin\AdminActivityLogController::class, 'index'])->name('log_activity.index');
});

Route::middleware('auth')->group(function () {

    Route::prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PesertaController::class, 'dashboard'])->name('dashboard');
        Route::get('/daftar-magang', [App\Http\Controllers\PesertaController::class, 'daftarMagang'])->name('daftar_magang');
        Route::get('/profil', [App\Http\Controllers\PesertaController::class, 'profil'])->name('profil');
        Route::post('/profil', [App\Http\Controllers\PesertaController::class, 'updateProfil']);
        Route::get('/cek-presensi', [App\Http\Controllers\PesertaController::class, 'cekPresensi'])->name('cek_presensi');
        // Route presensi dipindah ke middleware presensi.auth (di atas)
    });

    Route::post('/pengajuan-magang', [PengajuanMagangController::class, 'store']);
    Route::put('/pengajuan-magang/{id}', [PengajuanMagangController::class, 'update']);
});