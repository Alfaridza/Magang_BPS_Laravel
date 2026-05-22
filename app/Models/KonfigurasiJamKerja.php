<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KonfigurasiJamKerja extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_jam_kerja';

    protected $fillable = [
        'nama',
        'jam_masuk',
        'jam_masuk_toleransi',
        'jam_pulang',
        'is_wfa',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'radius_meter',
    ];
}
