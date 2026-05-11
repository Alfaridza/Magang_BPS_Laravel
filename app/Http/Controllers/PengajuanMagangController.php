<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:255',
            'periode_mulai' => 'required|date',
            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
                function ($attribute, $value, $fail) use ($request) {
                    $mulai = Carbon::parse($request->periode_mulai);
                    $selesai = Carbon::parse($value);
                    if ($mulai->diffInDays($selesai) < 29) {
                        $fail('Periode magang minimal 30 hari.');
                    }
                },
            ],
            'tema_magang' => 'nullable|string',
            'surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'kartu_pelajar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_pengantar')) {
            $validated['surat_pengantar'] = $request->file('surat_pengantar')->store('berkas_magang/surat', 'public');
        }

        if ($request->hasFile('pas_foto')) {
            $validated['pas_foto'] = $request->file('pas_foto')->store('berkas_magang/foto', 'public');
        }

        if ($request->hasFile('kartu_pelajar')) {
            $validated['kartu_pelajar'] = $request->file('kartu_pelajar')->store('berkas_magang/kartu_pelajar', 'public');
        }

        PengajuanMagang::create($validated);

        return redirect('/peserta/daftar-magang')->with('success', 'Pengajuan magang berhasil dikirim!');
    }

    public function update(Request $request, $id)
    {
        $magang = PengajuanMagang::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($magang->status_pengajuan !== 'Menunggu') {
            return redirect('/peserta/daftar-magang')->withErrors(['Pengajuan tidak dapat diedit karena sudah diproses.']);
        }

        $validated = $request->validate([
            'status_peserta' => 'required|in:Mahasiswa,Fresh graduated,Siswa',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_magang' => 'required|in:Magang Wajib/PKL,Magang Mandiri',
            'nim_nisn' => 'required|string|max:50',
            'jenjang_pendidikan' => 'required|in:SMK/SMA,Diploma,D4/S1',
            'nama_sekolah' => 'required|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:255',
            'periode_mulai' => 'required|date',
            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
                function ($attribute, $value, $fail) use ($request) {
                    $mulai = Carbon::parse($request->periode_mulai);
                    $selesai = Carbon::parse($value);
                    if ($mulai->diffInDays($selesai) < 29) {
                        $fail('Periode magang minimal 30 hari.');
                    }
                },
            ],
            'tema_magang' => 'nullable|string',
            'surat_pengantar' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'kartu_pelajar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Preserve existing file paths if new files are not uploaded
        if ($request->hasFile('surat_pengantar')) {
            if ($magang->surat_pengantar) {
                Storage::disk('public')->delete($magang->surat_pengantar);
            }
            $validated['surat_pengantar'] = $request->file('surat_pengantar')->store('berkas_magang/surat', 'public');
        } else {
            $validated['surat_pengantar'] = $magang->surat_pengantar;
        }

        if ($request->hasFile('pas_foto')) {
            if ($magang->pas_foto) {
                Storage::disk('public')->delete($magang->pas_foto);
            }
            $validated['pas_foto'] = $request->file('pas_foto')->store('berkas_magang/foto', 'public');
        } else {
            $validated['pas_foto'] = $magang->pas_foto;
        }

        if ($request->hasFile('kartu_pelajar')) {
            if ($magang->kartu_pelajar) {
                Storage::disk('public')->delete($magang->kartu_pelajar);
            }
            $validated['kartu_pelajar'] = $request->file('kartu_pelajar')->store('berkas_magang/kartu_pelajar', 'public');
        } else {
            $validated['kartu_pelajar'] = $magang->kartu_pelajar;
        }

        $magang->update($validated);

        return redirect('/peserta/daftar-magang')->with('success', 'Pengajuan magang berhasil diperbarui!');
    }
}