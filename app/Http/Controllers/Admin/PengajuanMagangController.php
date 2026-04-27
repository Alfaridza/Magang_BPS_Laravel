<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Terima Pengajuan', 'Menerima pengajuan magang dengan ID ' . $pengajuan->id, PengajuanMagang::class, $pengajuan->id);

        return redirect()->back()->with('success', 'Pengajuan magang berhasil diterima.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $pengajuan = PengajuanMagang::findOrFail($id);
        $pengajuan->update([
            'status_pengajuan' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Tolak Pengajuan', 'Menolak pengajuan magang dengan ID ' . $pengajuan->id . ' dengan alasan: ' . $request->alasan_penolakan, PengajuanMagang::class, $pengajuan->id);

        return redirect()->back()->with('success', 'Pengajuan magang berhasil ditolak.');
    }

    public function cetakSurat($id)
    {
        $pengajuan = PengajuanMagang::with('user')->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.pengajuan_magang.surat_balasan', compact('pengajuan'))
                    ->setPaper('a4', 'portrait');

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Cetak Surat', 'Mencetak surat balasan untuk pengajuan ID ' . $pengajuan->id, PengajuanMagang::class, $pengajuan->id);

        return $pdf->download('Surat_Balasan_Magang_' . $pengajuan->nim_nisn . '.pdf');
    }
}
