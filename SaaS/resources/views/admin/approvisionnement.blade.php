<x-app-layout>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 text-white rounded shadow-lg transition-opacity duration-1000">
        <span id="notification-message"></span>
    </div>

    @php
        $currentApprovisionnementId = null;
    @endphp
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-auto">

            <form method="GET" action="{{ route('admin.approvisionnement') }}">
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
                    <a href="{{ route('admin.approvisionnement') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
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
                        <th class="p-3 text-sm font-semibold tracking-wide text-left"></th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $count = 1;
                    @endphp
                    @foreach($approvisionnements as $approvisionnement)
                        @if($count % 2 == 0)
                            @php
                                $bgColor = 'bg-gray-100';
                            @endphp
                        @else
                            @php
                                $bgColor = 'bg-white';
                            @endphp
                        @endif
                        <tr class="hover:bg-gray-200 {{$bgColor}}" id="row-{{$approvisionnement->id}}">
                            @php
                                $count++;
                                $currentApprovisionnementId = $approvisionnement->id;
                            @endphp
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

                                            <form id="form-{{ $approvisionnement->id }}" action="{{ route('admin.pieces.update', $approvisionnement->id) }}" method="POST">
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

                                            <form id="form-{{ $approvisionnement->id }}" action="{{ route('admin.pieces.update', $approvisionnement->id) }}" method="POST">
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
                                    <li onclick="handleStatutChange('status-{{ $approvisionnement->id }}', 'À planifier', 'bg-red-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">À planifier</li>
                                    <li onclick="handleStatutChange('status-{{ $approvisionnement->id }}', 'En attente', 'bg-yellow-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">En attente</li>
                                    <li onclick="handleStatutChange('status-{{ $approvisionnement->id }}', 'Fait', 'bg-green-500', 'status-{{ $approvisionnement->id }}-btn', {{ $approvisionnement->id }})" class="hover:bg-gray-200 p-1 cursor-pointer">Fait</li>
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                @if($approvisionnement->statut == 'Fait')
                                    <button onclick="handleStatutChange('', '', '', '', {{$approvisionnement->id}}, false)" class="text-blue-500 hover:underline hover:text-blue-600">Archiver</button>
                                    @else
                                    <p></p>
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleModal({{ $approvisionnement->id }})">❌</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4 flex justify-start">
                    {{ $approvisionnements->appends(request()->query())->links() }}
                </div>

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

    <!-- Modal de confirmation d'archivage -->
    <div id="confirmation-modal" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-1/3 relative">
            <!-- Croix de fermeture -->
            <button onclick="cancelArchive()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold leading-none">&times;</button>

            <h2 class="text-xl font-bold mb-4">Archivage de l'approvisionnement</h2>
            <p class="mb-6">Souhaitez-vous que cet approvisionnement reste visible ou qu’il soit seulement dans l’historique ?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="confirmArchive(false)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Rester visible</button>
                <button onclick="confirmArchive(true)" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Historique uniquement</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation d'archivage Bis -->
    <div id="confirmation-modal-bis" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-1/3 relative">
            <!-- Croix de fermeture -->
            <h2 class="text-xl font-bold mb-4">Archivage de l'approvisionnement</h2>
            <p class="mb-6">Souhaitez-vous que cet approvisionnement reste visible ou qu’il soit seulement dans l’historique ?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelArchiveBis()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Annuler</button>
                <button onclick="archiver()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Archiver</button>
            </div>
        </div>
    </div>

</x-app-layout>

<script>

    let openDropdownId = null;

    function openModalPiece(approvisionnementId) {
        document.getElementById(`modal-${approvisionnementId}-piece`).classList.remove('hidden');
    }

    function closeModalPiece(approvisionnementId) {
        document.getElementById(`modal-${approvisionnementId}-piece`).classList.add('hidden');
    }

    let approvisionnementIdToDelete = null;

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if (openDropdownId && openDropdownId !== id) {
            const oldDropdown = document.getElementById(openDropdownId);
            if (oldDropdown) oldDropdown.classList.add('hidden');
        }
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            openDropdownId = id;
        } else {
            dropdown.classList.add('hidden');
            openDropdownId = null;
        }
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

    function confirmArchive(archive) {
        document.getElementById('confirmation-modal').classList.add('hidden');
        updateStatus(window.selectedDropdownId,
            window.selectedStatus,
            window.selectedBgClass,
            window.selectedBtnId,
            window.selectedApproId,
            archive);
    }

    function cancelArchive() {
        document.getElementById('confirmation-modal').classList.add('hidden');

        toggleDropdown(window.selectedDropdownId)
        window.selectedDropdownId = null;
        window.selectedStatus = null;
        window.selectedBgClass = null;
        window.selectedBtnId = null;
        window.selectedApproId = null;
    }

    function cancelArchiveBis() {
        document.getElementById('confirmation-modal-bis').classList.add('hidden');
        window.selectedApproId = null;
    }

    function handleStatutChange(dropdownId, newStatus, newBgClass, buttonId, approvisionnementId, archived = '') {
        if (newStatus === 'Fait' && archived === '') {
            window.selectedDropdownId = dropdownId;
            window.selectedStatus = newStatus;
            window.selectedBgClass = newBgClass;
            window.selectedBtnId = buttonId;
            window.selectedApproId = approvisionnementId;

            document.getElementById('confirmation-modal').classList.remove('hidden');
        } else if (archived === false) {
            document.getElementById('confirmation-modal-bis').classList.remove('hidden');
            window.selectedApproId = approvisionnementId;
        } else {
            updateStatus(dropdownId, newStatus, newBgClass, buttonId, approvisionnementId);
        }
    }

    function updateStatus(dropdownId, newStatus, newBgClass, buttonId, approvisionnementId, archive = false) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        // Mise à jour du texte
        button.textContent = newStatus;

        // Mise à jour de la couleur de fond
        button.className = 'px-4 py-2 rounded-lg text-white ' + newBgClass;

        // Cacher le dropdown
        dropdown.classList.add('hidden');

        fetch(`/admin/approvisionnement/${approvisionnementId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                statut: newStatus,
                archive: archive,
            })
        })
            .then(response => response.json())
            .then(data => {
                location.reload();
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

        fetch(`/admin/approvisionnement/${approvisionnementId}/add-pieces`, {
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

    function archiver(){
        console.log("ID DEP = " + window.selectedApproId);
        fetch(`/admin/approvisionnement/${window.selectedApproId}/archiver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }, body: JSON.stringify({
                id: window.selectedApproId,
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
        fetch(`/admin/pieces/del/${pieceId}`, {
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
            fetch(`/admin/approvisionnement/del/${approvisionnementIdToDelete}`, {
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
        if (openDropdownId) {
            const dropdown = document.getElementById(openDropdownId);
            const button = document.getElementById(openDropdownId + '-btn');
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
                openDropdownId = null;
            }
        }
    });

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
