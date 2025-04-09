<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depannage extends Model
{

    use HasFactory;
    protected $fillable = [
        'nom', 'adresse', 'contact_email', 'statut', 'description_probleme',
        'telephone', 'type_materiel', 'message_erreur', 'infos_supplementaires'
    ];

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
