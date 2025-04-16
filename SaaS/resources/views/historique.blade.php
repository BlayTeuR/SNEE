<x-app-layout>
    <div class="flex flex-col md:flex-row bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <div class="w-full md:w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden mb-4 md:mb-0">

            <form method="GET" action="{{ route('historique') }}">
                <!-- Filtres -->
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par type -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="type" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="facturation" {{ request('type') == 'facturation' ? 'selected' : '' }}>Facturation</option>
                        <option value="approvisionnement" {{ request('type') == 'approvisionnement' ? 'selected' : '' }}>Approvisionnement</option>
                        <option value="depannage" {{ request('type') == 'depannage' || !request('type') ? 'selected' : '' }}>Dépannage</option>
                        <option value="Entretien" {{ request('type') == 'Entretien' ? 'selected' : '' }}>Entretien</option>
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
                    <a href="{{ route('historique') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>

                <!-- Bouton pour appliquer les filtres -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>
        </div>

        <!-- table des historiques -->
        <div class="w-full md:w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            @if($type == 'Facturation')
                <h2 class="text-lg font-bold">Historique des facturations</h2>
            @elseif($type == 'Approvisionnement')
                <h2 class="text-lg font-bold">Historique des approvisionnements</h2>
            @elseif($type == 'Dépannage')
                <h2 class="text-lg font-bold">Historique des dépannages</h2>
            @endif
            <div class="flex-1 overflow-auto">
                <table class="w-full table-fixed">

                </table>
            </div>
        </div>
    </div>
</x-app-layout>


<script>

</script>

<style>

</style>
