@extends('peserta.layouts.app')

@section('header', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                <span class="font-bold">Gagal memperbarui profil:</span>
            </div>
            <ul class="list-disc list-inside text-sm ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tampilan Data Diri (View Mode) -->
    <div id="view-profile" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ session('success') || $errors->any() ? 'hidden' : 'block' }}">
        <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-32"></div>
        <div class="px-8 pb-8 relative">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 -mt-12 space-y-4 sm:space-y-0">
                <div class="w-24 h-24 bg-white rounded-full p-1 shadow-lg shrink-0">
                    <div class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <button onclick="toggleProfile()" class="bg-blue-50 text-blue-600 hover:bg-blue-100 font-bold py-2 px-6 rounded-lg transition border border-blue-200 shadow-sm flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit Profil
                </button>
            </div>

            <div class="mt-2 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5">Informasi Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold mb-1">Jenis Kelamin</p>
                        <p class="text-gray-800 font-medium whitespace-nowrap"><i class="fas {{ $user->jenis_kelamin == 'L' ? 'fa-mars text-blue-500' : ($user->jenis_kelamin == 'P' ? 'fa-venus text-pink-500' : 'fa-genderless text-gray-400') }} mr-2 w-4 text-center"></i> {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum diatur') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold mb-1">Tanggal Lahir</p>
                        <p class="text-gray-800 font-medium"><i class="fas fa-calendar-alt text-gray-400 mr-2 w-4 text-center"></i> {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : 'Belum diatur' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold mb-1">No. WhatsApp</p>
                        <p class="text-gray-800 font-medium"><i class="fab fa-whatsapp text-green-500 mr-2 w-4 text-center"></i> {{ $user->no_hp ?? 'Belum diatur' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold mb-1">Status Akun</p>
                        <p class="text-gray-800 font-medium"><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fas fa-user-check mr-1"></i> Terverifikasi</span></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 font-semibold mb-2">Alamat Lengkap</p>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 mr-3 mt-1"></i>
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $user->alamat ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilan Form Edit (Edit Mode) -->
    <div id="edit-profile" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ session('success') || $errors->any() ? 'block' : 'hidden' }}">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-user-edit text-blue-500 mr-2"></i> Edit Biodata</h2>
            <button onclick="toggleProfile()" class="text-gray-400 hover:text-gray-600 transition w-8 h-8 rounded-full bg-white border border-gray-200 flex justify-center items-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-8">
            <form action="{{ url('peserta/profil') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Email tidak dikonfigurasi untuk diubah.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm appearance-none">
                            <option value="" disabled selected>Pilih jenis kelamin...</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. WhatsApp</label>
                        <input type="number" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Contoh: 08123456789">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Dusun, Desa/Kelurahan, Kecamatan, Kab/Kota) ...">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end items-center space-x-4 border-t border-gray-100 pt-6">
                    <button type="button" onclick="toggleProfile()" class="text-gray-500 font-bold hover:text-gray-700 hover:bg-gray-100 rounded-xl transition px-6 py-3">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition transform hover:-translate-y-0.5 shadow-lg shadow-blue-500/30">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleProfile() {
        const viewDiv = document.getElementById('view-profile');
        const editDiv = document.getElementById('edit-profile');
        
        if (viewDiv.classList.contains('hidden')) {
            viewDiv.classList.remove('hidden');
            viewDiv.classList.add('block');
            editDiv.classList.add('hidden');
            editDiv.classList.remove('block');
        } else {
            viewDiv.classList.add('hidden');
            viewDiv.classList.remove('block');
            editDiv.classList.remove('hidden');
            editDiv.classList.add('block');
        }
    }
</script>
@endsection
