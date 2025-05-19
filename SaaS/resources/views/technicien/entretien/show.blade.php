@extends('layouts.technicien')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
            <!-- Fiche dépannage -->
            <div class="bg-white shadow-md rounded-lg p-6 relative w-full">

                <h2 class="text-xl sm:text-2xl font-bold mb-6 text-center">
                    Détails de l'entretien de {{ $entretien->nom }} : #ID{{ $entretien->id }}
                </h2>

                <!-- Infos client -->
                <div class="border border-black p-4 mb-4 rounded-md">
                    <h3 class="font-semibold mb-4">Informations sur le client</h3>
                    <p class="text-sm"><strong>Nom:</strong> {{ $entretien->nom }}</p>
                    <p class="text-sm"><strong>Adresse:</strong> {{ $entretien->adresse }}</p>
                    <p class="text-sm"><strong>Code postal:</strong> {{ $entretien->code_postal }}</p>
                    <p class="text-sm"><strong>Contact:</strong> {{ $entretien->contact_email }}</p>
                    <p class="text-sm"><strong>Téléphone:</strong> {{ $entretien->telephone }}</p>
                </div>

                <!-- Description -->
                <div class="border border-black p-4 mb-4 rounded-md">
                    <h3 class="font-semibold mb-4">Description du problème</h3>
                    <p class="text-sm"><strong>Date d'intervention :</strong>
                        @if($entretien->derniere_date == null)
                            Pas encore planifiée
                        @else
                            {{ \Carbon\Carbon::parse($entretien->derniere_date)->format('d/m/Y') }}
                        @endif
                    </p>
                    <p class="text-sm font-semibold mt-2">Historique :</p>
                    <ul class="list-disc list-inside">
                        @forelse($entretien->historiques as $histo)
                            <li class="text-sm"> - {{ \Carbon\Carbon::parse($histo->date)->format('d/m/Y') }}</li>
                        @empty
                            <li class="text-sm">Aucun historique disponible.</li>
                        @endforelse
                    </ul>
                    <p class="text-sm"><strong>Type de matériel:</strong> {{ $depannage->type_materiel }}</p>
                    <p class="text-sm"><strong>Panne ou vigilance:</strong> {{ $depannage->description_probleme }}</p>
                    <p class="text-sm"><strong>Informations supplémentaires:</strong> {{ $depannage->infos_suppementaires }}</p>
                </div>

                <!-- Photos -->
                <h3 class="text-lg font-semibold mb-4">Photo(s)</h3>
                @if($entretien->photos->count() > 0)
                    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($entretien->photos as $photo)
                            <li class="mb-4">
                                <img src="{{ asset('images/' . $photo->chemin_photo) }}"
                                     alt="Photo du dépannage"
                                     class="w-full max-h-64 object-cover rounded" />
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm">Aucune photo disponible.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
