<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gereja extends Model
{
    protected $fillable = ['nama','foto_gereja','alamat','telepon','gambar_qris'];

    public function jadwalIbadah()
    {
        return $this->hasMany(JadwalIbadah::class);
    }
}
