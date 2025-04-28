<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Historique extends Model
{

    use HasFactory;
    protected $fillable = ['date'];

    public function historiqueable()
    {
        return $this->morphTo();
    }
}
