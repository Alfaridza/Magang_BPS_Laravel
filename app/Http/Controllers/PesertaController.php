<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $pengajuan = PengajuanMagang::where('user_id', $user->id)->latest()->first();
        return view('peserta.profil', compact('user', 'pengajuan'));
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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ])->with('show_password_form', true);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('password_success', 'Password berhasil diubah!');
    }
}