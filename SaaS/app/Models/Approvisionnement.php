<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Depannage;
use App\Models\Piece;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'depannage_id',
        'statut',
    ];

    public function depannage()
    {
        return $this->belongsTo(Depannage::class, 'depannage_id');
    }

    public function pieces(){
        return $this->hasMany(Piece::class, 'approvisionnement_id');
    }
}
