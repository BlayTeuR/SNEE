<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">

            <form method="GET" action="{{ route('admin.facturation') }}">
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Statut -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select id="status-filter" name="statut" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="all">Tous</option>
                        <option value="Non envoyée">Non envoyée</option>
                        <option value="Envoyée">Envoyée</option>
                    </select>
                </div>

                <!-- Date d’émission -->
                <div class="mb-4">
                    <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date d'émission</label>
                    <input type="date" id="date-filter" name="emission" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('emission') }}">
                </div>

                <!-- Date d’intervention -->
                <div class="mb-4">
                    <label for="date-filter-intervention" class="block text-sm font-medium text-gray-700">Filtrer par date d'intervention</label>
                    <input type="date" id="date-filter-intervention" name="intervention" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('intervention') }}">
                </div>

                <!-- Nom -->
                <div class="mb-4">
                    <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                    <input type="text" id="name-filter" name="nom" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom" value="{{ request('nom') }}">
                </div>

                <!-- Montants -->
                <div class="mb-4">
                    <label for="amount-min-filter" class="block text-sm font-medium text-gray-700">Filtrer par montant</label>

                    <input type="number" id="amount-min-filter" name="montant_min" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Montant minimum" value="{{ request('montant_min') }}">

                    <input type="number" id="amount-max-filter" name="montant_max" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Montant maximum" value="{{ request('montant_max') }}">
                </div>

                <!-- Boutons -->
                <div>
                    <a href="{{ route('admin.facturation') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>

        </div>

        <!-- Liste des factures -->
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">ID depannage</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Nom client</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Date d'émission</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Date d'intervention</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Montant</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Actions</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Supprimer</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach($facturations as $facturation)
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
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->depannage_id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                @if($facturation->date_intervention)
                                    {{ \Carbon\Carbon::parse($facturation->date_intervention)->format('d/m/Y') }}
                                @else
                                    Non définie
                                @endif
                                <!-- Bouton d'édition -->
                                <button
                                    onclick="openModalDate({{ $facturation->id }})"
                                    class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Modal -->
                                <div id="modal-{{ $facturation->id }}-date" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                        <h2 class="text-lg font-semibold">Modifier la date d'intervention</h2>

                                        <form id="form-{{ $facturation->id }}" action="{{ route('admin.facturation.update.date', $facturation->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Liste déroulante pour Contrat -->
                                            <label for="date-update" class="block mt-4 text-sm text-gray-700">Date d'intervention</label>
                                            <input type="date" id="date-update" name="date_intervention" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                            <!-- Boutons Valider et Annuler -->
                                            <div class="mt-4 flex justify-end">
                                                <button type="button" onclick="closeModalDate({{ $facturation->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>
                            <td class="p-3 text-sm text-gray-700">
                            {{ $facturation->montant }} €
                            <!-- Bouton d'édition -->
                            <button
                                onclick="openModalMontant({{ $facturation->id }})"
                                class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- Modal -->
                            <div id="modal-{{ $facturation->id }}-montant" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                    <h2 class="text-lg font-semibold">Modifier le montant du dépannage</h2>

                                    <form id="form-{{ $facturation->id }}" action="{{ route('admin.facturation.update.montant', $facturation->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Liste déroulante pour Contrat -->
                                        <label for="montant" class="block mt-4 text-sm text-gray-700">Montant</label>
                                        <input type="number" id="montant" name="montant" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                        <!-- Boutons Valider et Annuler -->
                                        <div class="mt-4 flex justify-end">
                                            <button type="button" onclick="closeModalMontant({{ $facturation->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button id="status-{{ $facturation->id }}-btn"
                                        onclick="toggleDropdown('status-{{ $facturation->id }}')"
                                        class="px-4 py-2 rounded-lg text-white
                                        {{ $facturation->statut == 'Non envoyée' ? 'bg-red-500' :
                                           ($facturation->statut == 'Envoyée' ? 'bg-green-500' : 'bg-gray-500')}}">
                                    {{ $facturation->statut }}
                                </button>
                                <ul id="status-{{ $facturation->id }}" class="hidden absolute left-3 top-full bg-gray-100 p-2 mt-2 rounded shadow-md z-10 w-48">
                                    <li onclick="updateStatus('status-{{ $facturation->id }}', 'Non envoyée', 'bg-red-500', 'status-{{ $facturation->id }}-btn', {{ $facturation->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">Non envoyée</li>
                                    <li onclick="updateStatus('status-{{ $facturation->id }}', 'Envoyée', 'bg-green-500', 'status-{{ $facturation->id }}-btn', {{ $facturation->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">Envoyée</li>
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700">
                                @if($facturation->statut == 'Envoyée')
                                    <button onclick="toggleModalArchived({{ $facturation->id }})" class="text-blue-500 hover:text-blue-700 hover:underline">Archiver</button>
                                @else
                                    <p class="text-xs">non disponible</p>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleModal({{ $facturation->id }})">❌</button>
                            </td>
                        </tr>
                        @php
                            $count++;
                        @endphp
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
            <p>Êtes-vous sûr de vouloir supprimer cette facturation ? Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModal()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delFacturation()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

    <div id="confirmation-modal-bis" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-1/3 relative">
            <!-- Croix de fermeture -->
            <h2 class="text-xl font-bold mb-4">Archivage de la facturation</h2>
            <p class="mb-6">Souhaitez-vous que cette facturation reste visible ou qu’il soit seulement dans l’historique ?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelArchiveBis()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</button>
                <button onclick="archiver()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Archiver</button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let facturationToDelete = null;
    let lastOpenedDropdown = null;
    let openedModals = new Set();

    function toggleModalArchived(id) {
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.remove('hidden');
        window.currentFacturationId = id;
    }

    function cancelArchiveBis() {
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.add('hidden');
        window.currentDepannageId = null;
    }

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    function toggleModal(facturationId = null) {
        const modal = document.getElementById('confirm-delete-modal');

        if (facturationId) {
            facturationToDelete = facturationId;
        }

        modal.classList.toggle('hidden');

        if (!modal.classList.contains('hidden')) {
            openedModals.add(modal);
        } else {
            openedModals.delete(modal);
        }
    }

    function delFacturation() {
        if (facturationToDelete !== null) {
            fetch(`/admin/facturation/del/${facturationToDelete}`, {
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

    function updateStatus(dropdownId, newStatus, newBgClass, buttonId, facturationId) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        // Mise à jour du texte
        button.textContent = newStatus;

        // Mise à jour de la couleur de fond
        button.className = 'px-4 py-2 rounded-lg text-white ' + newBgClass;

        // Cacher le dropdown
        dropdown.classList.add('hidden');

        fetch(`/admin/facturation/${facturationId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                statut: newStatus,
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                setTimeout(() => {
                    location.reload();
                }, 100);
            })
            .catch(error => {
                console.error('Erreur:', error);
            });

        toggleDropdown(dropdownId);
    }

    function archiver() {
        const facturationId = window.currentFacturationId;

        fetch(`/admin/facturation/archiver/${facturationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
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
                console.error('Erreur:', error);
            });

        cancelArchiveBis();
    }

    function openModalDate(facturationId) {
        const modal = document.getElementById(`modal-${facturationId}-date`);
        modal.classList.remove('hidden');
        openedModals.add(modal);
    }

    function closeModalDate(facturationId) {
        const modal = document.getElementById(`modal-${facturationId}-date`);
        modal.classList.add('hidden');
        openedModals.delete(modal);
    }

    function openModalMontant(facturationId) {
        const modal = document.getElementById(`modal-${facturationId}-montant`);
        modal.classList.remove('hidden');
        openedModals.add(modal);
    }

    function closeModalMontant(facturationId) {
        const modal = document.getElementById(`modal-${facturationId}-montant`);
        modal.classList.add('hidden');
        openedModals.delete(modal);
    }


</script>
