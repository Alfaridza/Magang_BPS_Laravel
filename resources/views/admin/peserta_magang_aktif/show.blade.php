@extends('admin.layouts.app')

@section('header', 'Detail Peserta Magang Aktif')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div class="flex items-center">
            <a href="{{ route('admin.peserta_magang_aktif.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Detail Magang: {{ $magang->nama_lengkap ?? ($magang->user->name ?? '-') }}</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.peserta_magang_aktif.edit', $magang->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition flex items-center shadow-sm">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.peserta_magang_aktif.destroy', $magang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan peserta ini? Akun peserta tidak akan terhapus.')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center shadow-sm">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Utama & Akun -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 text-center">
                @if($magang->pas_foto)
                    <img src="{{ Storage::url($magang->pas_foto) }}" alt="Pas Foto" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-md mb-4">
                @else
                    <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center border-4 border-white shadow-md mb-4 text-gray-400 text-4xl">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h3 class="font-bold text-lg text-gray-800">{{ $magang->nama_lengkap ?? ($magang->user->name ?? '-') }}</h3>
                <p class="text-blue-600 font-semibold">{{ $magang->status_peserta }}</p>
                <div class="mt-3 inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                    {{ $magang->status_pengajuan }}
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <h4 class="font-bold text-gray-700 mb-4 border-b border-gray-200 pb-2"><i class="fas fa-id-card text-blue-500 mr-2"></i> Data Akun</h4>
                @if($magang->user)
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Email</p>
                            <p class="font-medium text-gray-800 break-all">{{ $magang->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">No. Handphone</p>
                            <p class="font-medium text-gray-800">{{ $magang->user->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Jenis Kelamin</p>
                            <p class="font-medium text-gray-800">{{ $magang->user->jenis_kelamin ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Tempat, Tanggal Lahir</p>
                            <p class="font-medium text-gray-800">{{ $magang->user->tempat_lahir ?? '-' }}, {{ $magang->user->tanggal_lahir ? \Carbon\Carbon::parse($magang->user->tanggal_lahir)->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-red-500 italic">Data akun (User) telah dihapus dari sistem.</p>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Detail Magang & Berkas -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h4 class="font-bold text-gray-700 mb-5 border-b border-gray-200 pb-2"><i class="fas fa-graduation-cap text-yellow-500 mr-2"></i> Informasi Akademik & Magang</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Jenis Magang</p>
                        <p class="font-semibold text-gray-800">{{ $magang->jenis_magang }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Jenjang Pendidikan</p>
                        <p class="font-semibold text-gray-800">{{ $magang->jenjang_pendidikan }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-gray-500 text-xs">Nama Sekolah / Instansi</p>
                        <p class="font-semibold text-gray-800">{{ $magang->nama_sekolah }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">NIM / NISN</p>
                        <p class="font-semibold text-gray-800">{{ $magang->nim_nisn }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Jurusan</p>
                        <p class="font-semibold text-gray-800">{{ $magang->jurusan }}</p>
                    </div>
                    @if($magang->fakultas)
                    <div>
                        <p class="text-gray-500 text-xs">Fakultas</p>
                        <p class="font-semibold text-gray-800">{{ $magang->fakultas }}</p>
                    </div>
                    @endif
                    @if($magang->semester)
                    <div>
                        <p class="text-gray-500 text-xs">Semester</p>
                        <p class="font-semibold text-gray-800">{{ $magang->semester }}</p>
                    </div>
                    @endif
                    @if($magang->kelas)
                    <div>
                        <p class="text-gray-500 text-xs">Kelas</p>
                        <p class="font-semibold text-gray-800">{{ $magang->kelas }}</p>
                    </div>
                    @endif
                    
                    <div class="sm:col-span-2 mt-2 pt-2 border-t border-gray-200">
                        <p class="text-gray-500 text-xs mb-1">Periode Pelaksanaan</p>
                        <div class="flex items-center text-gray-800 font-bold bg-blue-50 rounded px-3 py-2 inline-block">
                            <i class="far fa-calendar-alt text-blue-500 mr-2"></i>
                            {{ \Carbon\Carbon::parse($magang->periode_mulai)->format('d F Y') }}
                            <span class="mx-2 text-gray-400">-</span>
                            {{ \Carbon\Carbon::parse($magang->periode_selesai)->format('d F Y') }}
                        </div>
                    </div>
                    
                    @if($magang->tema_magang)
                    <div class="sm:col-span-2 mt-2">
                        <p class="text-gray-500 text-xs">Tema / Judul Magang</p>
                        <p class="font-semibold text-gray-800">{{ $magang->tema_magang }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h4 class="font-bold text-gray-700 mb-5 border-b border-gray-200 pb-2"><i class="fas fa-folder text-blue-500 mr-2"></i> Dokumen Berkas</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Surat Pengantar -->
                    <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between bg-white">
                        <div class="flex items-center overflow-hidden">
                            <div class="text-red-500 text-2xl mr-3"><i class="fas fa-file-pdf"></i></div>
                            <div class="truncate">
                                <p class="text-sm font-semibold text-gray-800 truncate">Surat Pengantar</p>
                                @if($magang->surat_pengantar)
                                    <p class="text-xs text-green-500"><i class="fas fa-check-circle"></i> Terlampir</p>
                                @else
                                    <p class="text-xs text-red-500"><i class="fas fa-times-circle"></i> Tidak ada</p>
                                @endif
                            </div>
                        </div>
                        @if($magang->surat_pengantar)
                            <a href="{{ Storage::url($magang->surat_pengantar) }}" target="_blank" class="text-blue-500 hover:text-blue-700 p-2 bg-blue-50 rounded-lg shrink-0" title="Unduh/Lihat">
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Kartu Pelajar -->
                    <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between bg-white">
                        <div class="flex items-center overflow-hidden">
                            <div class="text-blue-500 text-2xl mr-3"><i class="fas fa-id-badge"></i></div>
                            <div class="truncate">
                                <p class="text-sm font-semibold text-gray-800 truncate">KTM / Kartu Pelajar</p>
                                @if($magang->kartu_pelajar)
                                    <p class="text-xs text-green-500"><i class="fas fa-check-circle"></i> Terlampir</p>
                                @else
                                    <p class="text-xs text-red-500"><i class="fas fa-times-circle"></i> Tidak ada</p>
                                @endif
                            </div>
                        </div>
                        @if($magang->kartu_pelajar)
                            <a href="{{ Storage::url($magang->kartu_pelajar) }}" target="_blank" class="text-blue-500 hover:text-blue-700 p-2 bg-blue-50 rounded-lg shrink-0" title="Unduh/Lihat">
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
