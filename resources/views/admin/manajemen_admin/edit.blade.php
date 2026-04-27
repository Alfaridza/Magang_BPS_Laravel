@php $isEdit = isset($admin); @endphp

<div class="flex items-center mb-6">
    <h1 class="text-2xl font-normal text-gray-800 font-sans">{{ $isEdit ? 'Ubah Data Admin' : 'Tambah Admin Baru' }}</h1>
</div>

<div class="bg-white rounded border border-gray-200 shadow-sm p-6 text-gray-700 max-w-2xl mb-2">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 flash-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-disc ml-5">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 flash-close-btn">
                <i class="fas fa-times text-red-700 hover:text-red-900"></i>
            </button>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.manajemen_admin.update', $admin->id) : route('admin.manajemen_admin.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $isEdit ? $admin->name : '') }}" required
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email', $isEdit ? $admin->email : '') }}" required
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $isEdit ? $admin->no_hp : '') }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>

        @if(!$isEdit)
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
            <input type="password" id="password" name="password" required minlength="8"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter.</p>
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        @else
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
        @endif

        <div class="flex justify-end pt-2">
            <a href="{{ route('admin.manajemen_admin.index') }}" data-modal-close="true" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow mr-2 transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Admin' }}</button>
        </div>
    </form>
</div>
