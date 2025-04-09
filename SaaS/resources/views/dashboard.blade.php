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
                    <option value="planifier">À planifier</option>
                    <option value="affecter">Affecter</option>
                    <option value="approvisionnement">Approvisionnement</option>
                    <option value="facturer">À facturer</option>
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

            <!-- Bouton de réinitialisation des filtres -->
            <div>
                <button id="reset-filters" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Réinitialiser les filtres</button>
            </div>
        </div>

        <!-- Liste des dépannages -->
        <div class="w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">

            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Nom</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Adresse</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">contact</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">historique</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">status</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Plus d'information</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($depannages as $depannage)
                        <tr class="hover:bg-gray-200">
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->nom }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->adresse }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $depannage->contact_email }}</td>
                            <td class="p-3 text-sm text-gray-700">
                                <button onclick="toggleDropdown('historique-{{ $depannage->id }}')" class="bg-gray-300 bg-opacity-50 rounded-lg">Afficher Historique</button>
                                <ul id="historique-{{ $depannage->id }}" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                    @if($depannage->historiques->isNotEmpty())
                                        @foreach ($depannage->historiques as $histo)
                                            <li>{{ $histo->date }}</li> <!-- Afficher la date ou d'autres informations -->
                                        @endforeach
                                    @else
                                        <li>Aucun historique</li>
                                    @endif
                                </ul>
                            </td>
                            <td class="p-3 text-sm text-gray-700">
                                <button id="status-{{ $depannage->id }}-btn" onclick="toggleDropdown('status-{{ $depannage->id }}', 'status-{{ $depannage->id }}-btn')" class="bg-gray-200 px-4 py-2 rounded-lg">
                                    {{ $depannage->status }} Choisir un statut
                                </button>
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

    function toggleDropdown(id, buttonId) {
        const dropdown = document.getElementById(id);
        const button = document.getElementById(buttonId);

        if (lastOpenedDropdown && lastOpenedDropdown !== dropdown) {
            lastOpenedDropdown.classList.add('hidden');
        }

        // Basculer l'état du menu actuel
        dropdown.classList.toggle('hidden');

        // Mettre à jour la référence du dernier menu ouvert
        lastOpenedDropdown = dropdown.classList.contains('hidden') ? null : dropdown;
    }

    function updateStatus(dropdownId, statusText, statusColor, buttonId) {
        const button = document.getElementById(buttonId);
        button.textContent = statusText;

        button.classList.remove('bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500');

        button.classList.add(statusColor);

        toggleDropdown(dropdownId, buttonId);
    }

</script>
