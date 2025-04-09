<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['depannage_id', 'chemin_photo'];

    public function depannage(){
        return $this->belongsTo(Depannage::class, 'depannage_id');
    }
}
