@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Monitoring Perizinan</h2>
            <p class="text-gray-500 text-sm">Kelola pengajuan izin, sakit, dan izin setengah hari peserta.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
            <form action="{{ route('admin.presensi.monitoring_izin') }}" method="GET" class="flex items-center gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase">Filter Status:</label>
                <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Izin" {{ $statusFilter == 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ $statusFilter == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Izin Setengah Hari" {{ $statusFilter == 'Izin Setengah Hari' ? 'selected' : '' }}>Izin Setengah Hari</option>
                </select>
                @if($statusFilter)
                    <a href="{{ route('admin.presensi.monitoring_izin') }}" class="text-red-500 hover:text-red-700 p-1" title="Hapus Filter">
                        <i class="fas fa-times-circle text-lg"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold">Peserta</th>
                        <th class="px-6 py-4 text-center font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-center font-semibold">Tipe</th>
                        <th class="px-6 py-4 text-left font-semibold">Keterangan</th>
                        <th class="px-6 py-4 text-center font-semibold">Bukti</th>
                        <th class="px-6 py-4 text-center font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($perizinan as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $p->user->name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase">{{ $p->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase
                                    @if($p->status == 'Izin') bg-blue-100 text-blue-700
                                    @elseif($p->status == 'Sakit') bg-red-100 text-red-700
                                    @else bg-purple-100 text-purple-700 @endif
                                ">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-600 max-w-xs truncate" title="{{ $p->keterangan }}">
                                    {{ $p->keterangan }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->bukti_izin)
                                    <a href="{{ asset('storage/bukti_izin/' . $p->bukti_izin) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition">
                                        <i class="fas fa-image text-lg"></i>
                                    </a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->status_approve == 0)
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-clock"></i> PENDING
                                    </span>
                                @elseif($p->status_approve == 1)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-check-circle"></i> DISETUJUI
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-times-circle"></i> DITOLAK
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->status_approve == 0)
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.presensi.approve_izin', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition" title="Setujui">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                        <button type="button" 
                                            onclick="openRejectModal({{ $p->id }}, '{{ $p->user->name }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition" title="Tolak">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @elseif($p->status_approve == 2 && $p->keterangan_admin)
                                    <div class="text-[10px] text-red-500 italic max-w-[150px] mx-auto">
                                        "{{ $p->keterangan_admin }}"
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-file-medical text-3xl mb-2 block"></i>
                                Tidak ada data pengajuan perizinan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($perizinan->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $perizinan->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[100]">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden animate-fade-in-down">
        <div class="bg-red-600 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-bold">Tolak Perizinan</h3>
            <button onclick="closeRejectModal()" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-sm text-gray-600 mb-4">Berikan alasan penolakan untuk <span id="rejectUserName" class="font-bold text-gray-800"></span>:</p>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Alasan Penolakan</label>
                <textarea name="keterangan_admin" required rows="4" 
                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-red-500 focus:border-red-500"
                    placeholder="Contoh: Bukti tidak valid atau diluar ketentuan..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 rounded-lg transition">Batal</button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition shadow-md">Tolak Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id, name) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const nameSpan = document.getElementById('rejectUserName');
        
        nameSpan.textContent = name;
        form.action = `/admin/presensi/monitoring-izin/${id}/reject`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }
</style>
@endsection
