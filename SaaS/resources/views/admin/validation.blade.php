<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">

            <form method="GET" action="{{ route('admin.validation') }}">
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par type -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="type" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="depannage" {{ request('type') == 'depannage' || !request('type') ? 'selected' : '' }}>Dépannage</option>
                        <option value="entretiens" {{ request('type') == 'entretiens' ? 'selected' : '' }}>Entretien</option>
                    </select>
                </div>

                <!-- Nom -->
                <div class="mb-4">
                    <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                    <input type="text" id="name-filter" name="nom" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom" value="{{ request('nom') }}">
                </div>

                <div class="mb-4">
                    <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date</label>
                    <input type="date" name="date" id="date-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date') }}">
                </div>

                <!-- Jour courant -->
                <div class="mb-4 flex items-center">

                @if(request('type', 'depannage') === 'depannage')
                        <label for="jour_courant" class="block text-sm font-medium text-gray-700 mr-4">
                            Intervention du {{ \Carbon\Carbon::parse(today())->format('d/m/Y') }} uniquement
                        </label>
                        <label for="jour_courant" class="inline-flex relative items-center cursor-pointer">
                            <!-- Champ caché pour forcer la valeur "off" si décoché -->
                            <input type="hidden" name="jour_courant" value="off">
                            <input type="checkbox" id="jour_courant" name="jour_courant" value="on" class="sr-only peer"
                                   @if(request()->get('jour_courant', 'on') === 'on') checked @endif>
                            <span class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600"></span>
                            <span class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></span>
                        </label>
                    @elseif(request('type') === 'entretiens')
                        <label for="mois_courant" class="block text-sm font-medium text-gray-700 mr-4">
                            Interventions du mois de {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('MMMM YYYY') }}
                        </label>
                        <label for="mois_courant" class="inline-flex relative items-center cursor-pointer">
                            <!-- Champ caché pour forcer la valeur "off" si décoché -->
                            <input type="hidden" name="mois_courant" value="off">
                            <input type="checkbox" id="mois_courant" name="mois_courant" value="on" class="sr-only peer"
                                   @if(request()->get('mois_courant', 'on') === 'on') checked @endif>
                            <span class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600"></span>
                            <span class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></span>
                        </label>
                    @endif
                </div>


                <!-- Boutons -->
                <div>
                    <a href="{{ route('admin.validation') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>

        </div>

        <!-- Liste des interventions -->
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                @if($type == 'depannage')
                    @foreach($interventions as $intervention)
                        @php
                            $depannage = $intervention['depannage'];
                            $date = \Carbon\Carbon::parse($intervention['date'])->format('Y-m-d');
                            $validation = $depannage->validations->first(function ($v) use ($date) {
                                return \Carbon\Carbon::parse($v->date)->format('Y-m-d') === $date;
                            });

                            $isTraite = !is_null($validation);
                            $isValidatedToday = $validation && \Carbon\Carbon::parse($validation->created_at)->isToday();
                        @endphp

                        <div class="bg-gray-100 p-4 mb-4 rounded-lg shadow-sm flex items-center justify-between">
                            <!-- Infos à gauche -->
                            <div>
                                <h3 class="text-lg font-bold">{{ $depannage->nom }}</h3>
                                <p class="text-gray-600">Adresse : {{ $depannage->adresse }}</p>
                                <p class="text-gray-600">Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                            </div>

                            <!-- À droite : boutons ou "Traité" -->
                            <div class="flex space-x-2">
                                @if($isTraite)
                                    <span class="text-sm font-semibold {{ $isValidatedToday ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-700' }} px-4 py-2 rounded">
                                        Traité le {{\Carbon\Carbon::parse($validation->created_at)->format('d/m/Y')}} ({{ $validation->validation ?? 'Inconnu' }})

                                @else
                                    <button onclick="openValideModal({{ $depannage->id }}, 'depannage')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Valide</button>
                                    <button onclick="openNonValideModal({{ $depannage->id }}, 'depannage')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Non valide</button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                @elseif($type == 'entretiens')
                    @foreach($interventions as $intervention)
                        @php
                            $entretien = $intervention['entretien'];
                            $date = \Carbon\Carbon::parse($intervention['date'])->format('Y-m-d');
                            // Trouver la validation correspondant à la date (si elle existe)
                            $validation = $entretien->validations->first(function ($v) use ($date) {
                                return \Carbon\Carbon::parse($v->date)->format('Y-m-d') === $date;
                            });

                            $isTraite = !is_null($validation);
                            $isValidatedToday = $validation && \Carbon\Carbon::parse($validation->created_at)->isToday();
                        @endphp

                        <div class="bg-gray-100 p-4 mb-4 rounded-lg shadow-sm flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold">{{ $entretien->nom }}</h3>
                                <p class="text-gray-600">Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                                <p class="text-gray-600">Adresse : {{ $entretien->adresse }}</p>
                            </div>

                            <div class="flex space-x-2">
                                @if($isTraite)
                                    <span class="text-sm font-semibold {{ $isValidatedToday ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-700' }} px-4 py-2 rounded">
                        Traité le {{ \Carbon\Carbon::parse($validation->created_at)->format('d/m/Y') }} ({{ $validation->validation ?? 'Inconnu' }})
                    </span>
                                @else
                                    <button onclick="toggleValideEntretien({{ $entretien->id }}, 'entretien')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Valide</button>
                                    <button onclick="toggleModalDateEntretien(true, {{ $entretien->id }}, 'entretien')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Non valide</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

        </div>
    </div>

    <!-- Modal VALIDE -->
    <div id="valideModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Validation du dépannage</h2>
            <p class="mb-4 text-gray-700">Vous êtes sur le point de valider l'intervention et d'enregistrer celle-ci. Que souhaitez-vous faire ?</p>

            <form>
                <label class="block mb-2">
                    <input type="radio" name="valide_option" value="nouvelle_date" class="mr-2"
                           onclick="toggleModalDate(true, 'valide')"> Affecter une nouvelle date
                </label>
                <label class="block mb-2">
                    <input type="radio" name="valide_option" value="approvisionnement" class="mr-2"> Créer un approvisionnement
                </label>
                <label class="block mb-4">
                    <input type="radio" name="valide_option" value="facturer" class="mr-2"> Passer ce dépannage dans l’état « à facturer »
                </label>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeValideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                    <button type="button" onclick="submitValide()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmation Entretien-->
    <div id="confirm-valide-modal-entretien" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Confirmer la validation de l'entretien</h2>
            <p>Êtes-vous sûr de vouloir valider cet entretien ? Ceci programmera un nouvel entretien pour dans 6 mois et créera un historique de celui-ci. Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleValideEntretien()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="submitValideEntretien()" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>

    <div id="create-date-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-[9999]">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative z-[10000]">
            <h2 class="text-xl font-semibold mb-4">À quelle date voulez-vous associer ce dépannage ?</h2>
            <label for="date-create" class="block text-sm font-medium text-gray-700 mb-2">Choisir une date</label>
            <input type="date" name="date-create" id="date-create" class="block w-full mb-4 p-2 border border-gray-300 rounded-lg" value="{{ request('date-crate') }}">

            <div class="flex justify-end space-x-4">
                <button onclick="toggleModalDate(false, currentContext, 'depannage')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="updateDate()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>

    <div id="create-date-modal-entretien" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-[9999]">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative z-[10000]">
            <h2 class="text-xl font-semibold mb-4">À quelle date souhaitez-vous reprogrammer cet entretien ?</h2>
            <label for="date-create-entretien" class="block text-sm font-medium text-gray-700 mb-2">Choisir une date</label>
            <input type="date" name="date-create-entretien" id="date-create-entretien" class="block w-full mb-4 p-2 border border-gray-300 rounded-lg" value="{{ request('date-crate-entretien') }}">

            <div class="flex justify-end space-x-4">
                <button onclick="toggleModalDateEntretien(false, null)" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="updateDate()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>

    <!-- Modal NON VALIDE -->
    <div id="nonValideModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Replanification du dépannage</h2>
            <p class="mb-4 text-gray-700">Que souhaitez-vous faire ?</p>

            <form>
                <label class="block mb-2">
                    <input type="radio" name="non_valide_option" value="nouvelle_date" class="mr-2"
                           onclick="toggleModalDate(true, 'nonValide')"> Affecter une nouvelle date
                </label>
                <label class="block mb-4">
                    <input type="radio" name="non_valide_option" value="ultérieurement" class="mr-2"> Affecter ultérieurement une date
                </label>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeNonValideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                    <button type="button" onclick="submitNonValide()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Commentaire -->
    <div id="commentModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg mx-auto rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Voulez-vous laisser un commentaire ?</h2>
            <textarea id="commentaire" rows="4" placeholder="Votre commentaire (facultatif)"
                      class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            <div class="mt-4 flex justify-end gap-2">
                <button onclick="closeModalCommentaire()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Annuler</button>
                <button onclick="sendWithCommentaire()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Envoyer</button>
            </div>
        </div>
    </div>

    <script>
        function getReplanifierUrl(id, etat) {
            if(etat === 'depannage'){
                console.log('return /admin/validationDepannage/'+id+'/ avec etat = ' + etat)
                return `/admin/validationDepannage/${id}`;
            } else if(etat === 'entretien') {
                console.log('return /admin/valideEntretien/'+id+'/ avec etat = ' + etat)
                return `/admin/valideEntretien/${id}`;
            }
            console.log('probleme avec etat = ' + etat);
            return '/admin/validationDepannage/${id}'
        }

        let currentContext = null;
        let currentInterventionId = null;
        let selectedDate = null;
        let pendingData = null;
        let currentEtat = null;

        function toggleValideEntretien(id = null, etat = null){
            document.getElementById('confirm-valide-modal-entretien').classList.toggle('hidden');
            currentInterventionId = id;
            currentEtat = etat;
            currentContext = 'valide';
            console.log('etat = ' + etat);
        }

        function openValideModal(id, etat = null) {
            document.getElementById('valideModal').classList.remove('hidden');
            currentContext = 'valide';
            currentInterventionId = id;
            currentEtat = etat;
            console.log('etat = ' + etat);
        }

        function openNonValideModal(id, etat = null) {
            document.getElementById('nonValideModal').classList.remove('hidden');
            currentContext = 'nonValide';
            currentInterventionId = id;
            currentEtat = etat;
            console.log('etat = ' + etat);
        }

        function toggleModalDateEntretien(show, id = null, etat = null){
            currentInterventionId = id;
            const dateModal = document.getElementById('create-date-modal-entretien');
            if(!show){
                dateModal.classList.add('hidden');
            } else {
                dateModal.classList.remove('hidden');
            }
            currentEtat = etat;
            currentContext = 'nonValide';
            console.log('etat = ' + etat);
            console.log('currentEtat before = ' + currentEtat)
        }

        function closeValideModal(context = null, id = null, etat = null) {
            document.getElementById('valideModal').classList.add('hidden');
            currentInterventionId = id;
            currentContext = context;
            currentEtat = etat;
            console.log('etat = ' + etat);
        }

        function closeNonValideModal(context = null, id = null, etat = null) {
            document.getElementById('nonValideModal').classList.add('hidden');
            currentContext = context;
            currentInterventionId = id;
            currentEtat = etat;
            console.log('etat = ' + etat);
        }

        function toggleModalDate(show, sourceModal, reopenParent = true, etat = null) {
            id = currentInterventionId;
            currentEtat = etat;
            console.log('etat = ' + etat);
            const dateModal = document.getElementById('create-date-modal');

            if (show) {
                // Masquer le modal source
                if (sourceModal === 'valide') {
                    closeValideModal('valide', id, 'depannage');
                } else if (sourceModal === 'nonValide') {
                    closeNonValideModal('nonValide', id, 'depannage');
                }

                dateModal.classList.remove('hidden');
            } else {
                dateModal.classList.add('hidden');
                resetRadioButtons();

                // Réafficher le modal parent
                if (reopenParent) {
                    // Réafficher le modal parent uniquement si demandé
                    if (sourceModal === 'valide') {
                        document.getElementById('valideModal').classList.remove('hidden');
                    } else if (sourceModal === 'nonValide') {
                        document.getElementById('nonValideModal').classList.remove('hidden');
                    }
                }
            }
            console.log('toggleDate = ' + currentInterventionId)
        }

        function resetRadioButtons() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.checked = false;
            });
        }

        function submitNonValide() {
            const selectedOption = document.querySelector('input[name="non_valide_option"]:checked');
            if (!selectedOption) {
                alert("Veuillez sélectionner une option.");
                return;
            }

            const type = document.getElementById("status-filter").value;
            const data = {
                intervention_id: currentInterventionId,
                context: currentContext,
                option: selectedOption.value,
                type: type,
                etat: currentEtat,
            };

            if (selectedOption.value === "nouvelle_date") {
                if (!selectedDate) {
                    alert("Veuillez choisir une date avant de valider.");
                    return;
                }
                data.date = selectedDate;
            }

            // Stocker temporairement les données dans pendingData
            pendingData = data;

            // Ouvrir le modal de commentaire
            document.getElementById("commentaire").value = "";
            document.getElementById("commentModal").classList.remove("hidden");
        }

        function submitValide() {
            console.log('on passe dans sendValide');
            const selectedOption = document.querySelector('input[name="valide_option"]:checked');
            if (!selectedOption) {
                alert("Veuillez sélectionner une option.");
                return;
            }

            const type = document.getElementById("status-filter").value;
            const data = {
                intervention_id: currentInterventionId,
                context: currentContext,
                option: selectedOption.value,
                type: type,
                etat: currentEtat,
            };

            if (selectedOption.value === "nouvelle_date") {
                if (!selectedDate) {
                    alert("Veuillez choisir une date avant de valider.");
                    return;
                }
                data.date = selectedDate;
            }

            // Stocker temporairement les données dans pendingData
            pendingData = data;

            // Ouvrir le modal de commentaire
            document.getElementById("commentaire").value = "";
            document.getElementById("commentModal").classList.remove("hidden");
        }

        function updateDate(){
            console.log('currentEtat = ' + currentEtat);
            if(currentEtat === 'depannage'){
                selectedDate = document.getElementById('date-create').value;
            } else if(currentEtat === 'entretien'){
                selectedDate = document.getElementById('date-create-entretien').value;
                console.log('SelectedDate = ' + selectedDate)
            } else {
                console.error('problème avec selecteDate');
            }


            console.log(selectedDate);
            if (!selectedDate) {
                saveNotificationBeforeReload('Veuillez choisir une date avant de valider', 'error');
                setTimeout(() => {location.reload();}, 100000)
                return;
            }

            if(currentEtat === 'depannage'){
                toggleModalDate(false, currentContext, false, currentEtat);
            } else if(currentEtat === 'entretien'){
                toggleModalDateEntretien(false, currentInterventionId, currentEtat);
            }

            // On construit les données manuellement puisque l'utilisateur vient de choisir la date
            const type = document.getElementById("status-filter").value;

            pendingData = {
                intervention_id: currentInterventionId,
                context: currentContext,
                option: "nouvelle_date",
                type: type,
                date: selectedDate,
            };

            // Ouvrir le modal de commentaire directement
            document.getElementById("commentaire").value = "";
            document.getElementById("commentModal").classList.remove("hidden");

            console.log('updateDate = ' + currentInterventionId);
        }

        function submitValideEntretien(){
            console.log('on passe dans sendValideEntretien');

            const type = document.getElementById("status-filter").value;
            const data = {
                intervention_id: currentInterventionId,
                context: currentContext,
                option: "valide",
                type: type,
                etat: currentEtat,
            };

            pendingData = data;

            document.getElementById("commentaire").value = "";
            document.getElementById("commentModal").classList.remove("hidden");
        }

        function closeModalCommentaire() {
            document.getElementById("commentModal").classList.add("hidden");
            currentContext = null;
            currentInterventionId = null;
            selectedOptionValue = null;
            selectedDate = null;
            typeIntervention = null;
            pendingData = null;
            currentEtat = null;
        }

        async function sendWithCommentaire() {
            console.log('on passe dans sendWithCommentaire');
            const commentaire = document.getElementById("commentaire").value.trim();

            // Ajouter le commentaire aux données
            pendingData.commentaire = commentaire || null;

            try {
                console.log("Données envoyées au serveur:", pendingData);

                const response = await fetch(getReplanifierUrl(currentInterventionId, currentEtat), {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(pendingData),
                });

                if (!response.ok) throw new Error("Erreur serveur");

                closeModalCommentaire();
                saveNotificationBeforeReload("Enregistrement effectué avec succès", 'success');
                location.reload();

            } catch (error) {
                console.error("Erreur complète :", error.response?.data || error.message);
                closeModalCommentaire();
                console.error("Erreur:", error);
                saveNotificationBeforeReload(error.message || 'Une erreur est survenue', 'error');
                setTimeout(() => location.reload(), 2000);
            }
        }

    </script>

</x-app-layout>
