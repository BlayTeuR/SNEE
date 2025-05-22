<?php

namespace App\Traits;

trait FormatsAdresse
{
    public function formatAdresse(string $adresse, string $codePostal): string
    {
        $adresse = preg_replace('/\s*,\s*/', ', ', trim($adresse)); // espaces mal placés autour des virgules
        $adresse = preg_replace('/\s+/', ' ', $adresse); // espaces multiples
        $adresse = str_ireplace($codePostal, '', $adresse); // suppression doublon du code postal
        $adresse = preg_replace('/,\s*,/', ',', $adresse); // double virgule
        $adresse = preg_replace('/,+$/', '', $adresse); // virgule finale
        return trim($adresse, ', ') . ', ' . $codePostal;
    }
}
