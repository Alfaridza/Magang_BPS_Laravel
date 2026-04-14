<!DOCTYPE html>
<html>
<head>
    <title>Setup Password Akun Magang BPS Provinsi Banten</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #003366;">Halo, {{ $user->email }}!</h2>
        <p>Terima kasih telah mendaftar di portal Magang BPS Provinsi Banten.</p>
        <p>Untuk mengaktifkan akun Anda dan mengatur *password* Anda, silakan klik tombol di bawah ini:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #0099CC; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Atur Password Anda</a>
        </div>
        
        <p>Atau Anda dapat menyalin dan menempelkan tautan berikut ke peramban Anda:</p>
        <p style="word-break: break-all; background: #f9f9f9; padding: 10px; border-radius: 5px; font-size: 0.9em;">{{ $url }}</p>
        
        <br>
        <p>Tautan ini akan kedaluwarsa dalam 60 menit.</p>
        <p>Salam hangat,<br>Tim Magang BPS Provinsi Banten</p>
    </div>
</body>
</html>
