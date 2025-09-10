<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WartaJemaat extends Model
{
    protected $fillable = ['judul_warta','tanggal_warta','isi_warta','lampiran_pdf','kontent'];

    // protected $casts = [
    //     'kontent' => 'array',
    // ];
}
