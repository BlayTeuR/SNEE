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
            ->count(30)
            ->create();
    }
}
