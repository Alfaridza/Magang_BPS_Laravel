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

Route::get('auth/setup-password/{id}', [AuthController::class, 'showSetupPasswordForm'])->name('setup_password.show');
Route::post('auth/setup-password/{id}', [AuthController::class, 'setupPassword']);

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/manajemen-admin', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.manajemen_admin.index');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/manajemen-peserta', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'index'])->name('manajemen_peserta.index');
        Route::get('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'show'])->name('manajemen_peserta.show');
        Route::get('/manajemen-peserta/{id}/edit', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'edit'])->name('manajemen_peserta.edit');
        Route::put('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'update'])->name('manajemen_peserta.update');
        Route::delete('/manajemen-peserta/{id}', [App\Http\Controllers\Admin\ManajemenPesertaController::class, 'destroy'])->name('manajemen_peserta.destroy');
        Route::get('/pengajuan-magang', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'index'])->name('pengajuan_magang.index');
        Route::post('/pengajuan-magang/{id}/terima', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'terima'])->name('pengajuan_magang.terima');
        Route::post('/pengajuan-magang/{id}/tolak', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'tolak'])->name('pengajuan_magang.tolak');
        Route::get('/pengajuan-magang/{id}/cetak-surat', [App\Http\Controllers\Admin\PengajuanMagangController::class, 'cetakSurat'])->name('pengajuan_magang.cetak_surat');
    });

    Route::prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PesertaController::class, 'dashboard'])->name('dashboard');
        Route::get('/daftar-magang', [App\Http\Controllers\PesertaController::class, 'daftarMagang'])->name('daftar_magang');
        Route::get('/profil', [App\Http\Controllers\PesertaController::class, 'profil'])->name('profil');
        Route::post('/profil', [App\Http\Controllers\PesertaController::class, 'updateProfil']);
    });

    Route::post('/pengajuan-magang', [PengajuanMagangController::class, 'store']);
});