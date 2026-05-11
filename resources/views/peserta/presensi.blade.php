@extends('peserta.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-blue-800 to-indigo-900 p-6 text-white relative overflow-hidden">
            <div class="absolute right-0 top-0 w-40 h-40 -mr-20 -mt-20 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute right-20 top-10 w-24 h-24 -mr-10 -mt-5 bg-white bg-opacity-5 rounded-full"></div>
            
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        PRESMA Mobile BPS
                    </h1>
                    <p class="text-blue-200 mt-1">Presensi Magang Provinsi Banten</p>
                </div>
                
                <div class="flex flex-col items-end gap-2">
                    <div class="bg-blue-700 bg-opacity-50 rounded-full py-2 px-4 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="truncate max-w-[100px]">{{ $user->name ?? 'User' }}</span>
                    </div>
                    {{-- Tombol Logout Presensi --}}
                    <form action="{{ route('presensi.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 bg-red-500 bg-opacity-80 hover:bg-opacity-100 text-white text-xs font-semibold py-1.5 px-3 rounded-full transition">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-blue-700 bg-opacity-50 px-3 py-1 rounded-full text-xs font-bold tracking-wider" id="current-day">--- ---</span>
                    <span class="text-blue-200 text-sm" id="current-date">-- -- ----</span>
                </div>
                <div class="text-4xl font-bold tracking-tighter" id="live-clock">--:--:--</div>
            </div>
        </div>
        
        <div class="p-6">
            @if(!$isProfileComplete || !$isApplicationApproved)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-red-800 font-bold text-lg">Akses Presensi Belum Tersedia</h3>
                            <div class="mt-2 text-red-700">
                                @if(!$isProfileComplete)
                                    <p><i class="fas fa-chevron-right text-xs mr-2"></i> Silakan lengkapi data profil terlebih dahulu</p>
                                    <p class="mb-3 ml-5"><a href="{{ url('peserta/profil') }}" class="text-blue-600 hover:underline font-medium">Lengkapi Profil <i class="fas fa-arrow-right ml-1 text-xs"></i></a></p>
                                @endif
                                
                                @if(!$isApplicationApproved)
                                    <p><i class="fas fa-chevron-right text-xs mr-2"></i> Status pengajuan magang belum disetujui</p>
                                    @php
                                        $latestPengajuan = \App\Models\PengajuanMagang::where('user_id', $user->id)->latest()->first();
                                    @endphp
                                    @if($latestPengajuan)
                                        <p class="mb-3 ml-5">Status terkini: 
                                            @if($latestPengajuan->status_pengajuan === 'Menunggu')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-medium">Menunggu Persetujuan</span>
                                            @elseif($latestPengajuan->status_pengajuan === 'Ditolak')
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">Ditolak</span>
                                                @if($latestPengajuan->alasan_penolakan)
                                                    <p class="mt-2 text-sm bg-red-100 p-2 rounded"><strong>Alasan Penolakan:</strong> {{ $latestPengajuan->alasan_penolakan }}</p>
                                                @endif
                                            @else
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ ucfirst($latestPengajuan->status_pengajuan) }}</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-3 ml-5">Anda belum mengajukan magang. <a href="{{ url('peserta/daftar-magang') }}" class="text-blue-600 hover:underline font-medium">Ajukan Sekarang <i class="fas fa-arrow-right ml-1 text-xs"></i></a></p>
                                    @endif
                                @endif
                                
                                <p class="mt-3">Fitur presensi hanya tersedia setelah:</p>
                                <ul class="mt-2 list-disc pl-5 space-y-1">
                                    <li>Data profil peserta sudah lengkap</li>
                                    <li>Pengajuan magang sudah diajukan dan disetujui oleh admin</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($isProfileComplete && $isApplicationApproved)
                {{-- Attendance Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Check In Card --}}
                    <div class="border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Waktu Masuk</h3>
                        </div>
                        
                        <div class="text-center py-4">
                            <div class="text-3xl font-bold text-gray-800 mb-2" id="time-masuk">--:--:--</div>
                            <div class="text-gray-500 text-sm" id="status-masuk">Belum check-in</div>
                        </div>
                    </div>
                    
                    {{-- Check Out Card --}}
                    <div class="border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Waktu Pulang</h3>
                        </div>
                        
                        <div class="text-center py-4">
                            <div class="text-3xl font-bold text-gray-800 mb-2" id="time-pulang">--:--:--</div>
                            <div class="text-gray-500 text-sm" id="status-pulang">Belum check-out</div>
                        </div>
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <button 
                        id="btn-checkin" 
                        onclick="doCheckIn()" 
                        class="flex-1 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg shadow transition duration-200"
                    >
                        <i class="fas fa-camera"></i> Check In Sekarang
                    </button>
                    <button 
                        id="btn-checkout" 
                        disabled 
                        class="flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-4 rounded-lg shadow transition duration-200 opacity-50 cursor-not-allowed"
                    >
                        <i class="fas fa-camera"></i> Check Out Sekarang
                    </button>
                </div>
                
                {{-- Menu Actions --}}
                <div class="space-y-3 mb-8">
                    <a href="#" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Histori Presensi</h4>
                                <p class="text-sm text-gray-500">Lihat seluruh presensi Anda</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-blue-600">
                            <span class="text-sm">Lihat</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-file-medical text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Form Izin</h4>
                                <p class="text-sm text-gray-500">Ajukan izin atau sakit</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-600">
                            <span class="text-sm">Ajukan</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </a>
                    
                    <a href="#" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Kendala Presensi?</h4>
                                <p class="text-sm text-gray-500">Lapor manual jika ada error</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-orange-600">
                            <span class="text-sm">Lapor</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </a>
                </div>
            @endif
            
            {{-- Note Banner --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex gap-3">
                    <i class="fas fa-lightbulb text-yellow-500 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-yellow-800">Catatan</h4>
                        <p class="text-yellow-700 text-sm">
                            @if($isProfileComplete && $isApplicationApproved)
                                Gunakan tombol <b class="font-bold">Check In</b> saat tiba dan <b class="font-bold">Check Out</b> saat hendak pulang. 
                                Data presensi tercatat otomatis dan dapat dilihat di menu Histori.
                            @else
                                Lengkapi data profil dan pastikan pengajuan magang disetujui untuk mengakses fitur presensi.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /* ── Live Clock ─────────────────────────────────────────── */
    const DAYS_ABR = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
    const DAYS_FULL = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const MONTHS_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function pad(n) { 
        return String(n).padStart(2, '0'); 
    }

    function tick() {
        const now = new Date();
        document.getElementById('live-clock').textContent =
            `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        document.getElementById('current-day').textContent = 
            `${DAYS_FULL[now.getDay()]}, ${now.getDate()} ${MONTHS_ID[now.getMonth()]} ${now.getFullYear()}`;
    }
    
    // Initial update
    tick();
    // Update every second
    setInterval(tick, 1000);

    /* ── Check In / Out Functions ───────────────────────────── */
    let checkedIn = false;

    function doCheckIn() {
        if (checkedIn) return;
        
        // Get current time
        const now = new Date();
        const timeStr = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

        // Update UI for check-in
        document.getElementById('time-masuk').textContent = timeStr;
        document.getElementById('status-masuk').textContent = 'Sudah check-in ✓';
        document.getElementById('status-masuk').className = 'text-green-600 text-sm font-medium';

        // Disable check-in button
        const btnIn = document.getElementById('btn-checkin');
        btnIn.disabled = true;
        btnIn.className = 'flex-1 flex items-center justify-center gap-2 bg-gray-400 text-white font-bold py-4 px-4 rounded-lg shadow cursor-not-allowed';

        // Enable check-out button
        const btnOut = document.getElementById('btn-checkout');
        btnOut.disabled = false;
        btnOut.className = 'flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-4 rounded-lg shadow transition duration-200';
        
        checkedIn = true;
        
        // Here you would typically send the check-in data to the server
        // fetch('/api/checkin', { method: 'POST', ... })
    }

    function doCheckOut() {
        const now = new Date();
        const timeStr = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

        // Update UI for check-out
        document.getElementById('time-pulang').textContent = timeStr;
        document.getElementById('status-pulang').textContent = 'Sudah check-out ✓';
        document.getElementById('status-pulang').className = 'text-green-600 text-sm font-medium';

        // Disable check-out button
        const btnOut = document.getElementById('btn-checkout');
        btnOut.disabled = true;
        btnOut.className = 'flex-1 flex items-center justify-center gap-2 bg-gray-400 text-white font-bold py-4 px-4 rounded-lg shadow opacity-50 cursor-not-allowed';
        
        // Here you would typically send the check-out data to the server
        // fetch('/api/checkout', { method: 'POST', ... })
    }
</script>
@endsection