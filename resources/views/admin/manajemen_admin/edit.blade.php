@extends('admin.layouts.app')

@section('header', 'Edit Admin')

@section('content')
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.manajemen_admin.index') }}" class="text-gray-500 hover:text-gray-700 mr-3">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-normal text-gray-800 font-sans">Edit Data Admin</h1>
    </div>

    <div class="bg-white rounded border border-gray-200 shadow-sm p-6 text-gray-700 max-w-2xl">
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="list-disc ml-5">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.manajemen_admin.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}" required 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $admin->no_hp) }}" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="border-t border-gray-200 pt-4 mt-6 mb-4">
                <h3 class="text-md font-semibold text-gray-800 mb-2">Ubah Password <span class="text-xs font-normal text-gray-500">(Operasional)</span></h3>
                <p class="text-xs text-gray-500 mb-4">Kosongkan kolom di bawah ini jika Anda tidak ingin mengubah password.</p>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="password" name="password" minlength="8" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.manajemen_admin.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow mr-2 transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
