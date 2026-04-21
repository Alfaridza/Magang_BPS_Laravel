@extends('admin.layouts.app')

@section('header', 'Edit Peserta')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Data Peserta</h2>
        <a href="{{ route('admin.manajemen_peserta.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.manajemen_peserta.update', $peserta->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $peserta->name) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $peserta->email) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-gray-700 font-semibold mb-2">Nomor HP</label>
                <input 
                    type="text" 
                    id="no_hp" 
                    name="no_hp" 
                    value="{{ old('no_hp', $peserta->no_hp) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_hp') border-red-500 @enderror"
                >
                @error('no_hp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin</label>
                <select 
                    id="jenis_kelamin" 
                    name="jenis_kelamin" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror"
                >
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tempat_lahir" class="block text-gray-700 font-semibold mb-2">Tempat Lahir</label>
                <input 
                    type="text" 
                    id="tempat_lahir" 
                    name="tempat_lahir" 
                    value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tempat_lahir') border-red-500 @enderror"
                >
                @error('tempat_lahir')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_lahir" class="block text-gray-700 font-semibold mb-2">Tanggal Lahir</label>
                <input 
                    type="date" 
                    id="tanggal_lahir" 
                    name="tanggal_lahir" 
                    value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_lahir') border-red-500 @enderror"
                >
                @error('tanggal_lahir')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2 mb-4">
                <label for="alamat" class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea 
                    id="alamat" 
                    name="alamat" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                >{{ old('alamat', $peserta->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.manajemen_peserta.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection