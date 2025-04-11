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
        <div class="w-3/4 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">

            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr class="bg-gray-50">
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Nom</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Date d'émission</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Montant</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Statut</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Contact</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="hover:bg-gray-200">
                        <td class="p-3 text-sm text-gray-700">
                            Bastien Jallais
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            14/11/2024
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            150€
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button onclick="toggleDropdown('status-1')" class="bg-gray-300 bg-opacity-50 rounded-lg">Statut</button>
                            <ul id="status-1" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li>Non payé</li>
                                <li>Payé</li>
                                <li>En attente</li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            bastjals@gmail.com
                        </td>
                    </tr>
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
</script>
