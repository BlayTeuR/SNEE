<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $fillable = ['depannage_id', 'date'];

    public function depannage()
    {
        return $this->belongsTo(Depannage::class);
    }
}
