@extends('admin.layouts.app')

@section('header', 'Peserta Magang Aktif')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flash-message" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Peserta Magang Disetujui</h2>
        
        <div class="flex gap-2 w-full md:w-auto">
            <form method="GET" action="{{ route('admin.peserta_magang_aktif.index') }}" class="w-full">
                <div class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari peserta..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-100 table-auto rounded-lg">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 border-b border-gray-200">
                    <th class="py-3 px-4 border-b">No</th>
                    <th class="py-3 px-4 border-b">Peserta</th>
                    <th class="py-3 px-4 border-b">Instansi</th>
                    <th class="py-3 px-4 border-b">Periode</th>
                    <th class="py-3 px-4 border-b">Kontak</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($pengajuans as $index => $magang)
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                        <td class="py-3 px-4">{{ $pengajuans->firstItem() + $index }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-800">{{ $magang->nama_lengkap ?? ($magang->user->name ?? '-') }}</div>
                            <div class="text-xs text-gray-500">{{ $magang->status_peserta }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div>{{ $magang->nama_sekolah }}</div>
                            <div class="text-xs text-gray-500">{{ $magang->jurusan }}</div>
                        </td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::parse($magang->periode_mulai)->format('d M Y') }} s/d <br>
                            {{ \Carbon\Carbon::parse($magang->periode_selesai)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4">
                            @if($magang->user)
                                <div><a href="mailto:{{ $magang->user->email }}" class="text-blue-600 hover:text-blue-800">{{ $magang->user->email }}</a></div>
                                <div class="text-gray-500 text-xs">{{ $magang->user->no_hp ?? '-' }}</div>
                            @else
                                <span class="text-gray-400">User terhapus</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.peserta_magang_aktif.show', $magang->id) }}" class="text-blue-600 hover:text-blue-900 text-base" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.peserta_magang_aktif.edit', $magang->id) }}" class="text-yellow-600 hover:text-yellow-900 text-base" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.peserta_magang_aktif.laporan_presensi', $magang->id) }}" class="text-green-600 hover:text-green-900 text-base" title="Laporan Presensi">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <form action="{{ route('admin.peserta_magang_aktif.destroy', $magang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan magang ini? Akun pengguna tidak akan terhapus.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-base" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500 font-medium">
                            @if(request('search'))
                                Tidak ditemukan peserta magang aktif dengan kata kunci "{{ request('search') }}"
                            @else
                                Belum ada peserta magang aktif.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pengajuans->count() > 0)
        <div class="mt-6">
            {{ $pengajuans->links() }}
        </div>
    @endif
</div>
@endsection
