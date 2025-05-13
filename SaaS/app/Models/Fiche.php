<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiche extends Model
{
    protected $fillable = ['user_id', 'ficheable_type', 'ficheable_id'];

    public function ficheable(){
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
