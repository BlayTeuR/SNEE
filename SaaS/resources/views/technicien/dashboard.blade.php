@extends('layouts.technicien')

@section('content')
    @php
        $ficheId = null;
        $count = 0;
    @endphp
    <div class="space-y-4">
        <h1 class="text-2xl font-bold mb-6">Fiches reçues</h1>
        @if($fiches->isEmpty())
            <p class="text-lg font-semibold text-gray-800">Aucune fiche</p>
        @endif
        @foreach ($fiches as $fiche)
            @php
                $ficheId = $fiche->id;
            @endphp
            <details class="group bg-white shadow-md rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg">
                <summary class="cursor-pointer px-6 py-4 transition-all duration-500 ease-in-out group-hover:bg-blue-50 group-active:bg-blue-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <!-- Flèche triangle placée à gauche du nom -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 transition-transform duration-300 ease-in-out group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <p class="text-sm font-semibold text-gray-800">{{ $fiche->ficheable->nom }}</p>
                            <p class="text-xs text-gray-500">
                                Reçu le {{ \Carbon\Carbon::parse($fiche->created_at)->translatedFormat('l j F Y \à H\hi') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Croix de suppression -->
                            <button onclick="event.stopPropagation(); toggleModal({{$ficheId}})" class="text-red-600 hover:text-red-800" aria-label="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </summary>
                <div class="px-6 pb-4 pt-2 text-sm text-gray-700 bg-gray-50">
                    <p><strong>Adresse:</strong> {{ $fiche->ficheable->adresse }}</p>
                    <p><strong>Téléphone:</strong> {{ $fiche->ficheable->telephone }}</p>
                    <p><strong>Email:</strong> {{ $fiche->ficheable->contact_email }}</p>
                    <p><strong>Matériel:</strong> {{ $fiche->ficheable->type_materiel }}</p>
                    <p><strong>Problème:</strong> {{ $fiche->ficheable->description_probleme }}</p>
                    @if($fiche->ficheable->date_depannage == null)
                        <p><strong>Date prévue:</strong> Pas encore planifiée</p>
                    @else
                        <p><strong>Date prévue:</strong> {{ \Carbon\Carbon::parse($fiche->ficheable->date_depannage)->format('d/m/Y') }}</p>
                    @endif

                    <!-- Lien pour voir la fiche complète -->
                    <a href="{{route('technicien.depannage.show', $fiche->ficheable->id) }}" class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                        Voir la fiche complète
                    </a>
                </div>
            </details>
        @endforeach
    </div>

    <div id="confirm-delete-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 px-4">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer cette fiche ? Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModal()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delFicheConfirm()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

    <style>
        details summary {
            list-style: none;
        }

        details summary::marker {
            display: none;
        }
    </style>

    <script>

        let ficheId = null;

        function toggleModal(id = null) {
            const modal = document.getElementById('confirm-delete-modal');
            ficheId = id;

            modal.classList.toggle('hidden');
        }

        function delFicheConfirm() {
            console.log(ficheId);
            fetch(`/technicien/fiche/${ficheId}/del`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({})
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Erreur serveur');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data.message);
                    saveNotificationBeforeReload("L'opération de suppression a été réalisée avec succès.", 'success');
                    location.reload();
                })
                .catch(error => {
                    console.err('Erreur:', error);
                    saveNotificationBeforeReload(error.message || 'Une erreur est survenue', 'error');
                });

            toggleModal();
        }

    </script>
@endsection
