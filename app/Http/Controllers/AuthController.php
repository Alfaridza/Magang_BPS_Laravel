<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $name = explode('@', $validatedData['email'])[0];

        $user = User::create([
            'name' => $name,
            'email' => $validatedData['email'],
            'password' => Hash::make(\Illuminate\Support\Str::random(16)),
        ]);

        try {
            $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'setup_password.show', now()->addMinutes(60), ['id' => $user->id]
            );

            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SetupPasswordMail($user, $url));

            return redirect('/auth/login')->with('success', 'Registrasi awal berhasil! Silakan periksa email Anda (' . $user->email . ') untuk mengatur password dan mengaktifkan akun.');
        } catch (\Exception $e) {
            // Jika gagal mengirim email, hapus akun agar user dapat mencoba daftar daftar lagi
            $user->delete();
            
            return back()->withInput()->withErrors([
                'email' => 'Gagal mengirim pesan ke email Anda. Pastikan alamat email benar dan koneksi (SMTP) server berfungsi. Detail: ' . $e->getMessage()
            ]);
        }
    }

    public function showSetupPasswordForm(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Tautan verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $user = User::findOrFail($id);

        return view('auth.setup_password', compact('user'));
    }

    public function setupPassword(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Tautan verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect('/auth/login')->with('success', 'Password berhasil diatur! Silakan login untuk melengkapi profil Anda.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan di sistem kami.',
            ])->onlyInput('email');
        }

        // Pastikan akun sudah terverifikasi (pernah setup password)
        if (!$user->email_verified_at) {
            return back()->withErrors([
                'email' => 'Akun ini belum diaktifkan. Silakan periksa email Anda untuk tautan aktivasi akun.',
            ])->onlyInput('email');
        }

        try {
            $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'setup_password.show', now()->addMinutes(60), ['id' => $user->id]
            );

            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($user, $url));

            return back()->with('success', 'Tautan reset password telah dikirim ke email ' . $user->email . '. Silakan periksa inbox atau folder spam Anda.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim email. Pastikan koneksi server SMTP berfungsi. Detail: ' . $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showPresensiLoginForm()
    {
        // Jika sudah login presensi, langsung ke halaman presensi
        if (session()->has('presensi_user_id')) {
            return redirect()->route('presensi.dashboard');
        }
        return view('presensi.login');
    }

    public function loginPresensi(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verifikasi kredensial tanpa membuat sesi web utama
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau Password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // Simpan ke session khusus presensi (terpisah dari auth web utama)
        $request->session()->put('presensi_user_id', $user->id);

        return redirect()->route('presensi.dashboard');
    }

    public function loginPresensiSistem(Request $request)
    {
        // Pastikan user sudah login di sistem web utama
        if (!Auth::check()) {
            return back()->withErrors(['email' => 'Anda belum login di sistem. Silakan login manual.']);
        }

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Simpan ke session khusus presensi
        $request->session()->put('presensi_user_id', $user->id);

        return redirect()->route('presensi.dashboard');
    }

    public function logoutPresensi(Request $request)
    {
        // Hapus hanya session presensi, session web utama tetap utuh
        $request->session()->forget('presensi_user_id');

        return redirect()->route('presensi.login')
            ->with('success', 'Anda telah keluar dari sistem presensi.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/peserta/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
