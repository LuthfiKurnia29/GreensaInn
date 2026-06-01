<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'ruangan_id',
        'user_id',
        'tanggal_mulai',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'jumlah_peserta',
        'tujuan_rapat',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
