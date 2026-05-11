@extends('admin.layouts.app')

@section('header', 'Dashboard Statistik')

@section('content')
    <h1 class="text-2xl font-normal text-gray-800 mb-6 font-sans">Dashboard Statistik</h1>

    <!-- Alert Informasi Pengajuan Baru -->
    @if($perlu_verifikasi > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-blue-500 bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div>
                    <p class="text-blue-800 font-bold text-lg">Terdapat Pengajuan Magang Baru!</p>
                    <p class="text-blue-600 text-sm mt-1">Ada {{ $perlu_verifikasi }} peserta yang mengirimkan formulir pengajuan magang dan sedang menunggu verifikasi Anda.</p>
                </div>
            </div>
            <a href="{{ route('admin.pengajuan_magang.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors text-sm whitespace-nowrap">
                Lihat Pengajuan <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif

    <!-- First Row: Top 4 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        
        <!-- Total Pendaftar -->
        <div class="bg-[#17a2b8] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $total_pendaftar }}</h3>
                <p class="text-sm mb-4">Total Pendaftar</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <!-- Perlu Verifikasi -->
        <div class="bg-[#ffc107] rounded-sm p-4 text-black relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $perlu_verifikasi }}</h3>
                <p class="text-sm mb-4">Perlu Verifikasi</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <!-- Sedang Magang -->
        <div class="bg-[#28a745] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $sedang_magang }}</h3>
                <p class="text-sm mb-4">Sedang Magang</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

        <!-- Alumni / Selesai -->
        <div class="bg-[#6f42c1] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $alumni_selesai }}</h3>
                <p class="text-sm mb-4">Alumni / Selesai</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>

    <!-- Second Row: Two Tables -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- Tabel Pengajuan Terbaru -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-signature text-blue-500 text-sm"></i>
                    </div>
                    <h2 class="text-base font-semibold text-gray-800">Pengajuan Menunggu Verifikasi</h2>
                </div>
                <a href="{{ route('admin.pengajuan_magang.index') }}" class="text-xs text-blue-600 hover:underline font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-0.5"></i>
                </a>
            </div>

            @if($pengajuan_terbaru->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">Tidak ada pengajuan yang menunggu verifikasi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-5 py-3 text-left font-semibold">Nama</th>
                                <th class="px-5 py-3 text-left font-semibold">Instansi</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                                <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pengajuan_terbaru as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-gray-800 truncate max-w-[140px]" title="{{ $item->nama_lengkap }}">
                                            {{ $item->nama_lengkap ?? ($item->user->name ?? '-') }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $item->nim_nisn ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-gray-600 truncate max-w-[130px]" title="{{ $item->nama_sekolah }}">
                                            {{ $item->nama_sekolah ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $item->jurusan ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @php
                                            $statusConfig = [
                                                'Menunggu'  => ['bg-yellow-100 text-yellow-700', 'fa-clock'],
                                                'Diterima'  => ['bg-green-100 text-green-700',  'fa-check-circle'],
                                                'Ditolak'   => ['bg-red-100 text-red-700',      'fa-times-circle'],
                                            ];
                                            $cfg = $statusConfig[$item->status_pengajuan] ?? ['bg-gray-100 text-gray-600', 'fa-question-circle'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $cfg[0] }}">
                                            <i class="fas {{ $cfg[1] }}"></i>
                                            {{ $item->status_pengajuan }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Tabel Peserta Segera Selesai Magang -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-hourglass-end text-orange-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">Segera Selesai Magang</h2>
                        <p class="text-xs text-gray-400">Periode berakhir dalam 14 hari ke depan</p>
                    </div>
                </div>
                <a href="{{ route('admin.peserta_magang_aktif.index') }}" class="text-xs text-blue-600 hover:underline font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-0.5"></i>
                </a>
            </div>

            @if($segera_selesai->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <i class="fas fa-calendar-check text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">Tidak ada peserta yang akan selesai dalam 14 hari ke depan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-5 py-3 text-left font-semibold">Nama</th>
                                <th class="px-5 py-3 text-left font-semibold">Instansi</th>
                                <th class="px-5 py-3 text-left font-semibold">Selesai</th>
                                <th class="px-5 py-3 text-left font-semibold">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($segera_selesai as $item)
                                @php
                                    $hariSisa = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($item->periode_selesai));
                                    $isKritis = $hariSisa <= 3;
                                    $isMendekat = $hariSisa <= 7;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors {{ $isKritis ? 'bg-red-50' : '' }}">
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-gray-800 truncate max-w-[140px]" title="{{ $item->nama_lengkap }}">
                                            {{ $item->nama_lengkap ?? ($item->user->name ?? '-') }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $item->nim_nisn ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-gray-600 truncate max-w-[130px]" title="{{ $item->nama_sekolah }}">
                                            {{ $item->nama_sekolah ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $item->jurusan ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 text-xs whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->periode_selesai)->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                                            {{ $isKritis ? 'bg-red-100 text-red-700' : ($isMendekat ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            <i class="fas {{ $isKritis ? 'fa-exclamation-triangle' : 'fa-hourglass-half' }}"></i>
                                            {{ $hariSisa }} hari
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

@endsection
