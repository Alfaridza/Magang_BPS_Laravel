@extends('presensi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-40 h-40 -mr-20 -mt-20 bg-white bg-opacity-10 rounded-full"></div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('presensi.dashboard') }}" class="text-blue-300 hover:text-white transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold">Histori Presensi</h1>
            </div>
            <p class="text-blue-200 text-sm ml-7">Riwayat kehadiran Anda</p>
        </div>

        {{-- Stats Summary --}}
        @php
            $totalHadir  = $riwayat->where('status', 'Hadir')->count();
            $totalIzin   = $riwayat->where('status', 'Izin')->count();
            $totalSakit  = $riwayat->where('status', 'Sakit')->count();
            $totalTerlambat = $riwayat->where('status', 'Terlambat')->count();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm text-center border border-gray-100">
                <div class="text-2xl font-bold text-green-600">{{ $totalHadir }}</div>
                <div class="text-xs text-gray-500 mt-1">Hadir</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center border border-gray-100">
                <div class="text-2xl font-bold text-yellow-500">{{ $totalTerlambat }}</div>
                <div class="text-xs text-gray-500 mt-1">Terlambat</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center border border-gray-100">
                <div class="text-2xl font-bold text-blue-500">{{ $totalIzin }}</div>
                <div class="text-xs text-gray-500 mt-1">Izin</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center border border-gray-100">
                <div class="text-2xl font-bold text-red-500">{{ $totalSakit }}</div>
                <div class="text-xs text-gray-500 mt-1">Sakit</div>
            </div>
        </div>

        {{-- Tabel Histori --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            @if($riwayat->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                    <p class="text-lg font-medium">Belum ada riwayat presensi</p>
                    <p class="text-sm mt-1">Data akan muncul setelah Anda melakukan absen pertama</p>
                </div>
            @else
                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($riwayat as $item)
                            @php
                                $isPerizinan = in_array($item->status, ['Izin', 'Sakit', 'Izin Setengah Hari']);
                                $durasi = null;
                                if (!$isPerizinan && $item->jam_in && $item->jam_out) {
                                    $masuk  = \Carbon\Carbon::parse($item->jam_in);
                                    $pulang = \Carbon\Carbon::parse($item->jam_out);
                                    $diff   = $masuk->diff($pulang);
                                    $durasi = $diff->h . 'j ' . $diff->i . 'm';
                                }

                                $approveStatus = match($item->status_approve) {
                                    1 => ['class' => 'bg-green-100 text-green-700', 'text' => 'Disetujui', 'icon' => 'fa-check-circle'],
                                    2 => ['class' => 'bg-red-100 text-red-700', 'text' => 'Ditolak', 'icon' => 'fa-times-circle'],
                                    default => ['class' => 'bg-yellow-100 text-yellow-700', 'text' => 'Pending', 'icon' => 'fa-clock'],
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($item->jam_in)
                                        <span class="inline-flex items-center gap-1 text-green-700 font-semibold">
                                            <i class="fas fa-sign-in-alt text-xs text-green-500"></i>
                                            {{ \Carbon\Carbon::parse($item->jam_in)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($item->jam_out)
                                        <span class="inline-flex items-center gap-1 text-red-600 font-semibold">
                                            <i class="fas fa-sign-out-alt text-xs text-red-400"></i>
                                            {{ \Carbon\Carbon::parse($item->jam_out)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($isPerizinan)
                                        <span class="text-gray-400">—</span>
                                    @elseif($durasi)
                                        <span class="text-gray-600 text-sm">{{ $durasi }}</span>
                                    @else
                                        <span class="text-gray-300 text-xs">Belum pulang</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $statusClass = match($item->status) {
                                            'Hadir'     => 'bg-green-100 text-green-700',
                                            'Terlambat', 'Terlambat/PSW' => 'bg-orange-100 text-orange-700',
                                            'KJK (Kekurangan Jam Kerja)' => 'bg-amber-100 text-amber-700',
                                            'Izin', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari' => 'bg-blue-100 text-blue-700',
                                            'Sakit'     => 'bg-red-100 text-red-700',
                                            'Tidak Presensi Pulang(TPP)' => 'bg-yellow-100 text-yellow-700',
                                            'Alpha/Tanpa Kabar' => 'bg-gray-200 text-gray-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $approveStatus['class'] }}">
                                        <i class="fas {{ $approveStatus['icon'] }} text-[10px]"></i>
                                        {{ $approveStatus['text'] }}
                                    </span>
                                    @if($item->status_approve == 2 && $item->keterangan_admin)
                                        <div class="mt-1 text-[10px] text-red-500 italic max-w-[120px] mx-auto leading-tight">
                                            "{{ $item->keterangan_admin }}"
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List --}}
                <div class="sm:hidden divide-y divide-gray-100">
                    @foreach($riwayat as $item)
                    @php
                        $isPerizinan = in_array($item->status, ['Izin', 'Sakit', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari']);
                        $durasi = null;
                        if (!$isPerizinan && $item->jam_in && $item->jam_out) {
                            $masuk  = \Carbon\Carbon::parse($item->jam_in);
                            $pulang = \Carbon\Carbon::parse($item->jam_out);
                            $diff   = $masuk->diff($pulang);
                            $durasi = $diff->h . 'j ' . $diff->i . 'm';
                        }
                        $statusClass = match($item->status) {
                            'Hadir'     => 'bg-green-100 text-green-700',
                            'Terlambat', 'Terlambat/PSW' => 'bg-orange-100 text-orange-700',
                            'KJK (Kekurangan Jam Kerja)' => 'bg-amber-100 text-amber-700',
                            'Izin', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari' => 'bg-blue-100 text-blue-700',
                            'Sakit'     => 'bg-red-100 text-red-700',
                            'Tidak Presensi Pulang(TPP)' => 'bg-yellow-100 text-yellow-700',
                            'Alpha/Tanpa Kabar' => 'bg-gray-200 text-gray-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $approveStatus = match($item->status_approve) {
                            1 => ['class' => 'bg-green-100 text-green-700', 'text' => 'Disetujui', 'icon' => 'fa-check-circle'],
                            2 => ['class' => 'bg-red-100 text-red-700', 'text' => 'Ditolak', 'icon' => 'fa-times-circle'],
                            default => ['class' => 'bg-yellow-100 text-yellow-700', 'text' => 'Pending', 'icon' => 'fa-clock'],
                        };
                    @endphp
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $item->status }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $approveStatus['class'] }}">
                                    <i class="fas {{ $approveStatus['icon'] }}"></i> {{ $approveStatus['text'] }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-green-50 rounded-lg p-2">
                                <div class="text-xs text-gray-400 mb-0.5">Masuk</div>
                                <div class="font-semibold text-green-600 text-sm">
                                    {{ $item->jam_in ? \Carbon\Carbon::parse($item->jam_in)->format('H:i') : '—' }}
                                </div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-2">
                                <div class="text-xs text-gray-400 mb-0.5">Pulang</div>
                                <div class="font-semibold text-red-500 text-sm">
                                    {{ $item->jam_out ? \Carbon\Carbon::parse($item->jam_out)->format('H:i') : '—' }}
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2">
                                <div class="text-xs text-gray-400 mb-0.5">Durasi</div>
                                <div class="font-semibold text-gray-600 text-sm">
                                    {{ $isPerizinan ? '—' : ($durasi ?? '—') }}
                                </div>
                            </div>
                        </div>
                        @if($item->keterangan)
                            <p class="mt-2 text-xs text-gray-500 italic">{{ $item->keterangan }}</p>
                        @endif
                        @if($item->status_approve == 2 && $item->keterangan_admin)
                            <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded-lg text-[11px] text-red-600">
                                <span class="font-bold">Alasan Ditolak:</span> {{ $item->keterangan_admin }}
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($riwayat->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
