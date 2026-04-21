@extends('admin.layouts.app')

@section('header', 'Detail Peserta')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Detail Peserta</h2>
        <a href="{{ route('admin.manajemen_peserta.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->name }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->email }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nomor HP</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->no_hp ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Jenis Kelamin</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->jenis_kelamin ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Tempat Lahir</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->tempat_lahir ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Tanggal Lahir</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                {{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d F Y') : '-' }}
            </p>
        </div>

        <div class="md:col-span-2 mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 min-h-[60px]">{{ $peserta->alamat ?? '-' }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Status Verifikasi Email</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                @if($peserta->email_verified_at)
                    <span class="text-green-600 font-medium">Terverifikasi</span> pada {{ $peserta->email_verified_at->format('d M Y H:i:s') }}
                @else
                    <span class="text-red-500 font-medium">Belum Terverifikasi</span>
                @endif
            </p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Tanggal Registrasi</label>
            <p class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">{{ $peserta->created_at->format('d M Y H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('admin.manajemen_peserta.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
            Kembali ke Daftar
        </a>
        <a href="{{ route('admin.manajemen_peserta.edit', $peserta->id) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Edit Data
        </a>
    </div>
</div>
@endsection