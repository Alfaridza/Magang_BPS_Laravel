<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;

class PengajuanMagangController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_peserta' => 'required|in:Mahasiswa,Fresh graduated,Siswa',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_magang' => 'required|in:Magang Wajib/PKL,Magang Mandiri',
            'nim_nisn' => 'required|string|max:50',
            'jenjang_pendidikan' => 'required|in:SMK/SMA,Diploma,D4/S1',
            'nama_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'periode_mulai' => 'required|date',
            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
                function ($attribute, $value, $fail) use ($request) {
                    $mulai = \Carbon\Carbon::parse($request->periode_mulai);
                    $selesai = \Carbon\Carbon::parse($value);
                    if ($mulai->diffInDays($selesai) < 29) { // 30 days inclusive means diff is 29
                        $fail('Periode magang minimal 30 hari.');
                    }
                },
            ],
            'tema_magang' => 'nullable|string',
            'surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_pengantar')) {
            $validated['surat_pengantar'] = $request->file('surat_pengantar')->store('berkas_magang/surat', 'public');
        }

        if ($request->hasFile('pas_foto')) {
            $validated['pas_foto'] = $request->file('pas_foto')->store('berkas_magang/foto', 'public');
        }

        PengajuanMagang::create($validated);

        return redirect('/peserta/daftar-magang')->with('success', 'Pengajuan magang berhasil dikirim!');
    }
}
