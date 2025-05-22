<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public static function geocode(string $adresse): ?array
    {
        $response = Http::get('https://api-adresse.data.gouv.fr/search/', [
            'q' => $adresse,
            'limit' => 1
        ]);

        if ($response->ok() && isset($response['features'][0])) {
            $coordinates = $response['features'][0]['geometry']['coordinates'];
            return [
                'longitude' => $coordinates[0],
                'latitude' => $coordinates[1],
            ];
        }

        return null;
    }
}
