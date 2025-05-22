<x-app-layout>
    <div class="py-12">
        <div class="flex">
            <span class="m-2 text-red-500 sm:px-6 lg:px-8">
                Il est possible que certains dépannages ou entretiens ne s'affichent pas correctement sur la carte (adresse erronée lors de la saisie) ->
            </span>
            <button class="m-2 text-blue-500 hover:text-blue-600 hover:underline">
                Voir les adresses non placées
            </button>
        </div>
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-5 gap-6" style="height: 75vh;">
                        <!-- Colonne des informations à gauche -->
                        <div class="col-span-1 bg-gray-100 p-4 rounded-lg">
                            <h2 class="text-xl font-semibold mb-4">Informations du point</h2>
                            <div>
                                <p>Latitude: <span id="lat">51.505</span></p>
                                <p>Longitude: <span id="lng">-0.09</span></p>
                                <p>Description: <span id="description">Point intéressant sur la carte</span></p>
                            </div>
                        </div>

                        <!-- Colonne de la carte à droite -->
                        <div class="col-span-4">
                            <div id="map" style="height: 100%; min-height: 600px;"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Initialisation de la carte Leaflet -->
    <script>
        // Données passées depuis le contrôleur Laravel
        const depannages = @json($depannage);
        const entretiens = @json($entretien);

        // Affichage de tous les marqueurs sur la carte
        function placerMarqueurs() {
            for (let item of [...depannages, ...entretiens]) {
                // Si les coordonnées sont présentes
                if (item.latitude && item.longitude) {
                    const lat = parseFloat(item.latitude);
                    const lng = parseFloat(item.longitude);

                    const adresse = item.adresse ?? '';
                    const codePostal = item.code_postal ?? '';
                    const fullAdresse = `${adresse}, ${codePostal}`;

                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(`
                    <strong>${fullAdresse}</strong><br>
                    ${item.description ?? 'Aucune description'}
                `);
                } else {
                    console.warn('Coordonnées manquantes pour :', item);
                }
            }
        }


        // Initialisation de la carte Leaflet
        document.addEventListener("DOMContentLoaded", function () {
            // Centrer sur la France
            window.map = L.map('map').setView([46.603354, 1.888334], 6);

            // Fond de carte OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Lancer l'ajout des marqueurs
            placerMarqueurs();
        });
    </script>

</x-app-layout>
