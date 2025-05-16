<?php

namespace Database\Factories;

use App\Models\Entretien;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntretienFactory extends Factory
{
    protected $model = Entretien::class;

    public function definition()
    {
        // Faker avec locale française
        $faker = \Faker\Factory::create('fr_FR');

        return [
            'nom' => $faker->company(),
            'adresse' => $faker->address(),
            'code_postal' => $faker->postcode(),
            'contact_email' => $faker->unique()->safeEmail(),
            'panne_vigilance' => $faker->sentence(3),
            'telephone' => $faker->phoneNumber(),
            'type_materiel' => $faker->randomElement(['Chaudière', 'Climatiseur', 'Pompe à chaleur', 'Chauffe-eau']),
            'derniere_date' => $faker->dateTimeBetween('-2 years', 'now'),
            'archived' => $faker->boolean(20),
        ];
    }
}
