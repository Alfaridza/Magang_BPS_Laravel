@extends('admin.layouts.app')

@section('header', 'Dashboard Statistik')

@section('content')
    <h1 class="text-2xl font-normal text-gray-800 mb-6 font-sans">Dashboard Statistik</h1>

    <!-- First Row: Top 4 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        
        <!-- Total Pendaftar -->
        <div class="bg-[#17a2b8] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $total_pendaftar }}</h3>
                <p class="text-sm mb-4">Total Pendaftar</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <!-- Perlu Verifikasi -->
        <div class="bg-[#ffc107] rounded-sm p-4 text-black relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $perlu_verifikasi }}</h3>
                <p class="text-sm mb-4">Perlu Verifikasi</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <!-- Sedang Magang -->
        <div class="bg-[#28a745] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $sedang_magang }}</h3>
                <p class="text-sm mb-4">Sedang Magang</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

        <!-- Alumni / Selesai -->
        <div class="bg-[#6f42c1] rounded-sm p-4 text-white relative flex flex-col justify-between shadow-sm hover:shadow-md transition">
            <div class="z-10 relative">
                <h3 class="text-3xl font-bold mb-1">{{ $alumni_selesai }}</h3>
                <p class="text-sm mb-4">Alumni / Selesai</p>
            </div>
            <div class="absolute right-4 top-4 text-black opacity-20 transform scale-[2.5] origin-top-right">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>



@endsection
