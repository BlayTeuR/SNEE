<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
            <!-- Fiche dépannage -->
            <div class="container-a4 bg-white overflow-hidden shadow-sm p-5 relative">
                <br>
                <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="absolute top-5 right-5 max-h-20 max-w-20">
                <h2 class="text-2xl font-bold mb-4 text-center">Détails du dépannage de {{$depannage->nom}} : #ID{{$depannage->id}}</h2>
                <div class="flex items-center mb-2 space-x-2">
                    <span class="text-sm font-semibold">Technicien(s) assigné(s):</span>
                    <button onclick="toggleModalAff()" class="text-sm text-blue-500 hover:text-blue-600 hover:underline">Edit</button>
                </div>

                @if($depannage->affectations->isNotEmpty())
                    <ul class="space-y-1">
                        @foreach($depannage->affectations as $affectation)
                            <li class="text-sm flex items-center">
                                <span>{{ $affectation->technicien->name }}</span>
                                <button onclick="toggleDeleteAff({{ $affectation->technicien->id }})" class="text-red-500 hover:text-red-600 hover:underline ml-2">Supprimer</button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-600">Aucun technicien assigné</p>
                @endif
                <br>
                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Information sur le client</h3>
                    <p class="text-xs"><strong>Nom:</strong> {{ $depannage->nom }}</p>
                    <p class="text-xs"><strong>Code postal:</strong> {{ $depannage->code_postal }}</p>
                    <p class="text-xs"><strong>Adresse:</strong> {{ $depannage->adresse }}</p>
                    <p class="text-xs"><strong>Contact:</strong> {{ $depannage->contact_email }}</p>
                    <p class="text-xs"><strong>Téléphone</strong> {{ $depannage->telephone  }}</p>
                    <p class="text-xs"><strong>Type de client:</strong> {{$depannage->types->contrat}}, {{$depannage->types->garantie}}</p>
                    <p class="text-xs"><strong>Statut:</strong> {{ $depannage->statut }}</p>
                </div>
                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Description du problème</h3>
                    <p class="text-xs"><strong>Date d'intervention</strong>

                    @if($depannage->date_depannage == null)
                        : pas encore planifiée
                        @else
                            {{ \Carbon\Carbon::parse($depannage->date_depannage)->format('d/m/Y') }}
                        @endif
                    </p>
                    <p class="text-xs"><strong>Nécessite un plan de prévention :</strong>
                        @if($depannage->prevention == 1)
                            Oui
                        @else
                            Non
                        @endif
                    </p>
                    <p class="text-xs"><strong>Historique:</strong></p>
                    <ul>
                        @if($depannage->historiques->isNotEmpty())
                            @foreach($depannage->historiques as $histo)
                                <li> - {{ \Carbon\Carbon::parse($histo->date)->format('d/m/Y') }}</li>
                            @endforeach
                        @else
                            <li class="text-xs">Aucun historique disponible.</li>
                        @endif
                    </ul>
                    <p class="text-xs"><strong>Type de matériel:</strong> {{ $depannage->type_materiel}}</p>
                    <p class="text-xs"><strong>Panne rencontrée:</strong> {{ $depannage->description_probleme}}</p>
                    <p class="text-xs"><strong>Message d'erreur sur la carte électronique:</strong> {{ $depannage->message_erreur}}</p>
                    <p class="text-xs"><strong>Informations supplémentaires:</strong> {{ $depannage->infos_suppementaires}}</p>
                </div>

                <h3 class="text-xl font-semibold mb-4">Photo(s)</h3>
                @if($depannage->photos->count() > 0)
                    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($depannage->photos as $photo)
                            <li class="mb-4">
                                <img src="{{ asset('images/' . $photo->chemin_photo) }}"
                                     alt="Photo du dépannage"
                                     class="max-w-full max-h-64 object-cover" />
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs">Aucune photo disponible.</p>
                @endif
            </div>

            <!-- Liste des techniciens -->
            <div class="bg-white shadow-sm p-5 rounded w-full lg:w-1/3">
                <h3 class="text-xl font-semibold mb-4 text-center">Envoyer la fiche à un technicien</h3>

                <label for="technicien_id" class="block mb-2 text-sm font-medium text-gray-700">Choisir un ou plusieurs techniciens</label>
                <!-- Liste des techniciens avec scroll -->
                <form id="assignForm" method="POST" action="{{ route('admin.show.store', ['depannage' => $depannage->id]) }}">
                    @csrf

                    <ul class="space-y-3 overflow-y-auto pr-2" style="max-height: 160px;" id="tech-list">
                        @foreach($users as $user)
                            <li class="flex items-center">
                                <!-- Case à cocher pour chaque technicien -->
                                <input type="checkbox" name="techniciens[]" value="{{ $user->id }}" id="tech{{ $user->id }}" class="mr-2">
                                <label for="tech{{ $user->id }}" class="text-sm">{{ $user->name }}</label>
                            </li>
                        @endforeach
                    </ul>

                    <button type="submit" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                        Envoyer
                    </button>
                </form>

                <!-- Deuxième formulaire : Assigner un technicien à ce dépannage -->
                <div class="mt-10 border-t border-gray-300 pt-6">
                    <button onclick="toggleModalAff()" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                        Affecter un ou plusieurs technicien
                    </button>
                </div>

            </div>

        </div>
    </div>

    <div id="replaceModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-md max-w-md text-center">
            <p id="modalText" class="mb-4"></p>
            <button id="confirmReplaceBtn" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Remplacer</button>
            <button id="addSecondTechBtn" class="bg-green-500 text-white px-4 py-2 rounded mr-2">Ajouter un deuxième technicien</button>
            <button id="cancelReplaceBtn" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
        </div>
    </div>

    <div id="create-aff-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                Associer des techniciens à ce dépannage ?
            </h2>
            <p class="text-sm text-gray-600 mb-6">
                Cette action est facultative et peut être réalisée ultérieurement.
            </p>

            <div>
                <label for="tech-list" class="block text-sm font-medium text-gray-700 mb-2">
                    Choisir un ou plusieurs techniciens :
                </label>
                <ul id="tech-list" class="space-y-2 max-h-48 overflow-y-auto pr-2 border rounded-md p-3 bg-gray-50">
                    @foreach($users as $technicien)
                        <li class="flex items-center">
                            <input type="checkbox" name="techniciens[]" value="{{ $technicien->id }}" id="tech{{ $technicien->id }}" class="mr-2 text-blue-600">
                            <label for="tech{{ $technicien->id }}" class="text-sm text-gray-700">
                                {{ $technicien->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-4">
                <button onclick="toggleModalAff()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Annuler
                </button>
                <button onclick="updateTechnicien()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Valider
                </button>
            </div>
        </div>
    </div>

    <div id="confirm-delete-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir désaffecter ce technicien du dépannage ?</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleDeleteAff()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delAffectation()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

    <script>

        let currentDepannageId = {{ $depannage->id }};
        let currentTechnicienId = null;

        function toggleModalAff(){
            document.querySelector('#create-aff-modal').classList.toggle('hidden');
        }

        function toggleDeleteAff(id = null){
            document.querySelector('#confirm-delete-modal').classList.toggle('hidden');
            currentTechnicienId = id;

        }

        async function delAffectation() {
            try {
                const res = await fetch(`/admin/depannage/${currentDepannageId}/affectation/${currentTechnicienId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await res.json();
                console.log("technicien supprimé avec succès", data);
                saveNotificationBeforeReload("Technicien désassocié avec succès", 'success');
                location.reload();

            } catch (err) {
                console.error("erreur suppression du technicien", err);
                saveNotificationBeforeReload("Echec lors de la désassociation du technicien ", 'success');
                location.reload();
            }
        }

        async function updateTechnicien() {

            const selectedTechniciens = Array.from(document.querySelectorAll('input[name="techniciens[]"]:checked')).map(checkbox => checkbox.value);

            try {
                const res = await fetch(`/admin/depannage/${currentDepannageId}/affectation`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ techniciens: selectedTechniciens })
                });

                const data = await res.json();
                console.log("technicien enregistré avec succès", data);
                saveNotificationBeforeReload("Technicien(s) associé(s) avec succès", 'success');
                location.reload();

            } catch (err) {
                console.error("erreur enregistrement du technicien", err);
                saveNotificationBeforeReload("Erreur lors de l'enregistrement du technicien", 'error');
                location.reload();
            }
        }



        document.getElementById('assignForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Empêche la soumission par défaut

            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    saveNotificationBeforeReload(data.message || "Fiche envoyée avec succès aux techniciens.", 'success');
                    location.reload();
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    saveNotificationBeforeReload("Erreur lors de l'envoi de la fiche.", 'error');
                    location.reload();
                });

        });

        document.getElementById('affectform').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const technicienId = form.technicien_id.value;
            if (!technicienId) {
                saveNotificationBeforeReload("Veuillez sélectionner un technicien", 'error');
                location.reload();
                return;
            }

            const formData = new FormData(form);
            formData.set('confirm_replace', 0);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.needs_confirmation) {
                        // Afficher modal avec message personnalisé
                        const modal = document.getElementById('replaceModal');
                        const modalText = document.getElementById('modalText');
                        modalText.textContent = `Le technicien ${data.technicien_actuel} est déjà associé à ce dépannage. Que souhaitez-vous faire ?`;
                        modal.classList.remove('hidden');

                        // Boutons du modal
                        const confirmBtn = document.getElementById('confirmReplaceBtn');
                        const addSecondBtn = document.getElementById('addSecondTechBtn');
                        const cancelBtn = document.getElementById('cancelReplaceBtn');

                        // Handler pour remplacer
                        confirmBtn.onclick = () => {
                            formData.set('confirm_replace', 1);
                            modal.classList.add('hidden');
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: formData
                            }).then(r => r.json())
                                .then(resp => {
                                    alert(resp.message);
                                    location.reload();
                                });
                        };

                        // Handler pour ajouter un second technicien
                        addSecondBtn.onclick = () => {
                            modal.classList.add('hidden');
                            fetch(`/admin/depannage/${currentDepannageId}/affectation`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: formData
                            }).then(r => r.json())
                                .then(resp => {
                                    alert(resp.message);
                                    location.reload();
                                });
                        };

                        // Handler pour annuler
                        cancelBtn.onclick = () => {
                            modal.classList.add('hidden');
                        };

                    } else {
                        saveNotificationBeforeReload("Erreur lors de l'envoi de l'affectation du technicien", 'error');
                        location.reload();
                    }
                })
                .catch(err => {
                    saveNotificationBeforeReload("Erreur lors de l'envoi de l'affectation du technicien", 'error');
                    location.reload();
                    console.error(err);
                });
        });

    </script>

    <style>
        .container-a4 {
            width: 210mm; /* Largeur du format A4 */
            height: 297mm; /* Hauteur du format A4 */
            margin: 0 auto;
            padding: 20mm; /* Espacement intérieur */
            background: #fff; /* Fond blanc */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombre pour simuler l'impression */
            overflow: auto; /* Si le contenu dépasse, afficher les barres de défilement */
            display: block;
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</x-app-layout>
