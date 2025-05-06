<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    <div class="flex flex-col md:flex-row bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <div class="w-full md:w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden mb-4 md:mb-0">

        <form method="GET" action="{{ route('dashboard') }}">
            <!-- Filtres -->
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par statut -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="statut" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="all">Tous</option>
                        <option value="À planifier">À planifier</option>
                        <option value="Affecter">Affecter</option>
                        <option value="Approvisionnement">Approvisionnement</option>
                        <option value="À facturer">À facturer</option>
                    </select>
                </div>

                <!-- Filtrer par date -->
                <div class="mb-4">
                    <label for="date-filter_min" class="block text-sm font-medium text-gray-700">Filtrer par date min</label>
                    <input type="date" name="date_min" id="date-filter_min" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date_min') }}">
                </div>

            <!-- Filtrer par date max-->
                 <div class="mb-4">
                    <label for="date-filter_max" class="block text-sm font-medium text-gray-700">Filtrer par date max</label>
                    <input type="date" name="date_max" id="date-filter_max" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date_max') }}">
                </div>

                <!-- Filtrer par nom -->
                <div class="mb-4">
                    <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                    <input type="text" name="nom" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom" value="{{ request('nom') }}">
                </div>

                <!-- Filtrer par lieu -->
                <div class="mb-4">
                    <label for="lieu-filter" class="block text-sm font-medium text-gray-700">Filtrer par lieu</label>
                    <input type="text" name="lieu" id="lieu-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Lieu" value="{{ request('lieu') }}">
                </div>

            <!-- Filtrer par garantie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Garantie :</label>
                    <input type="radio" name="garantie" value="oui" {{ request('garantie') == 'oui' ? 'checked' : '' }} > Oui
                    <input type="radio" name="garantie" value="non" {{ request('garantie') == 'non' ? 'checked' : '' }}> Non
                    <input type="radio" name="garantie" value="" {{ request('garantie') === null ? 'checked' : '' }}> Tous
                    <br>
                </div>

            <!-- Filtrer par contrat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contrat :</label>
                    <input type="radio" name="contrat" value="oui" {{ request('contrat') == 'oui' ? 'checked' : '' }}> Oui
                    <input type="radio" name="contrat" value="non" {{ request('contrat') == 'non' ? 'checked' : '' }}> Non
                    <input type="radio" name="contrat" value="" {{ request('contrat') === null ? 'checked' : '' }}> Tous
                </div>

            <!-- Bouton de réinitialisation -->
                <div>
                    <br>
                    <a href="{{ route('dashboard') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>

                <!-- Bouton pour appliquer les filtres -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
        </form>
        </div>

        <!-- Liste des dépannages -->
        <div class="w-full md:w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">ID</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Adresse</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Type de client</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Date intervention</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Statut</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Détails</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6"></th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-16"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $count = 1;
                    @endphp
                    @foreach ($depannages as $depannage)
                        @if($count % 2 == 0)
                            @php
                                $bgColor = 'bg-gray-100';
                            @endphp
                            @else
                            @php
                                $bgColor = 'bg-white';
                                @endphp
                        @endif
                        <tr class="hover:bg-gray-200 {{$bgColor}}">
                            <td class="p-3 text-sm text-gray-700 w-32 relative z-10">
                                @php
                                    $count++;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-800 font-medium">{{ $depannage->id }}</span>
                                    <div class="relative group">
                                        <span class="text-xs font-bold">({{$depannage->provenance}})</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->adresse }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                {{$depannage->types->contrat}}, {{$depannage->types->garantie}}

                                <!-- Bouton d'édition -->
                                <button
                                    onclick="openModal({{ $depannage->id }})"
                                    class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Modal -->
                                <div id="modal-{{ $depannage->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                        <h2 class="text-lg font-semibold">Modifier le type de contrat et de garantie</h2>

                                        <form id="form-{{ $depannage->id }}" action="{{ route('update.type', $depannage->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Liste déroulante pour Garantie -->
                                            <label for="garantie" class="block mt-4 text-sm text-gray-700">Garantie</label>
                                            <select id="garantie-{{ $depannage->id }}" name="garantie" class="w-full p-2 mt-2 border rounded">
                                                <option value="Avec garantie" {{ $depannage->types->garantie == 'Avec garantie' ? 'selected' : '' }}>Avec garantie</option>
                                                <option value="Sans garantie" {{ $depannage->types->garantie == 'Sans garantie' ? 'selected' : '' }}>Sans garantie</option>
                                            </select>

                                            <!-- Liste déroulante pour Contrat -->
                                            <label for="contrat" class="block mt-4 text-sm text-gray-700">Contrat</label>
                                            <select id="contrat-{{ $depannage->id }}" name="contrat" class="w-full p-2 mt-2 border rounded">
                                                <option value="Contrat de maintenance" {{ $depannage->types->contrat == 'Contrat de maintenance' ? 'selected' : '' }}>Contrat de maintenance</option>
                                                <option value="Sans contrat" {{ $depannage->types->contrat == 'Sans contrat' ? 'selected' : '' }}>Sans contrat</option>
                                            </select>

                                            <!-- Boutons Valider et Annuler -->
                                            <div class="mt-4 flex justify-end">
                                                <button type="button" onclick="closeModal({{ $depannage->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>

                            <td class="p-3 text-sm text-gray-700 relative">
                                @if($depannage->date_depannage)
                                    {{ \Carbon\Carbon::parse($depannage->date_depannage)->format('d/m/Y') }}
                                @else
                                    <span class="text-red-500">Non renseignée</span>
                                @endif
                                    @if($depannage->statut == 'Affecter' || $depannage->date_depannage != null)
                                        <button
                                            onclick="toggleModalDate(true, {{ $depannage->id }})"
                                            class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    @endif
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button id="status-{{ $depannage->id }}-btn"
                                        onclick="toggleDropdown('status-{{ $depannage->id }}', 'status-{{ $depannage->id }}-btn')"
                                        class="px-4 py-2 rounded-lg text-white {{
                                                $depannage->statut == 'À planifier' ? 'bg-red-500' :
                                                ($depannage->statut == 'Affecter' ? 'bg-yellow-500' :
                                                ($depannage->statut == 'Approvisionnement' ? 'bg-blue-500' :
                                                ($depannage->statut == 'À facturer' ? 'bg-green-500' : 'bg-gray-500')))
                                            }}">
                                    {{ $depannage->statut }}
                                </button>
                                <ul id="status-{{ $depannage->id }}" class="hidden absolute left-0 top-full bg-white p-2 mt-2 rounded shadow-md z-10 w-48">
                                    <li onclick="updateStatus('status-{{ $depannage->id }}', 'À planifier', 'bg-red-500', 'status-{{ $depannage->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">À planifier</li>
                                    <li onclick="updateStatus('status-{{ $depannage->id }}', 'Affecter', 'bg-yellow-500', 'status-{{ $depannage->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">Affecter</li>
                                    <li onclick="updateStatus('status-{{ $depannage->id }}', 'Approvisionnement', 'bg-blue-500', 'status-{{ $depannage->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">Approvisionnement</li>
                                    <li onclick="updateStatus('status-{{ $depannage->id }}', 'À facturer', 'bg-green-500', 'status-{{ $depannage->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">À facturer</li>
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700">
                                <a href="{{ route('depannage.show', $depannage->id) }}" class="text-blue-500 hover:underline">Voir plus</a>
                            </td>
                                <td class="text-left p-3 text-sm text-gray-700">
                                    @if($depannage->statut == 'À facturer')
                                        <button class="text-blue-500 hover:underline text-blue-600" onclick="toggleModalArchiveBis({{$depannage->id}})">Archiver</button>
                                    @endif
                                </td>

                            <!-- Colonne suppression -->
                            <td class="p-1 text-xs text-gray-700 w-10 text-center">
                                <button onclick="toggleModal({{ $depannage->id }})" class="text-red-600 hover:text-red-800">❌</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('adminform') }}"
           class="fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-3xl w-16 h-16 flex items-center justify-center rounded-full shadow-lg transition duration-300 ease-in-out z-50">
            +
        </a>
    </div>
    <!-- Modal de confirmation -->
    <div id="confirm-delete-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer ce dépannage ? Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModal()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delDepannage()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

    <div id="create-date-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">A quelle date voulez-vous associer ce dépannage ?</h2>
            <div class="mt-4 flex justify-end space-x-4">
                <label for="date-create" class="block text-sm font-medium text-gray-700">Choisir une date</label>
                <input type="date" name="date-create" id="date-create" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date-crate') }}">
                <button onclick="toggleModalDate(false, null)" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="updateDate()" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>

    <div id="confirmation-modal-bis" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-1/3 relative">
            <!-- Croix de fermeture -->
            <h2 class="text-xl font-bold mb-4">Archivage du dépannage</h2>
            <p class="mb-6">Souhaitez-vous que ce dépannage reste visible ou qu’il soit seulement dans l’historique ?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelArchiveBis()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</button>
                <button onclick="archiver()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Archiver</button>
            </div>
        </div>
    </div>
