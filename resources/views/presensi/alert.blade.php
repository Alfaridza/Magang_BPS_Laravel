@extends('presensi.layouts.app')

@section('header', 'Informasi Akses Presensi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-6">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-red-800 font-bold text-lg">Akses Presensi Belum Tersedia</h3>
                    <div class="mt-2 text-red-700">
                        @if(!$isProfileComplete)
                            <p><i class="fas fa-chevron-right text-xs mr-2"></i> Silakan lengkapi data profil terlebih dahulu</p>
                            <p class="mb-3 ml-5"><a href="{{ url('peserta/profil') }}" class="text-blue-600 hover:underline font-medium">Lengkapi Profil <i class="fas fa-arrow-right ml-1 text-xs"></i></a></p>
                        @endif
                        
                        @if(!$isApplicationApproved)
                            @if($pengajuan)
                                <p><i class="fas fa-chevron-right text-xs mr-2"></i> Status pengajuan magang belum disetujui</p>
                                <p class="mb-3 ml-5">Status terkini: 
                                    @if($pengajuan->status_pengajuan === 'Menunggu')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-medium">Menunggu Persetujuan</span>
                                    @elseif($pengajuan->status_pengajuan === 'Ditolak')
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">Ditolak</span>
                                        @if($pengajuan->alasan_penolakan)
                                            <p class="mt-2 text-sm bg-red-100 p-2 rounded"><strong>Alasan Penolakan:</strong> {{ $pengajuan->alasan_penolakan }}</p>
                                        @endif
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ ucfirst($pengajuan->status_pengajuan) }}</span>
                                    @endif
                                </p>
                            @else
                                <p><i class="fas fa-chevron-right text-xs mr-2"></i> Anda belum mengajukan magang</p>
                                <p class="mb-3 ml-5">Silakan lakukan pengajuan terlebih dahulu. <a href="{{ url('peserta/daftar-magang') }}" class="text-blue-600 hover:underline font-medium">Ajukan Sekarang <i class="fas fa-arrow-right ml-1 text-xs"></i></a></p>
                            @endif
                        @endif
                        
                        <p class="mt-3">Fitur presensi hanya tersedia setelah:</p>
                        <ul class="mt-2 list-disc pl-5 space-y-1">
                            <li>Data profil peserta sudah lengkap</li>
                            <li>Pengajuan magang sudah diajukan dan disetujui oleh admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
