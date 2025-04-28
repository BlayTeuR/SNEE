<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">
            <h2 class="text-lg font-bold">Filtres</h2>
            <!-- Filtres ici -->
            <div class="mb-4">
                <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                <select id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                    <option value="all">Tous</option>
                    <option value="non-paye">Non payé</option>
                    <option value="paye">Payé</option>
                    <option value="en-attente">En attente</option>
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
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">

            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">ID</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Adresse</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Prochaine visite</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Historique</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Détails</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entretiens as $entretien)
                    <tr class="hover:bg-gray-200">
                        <td class="p-3 text-sm text-gray-700">{{$entretien->id}}</td>
                        <td class="p-3 text-sm text-gray-700">{{$entretien->nom}}</td>
                        <td class="p-3 text-sm text-gray-700">{{$entretien->adresse}}</td>
                        <td class="p-3 text-sm text-gray-700">
                            @if($entretien->derniere_date == null)
                                <span class="text-red-500">Aucune date</span>
                            @else
                                {{ \Carbon\Carbon::parse($entretien->derniere_date)->format('d/m/Y') }}
                            @endif
                                <button onclick="toggleModalDate(true, {{$entretien->id}})"
                                        class="text-blue-500 hover:text-blue-700 hover:underline focus:outline-none p-2">Modifier</button>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button onclick="toggleDropdown('historique-dropdown')"
                                    class="text-blue-500 hover:text-blue-700 focus:outline-none">
                                Historique
                            </button>
                            <div id="historique-dropdown" class="hidden bg-white shadow-lg rounded-lg mt-2 p-4">
                                <!-- Contenu du dropdown -->
                                <ul>
                                    @php
                                        $numVisite = 1;
                                    @endphp
                                    @foreach($entretien->historiques as $historique)
                                        <li>Visite {{$numVisite}} - {{$historique->date}}</li>
                                        @php
                                            $numVisite++;
                                        @endphp
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <a href="{{ route('entretien.show', $entretien->id) }}" class="text-blue-500 hover:underline">Voir plus</a>
                        </td>
                        <!-- Colonne suppression -->
                        <td class="p-1 text-xs text-gray-700 w-10 text-center">
                            <button onclick="toggleModal({{ $entretien->id }})" class="text-red-600 hover:text-red-800">❌</button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('entretienform') }}"
           class="fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-3xl w-16 h-16 flex items-center justify-center rounded-full shadow-lg transition duration-300 ease-in-out z-50">
            +
        </a>
    </div>
    <!-- Modal de confirmation -->
    <div id="confirm-delete-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer cet entretien ? Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModalDate(true)" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delEntretien()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

    <div id="create-date-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Quelle sera la date du prochain entretien ?</h2>
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
    let entretienIdToDelete = null;
    let entretienIdToUpdate = null;

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    function toggleModal(depannageID = null) {
        const modal = document.getElementById('confirm-delete-modal');
        if (depannageID) {
            entretienIdToDelete = depannageID;
        }
        modal.classList.toggle('hidden');
    }

    function delEntretien() {
        if (entretienIdToDelete !== null) {
            fetch(`/entretien/del/${entretienIdToDelete}`, {
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

    function toggleModalDate(isOpen, id = null) {
        const modal = document.getElementById('create-date-modal');
        modal.classList.toggle('hidden', !isOpen);

        if (isOpen && id !== null) {
            entretienIdToUpdate = id;
            document.getElementById('date-create').value = "";
        }
    }
    function updateDate() {
        const date = document.getElementById('date-create').value;
        const id = document.getElementById('date-create').getAttribute('data-id');

        fetch(`/entretien/${entretienIdToUpdate}/update-date`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ date: date }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur serveur');
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                location.reload();
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        toggleModalDate(false);
    }


</script>
