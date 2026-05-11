<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesertaMagangAktifController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $pengajuans = PengajuanMagang::with('user')
            ->where('status_pengajuan', 'Diterima');
            
        if ($search) {
            $pengajuans = $pengajuans->where(function($query) use ($search) {
                $query->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('nim_nisn', 'LIKE', "%{$search}%")
                      ->orWhere('nama_sekolah', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('email', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%");
                      });
            });
        }
        
        $pengajuans = $pengajuans->orderBy('periode_mulai', 'desc')
                                 ->paginate(10)
                                 ->withQueryString();
                                 
        return view('admin.peserta_magang_aktif.index', compact('pengajuans', 'search'));
    }


    public function show($id)
    {
        $magang = PengajuanMagang::with('user')->findOrFail($id);
        return view('admin.peserta_magang_aktif.show', compact('magang'));
    }

    public function edit($id)
    {
        $magang = PengajuanMagang::with('user')->findOrFail($id);
        return view('admin.peserta_magang_aktif.edit', compact('magang'));
    }

    public function update(Request $request, $id)
    {
        $magang = PengajuanMagang::findOrFail($id);
        
        $validated = $request->validate([
            'status_peserta' => 'required|in:Mahasiswa,Fresh graduated,Siswa',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_magang' => 'required|in:Magang Wajib/PKL,Magang Mandiri',
            'nim_nisn' => 'required|string|max:50',
            'jenjang_pendidikan' => 'required|in:SMK/SMA,Diploma,D4/S1',
            'nama_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:255',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'tema_magang' => 'nullable|string',
            'status_pengajuan' => 'required|in:Menunggu,Diterima,Ditolak',
        ]);

        $magang->update($validated);

        return redirect()->route('admin.peserta_magang_aktif.index')->with('success', 'Berhasil memperbarui data pengajuan magang.');
    }

    public function destroy($id)
    {
        $magang = PengajuanMagang::findOrFail($id);
        
        // Hapus file jika mau, tetapi biasanya file tetap dibiarkan untuk log / backup
        // Namun kita bisa hapus jika emang mau dibersihkan
        if ($magang->surat_pengantar) Storage::disk('public')->delete($magang->surat_pengantar);
        if ($magang->pas_foto) Storage::disk('public')->delete($magang->pas_foto);
        if ($magang->kartu_pelajar) Storage::disk('public')->delete($magang->kartu_pelajar);

        $magang->delete();

        return redirect()->route('admin.peserta_magang_aktif.index')->with('success', 'Berhasil menghapus data pengajuan magang.');
    }
}
