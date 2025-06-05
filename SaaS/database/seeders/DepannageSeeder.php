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
            ['rue' => '10 rue de la République', 'code_postal' => '69002', 'ville' => 'Lyon'],
            ['rue' => '22 boulevard Haussmann', 'code_postal' => '75009', 'ville' => 'Paris'],
            ['rue' => '5 avenue Jean Médecin', 'code_postal' => '06000', 'ville' => 'Nice'],
            ['rue' => '17 place de la Comédie', 'code_postal' => '34000', 'ville' => 'Montpellier'],
            ['rue' => '3 rue Sainte-Catherine', 'code_postal' => '33000', 'ville' => 'Bordeaux'],
            ['rue' => '48 rue Nationale', 'code_postal' => '59800', 'ville' => 'Lille'],
            ['rue' => '12 cours Berriat', 'code_postal' => '38000', 'ville' => 'Grenoble'],
            ['rue' => '8 place Kléber', 'code_postal' => '67000', 'ville' => 'Strasbourg'],
            ['rue' => '4 rue du Palais', 'code_postal' => '21000', 'ville' => 'Dijon'],
            ['rue' => '19 rue Foch', 'code_postal' => '80000', 'ville' => 'Amiens'],
            ['rue' => '7 avenue Alsace Lorraine', 'code_postal' => '38100', 'ville' => 'Grenoble'],
            ['rue' => '6 rue Saint-Ferréol', 'code_postal' => '13001', 'ville' => 'Marseille'],
            ['rue' => '10 rue du Maréchal Foch', 'code_postal' => '80000', 'ville' => 'Amiens'],
            ['rue' => '25 rue des Carmes', 'code_postal' => '31000', 'ville' => 'Toulouse'],
            ['rue' => '11 rue Jean Jaurès', 'code_postal' => '44600', 'ville' => 'Saint-Nazaire'],
            ['rue' => '14 avenue du Général Leclerc', 'code_postal' => '25000', 'ville' => 'Besançon'],
            ['rue' => '8 place du Ralliement', 'code_postal' => '49100', 'ville' => 'Angers'],
            ['rue' => '29 rue des Grandes Arcades', 'code_postal' => '67000', 'ville' => 'Strasbourg'],
            ['rue' => '33 avenue du Prado', 'code_postal' => '13006', 'ville' => 'Marseille'],
            ['rue' => '12 rue Nationale', 'code_postal' => '72000', 'ville' => 'Le Mans'],
            ['rue' => '17 rue de Siam', 'code_postal' => '29200', 'ville' => 'Brest'],
            ['rue' => '21 boulevard Victor Hugo', 'code_postal' => '06000', 'ville' => 'Nice'],
            ['rue' => '15 rue d’Alsace', 'code_postal' => '88000', 'ville' => 'Épinal'],
            ['rue' => '9 rue de la Monnaie', 'code_postal' => '59800', 'ville' => 'Lille'],
            ['rue' => '6 place Saint-Corentin', 'code_postal' => '29000', 'ville' => 'Quimper'],
            ['rue' => '3 avenue Albert 1er', 'code_postal' => '21000', 'ville' => 'Dijon'],
            ['rue' => '44 rue de Vesle', 'code_postal' => '51100', 'ville' => 'Reims'],
            ['rue' => '8 rue des Minimes', 'code_postal' => '31000', 'ville' => 'Toulouse'],
            ['rue' => '19 avenue Foch', 'code_postal' => '92100', 'ville' => 'Boulogne-Billancourt'],
            ['rue' => '7 rue Saint-Nicolas', 'code_postal' => '17000', 'ville' => 'La Rochelle'],
            ['rue' => '2 rue Masséna', 'code_postal' => '06000', 'ville' => 'Nice'],
            ['rue' => '10 rue Victor Hugo', 'code_postal' => '42000', 'ville' => 'Saint-Étienne'],
            ['rue' => '5 place de Jaude', 'code_postal' => '63000', 'ville' => 'Clermont-Ferrand'],
            ['rue' => '13 rue du 11 Novembre', 'code_postal' => '42000', 'ville' => 'Saint-Étienne'],
            ['rue' => '6 avenue Georges Clemenceau', 'code_postal' => '06000', 'ville' => 'Nice'],
            ['rue' => '20 place Bellecour', 'code_postal' => '69002', 'ville' => 'Lyon'],
            ['rue' => '18 rue Jean Moulin', 'code_postal' => '29200', 'ville' => 'Brest'],
            ['rue' => '3 boulevard de la Liberté', 'code_postal' => '35000', 'ville' => 'Rennes'],
            ['rue' => '4 rue du Rempart', 'code_postal' => '67000', 'ville' => 'Strasbourg'],
            ['rue' => '9 rue Gambetta', 'code_postal' => '86000', 'ville' => 'Poitiers'],
            ['rue' => '12 rue de la République', 'code_postal' => '74000', 'ville' => 'Annecy'],
            ['rue' => '8 rue Pierre Semard', 'code_postal' => '30000', 'ville' => 'Nîmes'],
            ['rue' => '7 rue de Verdun', 'code_postal' => '80000', 'ville' => 'Amiens'],
            ['rue' => '11 rue Saint-Denis', 'code_postal' => '75001', 'ville' => 'Paris'],
            ['rue' => '6 rue de Metz', 'code_postal' => '31000', 'ville' => 'Toulouse'],
            ['rue' => '21 rue des Halles', 'code_postal' => '44000', 'ville' => 'Nantes'],
            ['rue' => '4 rue Lafayette', 'code_postal' => '38000', 'ville' => 'Grenoble'],
            ['rue' => '17 boulevard Maréchal Leclerc', 'code_postal' => '14000', 'ville' => 'Caen'],
            ['rue' => '3 avenue du Président Wilson', 'code_postal' => '93200', 'ville' => 'Saint-Denis'],
            ['rue' => '9 rue Charles de Gaulle', 'code_postal' => '91100', 'ville' => 'Corbeil-Essonnes'],
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
