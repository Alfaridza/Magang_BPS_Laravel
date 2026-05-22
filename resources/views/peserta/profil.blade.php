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
                        <p class="text-sm text-gray-500 font-semibold mb-1">Tempat Lahir</p>
                        <p class="text-gray-800 font-medium"> {{ $user->tempat_lahir ?? 'Belum diatur' }}</p>
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

            <!-- Informasi Pendidikan (Read-Only dari Pengajuan Magang) -->
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5">Informasi Pendidikan <span class="text-xs font-normal text-gray-400 ml-2"></span></h3>
                @if($pengajuan)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">NIM</p>
                            <p class="text-gray-800 font-medium"><i class="fas fa-id-card text-blue-400 mr-2 w-4 text-center"></i> {{ $pengajuan->nim_nisn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Instansi</p>
                            <p class="text-gray-800 font-medium"><i class="fas fa-university text-blue-400 mr-2 w-4 text-center"></i> {{ $pengajuan->nama_sekolah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Fakultas</p>
                            <p class="text-gray-800 font-medium"><i class="fas fa-building text-blue-400 mr-2 w-4 text-center"></i> {{ $pengajuan->fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Jurusan</p>
                            <p class="text-gray-800 font-medium"><i class="fas fa-graduation-cap text-blue-400 mr-2 w-4 text-center"></i> {{ $pengajuan->jurusan ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 text-center">
                        <i class="fas fa-info-circle text-blue-300 text-3xl mb-3"></i>
                        <p class="text-blue-700 font-medium">Data pendidikan akan otomatis terisi setelah Anda mengajukan magang.</p>
                    </div>
                @endif
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin </label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm"placeholder= "Jenis Kelamin">
                            <option value="" disabled selected> Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. WhatsApp</label>
                        <input type="number" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Contoh: 08123456789">
                    </div>

                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Contoh: Tangerang">
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

    <!-- Section Ubah Password -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center cursor-pointer" onclick="togglePasswordForm()">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-key text-amber-500 mr-3"></i> Ubah Password
            </h3>
            <button type="button" id="toggle-password-icon" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex justify-center items-center text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-chevron-down text-xs" id="password-chevron"></i>
            </button>
        </div>
        
        <div id="password-form-section" class="{{ session('show_password_form') || session('password_success') || $errors->has('current_password') || $errors->has('password') ? 'block' : 'hidden' }}">
            <div class="p-8">
                @if(session('password_success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        {{ session('password_success') }}
                    </div>
                @endif

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                            <span class="font-bold">Gagal mengubah password:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm ml-6">
                            @if($errors->has('current_password'))
                                <li>{{ $errors->first('current_password') }}</li>
                            @endif
                            @if($errors->has('password'))
                                <li>{{ $errors->first('password') }}</li>
                            @endif
                        </ul>
                    </div>
                @endif

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-amber-500 mr-3 mt-0.5"></i>
                        <p class="text-sm text-amber-700">Password harus minimal 6 karakter. Pastikan Anda mengingat password baru Anda.</p>
                    </div>
                </div>

                <form action="{{ route('peserta.profil.password') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="current_password" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Masukkan password saat ini">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" name="password" minlength="6" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Min. 6 karakter">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <i class="fas fa-check-double"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" minlength="6" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white shadow-sm" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-amber-500 text-white font-bold py-3 px-8 rounded-xl hover:bg-amber-600 transition transform hover:-translate-y-0.5 shadow-lg shadow-amber-500/30 flex items-center">
                                <i class="fas fa-save mr-2"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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

    function togglePasswordForm() {
        const section = document.getElementById('password-form-section');
        const chevron = document.getElementById('password-chevron');
        
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            section.classList.add('block');
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-up');
        } else {
            section.classList.add('hidden');
            section.classList.remove('block');
            chevron.classList.remove('fa-chevron-up');
            chevron.classList.add('fa-chevron-down');
        }
    }

    // Auto-rotate chevron if password form is visible on load
    document.addEventListener('DOMContentLoaded', function() {
        const section = document.getElementById('password-form-section');
        const chevron = document.getElementById('password-chevron');
        if (!section.classList.contains('hidden')) {
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-up');
        }
    });
</script>
@endsection
