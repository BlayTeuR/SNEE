<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['chemin_photo'];

    public function photoable()
    {
        return $this->morphTo();
    }
}

