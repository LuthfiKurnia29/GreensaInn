<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'lokasi_ruangan',
        'deskripsi',
        'status_tersedia',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
