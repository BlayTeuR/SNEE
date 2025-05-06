<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleAttribute($value)
    {
        return Role::from($value); // Utilise l'enum pour récupérer le rôle
    }

    // Ajoute un mutator pour enregistrer le rôle en tant que chaîne
    public function setRoleAttribute($value)
    {
        $this->attributes['role'] = $value instanceof Role ? $value->value : $value;
    }

    // Méthode pour vérifier si l'utilisateur est un admin
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    // Méthode pour vérifier si l'utilisateur est un technicien
    public function isTechnicien(): bool
    {
        return $this->role === Role::TECHNICIEN;
    }

    // Méthode pour vérifier si l'utilisateur est un client

}
