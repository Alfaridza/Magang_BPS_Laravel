@extends('presensi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-orange-600 to-red-700 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-40 h-40 -mr-20 -mt-20 bg-white bg-opacity-10 rounded-full"></div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('presensi.dashboard') }}" class="text-orange-200 hover:text-white transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold">Lapor Kendala Presensi</h1>
            </div>
            <p class="text-orange-100 text-sm ml-7">Sampaikan kendala teknis yang Anda alami</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <form action="{{ route('presensi.kendala.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Kendala</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Masuk & Pulang --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_in" class="block text-sm font-semibold text-gray-700 mb-1">Estimasi Jam Masuk</label>
                        <input type="time" name="jam_in" id="jam_in" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                        <p class="text-[10px] text-gray-400 mt-1">Isi jika terkendala saat masuk</p>
                    </div>
                    <div>
                        <label for="jam_out" class="block text-sm font-semibold text-gray-700 mb-1">Estimasi Jam Pulang</label>
                        <input type="time" name="jam_out" id="jam_out" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                        <p class="text-[10px] text-gray-400 mt-1">Isi jika terkendala saat pulang</p>
                    </div>
                </div>

                {{-- Jenis Kendala --}}
                <div>
                    <label for="jenis_kendala" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kendala</label>
                    <select name="jenis_kendala" id="jenis_kendala" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition @error('jenis_kendala') border-red-500 @enderror" required>
                        <option value="" disabled selected>Pilih Jenis Kendala</option>
                        <option value="Lokasi Tidak Terdeteksi">Lokasi Tidak Terdeteksi</option>
                        <option value="Sistem sedang Error">Sistem sedang Error</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('jenis_kendala')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Alasan / Keterangan Detail</label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Jelaskan secara detail kendala yang dialami..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition @error('keterangan') border-red-500 @enderror" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-exclamation-triangle"></i> Kirim Laporan Kendala
                    </button>
                </div>
            </form>
        </div>

        {{-- Note --}}
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded text-sm text-blue-800">
            <p><strong>Info:</strong> Laporan kendala akan ditinjau oleh Admin. Jika disetujui, admin akan melakukan koreksi data presensi Anda secara manual.</p>
        </div>
    </div>
</div>
@endsection
