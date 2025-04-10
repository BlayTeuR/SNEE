<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden">
            <h2 class="text-lg font-bold">Filtres</h2>

            <!-- Filtrer par statut -->
            <div class="mb-4">
                <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                <select id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                    <option value="all">Tous</option>
                    <option value="planifier">À planifier</option>
                    <option value="affecter">Affecter</option>
                    <option value="approvisionnement">Approvisionnement</option>
                    <option value="facturer">À facturer</option>
                </select>
            </div>

            <!-- Filtrer par date -->
            <div class="mb-4">
                <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date</label>
                <input type="date" id="date-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
            </div>

            <!-- Filtrer par nom -->
            <div class="mb-4">
                <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                <input type="text" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom">
            </div>

            <div class="mb-4">
                <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par lieu</label>
                <input type="text" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom">
            </div>

            <!-- Bouton de réinitialisation -->
            <div>
                <button id="reset-filters" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Réinitialiser les filtres</button>
            </div>
        </div>

        <!-- Liste des dépannages -->
        <div class="w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">ID</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Nom</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Adresse</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Contact</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Historique</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Plus d'information</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($depannages as $depannage)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->adresse }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->contact_email }}</td>
                            <td class="p-3 text-sm text-gray-700 relative">
                                <button onclick="toggleDropdown('historique-{{ $depannage->id }}')" class="bg-gray-300 bg-opacity-50 px-3 py-1 rounded-lg hover:bg-gray-400">
                                    Afficher Historique
                                </button>
                                <ul id="historique-{{ $depannage->id }}" class="hidden absolute left-0 top-full bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let lastOpenedDropdown = null;

    function toggleDropdown(id, buttonId = null) {
        const dropdown = document.getElementById(id);

        if (lastOpenedDropdown && lastOpenedDropdown !== dropdown) {
            lastOpenedDropdown.classList.add('hidden');
        }

        dropdown.classList.toggle('hidden');
        lastOpenedDropdown = dropdown.classList.contains('hidden') ? null : dropdown;
    }

    function updateStatus(dropdownId, statusText, statusColor, buttonId) {
        const button = document.getElementById(buttonId);
        const depannageId = buttonId.split('-')[1];

        button.textContent = statusText;
        button.classList.remove('bg-gray-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500');
        button.classList.add(statusColor);

        // AJAX update
        fetch(`/depannage/${depannageId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ statut: statusText }),
        })
            .then(response => response.json())
            .then(data => console.log(data.message))
            .catch(error => console.error('Erreur:', error));

        toggleDropdown(dropdownId);
    }
</script>

<style>
    .status-circle {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }
</style>
