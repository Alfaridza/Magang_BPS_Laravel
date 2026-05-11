@extends('peserta.layouts.app')

@section('header', 'Daftar Magang')

@section('content')
<div class="bg-white p-8 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-normal text-gray-800 tracking-wide font-sans m-auto pb-4">List Usulan Magang</h1>
    </div>

    <!-- Tampilkan Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <!-- Using custom green from the image -->
        <button onclick="openCreateModal()" class="bg-[#99e6d9] hover:bg-[#7dd3c5] text-white font-bold py-2 px-6 rounded shadow-sm text-sm uppercase tracking-wider transition">
            + DAFTAR MAGANG
        </button>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border-t border-b border-gray-100 table-auto">
            <thead>
                <tr class="bg-white text-left text-sm font-bold text-gray-800 border-b border-gray-100">
                    <th class="py-4 px-6 border-b border-gray-100">Id Magang</th>
                    <th class="py-4 px-6 border-b border-gray-100">Tanggal Pengajuan</th>
                    <th class="py-4 px-6 border-b border-gray-100">Tema Magang</th>
                    <th class="py-4 px-6 border-b border-gray-100">Periode Magang</th>
                    <th class="py-4 px-6 border-b border-gray-100">Status</th>
                    <th class="py-4 px-6 border-b border-gray-100 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($pengajuan_magangs as $magang)
                    <tr class="hover:bg-gray-50 border-b border-gray-50/50 transition">
                        <td class="py-4 px-6">{{ $magang->id }}</td>
                        <td class="py-4 px-6">{{ $magang->created_at->format('Y-m-d') }}</td>
                        <td class="py-4 px-6">{{ $magang->tema_magang ?? '-' }}</td>
                        <td class="py-4 px-6">{{ \Carbon\Carbon::parse($magang->periode_mulai)->format('Y-m-d') }} s/d {{ \Carbon\Carbon::parse($magang->periode_selesai)->format('Y-m-d') }}</td>
                        <td class="py-4 px-6">
                            @if($magang->status_pengajuan == 'Menunggu')
                                <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-md text-xs font-semibold">{{ $magang->status_pengajuan }}</span>
                            @elseif($magang->status_pengajuan == 'Diterima')
                                <span class="bg-green-100 text-green-800 py-1 px-3 rounded-md text-xs font-semibold">{{ $magang->status_pengajuan }}</span>
                            @else
                                <span class="bg-red-100 text-red-800 py-1 px-3 rounded-md text-xs font-semibold">{{ $magang->status_pengajuan }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <button onclick='openDetailModal(@json($magang))' class="text-blue-500 hover:text-blue-700 mx-1 transition rounded-full hover:bg-blue-100 p-2" title="Detail"><i class="fas fa-eye"></i></button>
                            @if($magang->status_pengajuan == 'Menunggu')
                                <button onclick='openEditModal(@json($magang))' class="text-yellow-500 hover:text-yellow-700 mx-1 transition rounded-full hover:bg-yellow-100 p-2" title="Edit"><i class="fas fa-edit"></i></button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500 font-medium tracking-wide">
                            Belum Ada Data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form Pengajuan -->
<div id="modal-pengajuan" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="toggleModal('modal-pengajuan')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800" id="modal-title">Formulir Pengajuan Magang</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition" onclick="toggleModal('modal-pengajuan')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-white px-6 py-5 sm:p-8">
                <form action="{{ url('pengajuan-magang') }}" method="POST" enctype="multipart/form-data" id="form-pengajuan">
                    @csrf
                    <div id="method-container"></div>
                    
                    <!-- STEP 1: Biodata & Akademik -->
                    <div id="step-1" class="space-y-6 block">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="step-1-inputs">
                            <!-- Status Peserta -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Peserta</label>
                                <select name="status_peserta" id="status_peserta" onchange="handleStatusChange()" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border bg-gray-50 focus:bg-white transition shadow-sm bgcheck-1">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="Mahasiswa" {{ old('status_peserta') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="Fresh graduated" {{ old('status_peserta') == 'Fresh graduated' ? 'selected' : '' }}>Fresh graduated</option>
                                    <option value="Siswa" {{ old('status_peserta') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                                </select>
                            </div>

                            <!-- Nama Lengkap-->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Jenis Magang -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Magang</label>
                                <select name="jenis_magang" id="jenis_magang" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border bg-gray-50 focus:bg-white transition shadow-sm bgcheck-1">
                                    <option value="" disabled selected>Pilih Jenis</option>
                                    <option value="Magang Wajib/PKL" {{ old('jenis_magang') == 'Magang Wajib/PKL' ? 'selected' : '' }}>Magang Wajib/PKL</option>
                                    <option value="Magang Mandiri" {{ old('jenis_magang') == 'Magang Mandiri' ? 'selected' : '' }}>Magang Mandiri</option>
                                </select>
                            </div>

                            <!-- NIM/NISN -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIM / NISN</label>
                                <input type="text" name="nim_nisn" id="nim_nisn" value="{{ old('nim_nisn') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Jenjang Pendidikan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenjang Pendidikan</label>
                                <select name="jenjang_pendidikan" id="jenjang_pendidikan" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border bg-gray-50 focus:bg-white transition shadow-sm bgcheck-1">
                                    <option value="" disabled selected>Pilih Jenjang</option>
                                    <option value="SMK/SMA" {{ old('jenjang_pendidikan') == 'SMK/SMA' ? 'selected' : '' }}>SMK/SMA</option>
                                    <option value="Diploma" {{ old('jenjang_pendidikan') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="D4/S1" {{ old('jenjang_pendidikan') == 'D4/S1' ? 'selected' : '' }}>D4/S1</option>
                                </select>
                            </div>

                            <!-- Nama Sekolah/PT -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2" id="label_nama_sekolah">Nama Sekolah / Perguruan Tinggi</label>
                                <input type="text" name="nama_sekolah" id="nama_sekolah" value="{{ old('nama_sekolah') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Fakultas (Mahasiswa/Fresh Grad) -->
                            <div id="container-fakultas" class="hidden md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Fakultas</label>
                                <input type="text" name="fakultas" id="fakultas" value="{{ old('fakultas') }}" class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Jurusan -->
                            <div class="md:col-span-1" id="container-jurusan">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Semester (Mahasiswa/Fresh Grad) -->
                            <div id="container-semester" class="hidden md:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                                <input type="text" name="semester" id="semester" value="{{ old('semester') }}" class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Kelas (Siswa) -->
                            <div id="container-kelas" class="hidden md:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                                <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}" class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Periode Mulai -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Periode Magang Mulai </label>
                                <input type="date" name="periode_mulai" id="periode_mulai" value="{{ old('periode_mulai') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Periode Selesai -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Periode Magang Selesai</label>
                                <input type="date" name="periode_selesai" id="periode_selesai" value="{{ old('periode_selesai') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white bgcheck-1">
                            </div>

                            <!-- Tema Magang (Opsional) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tema Magang (Opsional)</label>
                                <textarea name="tema_magang" id="tema_magang" rows="3" class="w-full rounded-xl border-gray-300 focus:border-[#0099CC] focus:ring-[#0099CC] px-5 py-3 border transition shadow-sm bg-gray-50 focus:bg-white">{{ old('tema_magang') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100 mt-6 md:space-x-3 items-center">
                            <button type="button" class="mt-3 w-full md:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition sm:mt-0" onclick="toggleModal('modal-pengajuan')">
                                Batal
                            </button>
                            <!-- Tombol Simpan / Edit -->
                            <button type="button" id="btn-simpan-1" class="mt-3 w-full md:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-3 bg-[#0099CC] text-base font-bold text-white hover:bg-blue-600 focus:outline-none transition sm:mt-0" onclick="handleSimpan()">
                                Simpan
                            </button>
                            <!-- Tombol Selanjutnya (Tersembunyi Awalnya) -->
                            <button type="button" id="btn-lanjut-1" class="hidden mt-3 w-full md:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-3 bg-green-500 text-base font-bold text-white hover:bg-green-600 focus:outline-none transition sm:mt-0" onclick="goToStep(2)">
                                Selanjutnya <i class="fas fa-arrow-right ml-2 mt-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Unggah Berkas -->
                    <div id="step-2" class="space-y-6 hidden">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-left mb-6 flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3 text-lg"></i>
                            <div>
                                <h5 class="font-bold text-blue-800 text-sm">Persyaratan Berkas Wajib</h5>
                                <p class="text-sm text-blue-700">Pastikan file surat pengantar (PDF/DOCX), Pas Foto (JPG/PNG), dan KTM/Kartu Pelajar (PDF/JPG/PNG) berukuran maksimal 5MB.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Surat Pengantar -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Surat Pengantar Kampus/Sekolah <span class="text-red-500">*</span></label>
                                <div id="container-surat" class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#0099CC] transition cursor-pointer bg-gray-50 hover:bg-[#E6F7FF]">
                                    <input type="file" name="surat_pengantar" id="surat_pengantar" accept=".pdf,.doc,.docx" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="updateFileLabel(this, 'label-surat', 'icon-surat', 'container-surat')">
                                    <div class="relative z-10 pointer-events-none">
                                        <i id="icon-surat" class="fas fa-file-alt text-3xl text-gray-400 mb-2 group-hover:text-[#0099CC] transition"></i>
                                        <p id="label-surat" class="text-sm text-gray-500 group-hover:text-gray-800 font-medium truncate px-4">Upload Surat Pengantar</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pas Foto -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pas Foto Resmi (Ukuran 4x6 cm Background Merah)<span class="text-red-500">*</span></label>
                                <div id="container-foto" class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#0099CC] transition cursor-pointer bg-gray-50 hover:bg-[#E6F7FF]">
                                    <input type="file" name="pas_foto" id="pas_foto" accept=".jpg,.jpeg,.png" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="updateFileLabel(this, 'label-foto', 'icon-foto', 'container-foto')">
                                    <div class="relative z-10 pointer-events-none">
                                        <i id="icon-foto" class="fas fa-image text-3xl text-gray-400 mb-2 group-hover:text-[#0099CC] transition"></i>
                                        <p id="label-foto" class="text-sm text-gray-500 group-hover:text-gray-800 font-medium truncate px-4">Upload Pas Foto</p>
                                    </div>
                                </div>
                            </div>

                            <!-- KTM / Kartu Pelajar -->
                            <div class="group md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">KTM / Kartu Pelajar <span class="text-red-500">*</span></label>
                                <div id="container-ktm" class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#0099CC] transition cursor-pointer bg-gray-50 hover:bg-[#E6F7FF]">
                                    <input type="file" name="kartu_pelajar" id="kartu_pelajar" accept=".pdf,.jpg,.jpeg,.png" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="updateFileLabel(this, 'label-ktm', 'icon-ktm', 'container-ktm')">
                                    <div class="relative z-10 pointer-events-none">
                                        <i id="icon-ktm" class="fas fa-id-card text-3xl text-gray-400 mb-2 group-hover:text-[#0099CC] transition"></i>
                                        <p id="label-ktm" class="text-sm text-gray-500 group-hover:text-gray-800 font-medium truncate px-4">Upload KTM / Kartu Pelajar</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6 border-t border-gray-100 mt-6 items-center">
                            <button type="button" class="text-gray-500 font-semibold hover:text-gray-800 px-4 py-2 transition" onclick="goToStep(1)">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </button>
                            <button type="submit" class="inline-flex justify-center items-center rounded-xl shadow-md px-8 py-3 bg-[#0099CC] text-base font-bold text-white hover:bg-blue-600 focus:outline-none transition">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pengajuan -->
<div id="modal-detail" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title-detail" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="toggleModal('modal-detail')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="bg-gradient-to-r from-[#0099CC] to-[#00a8cc] px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center" id="modal-title-detail">
                    <i class="fas fa-info-circle mr-3"></i> Detail Pengajuan Magang
                </h3>
                <button type="button" class="text-white hover:text-gray-200 bg-white/20 hover:bg-white/30 rounded-full w-8 h-8 flex items-center justify-center transition" onclick="toggleModal('modal-detail')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-white px-6 py-5 sm:p-8">
                <div class="space-y-6">
                    <!-- Status Section -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Status Pengajuan</p>
                            <div id="detail-status-badge" class="inline-block px-3 py-1 rounded-md text-sm font-bold"></div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Tanggal Daftar</p>
                            <p id="detail-tanggal" class="font-medium text-gray-800"></p>
                        </div>
                    </div>

                    <!-- Alasan Penolakan -->
                    <div id="wrapper-detail-alasan" class="hidden pb-4 border-b border-gray-100">
                        <p class="text-sm text-red-500 font-semibold uppercase tracking-wider mb-2"><i class="fas fa-exclamation-circle mr-1"></i> Alasan Penolakan</p>
                        <p id="detail-alasan" class="font-medium text-red-700 bg-red-50 p-3 rounded-lg border border-red-100 text-sm"></p>
                    </div>

                    <!-- Grid Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                        <!-- Identitas -->
                        <div class="col-span-1 sm:col-span-2">
                            <h4 class="text-md font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2"><i class="fas fa-user text-[#0099CC] mr-2"></i> Identitas Pemohon</h4>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Nama Lengkap</p>
                            <p id="detail-nama-lengkap" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Status Peserta</p>
                            <p id="detail-status-peserta" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">NIM / NISN</p>
                            <p id="detail-nim" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Sekolah / Kampus</p>
                            <p id="detail-instansi" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div id="wrapper-detail-fakultas" class="hidden">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Fakultas</p>
                            <p id="detail-fakultas" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jurusan (Jenjang)</p>
                            <p id="detail-jurusan" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div id="wrapper-detail-kelas" class="hidden">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Kelas</p>
                            <p id="detail-kelas" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div id="wrapper-detail-semester" class="hidden">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Semester</p>
                            <p id="detail-semester" class="font-medium text-gray-800 mt-1"></p>
                        </div>

                        <!-- Info Magang -->
                        <div class="col-span-1 sm:col-span-2 mt-2">
                            <h4 class="text-md font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2"><i class="fas fa-briefcase text-[#0099CC] mr-2"></i> Rencana Magang</h4>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jenis Magang</p>
                            <p id="detail-jenis" class="font-medium text-gray-800 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Periode Magang</p>
                            <p id="detail-periode" class="font-medium whitespace-nowrap text-gray-800 mt-1"></p>
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tema Magang</p>
                            <p id="detail-tema" class="font-medium text-gray-800 mt-1 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[3rem]"></p>
                        </div>

                        <!-- Berkas -->
                        <div class="col-span-1 sm:col-span-2 mt-2">
                            <h4 class="text-md font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2"><i class="fas fa-file-alt text-[#0099CC] mr-2"></i> Kelengkapan Berkas</h4>
                        </div>
                        
                        <div class="col-span-1 sm:col-span-2 flex space-x-4">
                            <a id="btn-download-surat" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i> Surat Pengantar
                            </a>
                            <a id="btn-download-foto" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                <i class="fas fa-image text-blue-500 mr-2"></i> Pas Foto
                            </a>
                            <a id="btn-download-ktm" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                <i class="fas fa-id-card text-green-500 mr-2"></i> KTM / Kartu Pelajar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none transition sm:w-auto sm:text-sm" onclick="toggleModal('modal-detail')">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle modal and date validation -->
<script>
    function toggleModal(modalID) {
        let el = document.getElementById(modalID);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            el.querySelector('.transform').classList.add('scale-100');
            el.querySelector('.transform').classList.remove('scale-95');
        } else {
            el.classList.add('hidden');
        }
    }

    // Function to format date YYYY-MM-DD to DD MMM YYYY manually in JS
    function formatDateIndonesian(dateString) {
        if (!dateString) return '-';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const date = new Date(dateString);
        return `${String(date.getDate()).padStart(2, '0')} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    // Open detail modal and populate data
    function openDetailModal(magang) {
        // Populate standard text fields
        document.getElementById('detail-nama-lengkap').innerText = magang.nama_lengkap || '-';
        document.getElementById('detail-status-peserta').innerText = magang.status_peserta || '-';
        document.getElementById('detail-nim').innerText = magang.nim_nisn || '-';
        document.getElementById('detail-instansi').innerText = magang.nama_sekolah || '-';
        document.getElementById('detail-jurusan').innerText = `${magang.jurusan || '-'} (${magang.jenjang_pendidikan || '-'})`;
        
        // Handle conditional fields
        const wrapperFakultas = document.getElementById('wrapper-detail-fakultas');
        const wrapperSemester = document.getElementById('wrapper-detail-semester');
        const wrapperKelas = document.getElementById('wrapper-detail-kelas');
        
        if (magang.status_peserta === 'Siswa') {
            document.getElementById('detail-kelas').innerText = magang.kelas || '-';
            wrapperKelas.classList.remove('hidden');
            wrapperFakultas.classList.add('hidden');
            wrapperSemester.classList.add('hidden');
        } else {
            document.getElementById('detail-fakultas').innerText = magang.fakultas || '-';
            document.getElementById('detail-semester').innerText = magang.semester || '-';
            wrapperFakultas.classList.remove('hidden');
            wrapperSemester.classList.remove('hidden');
            wrapperKelas.classList.add('hidden');
        }
        
        document.getElementById('detail-jenis').innerText = magang.jenis_magang || '-';
        document.getElementById('detail-tema').innerText = magang.tema_magang || '-';
        
        // Format dates
        const createdDate = new Date(magang.created_at).toISOString().split('T')[0];
        document.getElementById('detail-tanggal').innerText = formatDateIndonesian(createdDate);
        
        const periodStr = `${formatDateIndonesian(magang.periode_mulai)} s/d ${formatDateIndonesian(magang.periode_selesai)}`;
        document.getElementById('detail-periode').innerText = periodStr;

        // Badge Styling
        const badge = document.getElementById('detail-status-badge');
        const wrapperAlasan = document.getElementById('wrapper-detail-alasan');
        badge.innerText = magang.status_pengajuan;
        badge.className = "inline-block px-3 py-1 rounded-md text-sm font-bold "; // reset classes
        if (magang.status_pengajuan === 'Menunggu') {
            badge.className += "bg-yellow-100 text-yellow-800";
            wrapperAlasan.classList.add('hidden');
        } else if (magang.status_pengajuan === 'Diterima') {
            badge.className += "bg-green-100 text-green-800";
            wrapperAlasan.classList.add('hidden');
        } else {
            badge.className += "bg-red-100 text-red-800";
            if (magang.alasan_penolakan) {
                document.getElementById('detail-alasan').innerText = magang.alasan_penolakan;
                wrapperAlasan.classList.remove('hidden');
            } else {
                wrapperAlasan.classList.add('hidden');
            }
        }

        // Setup File links (assume storage links are correctly formatted)
        const btnSurat = document.getElementById('btn-download-surat');
        const btnFoto = document.getElementById('btn-download-foto');
        const btnKtm = document.getElementById('btn-download-ktm');
        
        if (magang.surat_pengantar) {
            btnSurat.href = `{{ url('storage') }}/${magang.surat_pengantar}`;
            btnSurat.classList.remove('hidden');
        } else {
            btnSurat.classList.add('hidden');
        }

        if (magang.pas_foto) {
            btnFoto.href = `{{ url('storage') }}/${magang.pas_foto}`;
            btnFoto.classList.remove('hidden');
        } else {
            btnFoto.classList.add('hidden');
        }

        if (magang.kartu_pelajar) {
            btnKtm.href = `{{ url('storage') }}/${magang.kartu_pelajar}`;
            btnKtm.classList.remove('hidden');
        } else {
            btnKtm.classList.add('hidden');
        }

        // Open Modal
        toggleModal('modal-detail');
    }

    // Handle Simpan Step 1
    let isStep1Saved = false;
    function handleSimpan() {
        if (!isStep1Saved) {
            // Check HTML5 validity for step 1 inputs
            let inputs = document.querySelectorAll('#step-1-inputs input, #step-1-inputs select');
            let isValid = true;
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (isValid) {
                // Change UI to "Saved"
                isStep1Saved = true;
                let btnSimpan = document.getElementById('btn-simpan-1');
                btnSimpan.innerHTML = 'Edit';
                btnSimpan.classList.replace('bg-[#0099CC]', 'bg-gray-500');
                btnSimpan.classList.replace('hover:bg-blue-600', 'hover:bg-gray-600');

                // Show Selanjutnya
                document.getElementById('btn-lanjut-1').classList.remove('hidden');

                // Lock inputs initially to show it's saved
                inputs.forEach(input => input.setAttribute('readonly', true));
                document.querySelectorAll('#step-1-inputs select').forEach(s => s.style.pointerEvents = 'none');
            }
        } else {
            // Edit Mode
            isStep1Saved = false;
            let btnSimpan = document.getElementById('btn-simpan-1');
            btnSimpan.innerHTML = 'Simpan';
            btnSimpan.classList.replace('bg-gray-500', 'bg-[#0099CC]');
            btnSimpan.classList.replace('hover:bg-gray-600', 'hover:bg-blue-600');

            // Hide Selanjutnya
            document.getElementById('btn-lanjut-1').classList.add('hidden');

            // Unlock inputs
            let inputs = document.querySelectorAll('#step-1-inputs input');
            inputs.forEach(input => input.removeAttribute('readonly'));
            document.querySelectorAll('#step-1-inputs select').forEach(s => s.style.pointerEvents = 'auto');
        }
    }

    function goToStep(step) {
        if (step === 2) {
            document.getElementById('step-1').classList.replace('block', 'hidden');
            document.getElementById('step-2').classList.replace('hidden', 'block');
        } else if (step === 1) {
            document.getElementById('step-2').classList.replace('block', 'hidden');
            document.getElementById('step-1').classList.replace('hidden', 'block');
        }
    }

    function updateFileLabel(input, labelId, iconId, containerId) {
        const label = document.getElementById(labelId);
        const icon = document.getElementById(iconId);
        const container = document.getElementById(containerId);
        
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            label.innerText = fileName;
            label.classList.add('text-gray-800', 'font-bold');
            label.classList.remove('text-gray-500');
            icon.className = "fas fa-check-circle text-3xl text-green-500 mb-2 transition";
            container.classList.add('border-[#0099CC]', 'bg-[#E6F7FF]');
            container.classList.remove('border-gray-300', 'bg-gray-50');
        } else {
            label.innerText = "Upload Berkas";
            label.classList.remove('text-gray-800', 'font-bold');
            label.classList.add('text-gray-500');
            icon.className = "fas fa-file-alt text-3xl text-gray-400 mb-2 group-hover:text-[#0099CC] transition";
            container.classList.remove('border-[#0099CC]', 'bg-[#E6F7FF]');
            container.classList.add('border-gray-300', 'bg-gray-50');
        }
    }

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            toggleModal('modal-pengajuan');
        });
    @endif

    // Date validation
    document.addEventListener("DOMContentLoaded", function() {
        const inputMulai = document.getElementById('periode_mulai');
        const inputSelesai = document.getElementById('periode_selesai');

        if(inputMulai && inputSelesai) {
            inputMulai.addEventListener('change', function() {
                if(this.value) {
                    const tgl = new Date(this.value);
                    tgl.setDate(tgl.getDate() + 29); // Minimum 30 hari
                    
                    const yyyy = tgl.getFullYear();
                    const mm = String(tgl.getMonth() + 1).padStart(2, '0');
                    const dd = String(tgl.getDate()).padStart(2, '0');
                    
                    const minDate = `${yyyy}-${mm}-${dd}`;
                    
                    inputSelesai.setAttribute('min', minDate);
                    if(!inputSelesai.value || new Date(inputSelesai.value) < new Date(minDate)) {
                        inputSelesai.value = minDate;
                    }
                }
            });
        }

        // Initialize status field logic
        handleStatusChange();
    });

    function handleStatusChange() {
        const status = document.getElementById('status_peserta').value;
        const containerFakultas = document.getElementById('container-fakultas');
        const containerSemester = document.getElementById('container-semester');
        const containerKelas = document.getElementById('container-kelas');
        const labelNamaSekolah = document.getElementById('label_nama_sekolah');
        const inputFakultas = document.getElementById('fakultas');
        const inputSemester = document.getElementById('semester');
        const inputKelas = document.getElementById('kelas');

        if (status === 'Siswa') {
            labelNamaSekolah.innerText = 'Nama Sekolah';
            
            containerKelas.classList.remove('hidden');
            containerKelas.classList.add('block');
            inputKelas.required = true;

            containerFakultas.classList.add('hidden');
            containerFakultas.classList.remove('block');
            inputFakultas.required = false;

            containerSemester.classList.add('hidden');
            containerSemester.classList.remove('block');
            inputSemester.required = false;
        } else if (status === 'Mahasiswa' || status === 'Fresh graduated') {
            labelNamaSekolah.innerText = 'Nama Universitas';
            
            containerFakultas.classList.remove('hidden');
            containerFakultas.classList.add('block');
            inputFakultas.required = true;

            containerSemester.classList.remove('hidden');
            containerSemester.classList.add('block');
            inputSemester.required = true;

            containerKelas.classList.add('hidden');
            containerKelas.classList.remove('block');
            inputKelas.required = false;
        } else {
            labelNamaSekolah.innerText = 'Nama Sekolah / Perguruan Tinggi';
            containerFakultas.classList.add('hidden');
            containerSemester.classList.add('hidden');
            containerKelas.classList.add('hidden');
            inputFakultas.required = false;
            inputSemester.required = false;
            inputKelas.required = false;
        }
    }

    function openCreateModal() {
        document.getElementById('form-pengajuan').reset();
        document.getElementById('form-pengajuan').action = `{{ url('pengajuan-magang') }}`;
        document.getElementById('method-container').innerHTML = '';
        document.getElementById('modal-title').innerText = 'Formulir Pengajuan Magang';
        
        // reset UI for files
        document.getElementById('label-surat').innerText = 'Upload Surat Pengantar';
        document.getElementById('label-foto').innerText = 'Upload Pas Foto';
        document.getElementById('label-ktm').innerText = 'Upload KTM / Kartu Pelajar';
        
        document.getElementById('surat_pengantar').required = true;
        document.getElementById('pas_foto').required = true;
        document.getElementById('kartu_pelajar').required = true;
        
        // Reset unlock state
        goToStep(1);
        isStep1Saved = false;
        document.getElementById('btn-simpan-1').innerHTML = 'Simpan';
        document.getElementById('btn-simpan-1').classList.replace('bg-gray-500', 'bg-[#0099CC]');
        document.getElementById('btn-simpan-1').classList.replace('hover:bg-gray-600', 'hover:bg-blue-600');
        document.getElementById('btn-lanjut-1').classList.add('hidden');
        document.querySelectorAll('#step-1-inputs input').forEach(input => input.removeAttribute('readonly'));
        document.querySelectorAll('#step-1-inputs select').forEach(s => s.style.pointerEvents = 'auto');

        handleStatusChange();
        toggleModal('modal-pengajuan');
    }

    function openEditModal(magang) {
        document.getElementById('form-pengajuan').reset();
        document.getElementById('form-pengajuan').action = `{{ url('pengajuan-magang') }}/${magang.id}`;
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modal-title').innerText = 'Edit Pengajuan Magang';

        // populate fields
        document.getElementById('status_peserta').value = magang.status_peserta;
        document.getElementById('nama_lengkap').value = magang.nama_lengkap;
        document.getElementById('jenis_magang').value = magang.jenis_magang;
        document.getElementById('nim_nisn').value = magang.nim_nisn;
        document.getElementById('jenjang_pendidikan').value = magang.jenjang_pendidikan;
        document.getElementById('nama_sekolah').value = magang.nama_sekolah;
        
        if (magang.fakultas) document.getElementById('fakultas').value = magang.fakultas;
        if (magang.jurusan) document.getElementById('jurusan').value = magang.jurusan;
        if (magang.kelas) document.getElementById('kelas').value = magang.kelas;
        if (magang.semester) document.getElementById('semester').value = magang.semester;
        
        // date inputs expects YYYY-MM-DD
        document.getElementById('periode_mulai').value = magang.periode_mulai.split(' ')[0];
        document.getElementById('periode_selesai').value = magang.periode_selesai.split(' ')[0];
        document.getElementById('tema_magang').value = magang.tema_magang || '';

        // file inputs (remove required because they already exist)
        document.getElementById('surat_pengantar').required = false;
        document.getElementById('pas_foto').required = false;
        document.getElementById('kartu_pelajar').required = false;
        
        document.getElementById('label-surat').innerText = 'Berkas tersimpan (Upload baru untuk ganti)';
        document.getElementById('label-foto').innerText = 'Berkas tersimpan (Upload baru untuk ganti)';
        document.getElementById('label-ktm').innerText = 'Berkas tersimpan (Upload baru untuk ganti)';
        
        // Reset unlock state
        goToStep(1);
        isStep1Saved = false;
        document.getElementById('btn-simpan-1').innerHTML = 'Simpan';
        document.getElementById('btn-simpan-1').classList.replace('bg-gray-500', 'bg-[#0099CC]');
        document.getElementById('btn-simpan-1').classList.replace('hover:bg-gray-600', 'hover:bg-blue-600');
        document.getElementById('btn-lanjut-1').classList.add('hidden');
        document.querySelectorAll('#step-1-inputs input').forEach(input => input.removeAttribute('readonly'));
        document.querySelectorAll('#step-1-inputs select').forEach(s => s.style.pointerEvents = 'auto');

        handleStatusChange();
        toggleModal('modal-pengajuan');
    }
</script>
@endsection
