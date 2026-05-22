@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Monitoring Kendala Presensi</h2>
            <p class="text-gray-500 text-sm">Tinjau laporan masalah teknis yang dialami peserta saat melakukan presensi.</p>
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
                        <th class="px-6 py-4 text-center font-semibold">Klaim Jam</th>
                        <th class="px-6 py-4 text-left font-semibold">Jenis Kendala</th>
                        <th class="px-6 py-4 text-left font-semibold">Alasan/Kronologi</th>
                        <th class="px-6 py-4 text-center font-semibold">Status</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kendala as $k)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $k->user->name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase">{{ $k->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-xs">
                                    <span class="text-green-600 font-semibold">In:</span> {{ $k->jam_in ? substr($k->jam_in, 0, 5) : '-' }}<br>
                                    <span class="text-red-600 font-semibold">Out:</span> {{ $k->jam_out ? substr($k->jam_out, 0, 5) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-orange-600">{{ $k->jenis_kendala }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-600 max-w-xs" title="{{ $k->keterangan }}">
                                    {{ $k->keterangan }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($k->status_approve == 0)
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-clock"></i> PENDING
                                    </span>
                                @elseif($k->status_approve == 1)
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
                                @if($k->status_approve == 0)
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.presensi.approve_kendala', $k->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition" title="Setujui">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.presensi.reject_kendala', $k->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition" title="Tolak">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-exclamation-triangle text-3xl mb-2 block"></i>
                                Tidak ada data laporan kendala.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kendala->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $kendala->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
