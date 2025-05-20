<?php

namespace Database\Factories;

use App\Models\Depannage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepannageFactory extends Factory
{
    protected $model = Depannage::class;

    public function definition()
    {
        // Utilisation de la locale française
        $faker = \Faker\Factory::create('fr_FR');

        return [
            'nom' => $faker->name(),
            'adresse' => $faker->address(),
            'code_postal' => $faker->postcode(),
            'contact_email' => $faker->email(),
            'description_probleme' => $faker->sentence(),
            'statut' => 'À planifier',
            'telephone' => $faker->phoneNumber(),
            'type_materiel' => $faker->randomElement(['Barrière', 'Portail', 'Portillon', 'Tourniquet']),
            'message_erreur' => $faker->sentence(),
            'infos_supplementaires' => $faker->text(),
            'date_depannage' => null,
            'provenance' => $faker->randomElement(['ajout manuel', 'chargé d\'affaire', 'client']),
            'prevention' => $faker->boolean(),
            'archived' => false,
        ];
    }
}
