<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanFasilitas extends Model
{
    protected $table = 'detail_peminjaman_fasilitas';

    protected $fillable = [
        'peminjaman_id',
        'fasilitas_id',
        'stok_tersedia', // Digunakan sebagai jumlah yang dipinjam
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}
