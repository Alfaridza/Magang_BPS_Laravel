@extends('peserta.layouts.app')

@section('header', 'Dashboard Peserta')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600">Silahkan lengkapi dan isi data untuk pengajuan magang di halaman daftar magang atau dapat diakses melalui link ini <a href="{{ url('peserta/daftar-magang') }}" class="text-blue-500 hover:underline">Daftar Magang</a></p> 
        <br>
        <p class="text-gray-600">Silahkan isi data diri anda di halaman profil atau dapat diakses melalui link ini <a href="{{ url('peserta/profil') }}" class="text-blue-500 hover:underline">Profil</a>.</p>
    </div>
@endsection
