<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sbg admin, arahkan ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            AdminActivityLog::record(Auth::guard('admin')->user(), 'login', 'Admin berhasil masuk ke dashboard.');
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password Admin yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            AdminActivityLog::record($admin, 'logout', 'Admin keluar dari sistem.');
        }

        Auth::guard('admin')->logout();
        
        // Hanya invalidasi sesi admin agar peserta tidak ikut log out jika berada di tab lain
        // Meskipun regenerate token bisa ditunda, disarankan untuk clear guard yang aktif.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
