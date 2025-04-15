<x-app-layout>
    <div class="flex flex-col md:flex-row bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <div class="w-full md:w-1/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden mb-4 md:mb-0">

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
                    <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date</label>
                    <input type="date" name="date" id="date-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date') }}">
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
        <div class="w-full md:w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-16">ID</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Nom</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Adresse</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Type de client</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Historique</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Plus d'information</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($depannages as $depannage)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 text-sm text-gray-700 w-16 truncate">{{ $depannage->id }}</td>
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
                                <button onclick="toggleDropdown('historique-{{ $depannage->id }}')" class="bg-gray-300 bg-opacity-50 px-3 py-1 rounded-lg hover:bg-gray-400">
                                    Afficher Historique
                                </button>
                                <ul id="historique-{{ $depannage->id }}" class="hidden absolute left-3 top-full bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                    @if($depannage->historiques->isNotEmpty())
                                        @foreach ($depannage->historiques as $histo)
                                            <li>{{ $histo->date }}</li>
                                        @endforeach
                                    @else
                                        <li>Aucun historique</li>
                                    @endif
                                </ul>
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
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleModal({{ $depannage->id }})">❌</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                <button onclick="toggleModalDate(false)" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="updateDate()" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>
</x-app-layout>


<script>

    let currentDeppangeId = null;
    let pendingStatut = null;
    let lastOpenedDropdown = null;
    let depannageIdToDelete = null;

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

    function toggleModalDate(show = true) {
        console.log("appel de toggleModalDate avec show =", show);
        const modal = document.getElementById('create-date-modal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
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

        if(statusText === 'Affecter'){
            currentDeppangeId = depannageId;
            pendingStatut = {
                dropdownId,
                statusText,
                statusColor,
                buttonId,
            };
            toggleModalDate(true);
            return;
        }

        performStatusUpdate(dropdownId, statusText, statusColor, depannageId, button);
        toggleDropdown(dropdownId);
    }

    function performStatusUpdate(ropdownId, statusText, statusColor, depannageId, button){
        console.log()
        button.textContent = statusText;
        button.classList.remove('bg-gray-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500');
        button.classList.add(statusColor);

        fetch(`/depannage/${depannageId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ statut: statusText }),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Erreur:', error));
    }

    function updateDate() {
        const date = document.getElementById('date-create').value;

        fetch(`/depannage/${currentDeppangeId}/update-date`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ date_depannage: date })
        })
            .then(res => res.json())
            .then(data => {
                alert(data.success || 'Date enregistrée !');
                toggleModalDate(false);

                // On met à jour le statut seulement après que la date soit confirmée
                if (pendingStatus) {
                    const { dropdownId, statusText, statusColor, buttonId } = pendingStatus;
                    const button = document.getElementById(buttonId);
                    performStatusUpdate(dropdownId, statusText, statusColor, currentDeppangeId, button);
                    toggleDropdown(dropdownId);
                    pendingStatus = null;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erreur lors de l’enregistrement de la date.');
            });
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
                    location.reload();
                })
                .catch(error => {
                    console.error('Erreur:', error);
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
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5); /* Ajout d'une bordure lumineuse autour du bouton */
    }
</style>
