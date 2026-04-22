<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PengajuanMagang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_pendaftar = User::count();
        $perlu_verifikasi = PengajuanMagang::where('status_pengajuan', 'Menunggu')->count();
        
        $today = Carbon::today();

        // Sedang magang: status Diterima and today is within their magang period
        $sedang_magang = PengajuanMagang::where('status_pengajuan', 'Diterima')
            ->whereDate('periode_mulai', '<=', $today)
            ->whereDate('periode_selesai', '>=', $today)
            ->count();

        // Alumni/Selesai: status Diterima and today is after their magang period
        $alumni_selesai = PengajuanMagang::where('status_pengajuan', 'Diterima')
            ->whereDate('periode_selesai', '<', $today)
            ->count();

        return view('admin.dashboard', compact(
            'total_pendaftar', 
            'perlu_verifikasi', 
            'sedang_magang', 
            'alumni_selesai'
        ));
    }
}
