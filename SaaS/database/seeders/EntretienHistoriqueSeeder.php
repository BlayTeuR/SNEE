<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entretien;
use App\Models\Historique;

class EntretienHistoriqueSeeder extends Seeder
{
    public function run()
    {
        Entretien::factory()
            ->count(10)
            ->create()
            ->each(function ($entretien) {
                // Pour chaque Entretien créé, on crée 2 à 5 historiques
                Historique::factory()
                    ->count(rand(2, 5))
                    ->create([
                        'historiqueable_id' => $entretien->id,
                        'historiqueable_type' => Entretien::class,
                    ]);
            });
    }
}
