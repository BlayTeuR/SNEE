<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $fillable = ['date'];

    public function historiqueable()
    {
        return $this->morphTo();
    }
}
