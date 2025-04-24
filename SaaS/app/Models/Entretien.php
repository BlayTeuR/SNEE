<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    public function historiques()
    {
        return $this->morphMany(Historique::class, 'historiqueable');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

}
