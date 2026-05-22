<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Kendala;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function dashboard(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $statusFilter = $request->get('status');

        // Jalankan sinkronisasi untuk tanggal-tanggal sebelumnya jika melihat hari ini atau hari kemarin
        if ($tanggal <= date('Y-m-d')) {
            $this->syncMissingAttendance($tanggal);
        }

        // Total Peserta Aktif
        $totalPesertaAktif = User::whereHas('pengajuanMagang', function($q) {
            $q->where('status_pengajuan', 'Diterima');
        })->count();

        // Query Presensi (Termasuk Alpha dan TPP yang sudah di-generate)
        $queryPresensi = Presensi::with('user')->where('tanggal', $tanggal);
        if ($statusFilter && $statusFilter !== 'Kendala') {
            $queryPresensi->where('status', $statusFilter);
        }
        $presensi = $queryPresensi->get();

        // Query Kendala
        $queryKendala = Kendala::with('user')->where('tanggal', $tanggal);
        $kendala = [];
        if (!$statusFilter || $statusFilter === 'Kendala') {
            $kendala = $queryKendala->get();
        }

        // Gabungkan data
        $dataTabel = collect();

        foreach ($presensi as $p) {
            $dataTabel->push([
                'id'         => $p->id,
                'nama'       => $p->user->name ?? 'User Terhapus',
                'email'      => $p->user->email ?? '-',
                'jam_in'     => $p->jam_in,
                'jam_out'    => $p->jam_out,
                'status'     => $p->status,
                'keterangan' => $p->keterangan,
                'type'       => 'presensi',
                'approve'    => $p->status_approve
            ]);
        }

        foreach ($kendala as $k) {
            $dataTabel->push([
                'id'         => $k->id,
                'nama'       => $k->user->name ?? 'User Terhapus',
                'email'      => $k->user->email ?? '-',
                'jam_in'     => $k->jam_in,
                'jam_out'    => $k->jam_out,
                'status'     => 'Kendala',
                'keterangan' => '[' . $k->jenis_kendala . '] ' . $k->keterangan,
                'type'       => 'kendala',
                'approve'    => $k->status_approve
            ]);
        }

        $stats = [
            'total_peserta' => $totalPesertaAktif,
            'presensi_hari_ini' => Presensi::where('tanggal', $tanggal)->whereNotIn('status', ['Alpha/Tanpa Kabar'])->count(),
        ];

        return view('admin.presensi.dashboard', compact('stats', 'dataTabel', 'tanggal', 'statusFilter'));
    }

    /**
     * Sinkronisasi data absen yang hilang (TPP dan Alpha)
     */
    private function syncMissingAttendance($tanggal)
    {
        // Hanya sinkronisasi untuk hari kerja (Senin - Jumat) jika diperlukan, 
        // tapi kita asumsikan sinkronisasi setiap hari yang dibuka admin.
        
        $activeUsers = User::whereHas('pengajuanMagang', function($q) {
            $q->where('status_pengajuan', 'Diterima');
        })->get();

        foreach ($activeUsers as $user) {
            $presensi = Presensi::where('user_id', $user->id)
                                ->where('tanggal', $tanggal)
                                ->first();

            if (!$presensi) {
                // Jika hari sudah lewat dan tidak ada absen sama sekali -> Alpha
                if ($tanggal < date('Y-m-d')) {
                    Presensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $tanggal,
                        'status'  => 'Alpha/Tanpa Kabar',
                        'status_approve' => 1
                    ]);
                }
            } else {
                // Jika ada absen masuk tapi tidak ada absen pulang, dan hari sudah berganti -> TPP
                if ($presensi->jam_in && !$presensi->jam_out && $tanggal < date('Y-m-d')) {
                    if ($presensi->status !== 'Alpha/Tanpa Kabar' && !in_array($presensi->status, ['Izin', 'Sakit', 'Izin Setengah Hari', 'KJK (Kekurangan Jam Kerja)'])) {
                        $presensi->update(['status' => 'Tidak Presensi Pulang(TPP)']);
                    }
                }
            }
        }
    }

    public function updateManual(Request $request, $id)
    {
        $request->validate([
            'jam_in'  => 'nullable',
            'jam_out' => 'nullable',
            'status'  => 'required',
        ]);

        $presensi = Presensi::findOrFail($id);
        
        $data = [
            'status' => $request->status,
            'status_approve' => 1, // Auto approve jika admin yang edit
        ];

        if ($request->jam_in) {
            $data['jam_in'] = $request->jam_in;
        }
        
        if ($request->jam_out) {
            $data['jam_out'] = $request->jam_out;
        }

        $presensi->update($data);

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui secara manual.');
    }

    public function monitoringIzin(Request $request)
    {
        $statusFilter = $request->get('status');
        
        $query = Presensi::with('user')
                        ->whereIn('status', ['Izin', 'Sakit', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari']);
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $perizinan = $query->orderBy('status_approve', 'asc')
                        ->orderBy('tanggal', 'desc')
                        ->paginate(15);

        return view('admin.presensi.monitoring_izin', compact('perizinan', 'statusFilter'));
    }

    public function approveIzin($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update(['status_approve' => 1]);
        return redirect()->back()->with('success', 'Perizinan telah disetujui.');
    }

    public function rejectIzin(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'status_approve' => 2,
            'keterangan_admin' => $request->keterangan_admin
        ]);
        return redirect()->back()->with('success', 'Perizinan telah ditolak.');
    }

    public function monitoringKendala()
    {
        $kendala = Kendala::with('user')
                    ->orderBy('status_approve', 'asc')
                    ->orderBy('tanggal', 'desc')
                    ->paginate(15);

        return view('admin.presensi.monitoring_kendala', compact('kendala'));
    }

    public function approveKendala($id)
    {
        $kendala = Kendala::findOrFail($id);
        $kendala->update(['status_approve' => 1]);
        
        $presensi = Presensi::where('user_id', $kendala->user_id)
                            ->where('tanggal', $kendala->tanggal)
                            ->first();

        if ($presensi) {
            $presensi->update([
                'jam_in' => $kendala->jam_in ?? $presensi->jam_in,
                'jam_out' => $kendala->jam_out ?? $presensi->jam_out,
                'status_approve' => 1,
            ]);
        } else {
            Presensi::create([
                'user_id' => $kendala->user_id,
                'tanggal' => $kendala->tanggal,
                'jam_in' => $kendala->jam_in,
                'jam_out' => $kendala->jam_out,
                'status' => 'Hadir',
                'status_approve' => 1,
            ]);
        }
        
        return redirect()->back()->with('success', 'Laporan kendala telah disetujui dan data presensi berhasil diperbarui.');
    }

    public function rejectKendala($id)
    {
        $kendala = Kendala::findOrFail($id);
        $kendala->update(['status_approve' => 2]);
        return redirect()->back()->with('success', 'Laporan kendala telah ditolak.');
    }

    public function laporanBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $rekap = User::whereHas('pengajuanMagang', function($q) {
            $q->where('status_pengajuan', 'Diterima');
        })->with(['presensis' => function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }])->get();

        return view('admin.presensi.laporan_bulanan', compact('rekap', 'bulan', 'tahun'));
    }

    public function cetakLaporanBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $rekap = User::whereHas('pengajuanMagang', function($q) {
            $q->where('status_pengajuan', 'Diterima');
        })->with(['presensis' => function($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }])->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.presensi.cetak_laporan_bulanan', compact('rekap', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Presensi_Bulanan_'.$bulan.'_'.$tahun.'.pdf');
    }
}
