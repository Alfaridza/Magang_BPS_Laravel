@extends('presensi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-xl p-6 text-white mb-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-40 h-40 -mr-20 -mt-20 bg-white bg-opacity-10 rounded-full"></div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('presensi.dashboard') }}" class="text-blue-300 hover:text-white transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold">Form Izin/Sakit</h1>
            </div>
            <p class="text-blue-200 text-sm ml-7">Ajukan permohonan izin Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <form action="{{ route('presensi.izin.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                
                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Perizinan</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Izin --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Perizinan</label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('status') border-red-500 @enderror" required>
                        <option value="" disabled selected>Pilih Jenis Izin</option>
                        <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin Setengah Hari" {{ old('status') == 'Izin Setengah Hari' ? 'selected' : '' }}>Izin Setengah Hari</option>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1 italic leading-tight">
                        * Gunakan <b>Izin Setengah Hari</b> jika Anda tetap masuk/pulang di salah satu shift (pagi/sore). 
                        Anda masih bisa melakukan satu kali presensi kamera.
                    </p>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Berikan alasan perizinan Anda..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('keterangan') border-red-500 @enderror" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Bukti --}}
                <div>
                    <label for="bukti_izin" class="block text-sm font-semibold text-gray-700 mb-1">Upload Bukti (Opsional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="bukti_izin" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload file</span>
                                    <input id="bukti_izin" name="bukti_izin" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>
                    <div id="image-preview-container" class="mt-3 hidden text-center">
                        <p class="text-xs font-semibold text-gray-500 mb-2">Preview:</p>
                        <img id="image-preview" src="#" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow-sm border border-gray-200">
                    </div>
                    @error('bukti_izin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-paper-plane"></i> Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('image-preview');
            output.src = reader.result;
            document.getElementById('image-preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
