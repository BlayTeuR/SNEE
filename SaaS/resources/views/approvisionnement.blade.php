<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">

            <form method="GET" action="{{ route('approvisionnement') }}">
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par statut -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="type" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="Facturation" {{ request('type') == 'Facturation' ? 'selected' : '' }}>Facturation</option>
                        <option value="Approvisionnement" {{ request('type') == 'Approvisionnement' ? 'selected' : '' }}>Approvisionnement</option>
                        <option value="Dépannage" {{ request('type') == 'Dépannage' || !request('type') ? 'selected' : '' }}>Dépannage</option>
                        <option value="Entretient" {{ request('type') == 'Entretient' ? 'selected' : '' }}>Entretient</option>
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

                <!-- Filtrer par ID -->
                <div class="mb-4">
                    <label for="amount-filter" class="block text-sm font-medium text-gray-700">Filtrer par ID</label>
                    <input type="number" name="id" id="amount-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="ID" value="{{ request('id') }}">
                </div>

                <!-- Bouton de réinitialisation -->
                <div>
                    <a href="{{ route('approvisionnement') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>

                <!-- Bouton pour appliquer les filtres -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>

        </div>

        <!-- Liste des approvisionnements -->
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">ID Dépannage</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Pour client</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Date de création</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Piece(s)</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($approvisionnements as $approvisionnement)
                        <tr class="hover:bg-gray-200">
                            <td class="p-3 text-sm text-gray-700">{{ $approvisionnement->depannage_id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{$approvisionnement->depannage->nom}}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $approvisionnement->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                    @if($approvisionnement->pieces->isEmpty())
                                        <p class="p-3 text-sm text-gray-700">Aucune pièce</p>
                                    <button
                                        onclick="openModalPiece({{ $approvisionnement->id }})"
                                        class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                        <i class="fas fa-edit"></i> Ajouter une pièce
                                    </button>

                                    <!-- Modal -->
                                    <div id="modal-{{ $approvisionnement->id }}-piece" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                            <h2 class="text-lg font-semibold">Nom de la pièce</h2>

                                            <form id="form-{{ $approvisionnement->id }}" action="{{ route('pieces.update', $approvisionnement->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <label for="libelle" class="block mt-4 text-sm text-gray-700">Nom de la pièce</label>
                                                <input type="text" id="libelle" name="libelle" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                                <label for="quantite" class="block mt-4 text-sm text-gray-700">Nombre de pièce(s)</label>
                                                <input type="number" id="quantite" name="quantite" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                                <!-- Boutons Valider et Annuler -->
                                                <div class="mt-4 flex justify-end">
                                                    <button type="button" onclick="closeModalPiece({{ $approvisionnement->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                        <ul>
                                            @foreach($approvisionnement->pieces as $piece)
                                                <li class="p-3 text-sm text-gray-700 flex">
                                                    <span>{{ $piece->quantite }} * {{ $piece->libelle }}</span>
                                                    <button onclick="delPieces({{$piece->id}})" type="button" class="text-xs ml-2 text-red-500 hover:text-red-600 hover:underline" id="delete-img-btn">(supprimer)</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    <button
                                        onclick="openModalPiece({{ $approvisionnement->id }})"
                                        class="ml-2 text-blue-500 hover:text-blue-700 hover:underline">
                                        <i class="fas fa-edit"></i> Ajouter une pièce
                                    </button>

                                    <!-- Modal -->
                                    <div id="modal-{{ $approvisionnement->id }}-piece" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50 flex">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                            <h2 class="text-lg font-semibold">Nom de la pièce</h2>

                                            <form id="form-{{ $approvisionnement->id }}" action="{{ route('pieces.update', $approvisionnement->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <label for="libelle" class="block mt-4 text-sm text-gray-700">Nom de la pièce</label>
                                                <input type="text" id="libelle" name="libelle" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                                <label for="quantite" class="block mt-4 text-sm text-gray-700">Nombre de pièce(s)</label>
                                                <input type="number" id="quantite" name="quantite" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">

                                                <!-- Boutons Valider et Annuler -->
                                                <div class="mt-4 flex justify-end">
                                                    <button type="button" onclick="closeModalPiece({{ $approvisionnement->id }})" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-600">Annuler</button>
                                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Valider</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button id="status-{{ $approvisionnement->id }}-btn"
                                        onclick="toggleDropdown('status-{{ $approvisionnement->id }}')"
                                        class="px-4 py-2 rounded-lg text-white
                                        {{ $approvisionnement->statut == 'À planifier' ? 'bg-red-500' :
                                           ($approvisionnement->statut == 'En attente' ? 'bg-yellow-500' :
                                           ($approvisionnement->statut == 'Fait' ? 'bg-green-500' : 'bg-gray-500')) }}">
                                    {{ $approvisionnement->statut }}
                                </button>
                                <ul id="status-{{ $approvisionnement->id }}" class="hidden absolute left-3 top-full bg-gray-100 p-2 mt-2 rounded shadow-md z-10 w-48">
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'À planifier', 'bg-red-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">À planifier</li>
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'En attente', 'bg-yellow-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">En attente</li>
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'Fait', 'bg-green-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">Fait</li>
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleModal({{ $approvisionnement->id }})">❌</button>
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
            <p>Êtes-vous sûr de vouloir supprimer cet approvisionnement ? Cette action est irréversible.</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModal()" class="bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="delApprovisionnementConfirm()" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Supprimer</button>
            </div>
        </div>
    </div>

</x-app-layout>

<script>

    function openModalPiece(approvisionnementId) {
        document.getElementById(`modal-${approvisionnementId}-piece`).classList.remove('hidden');
    }

    function closeModalPiece(approvisionnementId) {
        document.getElementById(`modal-${approvisionnementId}-piece`).classList.add('hidden');
    }

    let approvisionnementIdToDelete = null;

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    function toggleModal(approvisionnementId = null) {
        const modal = document.getElementById('confirm-delete-modal');
        if (approvisionnementId) {
            approvisionnementIdToDelete = approvisionnementId;
        }
        modal.classList.toggle('hidden');
    }

    function toggleForm(approvisionnementId) {
        const form = document.getElementById(`form-${approvisionnementId}`);
        if (form) {
            form.classList.toggle('hidden');
        } else {
            console.error(`Form with id form-${approvisionnementId} not found`);
        }
    }

    function toggleConfirmDel(approvisionnementId) {
        const confirmDel = document.getElementById(`confirm-del-${approvisionnementId}`);
        if (!confirmDel) {
            console.error(`Confirm delete with id confirm-del-${approvisionnementId} not found`);
            return;
        }
        confirmDel.classList.toggle('hidden');
    }

    function updateStatus(dropdownId, newStatus, newBgClass, buttonId, approvisionnementId) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        // Mise à jour du texte
        button.textContent = newStatus;

        // Mise à jour de la couleur de fond
        button.className = 'px-4 py-2 rounded-lg text-white ' + newBgClass;

        // Cacher le dropdown
        dropdown.classList.add('hidden');

        fetch(`/approvisionnement/${approvisionnementId}/update-status`, {
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
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    function addPieces(approvisionnementId){
        const libelle = document.getElementById(`libelle-${approvisionnementId}`).value;
        const quantite = document.getElementById(`quantite-${approvisionnementId}`).value;

        if(!libelle || !quantite) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        fetch(`/approvisionnement/${approvisionnementId}/add-pieces`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                libelle: libelle,
                quantite: quantite,
            })
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

    function delPieces(pieceId){
        fetch(`/pieces/del/${pieceId}`, {
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

    function delApprovisionnementConfirm() {
        if (approvisionnementIdToDelete !== null) {
            fetch(`/approvisionnement/del/${approvisionnementIdToDelete}`, {
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
