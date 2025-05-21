<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{

    use HasFactory;

    protected $fillable = ['user_id', 'affecteable_type', 'affecteable_id'];

    public function affecteable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
