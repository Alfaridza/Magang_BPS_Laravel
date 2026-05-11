@extends('admin.layouts.app')

@section('header', 'Edit Peserta Magang Aktif')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl mx-auto">
    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
        <a href="{{ route('admin.peserta_magang_aktif.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Edit Data Magang: {{ $magang->nama_lengkap ?? ($magang->user->name ?? '-') }}</h2>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.peserta_magang_aktif.update', $magang->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-file-signature text-green-500 mr-2"></i> Detail Pengajuan Magang</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $magang->nama_lengkap) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Peserta <span class="text-red-500">*</span></label>
                <select name="status_peserta" id="status_peserta" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Mahasiswa" {{ old('status_peserta', $magang->status_peserta) == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="Fresh graduated" {{ old('status_peserta', $magang->status_peserta) == 'Fresh graduated' ? 'selected' : '' }}>Fresh graduated</option>
                    <option value="Siswa" {{ old('status_peserta', $magang->status_peserta) == 'Siswa' ? 'selected' : '' }}>Siswa (SMA/SMK)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Magang <span class="text-red-500">*</span></label>
                <select name="jenis_magang" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Magang Wajib/PKL" {{ old('jenis_magang', $magang->jenis_magang) == 'Magang Wajib/PKL' ? 'selected' : '' }}>Magang Wajib / PKL</option>
                    <option value="Magang Mandiri" {{ old('jenis_magang', $magang->jenis_magang) == 'Magang Mandiri' ? 'selected' : '' }}>Magang Mandiri</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Instansi/Sekolah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $magang->nama_sekolah) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">NIM / NISN <span class="text-red-500">*</span></label>
                <input type="text" name="nim_nisn" value="{{ old('nim_nisn', $magang->nim_nisn) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenjang Pendidikan <span class="text-red-500">*</span></label>
                <select name="jenjang_pendidikan" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="D4/S1" {{ old('jenjang_pendidikan', $magang->jenjang_pendidikan) == 'D4/S1' ? 'selected' : '' }}>D4 / S1</option>
                    <option value="Diploma" {{ old('jenjang_pendidikan', $magang->jenjang_pendidikan) == 'Diploma' ? 'selected' : '' }}>Diploma (D1/D2/D3)</option>
                    <option value="SMK/SMA" {{ old('jenjang_pendidikan', $magang->jenjang_pendidikan) == 'SMK/SMA' ? 'selected' : '' }}>SMA / SMK</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                <input type="text" name="jurusan" value="{{ old('jurusan', $magang->jurusan) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Conditional fields based on status_peserta -->
            <div id="fakultas-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Fakultas</label>
                <input type="text" name="fakultas" id="fakultas" value="{{ old('fakultas', $magang->fakultas) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div id="semester-group">
                <label class="block text-sm font-bold text-gray-700 mb-2">Semester</label>
                <input type="text" name="semester" id="semester" value="{{ old('semester', $magang->semester) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div id="kelas-group" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kelas</label>
                <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $magang->kelas) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <!-- End Conditional -->

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Periode Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="periode_mulai" value="{{ old('periode_mulai', $magang->periode_mulai ? \Carbon\Carbon::parse($magang->periode_mulai)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Periode Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="periode_selesai" value="{{ old('periode_selesai', $magang->periode_selesai ? \Carbon\Carbon::parse($magang->periode_selesai)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tema Magang (Opsional)</label>
                <textarea name="tema_magang" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('tema_magang', $magang->tema_magang) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Pengajuan <span class="text-red-500">*</span></label>
                <select name="status_pengajuan" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Diterima" {{ old('status_pengajuan', $magang->status_pengajuan) == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Menunggu" {{ old('status_pengajuan', $magang->status_pengajuan) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Ditolak" {{ old('status_pengajuan', $magang->status_pengajuan) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Mengubah status pengajuan menjadi selain "Diterima" akan membuat data ini hilang dari daftar Peserta Magang Aktif.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.peserta_magang_aktif.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('status_peserta').addEventListener('change', function() {
        const val = this.value;
        const fakultas = document.getElementById('fakultas-group');
        const semester = document.getElementById('semester-group');
        const kelas = document.getElementById('kelas-group');
        
        if (val === 'Siswa') {
            fakultas.classList.add('hidden');
            semester.classList.add('hidden');
            kelas.classList.remove('hidden');
        } else {
            fakultas.classList.remove('hidden');
            semester.classList.remove('hidden');
            kelas.classList.add('hidden');
        }
    });
    // Trigger on load
    document.getElementById('status_peserta').dispatchEvent(new Event('change'));
</script>
@endsection
