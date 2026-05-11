<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'status_peserta',
        'jenis_magang',
        'nim_nisn',
        'jenjang_pendidikan',
        'nama_sekolah',
        'fakultas',
        'jurusan',
        'kelas',
        'semester',
        'periode_mulai',
        'periode_selesai',
        'tema_magang',
        'surat_pengantar',
        'pas_foto',
        'kartu_pelajar',
        'status_pengajuan',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
