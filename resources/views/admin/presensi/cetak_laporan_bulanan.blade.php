<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi Bulanan - {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; color: #1a56db; }
        .header p { margin: 5px 0 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 10px; color: #475569; }
        
        .text-center { text-align: center; }
        .badge { padding: 4px 8px; border-radius: 4px; color: white; font-weight: bold; font-size: 10px; }
        .bg-green { background-color: #059669; }
        .bg-red { background-color: #dc2626; }
        .bg-blue { background-color: #2563eb; }
        
        .footer { margin-top: 50px; text-align: right; }
        .footer p { margin-bottom: 60px; }
        .signature { display: inline-block; border-top: 1px solid #000; width: 200px; text-align: center; padding-top: 5px; }

        .info-rekap { margin-bottom: 20px; }
        .info-rekap span { font-weight: bold; color: #1a56db; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekapitulasi Presensi Peserta Magang</h2>
        <p>Badan Pusat Statistik Provinsi Banten</p>
        <p>Periode: {{ \Carbon\Carbon::create(null, $bulan, 1)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <div class="info-rekap">
        Dicetak pada: <span>{{ date('d/m/Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Izin</th>
                <th class="text-center">KJK</th>
                <th class="text-center">Alpha</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $index => $row)
                @php
                    $hadir = $row->presensis->whereIn('status', ['Hadir', 'Terlambat', 'Terlambat/PSW', 'KJK (Kekurangan Jam Kerja)', 'Tidak Presensi Pulang(TPP)'])->count();
                    $sakit = $row->presensis->where('status', 'Sakit')->count();
                    $izin  = $row->presensis->whereIn('status', ['Izin', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari'])->count();
                    $kjk   = $row->presensis->where('status', 'KJK (Kekurangan Jam Kerja)')->count();
                    $alpha = $row->presensis->where('status', 'Alpha/Tanpa Kabar')->count();
                    $total = $hadir + $sakit + $izin;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $row->name }}</strong></td>
                    <td>{{ $row->email }}</td>
                    <td class="text-center"><span class="badge bg-green">{{ $hadir }}</span></td>
                    <td class="text-center"><span class="badge bg-red">{{ $sakit }}</span></td>
                    <td class="text-center"><span class="badge bg-blue">{{ $izin }}</span></td>
                    <td class="text-center"><span class="badge" style="background-color: #d97706;">{{ $kjk }}</span></td>
                    <td class="text-center"><span class="badge" style="background-color: #94a3b8;">{{ $alpha }}</span></td>
                    <td class="text-center"><strong>{{ $total }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Serang, {{ date('d F Y') }}</p>
        <div class="signature">
            Kepala Bagian Umum
        </div>
    </div>
</body>
</html>
