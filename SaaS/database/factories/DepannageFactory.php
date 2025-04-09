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
            'contact_email' => $this->faker->email(), // Mise à jour du champ
            'description_probleme' => $this->faker->sentence(),
            'statut' => $this->faker->randomElement(['À planifier', 'Affecter', 'Approvisionnement', 'À facturer']),
            'telephone' => $this->faker->phoneNumber(),
            'type_materiel' => $this->faker->word(),
            'message_erreur' => $this->faker->sentence(),
            'infos_supplementaires' => $this->faker->text(),
        ];
    }
}
