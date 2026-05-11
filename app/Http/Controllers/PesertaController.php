<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function dashboard()
    {
        return view('peserta.dashboard');
    }

    public function daftarMagang()
    {
        $pengajuan_magangs = PengajuanMagang::where('user_id', Auth::id())->get();
        return view('peserta.daftar_magang', compact('pengajuan_magangs'));
    }

    public function profil()
    {
        $user = Auth::user();
        return view('peserta.profil', compact('user'));
    }

    public function cekPresensi()
    {
        $user = Auth::user();
        
        $isProfileComplete = !empty($user->jenis_kelamin) && 
                           !empty($user->tanggal_lahir) && 
                           !empty($user->tempat_lahir) && 
                           !empty($user->no_hp) && 
                           !empty($user->alamat);
                           
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
                        ->latest()
                        ->first();
        $isApplicationApproved = $pengajuan && $pengajuan->status_pengajuan === 'Diterima';

        if (!$isProfileComplete || !$isApplicationApproved) {
            return view('peserta.presensi_alert', compact('user', 'isProfileComplete', 'isApplicationApproved', 'pengajuan'));
        }

        return redirect()->route('presensi.login');
    }

    public function presensi()
    {
        // Ambil user dari session presensi (bukan dari sesi web utama)
        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);
        
        // Check if user profile is complete
        $isProfileComplete = !empty($user->jenis_kelamin) && 
                           !empty($user->tanggal_lahir) && 
                           !empty($user->tempat_lahir) && 
                           !empty($user->no_hp) && 
                           !empty($user->alamat);
                           
        // Check if user has an approved application
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
                        ->where('status_pengajuan', 'Diterima')
                        ->latest()
                        ->first();
        $isApplicationApproved = !is_null($pengajuan);

        return view('peserta.presensi', compact('user', 'isProfileComplete', 'isApplicationApproved', 'pengajuan'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}