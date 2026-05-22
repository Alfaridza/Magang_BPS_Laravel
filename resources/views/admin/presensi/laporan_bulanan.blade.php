@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Presensi Bulanan</h2>
            <p class="text-gray-500 text-sm">Rekapitulasi kehadiran peserta magang per bulan.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 mb-6">
        <form action="{{ route('admin.presensi.laporan_bulanan') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                <select name="tahun" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    @for($t=date('Y'); $t>=date('Y')-2; $t--)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.presensi.cetak_laporan_bulanan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-700">Rekap Presensi Peserta - {{ \Carbon\Carbon::create(null, $bulan, 1)->translatedFormat('F') }} {{ $tahun }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white text-gray-400 uppercase text-[11px] tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left font-bold">No</th>
                        <th class="px-6 py-4 text-left font-bold">Peserta Magang</th>
                        <th class="px-6 py-4 text-center font-bold">Hadir</th>
                        <th class="px-6 py-4 text-center font-bold">Sakit</th>
                        <th class="px-6 py-4 text-center font-bold">Izin</th>
                        <th class="px-6 py-4 text-center font-bold">KJK</th>
                        <th class="px-6 py-4 text-center font-bold">Alpha</th>
                        <th class="px-6 py-4 text-center font-bold">Total</th>
                        <th class="px-6 py-4 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekap as $index => $row)
                        @php
                            $hadir = $row->presensis->whereIn('status', ['Hadir', 'Terlambat', 'Terlambat/PSW', 'KJK (Kekurangan Jam Kerja)', 'Tidak Presensi Pulang(TPP)'])->count();
                            $sakit = $row->presensis->where('status', 'Sakit')->count();
                            $izin  = $row->presensis->whereIn('status', ['Izin', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari'])->count();
                            $kjk   = $row->presensis->where('status', 'KJK (Kekurangan Jam Kerja)')->count();
                            $alpha = $row->presensis->where('status', 'Alpha/Tanpa Kabar')->count();
                            $total = $hadir + $sakit + $izin;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                                        {{ substr($row->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $row->name }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $row->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $hadir }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $sakit }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $izin }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-amber-500 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $kjk }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-400 text-white px-3 py-1 rounded-lg text-xs font-bold">{{ $alpha }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                {{ $total }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    // Cari ID pengajuan magang yang diterima untuk link detail
                                    $magangId = $row->pengajuanMagang->id ?? null;
                                @endphp
                                @if($magangId)
                                    <a href="{{ route('admin.peserta_magang_aktif.laporan_presensi', $magangId) }}?bulan={{ $bulan }}&tahun={{ $tahun }}" 
                                       class="inline-flex items-center gap-1 border border-blue-200 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        <i class="fas fa-chart-line"></i> Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-users-slash text-4xl mb-3 block"></i>
                                Tidak ada peserta magang aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    aside, header, footer, form, .container > div:first-child, .flex.gap-2 {
        display: none !important;
    }
    main {
        padding: 0 !important;
        background: white !important;
    }
    .bg-white {
        box-shadow: none !important;
        border: 0 !important;
    }
}
</style>
@endsection
