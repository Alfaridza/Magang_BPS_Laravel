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

    public function showLoginForm()
    {
        return view('auth.login');
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
