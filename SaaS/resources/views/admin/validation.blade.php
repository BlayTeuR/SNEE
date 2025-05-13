<x-app-layout>
    <div class="flex bg-gray-200 p-4 space-x-4 overflow-hidden" style="height: calc(100vh - 6rem);">
        <!-- Filtres -->
        <div class="w-1/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden">

            <form method="GET" action="{{ route('admin.validation') }}">
                <h2 class="text-lg font-bold">Filtres</h2>

                <!-- Filtrer par type -->
                <div class="mb-4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Filtrer par statut</label>
                    <select name="type" id="status-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg">
                        <option value="depannage" {{ request('type') == 'depannage' || !request('type') ? 'selected' : '' }}>Dépannage</option>
                        <option value="entretiens" {{ request('type') == 'entretiens' ? 'selected' : '' }}>Entretien</option>
                    </select>
                </div>

                <!-- Nom -->
                <div class="mb-4">
                    <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par nom</label>
                    <input type="text" id="name-filter" name="nom" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Nom" value="{{ request('nom') }}">
                </div>

                <!-- Jour courant -->
                <div class="mb-4 flex items-center">
                    <label for="jour_courant" class="block text-sm font-medium text-gray-700 mr-4">Intervention du {{ \Carbon\Carbon::parse(today())->format('d/m/Y') }} uniquement</label>
                    <label for="jour_courant" class="inline-flex relative items-center cursor-pointer">
                        <!-- Champ caché pour forcer la valeur "off" si décoché -->
                        <input type="hidden" name="jour_courant" value="off">

                        <input type="checkbox" id="jour_courant" name="jour_courant" value="on" class="sr-only peer"
                               @if(request()->get('jour_courant', 'on') == 'on') checked @endif>
                        <span class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 peer-checked:dark:bg-blue-600"></span>
                        <span class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></span>
                    </label>
                </div>

                <!-- Boutons -->
                <div>
                    <a href="{{ route('admin.validation') }}" class="w-full bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 text-center block">Réinitialiser les filtres</a>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 mt-4">Appliquer les filtres</button>
                </div>
            </form>

        </div>

        <!-- Liste des interventions -->
        <div class="w-5/6 bg-white p-4 rounded-lg shadow-sm overflow-hidden flex flex-col">
            <!-- Contenu scrollable -->
            <div class="flex-1 overflow-auto">
                @if($type == 'depannage')
                    @foreach($depannages as $depannage)
                        <div class="bg-gray-100 p-4 mb-4 rounded-lg shadow-sm flex items-center justify-between">
                            <!-- Infos à gauche -->
                            <div>
                                <h3 class="text-lg font-bold">{{ $depannage->nom }}</h3>
                                <p class="text-gray-600">Adresse: {{ $depannage->adresse }}</p>
                                <p class="text-gray-600">Date: {{ \Carbon\Carbon::parse($depannage->date_depannage)->format('d/m/Y') }}</p>
                            </div>

                            <!-- Boutons à droite -->
                            <div class="flex space-x-2">
                                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Valide</button>
                                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Non valide</button>
                            </div>
                        </div>

                    @endforeach
                @elseif($type == 'entretiens')
                    @foreach($entretiens as $entretien)
                        <div class="bg-gray-100 p-4 mb-4 rounded-lg shadow-sm flex items-center justify-between">
                            <!-- Infos à gauche -->
                            <div>
                                <h3 class="text-lg font-bold">{{ $entretien->nom }}</h3>
                                <p class="text-gray-600">Date: {{ $entretien->adresse }}</p>
                                <p class="text-gray-600">Statut: {{ $entretien->derniere_date }}</p>
                            </div>

                            <!-- Boutons à droite -->
                            <div class="flex space-x-2">
                                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Valider</button>
                                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Annuler</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</x-app-layout>

<script>

</script>
