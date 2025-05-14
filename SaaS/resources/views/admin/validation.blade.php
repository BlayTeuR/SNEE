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
                                <button onclick="openValideModal({{ $depannage->id }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Valide</button>
                                <button onclick="openNonValideModal({{ $depannage->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Non valide</button>
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

    <!-- Modal VALIDE -->
    <div id="valideModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Validation du dépannage</h2>
            <p class="mb-4 text-gray-700">Vous êtes sur le point de valider l'intervention et d'enregistrer celle-ci. Que souhaitez-vous faire ?</p>

            <form>
                <label class="block mb-2">
                    <input type="radio" name="valide_option" value="nouvelle_date" class="mr-2"
                           onclick="toggleModalDate(true, 'valide')"> Affecter une nouvelle date
                </label>
                <label class="block mb-2">
                    <input type="radio" name="valide_option" value="approvisionnement" class="mr-2"> Créer un approvisionnement
                </label>
                <label class="block mb-4">
                    <input type="radio" name="valide_option" value="facturer" class="mr-2"> Passer ce dépannage dans l’état « à facturer »
                </label>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeValideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <div id="create-date-modal" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-[9999]">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 relative z-[10000]">
            <h2 class="text-xl font-semibold mb-4">À quelle date voulez-vous associer ce dépannage ?</h2>
            <label for="date-create" class="block text-sm font-medium text-gray-700 mb-2">Choisir une date</label>
            <input type="date" name="date-create" id="date-create" class="block w-full mb-4 p-2 border border-gray-300 rounded-lg" value="{{ request('date-crate') }}">

            <div class="flex justify-end space-x-4">
                <button onclick="toggleModalDate(false, currentContext)" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</button>
                <button onclick="updateDate()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Valider</button>
            </div>
        </div>
    </div>

    <!-- Modal NON VALIDE -->
    <div id="nonValideModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Replanification du dépannage</h2>
            <p class="mb-4 text-gray-700">Que souhaitez-vous faire ?</p>

            <form>
                <label class="block mb-2">
                    <input type="radio" name="non_valide_option" value="nouvelle_date" class="mr-2"
                           onclick="toggleModalDate(true, 'nonValide')"> Affecter une nouvelle date
                </label>
                <label class="block mb-4">
                    <input type="radio" name="non_valide_option" value="ultérieurement" class="mr-2"> Affecter ultérieurement une date
                </label>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeNonValideModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentContext = null;

        function openValideModal() {
            document.getElementById('valideModal').classList.remove('hidden');
            currentContext = 'valide';
        }

        function openNonValideModal() {
            document.getElementById('nonValideModal').classList.remove('hidden');
            currentContext = 'nonValide';
        }

        function closeValideModal() {
            document.getElementById('valideModal').classList.add('hidden');
        }

        function closeNonValideModal() {
            document.getElementById('nonValideModal').classList.add('hidden');
        }

        function toggleModalDate(show, sourceModal) {
            const dateModal = document.getElementById('create-date-modal');

            if (show) {
                // Masquer le modal source
                if (sourceModal === 'valide') {
                    closeValideModal();
                } else if (sourceModal === 'nonValide') {
                    closeNonValideModal();
                }

                dateModal.classList.remove('hidden');
            } else {
                dateModal.classList.add('hidden');
                resetRadioButtons();

                // Réafficher le modal parent
                if (sourceModal === 'valide') {
                    document.getElementById('valideModal').classList.remove('hidden');
                } else if (sourceModal === 'nonValide') {
                    document.getElementById('nonValideModal').classList.remove('hidden');
                }
            }
        }

        function resetRadioButtons() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.checked = false;
            });
        }

        function updateDate() {
            const selectedDate = document.getElementById('date-create').value;
            console.log("Nouvelle date sélectionnée :", selectedDate);

            document.getElementById('create-date-modal').classList.add('hidden');
        }
    </script>

</x-app-layout>
