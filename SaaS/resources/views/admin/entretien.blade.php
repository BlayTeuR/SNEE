<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">

            <form method="GET" action="{{ route('admin.entretien') }}" class="flex flex-col">
                <h2 class="text-lg font-bold mb-4">Filtres</h2>

                <!-- Filtrer par nom -->
                <div class="mb-4">
                    <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                    <input type="text" name="nom" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom" value="{{ request('nom') }}">
                </div>

                <!-- Filtre par date -->
                <div class="mb-4">
                    <label for="date-filter_min" class="block text-sm font-medium text-gray-700">Filtrer par date min</label>
                    <input type="date" name="date_min" id="date-filter_min" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date_min') }}">
                </div>

                <!-- Filtrer par date max-->
                <div class="mb-4">
                    <label for="date-filter_max" class="block text-sm font-medium text-gray-700">Filtrer par date max</label>
                    <input type="date" name="date_max" id="date-filter_max" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" value="{{ request('date_max') }}">
                </div>

                <!-- Filtrer par lieu -->
                <div class="mb-4">
                    <label for="cp-filter" class="block text-sm font-medium text-gray-700">Filtrer par code postal</label>
                    <input type="text" name="code_postal" id="cp-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Code postal" value="{{ request('code_postal') }}">
                </div>

                <!-- Filtrer par lieu -->
                <div class="mb-4">
                    <label for="lieu-filter" class="block text-sm font-medium text-gray-700">Filtrer par lieu</label>
                    <input type="text" name="lieu" id="lieu-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Lieu" value="{{ request('lieu') }}">
                </div

                <!-- Toggle switch pour le mois courant -->
                <div class="mb-4 flex items-center">
                    <label for="mois_courant" class="block text-sm font-medium text-gray-700 mr-4">Entretien pour mois courant</label>
                    <label for="mois_courant" class="inline-flex relative items-center cursor-pointer">
                        <!-- Champ caché pour forcer la valeur "off" si décoché -->
                        <input type="hidden" name="mois_courant" value="off">

                        <input type="checkbox" id="mois_courant" name="mois_courant" value="on" class="sr-only peer"
                               @if(request()->get('mois_courant', 'on') == 'on') checked @endif>
                        <span class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 peer-checked:dark:bg-blue-600"></span>
                        <span class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></span>
                    </label>
                </div>


                <!-- Bouton de réinitialisation des filtres -->
                <div>
                    <a href="{{ route('admin.entretien') }}" id="reset-filters" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>

                <!-- Bouton pour appliquer les filtres -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>
        </div>

        <!-- Liste des factures -->
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">

            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/12">ID</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Adresse</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Prochaine visite</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Historique</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Détails</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6"></th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-16"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach($entretiens as $entretien)
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
                        <td class="p-3 text-sm text-gray-700">{{$entretien->id}}</td>
                        <td class="p-3 text-sm text-gray-700">{{$entretien->nom}}</td>
                        <td class="p-3 text-sm text-gray-700"><p class="font-bold">CP : {{$entretien->code_postal}}</p>{{$entretien->adresse}}</td>
                        <td class="p-3 text-sm text-gray-700">
                            @if($entretien->derniere_date == null)
                                <span class="text-red-500">Aucune date</span>
                            @else
                                {{ \Carbon\Carbon::parse($entretien->derniere_date)->format('d/m/Y') }}
                            @endif
                                <button onclick="toggleModalDate(true, {{$entretien->id}})"
                                        class="text-blue-500 hover:text-blue-700 hover:underline focus:outline-none p-2">Modifier</button>
                        </td>
                        <td class="p-3 text-sm text-gray-700 relative">
                            <button onclick="toggleDropdown(this)" class="flex items-center bg-gray-300 bg-opacity-50 px-3 py-1 rounded-lg hover:bg-gray-400">
                                <span>Historique</span>
                                <svg class="ml-2 h-4 w-4 transform transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            <div class="dropdown hidden absolute z-10 bg-white shadow-lg rounded-lg mt-2 p-4 w-48">
                                <ul>
                                    @php $numVisite = 1; @endphp
                                    @if($entretien->historiques->isEmpty())
                                        <li>Aucun historique</li>
                                    @endif
                                    @foreach($entretien->historiques as $historique)
                                        <li>Visite {{ $numVisite }} - {{ \Carbon\Carbon::parse($historique->date)->format('d/m/Y') }}</li>
                                        @php $numVisite++; @endphp
                                    @endforeach
                                </ul>
                            </div>
                        </td>

                        <td class="p-3 text-sm text-gray-700">
                            <a href="{{ route('admin.entretien.show', $entretien->id) }}" class="text-blue-500 hover:underline">Voir plus</a>
                        </td>

                        <td class="text-left p-3 text-sm text-gray-700">
                            <button class="text-blue-500 hover:underline text-blue-600" onclick="toggleModalArchiveBis({{$entretien->id}})">Archiver</button>
                        </td>

                        <!-- Colonne suppression -->
                        <td class="p-1 text-xs text-gray-700 w-10 text-center">
                            <button onclick="toggleModal({{ $entretien->id }})" class="text-red-600 hover:text-red-800">❌</button>
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
        <a href="{{ route('admin.entretienform') }}"
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
                <button onclick="toggleModal(true)" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
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

    <div id="confirmation-modal-bis" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-1/3 relative">
            <!-- Croix de fermeture -->
            <h2 class="text-xl font-bold mb-4">Archivage de l'entretien</h2>
            <p class="mb-6">Souhaitez-vous que cet entretien reste visible ou qu’il soit seulement dans l’historique ?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelArchiveBis()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</button>
                <button onclick="archiver()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Archiver</button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let entretienIdToDelete = null;
    let entretienIdToUpdate = null;

    function toggleModalArchiveBis(id) {
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.remove('hidden');
        window.currentEntretienId = id;
    }

    function cancelArchiveBis(){
        const modal = document.getElementById('confirmation-modal-bis');
        modal.classList.add('hidden');
        window.currentDepannageId = null;
    }

    function archiver() {
        const id = window.currentEntretienId;
        fetch(`/admin/entretien/${id}/archiver`, {
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
        cancelArchiveBis();
    }

    function toggleDropdown(button) {
        const dropdown = button.parentElement.querySelector('.dropdown');
        const icon = button.querySelector('svg');

        dropdown.classList.toggle('hidden');
        icon.classList.toggle('rotate-90');
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
            fetch(`/admin/entretien/del/${entretienIdToDelete}`, {
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

        fetch(`/admin/entretien/${entretienIdToUpdate}/update-date`, {
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

<style>
    .rotate-90 {
        transform: rotate(90deg);
    }
</style>

