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

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
