<?php

namespace Database\Seeders;

use App\Models\Depannage;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepannageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Depannage::factory(10)->create()->each(function ($depannage) {
            // Créer un objet Type pour chaque Depannage
            Type::create([
                'depannage_id' => $depannage->id,
                'garantie' => 'Non renseigné',
                'contrat' => 'Non renseigné',
            ]);
        });
    }
}
