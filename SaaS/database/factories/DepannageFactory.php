<?php

namespace Database\Factories;

use App\Models\Depannage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepannageFactory extends Factory
{
    protected $model = Depannage::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->name(),
            'adresse' => $this->faker->address(),
            'contact_email' => $this->faker->email(),
            'description_probleme' => $this->faker->sentence(),
            'statut' => 'À planifier',
            'telephone' => $this->faker->phoneNumber(),
            'type_materiel' => $this->faker->randomElement(['Barrière', 'Portail', 'Portillon, Tourniquet']),
            'message_erreur' => $this->faker->sentence(),
            'infos_supplementaires' => $this->faker->text(),
            'date_depannage' => null, // Valeur par défaut
            'provenance' => $this->faker->randomElement(['ajout manuel', 'chargé d\'affaire', 'client']),
            'archived' => false,
        ];
    }
}
