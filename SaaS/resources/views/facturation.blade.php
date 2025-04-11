<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden">
            <h2 class="text-lg font-bold">Filtres</h2>
            <!-- Filtres ici -->
            <div class="mb-4">
                <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                <select id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                    <option value="all">Tous</option>
                    <option value="non-paye">Envoyée</option>
                    <option value="paye">Non envoyée</option>
                </select>
            </div>

            <!-- Filtre par date -->
            <div class="mb-4">
                <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date</label>
                <input type="date" id="date-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
            </div>

            <!-- Filtre par nom -->
            <div class="mb-4">
                <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                <input type="text" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom">
            </div>

            <!-- Filtre par montant -->
            <div class="mb-4">
                <label for="amount-filter" class="block text-sm font-medium text-gray-700">Filtrer par montant</label>
                <input type="number" id="amount-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Montant">
            </div>

            <!-- Bouton de réinitialisation des filtres -->
            <div>
                <button id="reset-filters" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Réinitialiser les filtres</button>
            </div>
        </div>

        <!-- Liste des factures -->
        <div class="w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
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
                        <th class="p-3 text-sm font-semibold tracking-wide text-left" style="width: 10px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($facturations as $facturation)
                        <tr class="hover:bg-gray-200">
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->depannage_id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                @if($facturation->date_intervention)
                                    {{ $facturation->date_intervention}}
                                @else
                                    Non définie
                                @endif
                                <!-- Bouton d'édition -->
                                <button
                                    onclick="openModal({{ $facturation->id }})"
                                    class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Modal -->
                                <div id="modal-{{ $facturation->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                        <h2 class="text-lg font-semibold">Modifier la date d'intervention</h2>

                                        <form id="form-{{ $facturation->id }}" action="{{ route('facturation.update.date', $facturation->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Liste déroulante pour Contrat -->
                                            <label for="date-update" class="block mt-4 text-sm text-gray-700">Date d'intervention</label>
                                            <input type="date" id="date-update" name="date_intervention" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                            <!-- Boutons Valider et Annuler -->
                                            <div class="mt-4 flex justify-end">
                                                <button type="button" onclick="closeModal({{ $facturation->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->montant }}€</td>
                            <td class="p-3 text-sm text-gray-700">
                                <button onclick="toggleDropdown('status-{{ $facturation->id }}')" class="bg-gray-300 bg-opacity-50 rounded-lg">Statut</button>
                                <ul id="status-{{ $facturation->id }}" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                    <li>{{ $facturation->statut }}</li>
                                    <li>Envoyée</li>
                                    <li>Non envoyée</li>
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700">{{ $facturation->contact }}</td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleModal({{ $facturation->id }})">❌</button>
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
                <button onclick="delFacturation()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let facturationToDelete = null;

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
    }

    function delFacturation() {
        if (facturationToDelete !== null) {
            fetch(`/facturation/del/${facturationToDelete}`, {
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

    function openModal(facturationId) {
        document.getElementById(`modal-${facturationId}`).classList.remove('hidden');
    }

    function closeModal(facturationId) {
        document.getElementById(`modal-${facturationId}`).classList.add('hidden');
    }
</script>
