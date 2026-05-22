<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Magang BPS Provinsi Banten</title>
    
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
        <div class="max-w-md w-full mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden shadow-bps-dark/50 p-8 lg:p-12">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-bps-soft rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-bps-light text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Lupa Password?</h3>
                <p class="text-gray-500 mt-2 text-sm">Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur password baru.</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-0.5"></i>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
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

            <form action="{{ route('forgot_password.send') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-bps-light focus:border-bps-light outline-none transition bg-gray-50 focus:bg-white" placeholder="nama@email.com">
                    </div>
                </div>

                <button type="submit" class="w-full bg-bps-dark text-white font-bold py-4 px-4 rounded-xl hover:bg-opacity-90 transition transform hover:-translate-y-0.5 shadow-lg shadow-bps-dark/30 flex justify-center items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Tautan Reset
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ url('auth/login') }}" class="text-bps-light font-semibold hover:underline text-sm inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke halaman Login
                </a>
            </div>
            
        </div>
    </div>
</body>
</html>
