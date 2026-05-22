<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use App\Models\Presensi;
use App\Models\Kendala;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KonfigurasiJamKerja;
use App\Models\HariLibur;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function cekKelayakan()
    {
        $user = Auth::user();
        
        $isProfileComplete = !empty($user->jenis_kelamin) && 
                           !empty($user->tanggal_lahir) && 
                           !empty($user->tempat_lahir) && 
                           !empty($user->no_hp) && 
                           !empty($user->alamat);
                           
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
                        ->latest()
                        ->first();
        $isApplicationApproved = $pengajuan && $pengajuan->status_pengajuan === 'Diterima';

        if (!$isProfileComplete || !$isApplicationApproved) {
            return view('presensi.alert', compact('user', 'isProfileComplete', 'isApplicationApproved', 'pengajuan'));
        }

        return redirect()->route('presensi.login');
    }

    public function dashboard()
    {
        // Ambil user dari session presensi (bukan dari sesi web utama)
        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);
        
        // Check if user profile is complete
        $isProfileComplete = !empty($user->jenis_kelamin) && 
                           !empty($user->tanggal_lahir) && 
                           !empty($user->tempat_lahir) && 
                           !empty($user->no_hp) && 
                           !empty($user->alamat);
                           
        // Check if user has an approved application
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
                        ->where('status_pengajuan', 'Diterima')
                        ->latest()
                        ->first();
        $isApplicationApproved = !is_null($pengajuan);

        // Ambil data presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $userId)
                            ->where('tanggal', date('Y-m-d'))
                            ->first();

        return view('presensi.index', compact(
            'user', 'isProfileComplete', 'isApplicationApproved', 'pengajuan', 'presensiHariIni'
        ));
    }

    public function kamera($tipe)
    {
        // Validasi tipe presensi
        if (!in_array($tipe, ['masuk', 'pulang'])) {
            abort(404);
        }

        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);
        $hariIni = date('Y-m-d');

        // Ambil radius dari konfigurasi aktif
        $konfigurasi = KonfigurasiJamKerja::where('status', 1)
            ->where(function($query) use ($hariIni) {
                $query->where(function($q) use ($hariIni) {
                    $q->whereDate('tanggal_mulai', '<=', $hariIni)
                      ->whereDate('tanggal_selesai', '>=', $hariIni);
                })
                ->orWhere(function($q) {
                    $q->whereNull('tanggal_mulai')
                      ->whereNull('tanggal_selesai');
                });
            })
            ->orderByRaw('tanggal_mulai IS NULL ASC')
            ->first();

        $radius = $konfigurasi->radius_meter ?? 50;

        return view('presensi.kamera', compact('user', 'tipe', 'radius'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'      => 'required|in:masuk,pulang',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $userId = session('presensi_user_id');
        $hariIni = date('Y-m-d');
        $waktuSekarang = date('H:i:s');

        // Cek Hari Libur
        $isLibur = HariLibur::where('tanggal', $hariIni)->first();
        if ($isLibur) {
            return response()->json(['status' => 'error', 'message' => 'Hari ini adalah hari libur: ' . $isLibur->keterangan]);
        }

        // Cek Akhir Pekan (Sabtu & Minggu)
        $hariNama = date('l');
        if ($hariNama == 'Saturday' || $hariNama == 'Sunday') {
             return response()->json(['status' => 'error', 'message' => 'Hari ini adalah akhir pekan. Presensi hanya berlaku di hari kerja.']);
        }

        // Ambil Konfigurasi Jam Kerja yang sesuai dengan tanggal hari ini
        $konfigurasi = KonfigurasiJamKerja::where('status', 1)
            ->where(function($query) use ($hariIni) {
                // Cari yang periodenya masuk hari ini
                $query->where(function($q) use ($hariIni) {
                    $q->whereDate('tanggal_mulai', '<=', $hariIni)
                      ->whereDate('tanggal_selesai', '>=', $hariIni);
                })
                // ATAU yang tidak punya periode (default)
                ->orWhere(function($q) {
                    $q->whereNull('tanggal_mulai')
                      ->whereNull('tanggal_selesai');
                });
            })
            ->orderByRaw('tanggal_mulai IS NULL ASC') // Prioritaskan yang ada tanggal_mulai-nya (bukan null)
            ->first();

        if (!$konfigurasi) {
            // Fallback default jika database kosong sama sekali (safety)
            $jamMasukConfig = "07:30:00";
            $jamMasukToleransi = "07:32:00";
            $jamPulangConfig = "16:00:00";
        } else {
            $jamMasukConfig = $konfigurasi->jam_masuk;
            $jamMasukToleransi = $konfigurasi->jam_masuk_toleransi;
            $jamPulangConfig = $konfigurasi->jam_pulang;
        }

        $batasMasuk = $jamMasukToleransi;

        $presensi = Presensi::where('user_id', $userId)
                            ->where('tanggal', $hariIni)
                            ->first();

        // Validasi Radius (Backend)
        $BPS_LAT = -6.171274937865753;  
        $BPS_LNG = 106.16087446395497;
        $MAX_RADIUS = $konfigurasi->radius_meter ?? 50;

        $lat1 = $request->latitude;
        $lon1 = $request->longitude;
        $lat2 = $BPS_LAT;
        $lon2 = $BPS_LNG;

        $R = 6371000; // radius of earth in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;

        if ($distance > $MAX_RADIUS) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Silakan mendekat ke lokasi BPS.'
            ]);
        }

        if ($request->tipe == 'masuk') {
            if ($presensi) {
                // Jika sudah ada record Izin Setengah Hari
                if ($presensi->status == 'Izin Setengah Hari') {
                    if ($presensi->jam_in) {
                        return response()->json(['status' => 'error', 'message' => 'Anda sudah melakukan presensi masuk hari ini.']);
                    }
                    
                    // Update data izin dengan jam masuk
                    $presensi->update([
                        'jam_in'    => $waktuSekarang,
                        'lokasi_in' => $request->latitude . ',' . $request->longitude,
                    ]);
                    return response()->json(['status' => 'success', 'message' => 'Presensi masuk (Izin Setengah Hari) berhasil dicatat.']);
                }

                return response()->json(['status' => 'error', 'message' => 'Anda sudah memiliki data presensi (' . $presensi->status . ') hari ini.']);
            }

            $status = ($waktuSekarang <= $batasMasuk) ? 'Hadir' : 'Terlambat/PSW';

            Presensi::create([
                'user_id'         => $userId,
                'tanggal'         => $hariIni,
                'jam_in'          => $waktuSekarang,
                'lokasi_in'       => $request->latitude . ',' . $request->longitude,
                'status'          => $status,
                'status_approve'  => 1 
            ]);

            return response()->json(['status' => 'success', 'message' => 'Presensi masuk berhasil dicatat. Status: ' . $status]);
        } else {
            if (!$presensi) {
                return response()->json(['status' => 'error', 'message' => 'Anda belum melakukan presensi masuk hari ini.']);
            }

            // Jika statusnya adalah Izin/Sakit full day
            if (in_array($presensi->status, ['Izin', 'Sakit']) && $presensi->status_approve == 1) {
                return response()->json(['status' => 'error', 'message' => 'Anda sedang dalam status ' . $presensi->status . ' (Full Day).']);
            }

            if ($presensi->jam_out) {
                return response()->json(['status' => 'error', 'message' => 'Anda sudah melakukan presensi pulang hari ini.']);
            }

            $statusFinal = $presensi->status; 

            // Jika pulang mendahului jam pulang (PSW)
            if ($waktuSekarang < $jamPulangConfig) {
                if ($presensi->status == 'Izin Setengah Hari') {
                    // Jika Izin Setengah Hari, status tetap Izin Setengah Hari (tidak jadi PSW)
                } elseif ($presensi->status == 'Terlambat/PSW') {
                    // Jika sudah terlambat masuk DAN pulang sebelum waktunya → KJK
                    $statusFinal = 'KJK (Kekurangan Jam Kerja)';
                } else {
                    $statusFinal = 'Terlambat/PSW';
                }
            }

            $presensi->update([
                'jam_out'    => $waktuSekarang,
                'lokasi_out' => $request->latitude . ',' . $request->longitude,
                'status'     => $statusFinal
            ]);

            return response()->json(['status' => 'success', 'message' => 'Presensi pulang berhasil dicatat. Status: ' . $statusFinal]);
        }
    }

    public function histori()
    {
        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);

        $riwayat = Presensi::where('user_id', $userId)
                    ->orderBy('tanggal', 'desc')
                    ->paginate(15);

        return view('presensi.histori', compact('user', 'riwayat'));
    }

    public function izin()
    {
        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);
        return view('presensi.izin', compact('user'));
    }

    public function storeIzin(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'status'      => 'required|in:Izin,Sakit,Izin Setengah Hari',
            'keterangan'  => 'required|string',
            'bukti_izin'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = session('presensi_user_id');
        
        // Cek apakah sudah ada presensi di tanggal tersebut
        $cek = Presensi::where('user_id', $userId)
                        ->where('tanggal', $request->tanggal)
                        ->first();
        
        if ($cek) {
            // Jika dia mau izin SETENGAH HARI, dan record yang ada adalah HADIR/TERLAMBAT, maka ijinkan update
            if ($request->status == 'Izin Setengah Hari') {
                if (in_array($cek->status, ['Izin', 'Sakit', 'Alpha/Tanpa Kabar'])) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki status ' . $cek->status . ' pada tanggal tersebut.');
                }
                // Update status menjadi Izin Setengah Hari (menunggu approval)
                $dataUpdate = [
                    'status' => 'Izin Setengah Hari',
                    'keterangan' => $request->keterangan,
                    'status_approve' => 0,
                ];

                if ($request->hasFile('bukti_izin')) {
                    $file = $request->file('bukti_izin');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/bukti_izin', $filename);
                    $dataUpdate['bukti_izin'] = $filename;
                }

                $cek->update($dataUpdate);
                return redirect()->route('presensi.dashboard')->with('success', 'Permohonan Izin Setengah Hari berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Anda sudah memiliki data presensi pada tanggal tersebut. Gunakan Izin Setengah Hari jika diperlukan.');
            }
        }

        $data = [
            'user_id'        => $userId,
            'tanggal'        => $request->tanggal,
            'status'         => $request->status,
            'keterangan'     => $request->keterangan,
            'status_approve' => 0, // Pending
        ];

        if ($request->hasFile('bukti_izin')) {
            $file = $request->file('bukti_izin');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_izin', $filename);
            $data['bukti_izin'] = $filename;
        }

        Presensi::create($data);

        return redirect()->route('presensi.dashboard')->with('success', 'Permohonan izin berhasil dikirim.');
    }

    public function kendala()
    {
        $userId = session('presensi_user_id');
        $user   = \App\Models\User::findOrFail($userId);
        return view('presensi.kendala', compact('user'));
    }

    public function storeKendala(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'jam_in'        => 'nullable',
            'jam_out'       => 'nullable',
            'jenis_kendala' => 'required|in:Lokasi Tidak Terdeteksi,Sistem sedang Error,Lainnya',
            'keterangan'    => 'required|string',
        ]);

        $userId = session('presensi_user_id');

        Kendala::create([
            'user_id'       => $userId,
            'tanggal'       => $request->tanggal,
            'jam_in'        => $request->jam_in,
            'jam_out'       => $request->jam_out,
            'jenis_kendala' => $request->jenis_kendala,
            'keterangan'    => $request->keterangan,
            'status_approve' => 0, // Pending
        ]);

        return redirect()->route('presensi.dashboard')->with('success', 'Laporan kendala berhasil dikirim. Harap tunggu konfirmasi admin.');
    }
}
