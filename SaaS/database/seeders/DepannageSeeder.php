<?php

namespace Database\Seeders;

use App\Models\Depannage;
use App\Models\Type;
use App\Services\GeocodingService;
use App\Traits\FormatsAdresse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepannageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    use FormatsAdresse;
    public function run(): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $adresses = [
            ['rue' => '5 rue des Mimosas', 'code_postal' => '34000', 'ville' => 'Montpellier'],
            ['rue' => '10 avenue Jean Jaurès', 'code_postal' => '75019', 'ville' => 'Paris'],
            ['rue' => '3 place Kléber', 'code_postal' => '67000', 'ville' => 'Strasbourg'],
            ['rue' => '18 boulevard Gambetta', 'code_postal' => '06000', 'ville' => 'Nice'],
            ['rue' => '1 rue Sainte-Catherine', 'code_postal' => '33000', 'ville' => 'Bordeaux'],
            ['rue' => '7 avenue de la République', 'code_postal' => '69100', 'ville' => 'Villeurbanne'],
            ['rue' => '22 rue Nationale', 'code_postal' => '59000', 'ville' => 'Lille'],
            ['rue' => '2 rue de la République', 'code_postal' => '13001', 'ville' => 'Marseille'],
            ['rue' => '9 rue de la Monnaie', 'code_postal' => '31000', 'ville' => 'Toulouse'],
            ['rue' => '11 rue des Docks', 'code_postal' => '76600', 'ville' => 'Le Havre'],
        ];

        foreach ($adresses as $adresse) {
            $adresseNettoyee = $this->formatAdresse($adresse['rue'], $adresse['code_postal']);
            $coordinates = GeocodingService::geocode($adresseNettoyee);
            $depannage = Depannage::create([
                'nom' => $faker->name(),
                'adresse' => $adresse['rue'] . ', ' . $adresse['ville'],
                'code_postal' => $adresse['code_postal'],
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
                'latitude' => $coordinates['latitude'] ?? null,
                'longitude' => $coordinates['longitude'] ?? null,
            ]);

            Type::create([
                'depannage_id' => $depannage->id,
                'garantie' => 'Non renseigné',
                'contrat' => 'Non renseigné',
            ]);
        }
    }
    }
