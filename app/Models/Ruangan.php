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
        'harga_per_jam',
        'tipe_ruangan',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function fotoRuangan()
    {
        return $this->hasMany(FotoRuangan::class);
    }
}
