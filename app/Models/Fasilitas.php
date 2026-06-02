<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    protected $fillable = ['nama_fasilitas', 'stok_tersedia'];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjamanFasilitas::class);
    }
}
