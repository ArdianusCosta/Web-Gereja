<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['gereja_id', 'link_youtube', 'link_instagram', 'link_facebook'];

    public function gereja()
    {
        return $this->belongsTo(Gereja::class);
    }
}
