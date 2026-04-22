@extends('admin.layouts.app')

@section('header', 'Manajemen Admin')

@section('content')
    <h1 class="text-2xl font-normal text-gray-800 mb-6 font-sans">Manajemen Admin</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded border border-gray-200 shadow-sm p-6 text-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold">Daftar Admin Sistem</h2>
            <a href="{{ route('admin.manajemen_admin.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center transition">
                <i class="fas fa-plus mr-2"></i> Tambah Admin
            </a>
        </div>
        
        <form action="{{ route('admin.manajemen_admin.index') }}" method="GET" class="mb-4 flex">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari admin..." class="border border-gray-300 rounded-l px-4 py-2 w-full max-w-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            <button type="submit" class="bg-gray-100 border border-gray-300 border-l-0 px-4 py-2 rounded-r hover:bg-gray-200"><i class="fas fa-search"></i></button>
        </form>

        <div class="overflow-x-auto border border-gray-200 rounded-sm mb-4">
            <table class="min-w-full w-full bg-white text-sm text-left">
                <thead class="bg-gray-50 text-gray-800 border-b border-gray-200 font-bold">
                    <tr>
                        <th class="py-2.5 px-4 w-1/4">Nama</th>
                        <th class="py-2.5 px-4 w-1/4">Email</th>
                        <th class="py-2.5 px-4 w-1/4">No HP</th>
                        <th class="py-2.5 px-4 w-1/4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $admin->name }}</td>
                        <td class="py-3 px-4">{{ $admin->email }}</td>
                        <td class="py-3 px-4">{{ $admin->no_hp ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('admin.manajemen_admin.edit', $admin->id) }}" class="text-blue-500 hover:text-blue-700 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.manajemen_admin.destroy', $admin->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus" {{ Auth::guard('admin')->id() === $admin->id ? 'disabled class=opacity-50 cursor-not-allowed' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data admin ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $admins->links() }}
        </div>
    </div>
@endsection
