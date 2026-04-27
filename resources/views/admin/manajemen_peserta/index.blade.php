@extends('admin.layouts.app')

@section('header', 'Manajemen Peserta')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flash-message" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 flash-close-btn">
            <i class="fas fa-times text-green-700 hover:text-green-900"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 flash-message" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 flash-close-btn">
            <i class="fas fa-times text-red-700 hover:text-red-900"></i>
        </button>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Semua Peserta Terdaftar</h2>
        
        <div class="w-full md:w-auto">
            <form method="GET" action="{{ route('admin.manajemen_peserta.index') }}">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari berdasarkan nama, email, atau nomor HP..." 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-100 table-auto">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 border-b border-gray-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Peserta</th>
                    <th class="py-3 px-4">Kontak</th>
                    <th class="py-3 px-4">Data Diri</th>
                    <th class="py-3 px-4">Tgl Terdaftar</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($pesertas as $index => $peserta)
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                        <td class="py-3 px-4">{{ $pesertas->firstItem() + $index }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-800">{{ $peserta->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($peserta->email_verified_at)
                                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                @else
                                    <span class="text-red-500"><i class="fas fa-times-circle"></i> Blm Verifikasi</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div><a href="mailto:{{ $peserta->email }}" class="text-blue-600 hover:text-blue-800">{{ $peserta->email }}</a></div>
                            <div class="text-gray-500 mt-1">{{ $peserta->no_hp ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div>JK: {{ $peserta->jenis_kelamin ?? '-' }}</div>
                            <div>TTL: {{ $peserta->tempat_lahir ?? '-' }}, {{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d M Y') : '-' }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs mt-1" title="{{ $peserta->alamat }}">{{ strlen($peserta->alamat) > 30 ? substr($peserta->alamat, 0, 30) . '...' : $peserta->alamat ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            {{ $peserta->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button class="open-modal text-blue-600 hover:text-blue-900 text-sm" data-url="{{ route('admin.manajemen_peserta.show', $peserta->id) }}">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="open-modal text-yellow-600 hover:text-yellow-900 text-sm" data-url="{{ route('admin.manajemen_peserta.edit', $peserta->id) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.manajemen_peserta.destroy', $peserta->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500 font-medium">
                            @if(request('search'))
                                Tidak ditemukan peserta dengan kata kunci "{{ request('search') }}"
                            @else
                                Belum ada peserta terdaftar.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pesertas->count() > 0)
        <div class="mt-6">
            {{ $pesertas->links() }}
        </div>
    @endif
</div>

@endsection