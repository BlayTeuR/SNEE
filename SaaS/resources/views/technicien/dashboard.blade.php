@extends('layouts.technicien')

@section('content')
    <div class="space-y-4">
        <h1 class="text-2xl font-bold mb-6">Fiches reçues</h1>

        @php
            $fiches = [
                [
                    'id' => 1,
                    'nom' => 'Dupont Jean',
                    'adresse' => '123 Rue de la Paix, Paris',
                    'telephone' => '01 23 45 67 89',
                    'email' => 'jean.dupont@email.fr',
                    'type_materiel' => 'Chaudière gaz',
                    'probleme' => 'Ne chauffe plus',
                    'date' => '2025-05-05',
                ],
                [
                    'id' => 2,
                    'nom' => 'Martin Claire',
                    'adresse' => '456 Avenue Victor Hugo, Lyon',
                    'telephone' => '04 56 78 90 12',
                    'email' => 'claire.martin@email.fr',
                    'type_materiel' => 'Pompe à chaleur',
                    'probleme' => 'Fuite de liquide',
                    'date' => '2025-05-06',
                ]
            ];
        @endphp

        @foreach ($fiches as $fiche)
            <details class="group bg-white shadow-md rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg">
                <summary class="cursor-pointer px-6 py-4 transition-all duration-500 ease-in-out group-hover:bg-blue-50 group-active:bg-blue-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <!-- Flèche triangle placée à gauche du nom -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 transition-transform duration-300 ease-in-out group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-800">{{ $fiche['nom'] }}</h2>
                            <p class="text-sm text-gray-500">{{ $fiche['adresse'] }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Croix de suppression -->
                            <button class="text-red-600 hover:text-red-800" aria-label="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </summary>
                <div class="px-6 pb-4 pt-2 text-sm text-gray-700 bg-gray-50">
                    <p><strong>Téléphone:</strong> {{ $fiche['telephone'] }}</p>
                    <p><strong>Email:</strong> {{ $fiche['email'] }}</p>
                    <p><strong>Matériel:</strong> {{ $fiche['type_materiel'] }}</p>
                    <p><strong>Problème:</strong> {{ $fiche['probleme'] }}</p>
                    <p><strong>Date prévue:</strong> {{ $fiche['date'] }}</p>

                    <!-- Lien pour voir la fiche complète -->
                    <a href="#" class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                        Voir la fiche complète
                    </a>
                </div>
            </details>
        @endforeach
    </div>

    <style>
        details summary {
            list-style: none;
        }

        details summary::marker {
            display: none;
        }
    </style>
@endsection
