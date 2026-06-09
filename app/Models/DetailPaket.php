<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPaket extends Model
{
    protected $table = 'detail_pakets';

    protected $fillable = [
        'paket_id',
        'fasilitas_id',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}
