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

            <!-- Filtre par lieu -->
            <div class="mb-4">
                <label for="name-filter" class="block text-sm font-medium text-gray-700">Filtrer par lieu</label>
                <input type="text" id="name-filter" class="block w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Lieu">
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
                    <tr class="hover:bg-gray-200">
                        <td class="p-3 text-sm text-gray-700">
                            Bastien Jallais
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            13 route des Molières 88100
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            bastjals@gmail.com
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button onclick="toggleDropdown('historique-1')" class="bg-gray-300 bg-opacity-50 rounded-lg">Afficher Historique</button>
                            <ul id="historique-1" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li>Dépannage 1</li>
                                <li>Dépannage 2</li>
                                <li>Dépannage 3</li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button id="status-1-btn" onclick="toggleDropdown('status-1', 'status-1-btn')" class="bg-gray-200 px-4 py-2 rounded-lg bg">
                                Choisir un statut
                            </button>
                            <ul id="status-1" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li onclick="updateStatus('status-1', 'À planifier', 'bg-red-500', 'status-1-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span> À planifier
                                </li>
                                <li onclick="updateStatus('status-1', 'Affecter', 'bg-orange-500', 'status-1-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-2"></span> Affecter
                                </li>
                                <li onclick="updateStatus('status-1', 'Approvisionnement', 'bg-blue-500', 'status-1-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Approvisionnement
                                </li>
                                <li onclick="updateStatus('status-1', 'À facturer', 'bg-green-500', 'status-1-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span> À facturer
                                </li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Voir plus</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-200">
                        <td class="p-3 text-sm text-gray-700">
                            John Doe
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            10 rue du Placieux, 54000
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            johndoe@gmail.com
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button onclick="toggleDropdown('historique-2')" class="bg-gray-300 bg-opacity-50 rounded-lg">Afficher Historique</button>
                            <ul id="historique-2" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li>Aucun dépannage</li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button id="status-2-btn" onclick="toggleDropdown('status-2', 'status-2-btn')" class="bg-gray-200 px-4 py-2 rounded-lg">
                                Choisir un statut
                            </button>
                            <ul id="status-2" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li onclick="updateStatus('status-2', 'À planifier', 'bg-red-500', 'status-2-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span> À planifier
                                </li>
                                <li onclick="updateStatus('status-2', 'Affecter', 'bg-orange-500', 'status-2-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-2"></span> Affecter
                                </li>
                                <li onclick="updateStatus('status-2', 'Approvisionnement', 'bg-blue-500', 'status-2-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Approvisionnement
                                </li>
                                <li onclick="updateStatus('status-2', 'À facturer', 'bg-green-500', 'status-2-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span> À facturer
                                </li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Voir plus</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-200">
                        <td class="p-3 text-sm text-gray-700">
                            Maximus
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            4 rue des feuilles, 77800
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            max@gmail.com
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button onclick="toggleDropdown('historique-3')" class="bg-gray-300 bg-opacity-50 rounded-lg">Afficher Historique</button>
                            <ul id="historique-3" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li>Dépannage 1</li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <button id="status-3-btn" onclick="toggleDropdown('status-3', 'status-3-btn')" class="bg-gray-200 px-4 py-2 rounded-lg">
                                Choisir un statut
                            </button>
                            <ul id="status-3" class="hidden absolute bg-gray-100 p-2 mt-2 rounded shadow-md z-10">
                                <li onclick="updateStatus('status-3', 'À planifier', 'bg-red-500', 'status-3-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span> À planifier
                                </li>
                                <li onclick="updateStatus('status-3', 'Affecter', 'bg-orange-500', 'status-3-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-2"></span> Affecter
                                </li>
                                <li onclick="updateStatus('status-3', 'Approvisionnement', 'bg-blue-500', 'status-3-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Approvisionnement
                                </li>
                                <li onclick="updateStatus('status-3', 'À facturer', 'bg-green-500', 'status-3-btn')" class="hover:bg-gray-200">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span> À facturer
                                </li>
                            </ul>
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Voir plus</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
