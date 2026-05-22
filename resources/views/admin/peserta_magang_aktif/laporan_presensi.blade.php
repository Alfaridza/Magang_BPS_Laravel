@extends('admin.layouts.app')

@section('header', 'Laporan Presensi Peserta')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.peserta_magang_aktif.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Presensi</h2>
            <p class="text-gray-500 text-sm">{{ $magang->nama_lengkap }} ({{ $magang->nama_sekolah }})</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <form action="{{ route('admin.peserta_magang_aktif.laporan_presensi', $magang->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bulan</label>
                <select name="bulan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tahun</label>
                <select name="tahun" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    @for($t=date('Y'); $t>=date('Y')-2; $t--)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Filter Tanggal Spesifik</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" 
                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition shadow-md">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <button type="button" onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-lg transition border border-gray-200">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- Report Content --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 print:shadow-none print:border-0">
        {{-- Header Report --}}
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="text-xl font-bold text-gray-800 uppercase tracking-wider">Rekapitulasi Kehadiran Peserta</h3>
                <p class="text-gray-500 mt-1">
                    Periode: 
                    @if($tanggal) 
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    @else
                        {{ \Carbon\Carbon::create(null, $bulan, 1)->translatedFormat('F') }} {{ $tahun }}
                    @endif
                </p>
            </div>
            <div class="text-right">
                <div class="font-extrabold text-blue-700 text-lg">{{ $magang->nama_lengkap }}</div>
                <div class="text-sm text-gray-500">{{ $magang->nim_nisn }} | {{ $magang->nama_sekolah }}</div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-center font-semibold">Jam Masuk</th>
                        <th class="px-6 py-4 text-center font-semibold">Jam Pulang</th>
                        <th class="px-6 py-4 text-center font-semibold">Status</th>
                        <th class="px-6 py-4 text-left font-semibold">Persetujuan</th>
                        <th class="px-6 py-4 text-left font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($presensis as $p)
                        <tr class="hover:bg-blue-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-gray-700">
                                {{ $p->jam_in ? substr($p->jam_in, 0, 5) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-gray-700">
                                {{ $p->jam_out ? substr($p->jam_out, 0, 5) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClass = match($p->status) {
                                        'Hadir'     => 'bg-green-100 text-green-700',
                                        'Terlambat', 'Terlambat/PSW' => 'bg-yellow-100 text-yellow-700',
                                        'KJK (Kekurangan Jam Kerja)' => 'bg-amber-100 text-amber-700',
                                        'Izin'      => 'bg-blue-100 text-blue-700',
                                        'Sakit'     => 'bg-red-100 text-red-700',
                                        'Izin Setengah Hari' => 'bg-purple-100 text-purple-700',
                                        'Tidak Presensi Pulang(TPP)' => 'bg-yellow-100 text-yellow-700',
                                        'Alpha/Tanpa Kabar' => 'bg-gray-200 text-gray-700',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $statusClass }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status_approve == 1)
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                        <i class="fas fa-check-circle"></i> DISETUJUI
                                    </span>
                                @elseif($p->status_approve == 2)
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                        <i class="fas fa-times-circle"></i> DITOLAK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                        <i class="fas fa-clock"></i> PENDING
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-500 max-w-xs" title="{{ $p->keterangan }}">
                                    {{ $p->keterangan ?? '-' }}
                                </p>
                                @if($p->status_approve == 2 && $p->keterangan_admin)
                                    <div class="mt-1 text-[10px] text-red-500 font-medium bg-red-50 p-1 rounded">
                                        <i class="fas fa-info-circle mr-1"></i> Admin: {{ $p->keterangan_admin }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                <i class="fas fa-calendar-times text-4xl mb-2 block"></i>
                                Tidak ada rekaman presensi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Summary Stats Footer --}}
        <div class="bg-gray-50 p-8 border-t border-gray-100 grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">Total Hadir</div>
                <div class="text-2xl font-black text-green-600">{{ $presensis->whereIn('status', ['Hadir', 'Terlambat', 'Terlambat/PSW', 'KJK (Kekurangan Jam Kerja)'])->count() }}</div>
            </div>
            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">Total Izin</div>
                <div class="text-2xl font-black text-blue-600">{{ $presensis->where('status', 'Izin')->count() }}</div>
            </div>
            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">Total Sakit</div>
                <div class="text-2xl font-black text-red-600">{{ $presensis->where('status', 'Sakit')->count() }}</div>
            </div>
            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">Terlambat</div>
                <div class="text-2xl font-black text-yellow-600">{{ $presensis->whereIn('status', ['Terlambat', 'Terlambat/PSW'])->count() }}</div>
            </div>
            <div class="text-center p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">KJK</div>
                <div class="text-2xl font-black text-amber-600">{{ $presensis->where('status', 'KJK (Kekurangan Jam Kerja)')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    aside, header, nav, form, .container > div:first-child, .flex.gap-2, .bg-gray-100 {
        display: none !important;
    }
    main {
        padding: 0 !important;
        margin: 0 !important;
        background: white !important;
    }
    .container {
        max-width: 100% !important;
        width: 100% !important;
    }
    .bg-white {
        box-shadow: none !important;
        border: 0 !important;
    }
    body {
        background: white !important;
    }
}
</style>
@endsection
