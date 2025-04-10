<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden">
            <h2 class="text-lg font-bold">Filtres</h2>

            <div class="mb-4">
                <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                <select id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                    <option value="all">Tous</option>
                    <option value="À planifier">À planifier</option>
                    <option value="En attente">En attente</option>
                    <option value="Fait">Fait</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="date-filter" class="block text-sm font-medium text-gray-700">Filtrer par date</label>
                <input type="date" id="date-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="mb-4">
                <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                <input type="text" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom">
            </div>

            <div class="mb-4">
                <label for="amount-filter" class="block text-sm font-medium text-gray-700">Filtrer par ID</label>
                <input type="number" id="amount-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="ID">
            </div>

            <div>
                <button id="reset-filters" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600">Réinitialiser les filtres</button>
            </div>
        </div>

        <!-- Liste des approvisionnements -->
        <div class="w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">ID Dépannage</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Date de création</th>
                        <th class="p-3 text-sm font-semibold tracking-wide text-left">Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($approvisionnements as $approvisionnement)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 text-sm text-gray-700">{{ $approvisionnement->depannage_id }}</td>
                            <td class="p-3 text-sm text-gray-700">{{ $approvisionnement->created_at->format('d/m/Y') }}</td>
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
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'À planifier', 'bg-red-500','status-{{ $approvisionnement->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">À planifier</li>
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'En attente', 'bg-yellow-500','status-{{ $approvisionnement->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">En attente</li>
                                    <li onclick="updateStatus('status-{{ $approvisionnement->id }}', 'Fait', 'bg-green-500','status-{{ $approvisionnement->id }}-btn')" class="hover:bg-gray-200 p-1 cursor-pointer">Fait</li>
                                </ul>
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
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    function updateStatus(dropdownId, newStatus, newBgClass, buttonId) {
        const button = document.getElementById(buttonId);
        const dropdown = document.getElementById(dropdownId);

        // Mise à jour du texte
        button.textContent = newStatus;

        // Mise à jour de la couleur de fond
        button.className = 'px-4 py-2 rounded-lg text-white ' + newBgClass;

        // Cacher le dropdown
        dropdown.classList.add('hidden');

        // Optionnel : appel AJAX pour sauvegarder côté serveur
    }
</script>
