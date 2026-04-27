<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Magang & PKL - Admin</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Sidebar active state */
        .sidebar-link-active {
            background-color: #00a8cc; /* approximate cyan blue from image */
            color: white;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-[#1e293b] text-white flex flex-col hidden md:flex h-screen overflow-y-auto">
            <!-- Brand -->
            <div class="p-4 border-b border-gray-700 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="max-w-full h-auto bg-white rounded-full p-0.5">
                </div>
                <div class="text-sm font-semibold leading-tight leading-4">
                    Sistem Informasi Magang & PKL
                </div>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-gray-700">
                <div class="text-sm font-semibold font-bold">{{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : 'Admin' }}</div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-3 space-y-1 mt-2">
                <a href="{{ url('admin/dashboard') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('admin/dashboard') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="ml-2 w-full">Dashboard</span>
                </a>
                
                <!-- Divider for Master Data -->
                <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Master Data
                </div>

                <a href="{{ url('admin/manajemen-admin') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('admin/manajemen-admin') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-user-shield w-6 text-center"></i>
                    <span class="ml-2 w-full">Manajemen Admin</span>
                </a>

                <a href="{{ url('admin/manajemen-peserta') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('admin/manajemen-peserta') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-2 w-full">Manajemen Peserta</span>
                </a>

                <!-- Divider for Approval Data -->
                <div class="px-4 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Approval
                </div>

                <a href="{{ url('admin/pengajuan-magang') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('admin/pengajuan-magang') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-file-signature w-6 text-center"></i>
                    <span class="ml-2 w-full">Pengajuan Magang</span>
                </a>

                <a href="{{ url('admin/log-aktivitas') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('admin/log-aktivitas*') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-clipboard-list w-6 text-center"></i>
                    <span class="ml-2 w-full">Log Aktivitas</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden h-screen">
            
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-10 h-16 flex items-center justify-between px-4 sm:px-6">
                <!-- Mobile menu button & Title -->
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <!-- <h2 class="ml-4 text-xl font-semibold text-gray-800 hidden sm:block">@yield('header', 'Dashboard')</h2> -->
                </div>

                <!-- Right Top bar (Admin profile dropdown) -->
                <div class="flex items-center">
                    <div class="relative" id="admin-user-menu-container">
                        <button id="admin-user-menu-button" class="flex items-center text-gray-700 hover:text-gray-900 font-semibold px-3 py-2 rounded-md hover:bg-gray-100 transition focus:outline-none">
                            <img src="{{ 'https://ui-avatars.com/api/?name='.urlencode(Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : 'Admin').'&background=1e293b&color=fff&size=128' }}" alt="Avatar" class="w-8 h-8 rounded-full mr-2 object-cover">
                            <span class="hidden sm:inline">{{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->name : 'Admin' }}</span>
                            <i class="fas fa-caret-down ml-2 text-gray-500"></i>
                        </button>

                        <div id="admin-user-menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50">
                            <div class="flex items-center px-4 py-2 text-sm text-gray-700">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ Auth::guard('admin')->user() ? Auth::guard('admin')->user()->email : 'admin@local' }}
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f4f6f9] p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 p-4 text-sm text-gray-600">
                <div class="max-w-7xl mx-auto flex justify-between">
                    <div>
                        © {{ date('Y') }} <a href="https://banten.bps.go.id/" class="text-blue-600 hover:underline hover:text-blue-800 font-semibold">BPS Provinsi Banten</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Global Modal Overlay -->
    <div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div id="modal-window" class="bg-white rounded shadow-lg w-full max-w-2xl mx-4 overflow-auto relative">
            <button id="modal-close" class="absolute top-6 right-6 text-gray-600 hover:text-gray-800 text-2xl leading-none bg-white rounded-full p-2 shadow">&times;</button>
            <div id="modal-content" class="p-4 pt-6"></div>
        </div>
    </div>
    
    <script src="{{ asset('js/admin-modal.js') }}" defer></script>
    <script src="{{ asset('js/auto-flash-close.js') }}" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var btn = document.getElementById('admin-user-menu-button');
            var menu = document.getElementById('admin-user-menu-dropdown');

            if (!btn || !menu) return;

            document.addEventListener('click', function(e){
                if (btn.contains(e.target)) {
                    menu.classList.toggle('hidden');
                } else {
                    if (!menu.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                }
            });

            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') menu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
