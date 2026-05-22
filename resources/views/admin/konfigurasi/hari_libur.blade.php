@extends('admin.layouts.app')

@section('header', 'Konfigurasi Hari Libur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Hari Libur</h2>
            
            <form action="{{ route('admin.konfigurasi.store_hari_libur') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                                  placeholder="Contoh: Idul Fitri, Hari Kemerdekaan, dsb.">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Hari Libur -->
    <div class="lg:col-span-2">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flash-message" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Daftar Hari Libur</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-100 table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 border-b border-gray-200">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Keterangan</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600">
                        @forelse($hariLibur as $index => $libur)
                            <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($libur->tanggal)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">{{ $libur->keterangan }}</td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('admin.konfigurasi.delete_hari_libur', $libur->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500 font-medium">
                                    Belum ada hari libur yang dikonfigurasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
