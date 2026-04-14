@extends('admin.layouts.app')

@section('header', 'Data Pengajuan Magang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Pengajuan Magang</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-100 table-auto rounded-lg">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-bold text-gray-700 border-b border-gray-200">
                    <th class="py-4 px-4 border-b">No</th>
                    <th class="py-4 px-4 border-b">Nama Peserta</th>
                    <th class="py-4 px-4 border-b">Institusi</th>
                    <th class="py-4 px-4 border-b">Periode</th>
                    <th class="py-4 px-4 border-b">Tema</th>
                    <th class="py-4 px-4 border-b text-center">Status</th>
                    <th class="py-4 px-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($pengajuan_magangs as $index => $magang)
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            @if($magang->user)
                                <button onclick='openDetailModal(@json($magang))' class="font-bold text-gray-800 text-left hover:text-blue-600 transition focus:outline-none" title="Lihat Detail Peserta">
                                    {{ $magang->user->name }}
                                </button>
                            @else
                                <div class="font-bold text-gray-800">User terhapus</div>
                            @endif
                            <div class="text-xs text-gray-500">{{ $magang->status_peserta }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div>{{ $magang->nama_sekolah }}</div>
                            <div class="text-xs text-gray-500">{{ $magang->jurusan }} ({{ $magang->jenjang_pendidikan }})</div>
                        </td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::parse($magang->periode_mulai)->format('d M Y') }} - <br>
                            {{ \Carbon\Carbon::parse($magang->periode_selesai)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4 max-w-xs truncate" title="{{ $magang->tema_magang }}">
                            {{ $magang->tema_magang ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($magang->status_pengajuan == 'Menunggu')
                                <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Menunggu</span>
                            @elseif($magang->status_pengajuan == 'Diterima')
                                <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Diterima</span>
                            @else
                                <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Ditolak</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($magang->status_pengajuan == 'Menunggu')
                                <div class="flex justify-center space-x-2">
                                    <form action="{{ route('admin.pengajuan_magang.terima', $magang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menerima pengajuan ini?');">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded shadow transition" title="Terima">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pengajuan_magang.tolak', $magang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menolak pengajuan ini?');">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded shadow transition" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">Selesai direview</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500 font-medium">
                            Belum ada pengajuan magang.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Pengajuan & Data Diri -->
<div id="modal-detail" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title-detail" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="toggleModal('modal-detail')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4 border-b border-gray-600 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center" id="modal-title-detail">
                    <i class="fas fa-clipboard-list mr-3"></i> Tinjauan Pengajuan & Data Pemohon
                </h3>
                <button type="button" class="text-white hover:text-gray-200 bg-white/20 hover:bg-white/30 rounded-full w-8 h-8 flex items-center justify-center transition" onclick="toggleModal('modal-detail')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-white px-6 py-5 sm:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Kiri: Data Registrasi (User) -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-gray-200 flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 font-bold"><i class="fas fa-user-circle"></i></div>
                            <h4 class="text-lg font-bold text-gray-800">Data Diri Registrasi</h4>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Nama Lengkap</p>
                                <p id="admin-detail-nama" class="font-bold text-gray-800 mt-1 text-base"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Email</p>
                                    <p id="admin-detail-email" class="font-medium text-gray-800 mt-1 break-all"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">No. WhatsApp</p>
                                    <p id="admin-detail-hp" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jenis Kelamin</p>
                                    <p id="admin-detail-jk" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tanggal Lahir</p>
                                    <p id="admin-detail-tgl-lahir" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Alamat Domisili</p>
                                <p id="admin-detail-alamat" class="font-medium text-gray-800 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Data Pengajuan -->
                    <div class="space-y-4">
                        <div class="pb-2 border-b border-gray-200 flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 font-bold"><i class="fas fa-file-signature"></i></div>
                            <h4 class="text-lg font-bold text-gray-800">Detail Akademik & Magang</h4>
                        </div>

                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Institusi Asal</p>
                                    <p id="admin-detail-instansi" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">NIM / NISN</p>
                                    <p id="admin-detail-nim" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jurusan (Jenjang)</p>
                                <p id="admin-detail-jurusan" class="font-medium text-gray-800 mt-1"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-3 mt-3">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jenis</p>
                                    <p id="admin-detail-jenis" class="font-medium text-gray-800 mt-1"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Periode Magang</p>
                                    <p id="admin-detail-periode" class="font-medium text-gray-800 mt-1 text-sm"></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-1">Tema Magang Diusulkan</p>
                                <p id="admin-detail-tema" class="font-medium text-gray-800 mt-1 bg-gray-50 p-2 rounded-lg border border-gray-100 italic"></p>
                            </div>
                            
                            <!-- Download Berkas -->
                            <div class="flex space-x-3 mt-4 pt-3 border-t border-gray-100">
                                <a id="admin-btn-surat" target="_blank" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i> Surat Pengantar
                                </a>
                                <a id="admin-btn-foto" target="_blank" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                    <i class="fas fa-image text-blue-500 mr-2"></i> Pas Foto
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center border-t border-gray-200" id="admin-action-footer">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-semibold text-gray-500 uppercase">Status:</span>
                    <span id="admin-detail-status-badge" class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm"></span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex space-x-3" id="admin-action-buttons">
                        <form id="form-terima" method="POST" onsubmit="return confirm('Apakah Anda yakin menerima pengajuan ini?');">
                            @csrf
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2 bg-green-500 text-sm font-bold text-white hover:bg-green-600 focus:outline-none transition">
                                <i class="fas fa-check mr-2 mt-0.5"></i> Terima Peserta
                            </button>
                        </form>
                        <form id="form-tolak" method="POST" onsubmit="return confirm('Apakah Anda yakin menolak pengajuan ini?');">
                            @csrf
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2 bg-red-500 text-sm font-bold text-white hover:bg-red-600 focus:outline-none transition">
                                <i class="fas fa-times mr-2 mt-0.5"></i> Tolak
                            </button>
                        </form>
                    </div>
                    <a id="admin-btn-cetak" target="_blank" class="hidden inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none transition">
                        <i class="fas fa-print mr-2 mt-0.5"></i> Cetak Surat Balasan
                    </a>
                    <button type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition sm:hidden" onclick="toggleModal('modal-detail')">
                        Tutup
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        let el = document.getElementById(modalID);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            el.querySelector('.transform').classList.add('scale-100');
            el.querySelector('.transform').classList.remove('scale-95');
        } else {
            el.classList.add('hidden');
        }
    }

    function formatDateIndonesian(dateString) {
        if (!dateString) return '-';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        const date = new Date(dateString);
        return `${String(date.getDate()).padStart(2, '0')} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    function openDetailModal(magang) {
        // Data Registrasi (User)
        if (magang.user) {
            document.getElementById('admin-detail-nama').innerText = magang.user.name || '-';
            document.getElementById('admin-detail-email').innerText = magang.user.email || '-';
            document.getElementById('admin-detail-hp').innerText = magang.user.no_hp || '-';
            document.getElementById('admin-detail-jk').innerText = magang.user.jenis_kelamin || '-';
            document.getElementById('admin-detail-tgl-lahir').innerText = formatDateIndonesian(magang.user.tanggal_lahir);
            document.getElementById('admin-detail-alamat').innerText = magang.user.alamat || '-';
        } else {
            document.getElementById('admin-detail-nama').innerText = 'User terhapus';
            ['email', 'hp', 'jk', 'tgl-lahir', 'alamat'].forEach(id => document.getElementById(`admin-detail-${id}`).innerText = '-');
        }

        // Data Magang
        document.getElementById('admin-detail-instansi').innerText = magang.nama_sekolah || '-';
        document.getElementById('admin-detail-nim').innerText = magang.nim_nisn || '-';
        document.getElementById('admin-detail-jurusan').innerText = `${magang.jurusan || '-'} (${magang.jenjang_pendidikan || '-'})`;
        document.getElementById('admin-detail-jenis').innerText = magang.jenis_magang || '-';
        document.getElementById('admin-detail-tema').innerText = magang.tema_magang || '-';
        
        const periodStr = `${formatDateIndonesian(magang.periode_mulai)} - ${formatDateIndonesian(magang.periode_selesai)}`;
        document.getElementById('admin-detail-periode').innerText = periodStr;

        // Berkas Links
        const btnSurat = document.getElementById('admin-btn-surat');
        const btnFoto = document.getElementById('admin-btn-foto');
        if (magang.surat_pengantar) {
            btnSurat.href = `{{ url('storage') }}/${magang.surat_pengantar}`;
            btnSurat.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            btnSurat.removeAttribute('href');
            btnSurat.classList.add('opacity-50', 'pointer-events-none');
        }
        if (magang.pas_foto) {
            btnFoto.href = `{{ url('storage') }}/${magang.pas_foto}`;
            btnFoto.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            btnFoto.removeAttribute('href');
            btnFoto.classList.add('opacity-50', 'pointer-events-none');
        }

        // Status & Actions
        const badge = document.getElementById('admin-detail-status-badge');
        badge.innerText = magang.status_pengajuan;
        badge.className = "px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm ";
        
        const actionButtons = document.getElementById('admin-action-buttons');
        const btnCetak = document.getElementById('admin-btn-cetak');
        
        if (magang.status_pengajuan === 'Menunggu') {
            badge.className += "bg-yellow-500";
            actionButtons.classList.remove('hidden');
            btnCetak.classList.add('hidden');
            
            // Set action URLs for the forms dynamically based on ID
            document.getElementById('form-terima').action = `/admin/pengajuan-magang/${magang.id}/terima`;
            document.getElementById('form-tolak').action = `/admin/pengajuan-magang/${magang.id}/tolak`;
        } else {
            actionButtons.classList.add('hidden');
            if (magang.status_pengajuan === 'Diterima') badge.className += "bg-green-500";
            if (magang.status_pengajuan === 'Ditolak') badge.className += "bg-red-500";
            
            // Show Cetak button and set url
            btnCetak.href = `{{ url('admin/pengajuan-magang') }}/${magang.id}/cetak-surat`;
            btnCetak.classList.remove('hidden');
        }

        toggleModal('modal-detail');
    }
</script>
@endsection
