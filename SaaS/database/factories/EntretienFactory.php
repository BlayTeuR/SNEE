<?php

namespace Database\Factories;

use App\Models\Entretien;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntretienFactory extends Factory
{
    protected $model = Entretien::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->company,
            'adresse' => $this->faker->address,
            'contact_email' => $this->faker->unique()->safeEmail,
            'panne_vigilance' => $this->faker->sentence(3),
            'telephone' => $this->faker->phoneNumber,
            'type_materiel' => $this->faker->randomElement(['Chaudière', 'Climatiseur', 'Pompe à chaleur', 'Chauffe-eau']),
            'derniere_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'archived' => $this->faker->boolean(20),
        ];
    }
}
