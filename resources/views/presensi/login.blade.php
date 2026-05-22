<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Presensi – PRESMA Mobile BPS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:    #2563eb;
            --blue-dk: #1d4ed8;
            --amber:   #d97706;
            --gray-50: #f8fafc;
            --gray-100:#f1f5f9;
            --gray-200:#e2e8f0;
            --gray-400:#94a3b8;
            --gray-500:#64748b;
            --gray-700:#334155;
            --gray-900:#0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Wrapper ─────────────────────────────── */
        .wrapper {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* ── Card ────────────────────────────────── */
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 28px 32px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06), 0 8px 32px rgba(0,0,0,.08);
        }

        /* ── Logo ────────────────────────────────── */
        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 22px;
        }
        .logo-img {
            width: 120px;
            height: 120px;
            border-radius: 22px;
            object-fit: cover;
            box-shadow: 0 6px 24px rgba(37,99,235,.20);
        }
        .logo-fallback {
            width: 120px;
            height: 120px;
            border-radius: 22px;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #7c3aed 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            box-shadow: 0 6px 24px rgba(37,99,235,.25);
        }
        .logo-fallback i    { font-size: 36px; color: #fff; }
        .logo-fallback span { font-size: 13px; font-weight: 800; color: #fff; letter-spacing: 1px; }

        /* ── Title ───────────────────────────────── */
        .title-block {
            text-align: center;
            margin-bottom: 28px;
        }
        .title-block h1 {
            font-size: 20px;
            font-weight: 800;
            color: var(--blue);
            letter-spacing: -0.3px;
            margin-bottom: 5px;
        }
        .title-block p {
            font-size: 13px;
            color: var(--gray-500);
        }

        /* ── Alerts ──────────────────────────────── */
        .alert {
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 18px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-error   { background: #fef2f2; border-left: 3px solid #ef4444; color: #dc2626; }
        .alert-success { background: #f0fdf4; border-left: 3px solid #22c55e; color: #16a34a; }
        .alert i { margin-top: 1px; flex-shrink: 0; }

        /* ── Form ────────────────────────────────── */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap .ico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 13px;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 38px;
            border: 1.5px solid var(--gray-200);
            border-radius: 11px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--gray-900);
            background: var(--gray-50);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .input-wrap input::placeholder { color: var(--gray-400); }
        .input-wrap input:focus {
            border-color: var(--blue);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.10);
        }

        /* ── Submit button ───────────────────────── */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--blue);
            color: #fff;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 14px rgba(37,99,235,.30);
            transition: background .2s, transform .15s, box-shadow .2s;
            margin-top: 4px;
        }
        .btn-submit:hover {
            background: var(--blue-dk);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,.38);
        }
        .btn-submit:active { transform: translateY(0); }

        /* ── Divider ─────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 22px 0;
        }
        .divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid var(--gray-200);
        }
        .divider span { font-size: 11px; color: var(--gray-400); white-space: nowrap; }

        /* ── Back button ────────────────────────── */
        .btn-back {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: var(--gray-500);
            font-size: 13.5px;
            font-weight: 600;
            border: 1.5px solid var(--gray-400);
            border-radius: 11px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all .2s;
        }
        .btn-back:hover {
            background: var(--gray-100);
            color: var(--gray-700);
            border-color: var(--gray-500);
        }

        /* ── Footer ──────────────────────────────── */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: var(--gray-400);
            line-height: 1.7;
        }
        .footer hr {
            border: none;
            border-top: 1px solid var(--gray-200);
            margin-bottom: 12px;
        }

        @media (max-width: 360px) {
            .card { padding: 28px 20px 24px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        {{-- Logo --}}
        <div class="logo-wrap">
            <img src="{{ asset('assets/img/logo.png') }}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                 alt="PRESMA Mobile BPS"
                 class="logo-img">
            <div class="logo-fallback" style="display:none;">
                <i class="fas fa-clipboard-check"></i>
                <span>BPS</span>
            </div>
        </div>

        {{-- Title --}}
        <div class="title-block">
            <h1>SIGMA BANTEN</h1>
            <p>Fasilitas Presensi Magang Secara Digital</p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="alert" style="background:#d1fae5;border-left:4px solid #10b981;color:#065f46;border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:10px;margin-bottom:12px;font-size:0.875rem;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>
                    @foreach($errors->all() as $err) {{ $err }} @endforeach
                </span>
            </div>
        @endif

        @if(Auth::check())
            <form action="{{ route('presensi.login_sistem') }}" method="POST" style="margin-bottom: 20px;">
                @csrf
                <button type="submit" class="btn-submit" style="background-color: #10b981; margin-bottom: 10px;">
                    <i class="fas fa-user-check"></i> Lanjut sebagai {{ Auth::user()->name }}
                </button>
            </form>

            <!--<div class="divider">
                <hr><span>atau login dengan akun lain</span><hr>
            </div> -->
        @endif

        {{-- Form Login Manual --}}
        <form action="{{ route('presensi.login.post') }}" method="POST">
            @csrf

           <!-- <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope ico"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock ico"></i>
                    <input type="password" id="password" name="password"
                           placeholder="Masukkan password anda"
                           required autocomplete="current-password">
                </div>
            </div> 

            <button type="submit" class="btn-submit">Masuk</button>
        </form> -->

        {{-- Divider --}}
        <div class="divider">
            <hr><span>atau</span><hr>
        </div> 

        {{-- Back to Dashboard --}}
        <a href="{{ route('peserta.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <hr>
        &copy; {{ date('Y') }} BADAN PUSAT STATISTIK PROVINSI BANTEN
    </div>
</div>
</body>
</html>

-->