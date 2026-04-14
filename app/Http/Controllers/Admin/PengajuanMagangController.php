<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        $pengajuan_magangs = PengajuanMagang::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pengajuan_magang.index', compact('pengajuan_magangs'));
    }

    public function terima($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $pengajuan->update(['status_pengajuan' => 'Diterima']);

        return redirect()->back()->with('success', 'Pengajuan magang berhasil diterima.');
    }

    public function tolak($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $pengajuan->update(['status_pengajuan' => 'Ditolak']);

        return redirect()->back()->with('success', 'Pengajuan magang berhasil ditolak.');
    }

    public function cetakSurat($id)
    {
        $pengajuan = PengajuanMagang::with('user')->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.pengajuan_magang.surat_balasan', compact('pengajuan'))
                    ->setPaper('a4', 'portrait');

        return $pdf->download('Surat_Balasan_Magang_' . $pengajuan->nim_nisn . '.pdf');
    }
}
