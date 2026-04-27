@extends('admin.layouts.app')

@section('header', 'Log Aktivitas Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Log Aktivitas Admin</h2>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('admin.log_activity.index') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Deskripsi/Subjek</label>
                <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Cari..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="admin_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Filter Admin</label>
                <select name="admin_id" id="admin_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Admin</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ $adminId == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="action" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Filter Aksi</label>
                <select name="action" id="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ $action == $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-100 table-auto rounded-lg">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 border-b border-gray-200">
                    <th class="py-4 px-4 border-b">Waktu</th>
                    <th class="py-4 px-4 border-b">Admin</th>
                    <th class="py-4 px-4 border-b">Aksi</th>
                    <th class="py-4 px-4 border-b">Deskripsi</th>
                    <th class="py-4 px-4 border-b">IP Address</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                        <td class="py-3 px-4 whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-gray-800">{{ $log->admin->name ?? 'System' }}</div>
                            <div class="text-xs text-gray-500">{{ $log->admin->email ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ in_array($log->action, ['Hapus Peserta', 'Hapus Admin', 'Tolak Pengajuan']) ? 'bg-red-100 text-red-700' : 
                                   (in_array($log->action, ['Tambah Admin', 'Terima Pengajuan']) ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            {{ $log->description }}
                        </td>
                        <td class="py-3 px-4 text-xs font-mono text-gray-400">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 font-medium">
                            Tidak ada data log aktivitas ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
