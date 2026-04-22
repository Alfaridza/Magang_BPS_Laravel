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

                <!-- Right Top bar (Logout) -->
                <div class="flex items-center">
                    <form action="{{ route('admin.logout') }}" method="POST">
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
                        © {{ date('Y') }} <a href="https://banten.bps.go.id/" class="text-blue-600 hover:underline hover:text-blue-800 font-semibold">BPS Provinsi Banten</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
</body>
</html>
