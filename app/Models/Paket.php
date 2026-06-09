<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'pakets';

    protected $fillable = [
        'ruangan_id',
        'nama_paket',
        'harga_paket',
        'deskripsi',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function detail_pakets()
    {
        return $this->hasMany(DetailPaket::class);
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'detail_pakets', 'paket_id', 'fasilitas_id')->withTimestamps();
    }
}
