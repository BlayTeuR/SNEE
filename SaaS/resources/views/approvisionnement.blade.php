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
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">Pièces</th>
                        <th class="p-3 tewt-sm font-semibold tracking-wide text-left">ID depannage</th>
                    </tr>
                    </thead>
                    <tbody>

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
