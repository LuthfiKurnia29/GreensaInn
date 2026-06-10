<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPendukung extends Model
{
    protected $table = 'dokumen_pendukungs';
    protected $guarded = ['id'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
