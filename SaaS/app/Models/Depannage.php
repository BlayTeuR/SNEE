<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depannage extends Model
{

    use HasFactory;

    protected $table = 'depannages';
    protected $fillable = [
        'nom',
        'adresse',
        'code_postal',
        'contact_email',
        'telephone',
        'type_materiel',
        'description_probleme',
        'message_erreur',
        'infos_supplementaires',
        'statut',
        'date_depannage',
        'provenance',
        'prevention',
        'archived',
    ];

    protected static function booted()
    {
        static::deleting(function ($depannage) {
            $depannage->photos()->delete();
            $depannage->historiques()->delete();
            $depannage->fiches()->delete();
        });
    }

    public function historiques()
    {
        return $this->morphMany(Historique::class, 'historiqueable');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function approvisionnements()
    {
        return $this->hasMany(Approvisionnement::class, 'depannage_id');
    }

    public function types(){
        return $this->hasOne(Type::class, 'depannage_id');
    }

    public function facturations()
    {
        return $this->hasMany(Facturations::class, 'depannage_id');
    }

    public function fiches(){
        return $this->morphMany(Fiche::class, 'ficheable');
    }

    public function affectation()
    {
        return $this->morphOne(Affectation::class, 'affecteable');
    }
}
