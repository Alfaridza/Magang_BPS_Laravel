@extends('admin.layouts.app')

@section('header', 'Manajemen Admin')

@section('content')
    <h1 class="text-2xl font-normal text-gray-800 mb-6 font-sans">Manajemen Admin</h1>

    <div class="bg-white rounded border border-gray-200 shadow-sm p-6 text-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold">Daftar Admin Sistem</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center transition">
                <i class="fas fa-plus mr-2"></i> Tambah Admin
            </button>
        </div>
        
        <p class="mb-4">Halaman ini adalah kerangka kerja (placeholder) untuk fitur manajemen data administrator.</p>
        
        <div class="overflow-x-auto border border-gray-200 rounded-sm mb-4">
            <table class="min-w-full w-full bg-white text-sm text-left">
                <thead class="bg-gray-50 text-gray-800 border-b border-gray-200 font-bold">
                    <tr>
                        <th class="py-2.5 px-4 w-1/4">Nama</th>
                        <th class="py-2.5 px-4 w-1/4">Email</th>
                        <th class="py-2.5 px-4 w-1/4">No HP</th>
                        <th class="py-2.5 px-4 w-1/4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ Auth::user()->name }}</td>
                        <td class="py-3 px-4">{{ Auth::user()->email }}</td>
                        <td class="py-3 px-4">{{ Auth::user()->no_hp ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <button class="text-blue-500 hover:text-blue-700 mr-2" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="text-red-500 hover:text-red-700" title="Hapus"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
