<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturations extends Model
{
    protected $fillable = [
        'depannage_id',
        'montant',
        'statut',
    ];

    public function depannage()
    {
        return $this->belongsTo(Depannage::class, 'depannage_id');
    }
}
