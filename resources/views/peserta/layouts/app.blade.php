<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Magang & PKL - Peserta</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Sidebar active state */
        .sidebar-link-active {
            background-color: #0099CC; /* matching BPS light blue from the landing page */
            color: white;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-[#003366] text-white flex flex-col hidden md:flex h-screen overflow-y-auto"> <!-- matching BPS dark blue -->
            <!-- Brand -->
            <div class="p-4 border-b border-gray-700/50 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-white p-0.5">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="max-w-full h-auto">
                </div>
                <div class="text-sm font-semibold leading-tight leading-4">
                    Sistem Informasi Magang & PKL
                </div>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-gray-700/50">
                <div class="text-sm font-semibold font-bold">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-300">Peserta</div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-3 space-y-1 mt-2">
                <a href="{{ url('peserta/dashboard') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('peserta/dashboard') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-home w-6 text-center"></i>
                    <span class="ml-2 w-full">Dashboard</span>
                </a>
                
                <a href="{{ url('peserta/daftar-magang') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('peserta/daftar-magang') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span class="ml-2 w-full">Daftar Magang</span>
                </a>

                <a href="{{ url('peserta/profil') }}" class="flex items-center px-4 py-2.5 text-sm {{ request()->is('peserta/profil') ? 'sidebar-link-active' : 'text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors' }}">
                    <i class="fas fa-user w-6 text-center"></i>
                    <span class="ml-2 w-full">Profil Saya</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden h-screen">
            
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-10 h-16 flex items-center justify-between px-4 sm:px-6">
                <!-- Mobile menu button -->
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="ml-4 text-xl font-semibold text-gray-800 hidden sm:block">@yield('header', 'Beranda')</h2>
                </div>

                <!-- Right Top bar (Logout) -->
                <div class="flex items-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-red-600 hover:text-red-800 font-semibold px-3 py-2 rounded-md hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
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
                        © {{ date('Y') }} <a href="https://banten.bps.go.id/" class="text-[#0099CC] hover:underline font-semibold">BPS Provinsi Banten</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
</body>
</html>
