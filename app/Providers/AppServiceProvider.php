<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\PengajuanMagang;

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
        View::composer('admin.layouts.app', function ($view) {
            $pendingPengajuans = PengajuanMagang::with('user')->where('status_pengajuan', 'Menunggu')->orderBy('created_at', 'desc')->take(5)->get();
            $pendingCount = PengajuanMagang::where('status_pengajuan', 'Menunggu')->count();
            $view->with(compact('pendingPengajuans', 'pendingCount'));
        });
    }
}
