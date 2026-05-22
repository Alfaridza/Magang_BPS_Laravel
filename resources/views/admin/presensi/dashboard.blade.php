@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Presensi</h2>
        <div class="text-sm text-gray-500">
            <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Left Column: Stats, Filters, Table --}}
        <div class="lg:col-span-3">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10 -mr-4 -mt-4">
                        <i class="fas fa-users text-8xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="text-sm font-semibold opacity-80 uppercase tracking-wider mb-1">Total Peserta Aktif</div>
                        <div class="text-4xl font-extrabold">{{ $stats['total_peserta'] }}</div>
                        <div class="mt-4 text-xs bg-white bg-opacity-20 inline-block px-3 py-1 rounded-full">
                            <i class="fas fa-info-circle mr-1"></i> Peserta dengan pengajuan 'Diterima'
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10 -mr-4 -mt-4">
                        <i class="fas fa-calendar-check text-8xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="text-sm font-semibold opacity-80 uppercase tracking-wider mb-1">Presensi Terdaftar (Hari Ini)</div>
                        <div class="text-4xl font-extrabold">{{ $stats['presensi_hari_ini'] }}</div>
                        <div class="mt-4 text-xs bg-white bg-opacity-20 inline-block px-3 py-1 rounded-full">
                            <i class="fas fa-clock mr-1"></i> Per {{ date('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 mb-8">
                <form action="{{ route('admin.presensi.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" 
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Kehadiran</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Hadir" {{ $statusFilter == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Terlambat" {{ $statusFilter == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="KJK (Kekurangan Jam Kerja)" {{ $statusFilter == 'KJK (Kekurangan Jam Kerja)' ? 'selected' : '' }}>KJK (Kekurangan Jam Kerja)</option>
                            <option value="Izin" {{ $statusFilter == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ $statusFilter == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin Setengah Hari" {{ $statusFilter == 'Izin Setengah Hari' ? 'selected' : '' }}>Izin Setengah Hari</option>
                            <option value="Kendala" {{ $statusFilter == 'Kendala' ? 'selected' : '' }}>Lapor Kendala</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Main Table --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 mb-8">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Daftar Kehadiran Peserta</h3>
                    <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                <th class="px-6 py-4 text-left font-semibold">Peserta</th>
                                <th class="px-6 py-4 text-center font-semibold">Jam Masuk</th>
                                <th class="px-6 py-4 text-center font-semibold">Jam Pulang</th>
                                <th class="px-6 py-4 text-center font-semibold">Status</th>
                                <th class="px-6 py-4 text-center font-semibold">Persetujuan</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($dataTabel as $row)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $row['nama'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-700">
                                        {{ $row['jam_in'] ? substr($row['jam_in'], 0, 5) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-700">
                                        {{ $row['jam_out'] ? substr($row['jam_out'], 0, 5) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClass = match($row['status']) {
                                                'Hadir'     => 'bg-green-100 text-green-700 border border-green-200',
                                                'Terlambat', 'Terlambat/PSW' => 'bg-orange-100 text-orange-700 border border-orange-200',
                                                'KJK (Kekurangan Jam Kerja)' => 'bg-amber-100 text-amber-700 border border-amber-300',
                                                'Izin', 'Izin Setengah Hari', 'Izin Kerja Setengah Hari' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                                'Sakit'     => 'bg-red-100 text-red-700 border border-red-200',
                                                'Kendala'   => 'bg-purple-100 text-purple-700 border border-purple-200',
                                                'Tidak Presensi Pulang(TPP)' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                                'Alpha/Tanpa Kabar' => 'bg-gray-200 text-gray-700 border border-gray-300',
                                                default     => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClass }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($row['approve'] == 1)
                                            <span class="text-green-600" title="Disetujui"><i class="fas fa-check-circle"></i></span>
                                        @elseif($row['approve'] == 2)
                                            <span class="text-red-600" title="Ditolak"><i class="fas fa-times-circle"></i></span>
                                        @else
                                            <span class="text-yellow-500" title="Pending"><i class="fas fa-clock"></i></span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="openEditModal({{ json_encode($row) }})" class="text-blue-600 hover:text-blue-900 transition">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                        <i class="fas fa-folder-open text-3xl mb-2 block"></i>
                                        Tidak ada data untuk kriteria ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column: Quick Actions & Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Quick Actions / Shortcuts --}}
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white border border-gray-700">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-400"></i> Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.presensi.monitoring_izin') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-xl flex items-center gap-3 transition border border-white border-opacity-10">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-medical text-lg"></i>
                        </div>
                        <span class="text-sm font-semibold">Cek Perizinan</span>
                    </a>
                    <a href="{{ route('admin.presensi.monitoring_kendala') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-xl flex items-center gap-3 transition border border-white border-opacity-10">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-lg"></i>
                        </div>
                        <span class="text-sm font-semibold">Cek Kendala</span>
                    </a>
                    <a href="{{ route('admin.presensi.laporan_bulanan') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-xl flex items-center gap-3 transition border border-white border-opacity-10">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-print text-lg"></i>
                        </div>
                        <span class="text-sm font-semibold">Laporan Bulanan</span>
                    </a>
                    <a href="{{ route('admin.log_activity.index') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-xl flex items-center gap-3 transition border border-white border-opacity-10">
                        <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-history text-lg"></i>
                        </div>
                        <span class="text-sm font-semibold">Log Aktivitas</span>
                    </a>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 text-6xl transform -rotate-12">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i> Informasi Sistem
                </h4>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Data di dashboard ini diperbarui secara real-time berdasarkan aktivitas peserta magang. Pastikan untuk meninjau pengajuan <b>Izin</b> dan <b>Kendala</b> secara berkala agar laporan bulanan akurat.
                </p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                        <i class="fas fa-sync-alt fa-spin"></i> Live Update Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Presensi Manual -->
<div id="editManualModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[60]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Edit Presensi Manual</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="editManualForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Peserta</label>
                <input type="text" id="modal_nama" disabled class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Masuk</label>
                    <input type="time" name="jam_in" id="modal_jam_in" step="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Pulang</label>
                    <input type="time" name="jam_out" id="modal_jam_out" step="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Kehadiran</label>
                <select name="status" id="modal_status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="Hadir">Hadir</option>
                    <option value="Terlambat/PSW">Terlambat/PSW</option>
                    <option value="KJK (Kekurangan Jam Kerja)">KJK (Kekurangan Jam Kerja)</option>
                    <option value="Izin Setengah Hari">Izin Setengah Hari</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpha/Tanpa Kabar">Alpha/Tanpa Kabar</option>
                    <option value="Tidak Presensi Pulang(TPP)">Tidak Presensi Pulang(TPP)</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('editManualModal');
    const form = document.getElementById('editManualForm');

    function openEditModal(row) {
        document.getElementById('modal_nama').value = row.nama;
        document.getElementById('modal_jam_in').value = row.jam_in || '';
        document.getElementById('modal_jam_out').value = row.jam_out || '';
        document.getElementById('modal_status').value = row.status;
        
        form.action = "{{ url('admin/presensi/update-manual') }}/" + row.id;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Close on click outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
</script>
@endsection
