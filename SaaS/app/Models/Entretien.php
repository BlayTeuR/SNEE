<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{

    protected $fillable = [
        'nom',
        'adresse',
        'contact_email',
        'panne_vigilance',
        'telephone',
        'type_materiel',
        'derniere_date',
        'archived',
    ];
    public function historiques()
    {
        return $this->morphMany(Historique::class, 'historiqueable');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

}
