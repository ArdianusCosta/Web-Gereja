<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalIbadah extends Model
{
    protected $fillable = ['gereja_id','nama_ibadah','hari','tanggal','waktu','lokasi','keterangan'];

    public function gereja()
    {
        return $this->belongsTo(Gereja::class);
    }
}
