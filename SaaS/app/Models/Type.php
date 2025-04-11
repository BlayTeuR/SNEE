<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'depannage_id',
        'garantie',
        'contrat',
    ];

    public function depannage(){
        return $this->belongsTo(Depannage::class, 'depannage_id');
    }
}
