@extends('peserta.layouts.app')

@section('header', 'Dashboard Peserta')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600">Silahkan lengkapi dan isi data untuk pengajuan magang di halaman daftar magang atau dapat diakses melalui link ini <a href="{{ url('peserta/daftar-magang') }}" class="text-blue-500 hover:underline">Daftar Magang</a></p> 
        <br>
        <p class="text-gray-600">Silahkan isi data diri anda di halaman profil atau dapat diakses melalui link ini <a href="{{ url('peserta/profil') }}" class="text-blue-500 hover:underline">Profil</a>.</p>
        <br>
        <p class="text-gray-600">Untuk dapat mengakses presensi, mohon untuk lengkapkan data diri dan pengajuan magang anda.</p>
        <br>
        <p class="text-gray-600">Untuk melakukan presensi kehadiran silahkan klik tombol di bawah ini:</p>
        <br>
        <a href="{{ route('presensi.cek_kelayakan') }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md hover:shadow-lg hover:-translate-y-0.5 transform">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Presensi
        </a>
        <br>

    </div>
@endsection