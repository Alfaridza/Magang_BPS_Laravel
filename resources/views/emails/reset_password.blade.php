<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Magang BPS Provinsi Banten</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7fa; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; padding: 30px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="display: inline-block; background: #003366; padding: 10px 15px; border-radius: 8px;">
                <span style="color: white; font-weight: bold; font-size: 16px;">BPS Provinsi Banten</span>
            </div>
        </div>

        <h2 style="color: #003366; margin-bottom: 5px;">Reset Password</h2>
        <p style="color: #64748b; font-size: 14px;">Halo, <strong>{{ $user->email }}</strong></p>
        
        <p>Kami menerima permintaan untuk mereset password akun Anda di portal Magang BPS Provinsi Banten.</p>
        <p>Silakan klik tombol di bawah ini untuk mengatur password baru:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #0099CC; color: white; padding: 14px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 15px;">Atur Password Baru</a>
        </div>
        
        <p style="font-size: 13px; color: #64748b;">Atau salin dan tempelkan tautan berikut ke peramban Anda:</p>
        <p style="word-break: break-all; background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 0.85em; color: #475569; border: 1px solid #e2e8f0;">{{ $url }}</p>
        
        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <p style="font-size: 13px; color: #94a3b8;">
                <strong>⏱ Tautan ini akan kedaluwarsa dalam 60 menit.</strong><br>
                Jika Anda tidak merasa meminta reset password, abaikan email ini.
            </p>
        </div>
        
        <p style="margin-top: 20px; font-size: 13px; color: #64748b;">
            Salam hangat,<br>
            <strong>Panitia Seleksi Calon Peserta Magang BPS Provinsi Banten</strong>
        </p>
    </div>
</body>
</html>
