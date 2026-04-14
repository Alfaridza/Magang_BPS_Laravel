<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magang BPS Provinsi Banten</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bps: {
                            dark: '#003366', 
                            light: '#0099CC', 
                            accent: '#FF9900', 
                            soft: '#E6F7FF',   
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg {
            background: linear-gradient(to bottom, rgba(0, 51, 102, 0.9), rgba(0, 51, 102, 0.7)), url('{{ asset("assets/img/background.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen relative">
    <!-- Background Design -->
    <div class="fixed inset-0 z-0 hero-bg"></div>
    <div class="fixed inset-0 z-0 bg-bps-dark/80 backdrop-blur-sm"></div>

    <div class="relative z-10 min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden shadow-bps-dark/50">
            <div class="grid md:grid-cols-2 h-full">
                <!-- Sidebar Form -->
                <div class="bg-gradient-to-br from-bps-dark to-bps-light p-10 text-white flex flex-col justify-between hidden md:flex">
                    <div>
                        <a href="{{ url('/') }}" class="inline-flex items-center text-white/80 hover:text-white transition group mb-12">
                            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition"></i> Kembali ke Beranda
                        </a>
                        <img src="{{ asset('assets/img/logo.png') }}" class="h-16 w-auto bg-white rounded-xl p-2 mb-6 shadow-lg">
                        <h2 class="text-3xl font-extrabold mb-4 leading-tight">Selamat Datang</h2>
                        <p class="text-bps-soft/80 leading-relaxed font-light">
                            Masuk ke portal magang BPS Provinsi Banten untuk mendaftar pengajuan magang dan melihat status pengajuan magang Anda.
                        </p>
                    </div>
                    <div class="mt-8">
                        <p class="text-sm text-white/60">Belum punya akun?</p>
                        <a href="{{ url('auth/register') }}" class="inline-block mt-2 px-6 py-2 border-2 border-white/30 rounded-full hover:bg-white hover:text-bps-dark transition font-semibold">Daftar Sekarang</a>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <div class="md:hidden flex justify-between items-center mb-8">
                        <img src="{{ asset('assets/img/logo.png') }}" class="h-10 w-auto bg-bps-dark rounded p-1">
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-bps-dark"><i class="fas fa-times text-xl"></i></a>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">Masuk ke Akun Anda</h3>
                        <p class="text-gray-500 mt-1">Silakan masukkan email dan password yang terdaftar.</p>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('auth/login') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Aktif</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-bps-light focus:border-bps-light outline-none transition bg-gray-50 focus:bg-white" placeholder="nama@email.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-bps-light focus:border-bps-light outline-none transition bg-gray-50 focus:bg-white" placeholder="Masukkan password Anda">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-bps-dark text-white font-bold py-4 px-4 rounded-xl hover:bg-opacity-90 transition transform hover:-translate-y-0.5 shadow-lg shadow-bps-dark/30 flex justify-center items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                        </button>
                    </form>
                    
                    <div class="mt-8 text-center md:hidden">
                        <p class="text-gray-600 text-sm">Belum punya akun? <a href="{{ url('auth/register') }}" class="text-bps-light font-bold hover:underline">Daftar</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
