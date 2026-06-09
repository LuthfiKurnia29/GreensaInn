<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoRuangan extends Model
{
    protected $fillable = [
        'file_foto',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
