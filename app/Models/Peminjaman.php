<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruangan;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $guarded = ['id'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailFasilitas()
    {
        return $this->hasMany(DetailPeminjamanFasilitas::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function dokumenPendukung()
    {
        return $this->hasOne(DokumenPendukung::class);
    }
}