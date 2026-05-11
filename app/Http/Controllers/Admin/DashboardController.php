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

        // 5 pengajuan magang terbaru yang masih menunggu verifikasi
        $pengajuan_terbaru = PengajuanMagang::with('user')
            ->where('status_pengajuan', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Peserta yang periode magangnya akan selesai dalam 14 hari ke depan
        $batas_selesai = Carbon::today()->addDays(14);
        $segera_selesai = PengajuanMagang::with('user')
            ->where('status_pengajuan', 'Diterima')
            ->whereDate('periode_selesai', '>=', $today)
            ->whereDate('periode_selesai', '<=', $batas_selesai)
            ->orderBy('periode_selesai', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'total_pendaftar', 
            'perlu_verifikasi', 
            'sedang_magang', 
            'alumni_selesai',
            'pengajuan_terbaru',
            'segera_selesai'
        ));
    }
}
