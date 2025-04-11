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
        'contact_email',
        'telephone',
        'type_materiel',
        'description_probleme',
        'message_erreur',
        'infos_supplementaires',
        'statut',
    ];

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'depannage_id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'depannage_id');
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
}
