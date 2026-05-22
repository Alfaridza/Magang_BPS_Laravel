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

Route::get('auth/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password.show');
Route::post('auth/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot_password.send');

// Presensi Login (mobile-style) — sesi terpisah dari web utama
Route::get('presensi/login', [AuthController::class, 'showPresensiLoginForm'])->name('presensi.login');
Route::post('presensi/login', [AuthController::class, 'loginPresensi'])->name('presensi.login.post');
Route::post('presensi/login-sistem', [AuthController::class, 'loginPresensiSistem'])->name('presensi.login_sistem');
Route::post('presensi/logout', [AuthController::class, 'logoutPresensi'])->name('presensi.logout');

// Route presensi — dilindungi middleware presensi.auth (bukan auth web)
Route::middleware('presensi.auth')->group(function () {
    Route::get('/presensi/dashboard', [App\Http\Controllers\PresensiController::class, 'dashboard'])->name('presensi.dashboard');
    Route::get('/presensi/kamera/{tipe}', [App\Http\Controllers\PresensiController::class, 'kamera'])->name('presensi.kamera');
    Route::post('/presensi/store', [App\Http\Controllers\PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/presensi/histori', [App\Http\Controllers\PresensiController::class, 'histori'])->name('presensi.histori');
    Route::get('/presensi/izin', [App\Http\Controllers\PresensiController::class, 'izin'])->name('presensi.izin');
    Route::post('/presensi/izin', [App\Http\Controllers\PresensiController::class, 'storeIzin'])->name('presensi.izin.store');
    Route::get('/presensi/kendala', [App\Http\Controllers\PresensiController::class, 'kendala'])->name('presensi.kendala');
    Route::post('/presensi/kendala', [App\Http\Controllers\PresensiController::class, 'storeKendala'])->name('presensi.kendala.store');
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
        Route::get('/peserta-magang-aktif/{id}/laporan-presensi', [App\Http\Controllers\Admin\PesertaMagangAktifController::class, 'laporanPresensi'])->name('peserta_magang_aktif.laporan_presensi');
        Route::get('/pengajuan-magang', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'index'])->name('pengajuan_magang.index');
        Route::post('/pengajuan-magang/{id}/terima', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'terima'])->name('pengajuan_magang.terima');
        Route::post('/pengajuan-magang/{id}/tolak', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'tolak'])->name('pengajuan_magang.tolak');
        Route::get('/pengajuan-magang/{id}/cetak-surat', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'cetakSurat'])->name('pengajuan_magang.cetak_surat');
        Route::get('/log-aktivitas', [App\Http\Controllers\Admin\AdminActivityLogController::class, 'index'])->name('log_activity.index');

        // Admin Presensi Management
        Route::prefix('presensi')->name('presensi.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\PresensiController::class, 'dashboard'])->name('dashboard');
            Route::put('/update-manual/{id}', [App\Http\Controllers\Admin\PresensiController::class, 'updateManual'])->name('update_manual');
            Route::get('/monitoring-izin', [App\Http\Controllers\Admin\PresensiController::class, 'monitoringIzin'])->name('monitoring_izin');
            Route::post('/monitoring-izin/{id}/approve', [App\Http\Controllers\Admin\PresensiController::class, 'approveIzin'])->name('approve_izin');
            Route::post('/monitoring-izin/{id}/reject', [App\Http\Controllers\Admin\PresensiController::class, 'rejectIzin'])->name('reject_izin');
            
            Route::get('/monitoring-kendala', [App\Http\Controllers\Admin\PresensiController::class, 'monitoringKendala'])->name('monitoring_kendala');
            Route::post('/monitoring-kendala/{id}/approve', [App\Http\Controllers\Admin\PresensiController::class, 'approveKendala'])->name('approve_kendala');
            Route::post('/monitoring-kendala/{id}/reject', [App\Http\Controllers\Admin\PresensiController::class, 'rejectKendala'])->name('reject_kendala');
            
            Route::get('/laporan-bulanan', [App\Http\Controllers\Admin\PresensiController::class, 'laporanBulanan'])->name('laporan_bulanan');
            Route::get('/laporan-bulanan/cetak', [App\Http\Controllers\Admin\PresensiController::class, 'cetakLaporanBulanan'])->name('cetak_laporan_bulanan');
        });

        // Konfigurasi Management
        Route::prefix('konfigurasi')->name('konfigurasi.')->group(function () {
            Route::get('/jam-kerja', [App\Http\Controllers\Admin\KonfigurasiController::class, 'jamKerja'])->name('jam_kerja');
            Route::post('/jam-kerja', [App\Http\Controllers\Admin\KonfigurasiController::class, 'storeJamKerja'])->name('store_jam_kerja');
            Route::put('/jam-kerja/{id}', [App\Http\Controllers\Admin\KonfigurasiController::class, 'updateJamKerja'])->name('update_jam_kerja');
            Route::delete('/jam-kerja/{id}', [App\Http\Controllers\Admin\KonfigurasiController::class, 'destroyJamKerja'])->name('delete_jam_kerja');
            
            Route::get('/hari-libur', [App\Http\Controllers\Admin\KonfigurasiController::class, 'hariLibur'])->name('hari_libur');
            Route::post('/hari-libur', [App\Http\Controllers\Admin\KonfigurasiController::class, 'storeHariLibur'])->name('store_hari_libur');
            Route::delete('/hari-libur/{id}', [App\Http\Controllers\Admin\KonfigurasiController::class, 'destroyHariLibur'])->name('delete_hari_libur');
        });
});

Route::middleware('auth')->group(function () {

    Route::prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PesertaController::class, 'dashboard'])->name('dashboard');
        Route::get('/daftar-magang', [App\Http\Controllers\PesertaController::class, 'daftarMagang'])->name('daftar_magang');
        Route::get('/profil', [App\Http\Controllers\PesertaController::class, 'profil'])->name('profil');
        Route::post('/profil', [App\Http\Controllers\PesertaController::class, 'updateProfil']);
        Route::post('/profil/password', [App\Http\Controllers\PesertaController::class, 'updatePassword'])->name('profil.password');
        // Route presensi dipindah ke luar prefix peserta
    });

    Route::prefix('presensi')->name('presensi.')->group(function () {
        Route::get('/cek-kelayakan', [App\Http\Controllers\PresensiController::class, 'cekKelayakan'])->name('cek_kelayakan');
    });
        // Route presensi dipindah ke middleware presensi.auth (di atas)

    Route::post('/pengajuan-magang', [PengajuanMagangController::class, 'store']);
    Route::put('/pengajuan-magang/{id}', [PengajuanMagangController::class, 'update']);
});