<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← ajoute ceci


class Entretien extends Model
{

    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'code_postal',
        'contact_email',
        'panne_vigilance',
        'telephone',
        'type_materiel',
        'derniere_date',
        'archived',
        'latitude',
        'longitude',
    ];
    public function historiques()
    {
        return $this->morphMany(Historique::class, 'historiqueable');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function fiches(){
        return $this->morphMany(Fiche::class, 'ficheable');
    }

    public function affectations(){
        return $this->morphMany(Affectation::class, 'affecteable');    }
}
