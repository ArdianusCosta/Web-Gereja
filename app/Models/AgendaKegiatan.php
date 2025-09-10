<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaKegiatan extends Model
{
    protected $fillable = ['gereja_id','alamat','tanggal','waktu','kegiatan','tempat','keterangan'];

    public function gereja():BelongsTo
    {
        return $this->belongsTo(Gereja::class);
    }
}
