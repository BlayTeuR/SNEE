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
                <p class="text-sm"><strong>Technicien(s) assigné(s):</strong></p>
                    @if($depannage->affectations->isNotEmpty())
                        <ul>
                        @foreach($depannage->affectations as $affectation)
                            <li class="text-sm"> - {{ $affectation->technicien->name }}</li>
                        @endforeach
                        </ul>
                    @else
                        <p class="text-sm">Aucun technicien assigné</p>
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

                    <ul class="space-y-3 overflow-y-auto pr-2" style="max-height: 160px;" id="tech-list">                        @foreach($users as $user)
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
                    <h3 class="text-xl font-semibold mb-4 text-center">Assigner un technicien au dépannage</h3>

                    <form id="affectform" method="POST" action="{{ route('admin.show.affectation', ['depannage' => $depannage->id]) }}">

                        <input type="hidden" name="confirm_replace" id="confirm_replace" value="0">

                        <!-- CSRF uniquement si tu prépares une action plus tard -->
                        @csrf
                        <div class="mb-4">
                            <label for="technicien_id" class="block mb-2 text-sm font-medium text-gray-700">Choisir un technicien</label>
                            <select name="technicien_id" id="technicien_id" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Sélectionner un technicien --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                            Assigner au dépannage
                        </button>
                    </form>
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

    <script>

        let currentDepannageId = {{ $depannage->id }};

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
