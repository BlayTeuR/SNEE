<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $table = 'validation';
    protected $fillable = ['validation', 'validationable_type', 'validationable_id', 'commentaire', 'date', 'detail'];

    public function validationable()
    {
        return $this->morphTo();
    }
}