</x-app-layout>


<script>

    let currentDeppangeId = null;
    let pendingStatut = null;
    let lastOpenedDropdown = null;
    let depannageIdToDelete = null;

    function toggleModalArchiveBis(id) {
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.remove('hidden');
        window.currentDepannageId = id;
    }

    function cancelArchiveBis(){
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.add('hidden');
        window.currentDepannageId = null;
    }

    function archiver(){
        fetch(`/depannage/${window.currentDepannageId}/archiver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ id: window.currentDepannageId })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                setTimeout(() => {
                    saveNotificationBeforeReload("Dépannage archivé avec succès.", 'success');
                    location.reload();
                }, 100);

            })
            .catch(error => {
                saveNotificationBeforeReload("Erreur lors de l'archivage du dépannage", 'error');
                console.error('Erreur:', error);
            }
        );
    }

    function toggleChoice(buttonId) {
        const buttons = document.querySelectorAll(`#${buttonId}, #${buttonId}-2`); // Sélectionner les deux boutons correspondants

        // Bascule entre les deux états
        buttons.forEach(button => {
            button.classList.toggle('bg-blue-500');
            button.classList.toggle('bg-blue-600');
            button.classList.toggle('bg-gray-500');
            button.classList.toggle('bg-gray-600');
        });
    }

    function openModal(depannageId) {
        document.getElementById(`modal-${depannageId}`).classList.remove('hidden');
    }

    // Fonction pour fermer le modal
    function closeModal(depannageId) {
        document.getElementById(`modal-${depannageId}`).classList.add('hidden');
    }

    // Fermer le modal si on clique à l'extérieur
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('bg-opacity-50')) {
            const modalId = event.target.id.split('-')[1];
            closeModal(modalId);
        }
    });

    function toggleModalDate(show = true, id) {
        console.log("appel de toggleModalDate avec show =", show);
        const modal = document.getElementById('create-date-modal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
        currentDeppangeId = id;
    }

    // Fonction pour afficher ou masquer le menu déroulant
    function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        // Si le menu est visible, on le cache, sinon on l'affiche
        menu.classList.toggle('hidden');
    }

    function toggleDropdown(id, buttonId = null) {
        const dropdown = document.getElementById(id);

        if (lastOpenedDropdown && lastOpenedDropdown !== dropdown) {
            lastOpenedDropdown.classList.add('hidden');
        }

        dropdown.classList.toggle('hidden');
        lastOpenedDropdown = dropdown.classList.contains('hidden') ? null : dropdown;
    }

    function toggleModal(depannageID = null) {
        const modal = document.getElementById('confirm-delete-modal');
        if (depannageID) {
            depannageIdToDelete = depannageID;
        }
        modal.classList.toggle('hidden');
    }

    function updateStatus(dropdownId, statusText, statusColor, buttonId) {
        const button = document.getElementById(buttonId);
        const depannageId = buttonId.split('-')[1].trim();

        console.log(depannageId)
        if(statusText === 'Affecter'){
            currentDeppangeId = depannageId;
            pendingStatut = {
                dropdownId,
                statusText,
                statusColor,
                buttonId,
            };
            toggleModalDate(true, depannageId);
            return;
        }

        performStatusUpdate(dropdownId, statusText, statusColor, depannageId, button);
        toggleDropdown(dropdownId);
        setTimeout(() => {
            location.reload();
        }, 300);
    }

    async function performStatusUpdate(dropdownId, statusText, statusColor, depannageId, button) {
        button.textContent = statusText;
        button.classList.remove('bg-gray-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500');
        button.classList.add(statusColor);

        await fetch(`/depannage/${depannageId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ statut: statusText }),
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
                console.log('Statut mis à jour avec succès:', data);
            })
            .catch(error => {
                saveNotificationBeforeReload(error.message || 'Une erreur est survenue', 'error');
            });
    }

    async function updateDate() {
        const date = document.getElementById('date-create').value;

        try {
            const res = await fetch(`/depannage/${currentDeppangeId}/update-date`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ date_depannage: date })
            });

            const data = await res.json();
            console.log("date enregistrée avec succès", data);

            if (pendingStatut) {
                console.log("pendingStatus", pendingStatut);
                const { dropdownId, statusText, statusColor, buttonId } = pendingStatut;
                console.log("id = " + currentDeppangeId);
                const button = document.getElementById(buttonId);

                await performStatusUpdate(dropdownId, statusText, statusColor, currentDeppangeId, button);

                console.log("toujours pas d'erreur", pendingStatut);
                pendingStatut = null;
                toggleModalDate(false, null);
            }

            location.reload();

        } catch (err) {
            console.error("erreur enregistrement de la date", err);

            saveNotificationBeforeReload("Erreur lors de l'enregistrement de la date", 'error');
            location.reload();
        }
    }


    function delDepannage() {
        if (depannageIdToDelete !== null) {
            fetch(`/depannage/del/${depannageIdToDelete}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    saveNotificationBeforeReload("L'opération de suppression a été réalisée avec succès.", 'success');
                    location.reload();
                })
                .catch(error => {
                    console.err('Erreur:', error);
                    saveNotificationBeforeReload("Erreur lors de l'opération du suppression", 'error');
                });
        }
        toggleModal();
    }

    document.addEventListener('click', function(event) {
        const isClickInsideMenu = event.target.closest('.relative');
        const isClickInsideDropdown = event.target.closest('.absolute');
        const isClickInsideButton = event.target.closest('.cursor-pointer');

        // Si le clic n'est pas à l'intérieur d'un bouton de menu ou d'un menu, fermer les menus ouverts
        if (!isClickInsideMenu && !isClickInsideDropdown && !isClickInsideButton) {
            if (lastOpenedDropdown) {
                lastOpenedDropdown.classList.add('hidden');
                lastOpenedDropdown = null;
            }
        }
    });

</script>

<style>
    button:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }
</style>
