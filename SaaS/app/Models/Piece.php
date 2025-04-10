<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    protected $fillable = [
        'approvisionnement_id',
        'libelle',
        'quantite',
        'prix_unitaire',
    ];

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class, 'approvisionnement_id');
    }
}
