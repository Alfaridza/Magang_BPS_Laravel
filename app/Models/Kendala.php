<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendala extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_in',
        'jam_out',
        'jenis_kendala',
        'keterangan',
        'status_approve'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
