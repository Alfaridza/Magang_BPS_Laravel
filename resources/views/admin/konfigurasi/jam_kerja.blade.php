@extends('admin.layouts.app')

@section('header', 'Konfigurasi Jam Kerja')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-clock mr-3 text-gray-700"></i> Konfigurasi Jam Kerja
        </h1>
        <p class="text-gray-600 mt-1">Atur jam masuk, pulang, dan toleransi untuk berbagai periode.</p>
    </div>
    <button onclick="openAddModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center font-semibold shadow-sm">
        <i class="fas fa-plus mr-2"></i> Tambah Konfigurasi
    </button>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flash-message" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6">Daftar Konfigurasi</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white table-auto">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Jam Masuk</th>
                    <th class="py-3 px-4">Toleransi</th>
                    <th class="py-3 px-4">Jam Pulang</th>
                    <th class="py-3 px-4">Periode</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($konfigurasi as $item)
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                        <td class="py-4 px-4">
                            <div class="font-bold text-gray-900">{{ $item->nama }}</div>
                            <div class="text-xs text-blue-500 mt-0.5">{{ $item->is_wfa ? 'WFA & WFO' : 'WFO Only' }}</div>
                        </td>
                        <td class="py-4 px-4 font-medium">{{ $item->jam_masuk }}</td>
                        <td class="py-4 px-4 font-medium">{{ $item->jam_masuk_toleransi }}</td>
                        <td class="py-4 px-4 font-medium">{{ $item->jam_pulang }}</td>
                        <td class="py-4 px-4">
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            @else
                                <span class="text-gray-500 italic">Default (Selamanya)</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($item->status)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($item) }})" class="bg-yellow-500 text-white p-2 rounded-md hover:bg-yellow-600 transition shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.konfigurasi.delete_jam_kerja', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konfigurasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition shadow-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500 font-medium">Belum ada konfigurasi jam kerja.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="konfigurasiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[60]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden relative">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah Konfigurasi</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="modalForm" method="POST" class="p-6 space-y-5">
            @csrf
            <div id="methodField"></div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" id="form_nama" required placeholder="Contoh: Reguler, Ramadan 2026"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_wfa" id="form_is_wfa" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="form_is_wfa" class="ml-2 text-sm text-gray-700">Izinkan absensi WFA pada periode ini</label>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 text-xs uppercase">Jam Masuk</label>
                    <div class="relative">
                        <input type="time" name="jam_masuk" id="form_jam_masuk" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 text-xs uppercase">Toleransi</label>
                    <div class="relative">
                        <input type="time" name="jam_masuk_toleransi" id="form_jam_masuk_toleransi" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1 text-xs uppercase">Jam Pulang</label>
                    <div class="relative">
                        <input type="time" name="jam_pulang" id="form_jam_pulang" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tgl Mulai (Opsional)</label>
                    <input type="date" name="tanggal_mulai" id="form_tanggal_mulai"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tgl Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" id="form_tanggal_selesai"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Radius Presensi (Meter)</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="radius_meter" id="form_radius_meter" required min="1" value="50"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-sm text-gray-500 font-medium whitespace-nowrap">Meter</span>
                </div>
                <p class="text-[10px] text-gray-500 mt-1 italic">* Jarak maksimal peserta dari titik kantor untuk bisa absen.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('konfigurasiModal');
    const modalForm = document.getElementById('modalForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openAddModal() {
        modalTitle.innerText = 'Tambah Konfigurasi';
        modalForm.action = "{{ route('admin.konfigurasi.store_jam_kerja') }}";
        methodField.innerHTML = '';
        
        // Reset form
        modalForm.reset();
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(item) {
        modalTitle.innerText = 'Edit Konfigurasi';
        modalForm.action = "{{ url('admin/konfigurasi/jam-kerja') }}/" + item.id;
        methodField.innerHTML = '@method("PUT")';
        
        // Fill form
        document.getElementById('form_nama').value = item.nama;
        document.getElementById('form_is_wfa').checked = item.is_wfa == 1;
        document.getElementById('form_jam_masuk').value = item.jam_masuk;
        document.getElementById('form_jam_masuk_toleransi').value = item.jam_masuk_toleransi;
        document.getElementById('form_jam_pulang').value = item.jam_pulang;
        document.getElementById('form_tanggal_mulai').value = item.tanggal_mulai || '';
        document.getElementById('form_tanggal_selesai').value = item.tanggal_selesai || '';
        document.getElementById('form_radius_meter').value = item.radius_meter || 50;
        
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
