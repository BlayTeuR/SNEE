<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiche extends Model
{
    public function ficheable(){
        return $this->morphTo();
    }
}
