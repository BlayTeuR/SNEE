<?php

namespace Database\Factories;

use App\Models\Historique;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriqueFactory extends Factory
{
    protected $model = Historique::class;

    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
