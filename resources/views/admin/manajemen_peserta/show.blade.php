<div class="flex items-center mb-6">
    <h1 class="text-2xl font-normal text-gray-800 font-sans">Data Peserta</h1>
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


    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

        <div>
            <label class="font-medium text-gray-600">Nama Lengkap</label>
            <p class="bg-gray-50 border rounded px-3 py-2">{{ $peserta->name }}</p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Email</label>
            <p class="bg-gray-50 border rounded px-3 py-2">{{ $peserta->email }}</p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Nomor HP</label>
            <p class="bg-gray-50 border rounded px-3 py-2">{{ $peserta->no_hp ?? '-' }}</p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Jenis Kelamin</label>
            <p class="bg-gray-50 border rounded px-3 py-2">{{ $peserta->jenis_kelamin ?? '-' }}</p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Tempat Lahir</label>
            <p class="bg-gray-50 border rounded px-3 py-2">{{ $peserta->tempat_lahir ?? '-' }}</p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Tanggal Lahir</label>
            <p class="bg-gray-50 border rounded px-3 py-2">
                {{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d F Y') : '-' }}
            </p>
        </div>

        <div class="md:col-span-2">
            <label class="font-medium text-gray-600">Alamat</label>
            <p class="bg-gray-50 border rounded px-3 py-2 min-h-[60px]">
                {{ $peserta->alamat ?? '-' }}
            </p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Status Email</label>
            <p class="bg-gray-50 border rounded px-3 py-2">
                @if($peserta->email_verified_at)
                    <span class="text-green-600">Terverifikasi</span>
                @else
                    <span class="text-red-500">Belum Terverifikasi</span>
                @endif
            </p>
        </div>

        <div>
            <label class="font-medium text-gray-600">Tanggal Registrasi</label>
            <p class="bg-gray-50 border rounded px-3 py-2">
                {{ $peserta->created_at->format('d M Y H:i') }}
            </p>
        </div>
    </div>

</div>