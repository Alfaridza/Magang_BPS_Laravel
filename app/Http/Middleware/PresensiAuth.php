<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PresensiAuth
{
    /**
     * Handle an incoming request.
     * Cek apakah peserta sudah login lewat halaman presensi (session terpisah).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('presensi_user_id')) {
            return redirect()->route('presensi.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses presensi.');
        }

        return $next($request);
    }
}
