<x-app-layout>
    @php
        $currentApprovisionnementId = null;
        $currentDeppangeId = null;
        $currentEntretienId = null;
        $currentFacturationId = null;
    @endphp
    <div class="flex flex-col md:flex-row bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <div class="w-full md:w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden mb-4 md:mb-0">

            <form method="GET" action="{{ route('admin.historique') }}">
                <!-- Filtres -->
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par type -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="type" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="facturation" {{ request('type') == 'facturation' ? 'selected' : '' }}>Facturation</option>
                        <option value="approvisionnement" {{ request('type') == 'approvisionnement' ? 'selected' : '' }}>Approvisionnement</option>
                        <option value="depannage" {{ request('type') == 'depannage' || !request('type') ? 'selected' : '' }}>Dépannage</option>
                        <option value="entretiens" {{ request('type') == 'entretiens' ? 'selected' : '' }}>Entretien</option>
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

                <!-- Bouton de réinitialisation -->
                <div>
                    <br>
                    <a href="{{ route('admin.historique') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>

                <!-- Bouton pour appliquer les filtres -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>
        </div>

        <!-- table des historiques -->
        <div class="w-full md:w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            @if($type == 'facturation')
                <h2 class="text-lg font-bold">Historique des facturations envoyées</h2>
                <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed mt-3">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">ID depannage</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom client</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Date d'émission</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Date d'intervention</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Montant</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Statut</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($model as $m)
                        @php
                            $count = 1;
                            $currentFacturationId = $m->id;
                        @endphp
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
                            <td class="p-3 text-sm text-gray-700">{{ $m->depannage_id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $m->depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $m->created_at->format('d/m/Y') }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                @if($m->date_intervention)
                                    {{ $m->date_intervention}}
                                @else
                                    Non définie
                                @endif
                            </td>
                            <td class="p-3 text-sm text-gray-700">
                                {{ $m->montant }} €
                            </td>
                            <td class="p-3 text-sm text-gray-700">
                                    {{ $m->statut }}
                            </td>
                            <td>
                                <button onclick="toggleModalDesarchiverFac('{{$m->id}}')" class="text-sm text-red-500 hover:underline hover:text-red-600">Désarchiver</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            @elseif($type == 'approvisionnement')
                <h2 class="text-lg font-bold">Historique des approvisionnements fait</h2>
                <div class="flex-1 overflow-auto">
                    <table class="w-full mt-3">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">ID Dépannage</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Pour client</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Date de création</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Date de validation</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Piece(s)</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model as $m)
                            @php
                                $currentApprovisionnementId = $m->id;
                                $count = 1;
                            @endphp
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
                            <tr class="hover:bg-gray-200">
                                <td class="p-3 text-sm text-gray-700">{{ $m->depannage_id }}</td>
                                <td class="p-3 text-sm text-gray-700">{{$m->depannage->nom}}</td>
                                <td class="p-3 text-sm text-gray-700">{{ $m->created_at->format('Y-m-d') }}</td>
                                <td class="p-3 text-sm text-gray-700">{{ $m->date_validation }}</td>
                                <td class="p-3 text-sm text-gray-700">
                                    @if($m->pieces->isEmpty())
                                        <p class="p-3 text-sm text-gray-700">Aucune pièce</p>
                                    @else
                                        <ul>
                                            @foreach($m->pieces as $piece)
                                                <li class="p-3 text-sm text-gray-700 flex">
                                                    <span>{{ $piece->quantite }} * {{ $piece->libelle }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="p-3 text-sm text-gray-700 relative">
                                        {{ $m->statut }}
                                </td>
                                <td>
                                    <button onclick="toggleModalDesarchiver('{{$m->id}}')" class="text-sm text-red-500 hover:underline hover:text-red-600">Désarchiver</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($type == 'depannage')
                <h2 class="text-lg font-bold">Historique des dépannages à facturer</h2>
                <div class="flex-1 overflow-auto">
                    <table class="w-full table-fixed mt-3">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-32">ID</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Adresse</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Type de client</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Historique</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Statut</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Détails</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left  w-1/6"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @foreach ($model as $m)
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
                                        $currentDeppangeId = $m->id;
                                        $icons = [
                                            'chargé d\'affaire' => ['icon' => '💼', 'label' => 'Chargé d\'affaire'],
                                            'client' => ['icon' => '👤', 'label' => 'Client'],
                                            'ajout manuel' => ['icon' => '🛠️', 'label' => 'Ajout manuel'],
                                        ];

                                        $provenance = strtolower($m->provenance);
                                        $iconData = $icons[$provenance] ?? ['icon' => '❓', 'label' => 'Inconnu'];
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-800 font-medium">{{ $m->id }}</span>
                                        <div class="relative group">
                                            <span class="text-lg">{{ $iconData['icon'] }}</span>
                                            <span class="absolute left-1/2 transform -translate-x-1/2 top-full mt-1 px-2 py-1 text-[10px] rounded bg-gray-700 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 shadow">
                                        {{ $iconData['label'] }}
                                        </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-sm text-gray-700">{{ $m->nom }}</td>
                                <td class="p-3 text-sm text-gray-700">{{ $m->adresse }}</td>
                                <td class="p-3 text-sm text-gray-700">
                                    {{$m->types->contrat}}, {{$m->types->garantie}}
                                </td>

                                <td class="p-3 text-sm text-gray-700 relative">
                                    <button onclick="toggleDropdown('historique-{{ $m->id }}')" class="bg-gray-300 bg-opacity-50 px-3 py-1 rounded-lg hover:bg-gray-400">
                                        Afficher Historique
                                    </button>
                                    <ul id="historique-{{ $m->id }}" class="hidden absolute left-3 top-full bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                        @if($m->historiques->isNotEmpty())
                                            @foreach ($m->historiques as $histo)
                                                <li>{{ $histo->date }}</li>
                                            @endforeach
                                        @else
                                            <li>Aucun historique</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="p-3 text-sm text-gray-700 relative">
                                        {{ $m->statut }}
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    <a href="{{ route('admin.depannage.show', $m->id) }}" class="text-blue-500 hover:underline">Voir plus</a>
                                </td>
                                <td>
                                    <button onclick="toggleModalDesarchiverDep('{{$m->id}}')" class="text-sm text-red-500 hover:underline hover:text-red-600">Désarchiver</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif($type == 'entretiens')
                    <h2 class="text-lg font-bold">Historique des entretiens archiver</h2>
                    <div class="flex-1 overflow-auto">
                        <table class="w-full table-fixed mt-3">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-32">ID</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Nom</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Adresse</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Dernière intervention</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Historique</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left w-1/6">Détails</th>
                                <th class="p-3 text-sm font-semibold tracking-wide text-left  w-1/6"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($model as $m)
                                @php
                                    $currentEntretienId = $m->id;
                                    $count++;
                                @endphp
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
                                    <td class="p-3 text-sm text-gray-700">{{$m->id}}</td>
                                    <td class="p-3 text-sm text-gray-700">{{$m->nom}}</td>
                                    <td class="p-3 text-sm text-gray-700">{{$m->adresse}}</td>
                                    <td class="p-3 text-sm text-gray-700">
                                        @if($m->derniere_date == null)
                                            <span class="text-red-500">Aucune date</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($m->derniere_date)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-gray-700 relative">
                                        <button onclick="toggleDropdown(this)" class="flex items-center text-blue-500 hover:text-blue-700 focus:outline-none">
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
                                                @foreach($m->historiques as $historique)
                                                    <li>Visite {{ $numVisite }} - {{ $historique->date }}</li>
                                                    @php $numVisite++; @endphp
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-700">
                                        <a href="{{ route('admin.entretien.show', $m->id) }}" class="text-blue-500 hover:underline">Voir plus</a>
                                    </td>
                                    <td>
                                        <button onclick="toggleModalDesarchiverEnt('{{$m->id}}')" class="text-sm text-red-500 hover:underline hover:text-red-600">Désarchiver</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">

                </table>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation facturation-->
    <div id="modal-desarchiver-facturation" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Voulez-vous désarchiver cette facturation ?</h2>
            <p class="text-gray-800">
                Si vous confirmez, cette facturation sera de nouveau disponible dans l'onglet 'Facturation' et sera supprimé de l'onglet 'Historique'.
            </p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModalDesarchiverFac({{$currentFacturationId}})" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="desarchiverFac()" id="confirm-desarchiver" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Désarchiver</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation approvisionnement-->
    <div id="modal-desarchiver-approvisionnement" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Voulez-vous désarchiver cet approvisionnement ?</h2>
            <p class="text-gray-800">
                Si vous confirmez, cet approvisionnement sera de nouveau disponible dans l'onglet 'Approvisionnement' et sera supprimé de l'onglet 'Historique'.
            </p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModalDesarchiver({{$currentApprovisionnementId}})" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="desarchiver()" id="confirm-desarchiver" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Désarchiver</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation dépannage-->
    <div id="modal-desarchiver-dep" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Voulez-vous désarchiver ce dépannage ?</h2>
            <p class="text-gray-800">
                Si vous confirmez, ce dépannage sera de nouveau disponible dans l'onglet 'Dépannage' et sera supprimé de l'onglet 'Historique'.
            </p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModalDesarchiverDep({{$currentDeppangeId}})" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="desarchiverDep()" id="confirm-desarchiver" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Désarchiver</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation entretiens-->
    <div id="modal-desarchiver-ent" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Voulez-vous désarchiver cet entretien ?</h2>
            <p class="text-gray-800">
                Si vous confirmez, cet entretien sera de nouveau disponible dans l'onglet 'Entretien' et sera supprimé de l'onglet 'Historique'.
            </p>
            <div class="mt-4 flex justify-end space-x-4">
                <button onclick="toggleModalDesarchiverEnt({{$currentEntretienId}})" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="desarchiverEnt()" id="confirm-desarchiver" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Désarchiver</button>
            </div>
        </div>
    </div>

</x-app-layout>

<script>

    let currentApprovisionnementId = null;
    let currentDepannageId = null;
    let currentEntretienId = null;
    let currentFacturationId = null;

    function toggleModalDesarchiver(id) {
        var modal = document.getElementById('modal-desarchiver-approvisionnement');
        currentApprovisionnementId = id;
        modal.classList.toggle('hidden');
    }

    function toggleModalDesarchiverFac(id){
        var modal = document.getElementById('modal-desarchiver-facturation');
        currentFacturationId = id;
        console.log(currentFacturationId +"currentFacturationId");
        modal.classList.toggle('hidden');
    }

    function toggleDropdown(button) {
        const dropdown = button.parentElement.querySelector('.dropdown');
        const icon = button.querySelector('svg');

        dropdown.classList.toggle('hidden');
        icon.classList.toggle('rotate-90');
    }

    function toggleModalDesarchiverDep(id) {
        var modal = document.getElementById('modal-desarchiver-dep');
        currentDepannageId = id;
        console.log(currentDepannageId)
        modal.classList.toggle('hidden');
    }

    function toggleModalDesarchiverEnt(id) {
        var modal = document.getElementById('modal-desarchiver-ent');
        currentEntretienId = id;
        console.log(currentEntretienId)
        modal.classList.toggle('hidden');
    }

    function desarchiver() {
        fetch(`/admin/approvisionnement/${currentApprovisionnementId}/desarchiver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id: currentApprovisionnementId,
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                setTimeout(() => {
                        saveNotificationBeforeReload("Approvisionnement désarchivé avec succès", 'succes');
                        location.reload();
                    }
                    , 100)
            })
            .catch(error => {
                console.log('Erreur:', error);
                setTimeout(() => {
                        saveNotificationBeforeReload("Erreur lors de la tentative de désarchivage", 'error');
                        location.reload();
                    }
                    , 100)
            });

        toggleModalDesarchiver();
        currentApprovisionnementId = null;
    }

    function desarchiverDep() {
        console.log(currentDepannageId);
        fetch(`/admin/depannage/${currentDepannageId}/desarchiver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id: currentDepannageId,
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                setTimeout(() => {
                        saveNotificationBeforeReload("Dépannage désarchivé avec succès", 'succes');
                        location.reload();
                    }
                    , 100)
            })
            .catch(error => {
                console.log('Erreur:', error);
                setTimeout(() => {
                        saveNotificationBeforeReload("Erreur lors de la tentative de désarchivage", 'error');
                        location.reload();
                    }
                    , 100)
            });

        toggleModalDesarchiver();
        currentDepannageId = null;
    }

    function desarchiverEnt(){
        console.log(currentEntretienId);
        fetch(`/admin/entretien/${currentEntretienId}/desarchiver`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id: currentEntretienId,
            })
        })
            .then(response => response.json())
            .then(data => {
                setTimeout(() => {
                        saveNotificationBeforeReload("Entretien désarchivé avec succès", 'succes');
                        location.reload();
                    }
                    , 100)
            })
            .catch(error => {
                console.log('Erreur:', error);
                setTimeout(() => {
                        saveNotificationBeforeReload("Erreur lors de la tentative de désarchivage", 'error');
                        location.reload();
                    }
                    , 100)
            });

        toggleModalDesarchiverEnt();
        currentEntretienId = null;
    }

    function desarchiverFac(){
        console.log(currentFacturationId);
        fetch(`/admin/facturation/desarchiver/${currentFacturationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id: currentFacturationId,
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                setTimeout(() => {
                        saveNotificationBeforeReload("Facturation désarchivée avec succès", 'succes');
                        location.reload();
                    }
                , 100)
            })
            .catch(error => {
                console.log('Erreur:', error);
                setTimeout(() => {
                        saveNotificationBeforeReload("Erreur lors de la tentative de désarchivage", 'error');
                        location.reload();
                    }
                    , 100)
            });

        toggleModalDesarchiverFac();
        currentFacturationId = null;
    }


</script>

<style>
    .rotate-90 {
        transform: rotate(90deg);
    }
</style>
