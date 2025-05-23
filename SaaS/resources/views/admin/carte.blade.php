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
                        <div class="col-span-1 bg-gray-100 p-4 rounded-lg flex flex-col justify-between">
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Informations du point</h2>
                                <div class="space-y-1 text-sm">
                                    <p><strong>Type :</strong> <span id="type">—</span></p>
                                    <p><strong>Nom :</strong> <span id="nom">—</span></p>
                                    <p><strong>Adresse :</strong> <span id="adresse">—</span></p>
                                    <p><strong>Code Postal :</strong> <span id="code_postal">—</span></p>
                                    <p><strong>Email :</strong> <span id="contact_email">—</span></p>
                                    <p><strong>Téléphone :</strong> <span id="telephone">—</span></p>
                                    <p><strong>Problème / Vigilance :</strong> <span id="probleme_vigilance">—</span></p>
                                    <p><strong>Matériel :</strong> <span id="type_materiel">—</span></p>
                                    <p><strong>Latitude :</strong> <span id="lat">—</span></p>
                                    <p><strong>Longitude :</strong> <span id="lng">—</span></p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t text-sm text-gray-700">
                                <p class="flex items-center"><span class="w-3 h-3 bg-blue-500 inline-block rounded-full mr-2"></span> Dépannage</p>
                                <p class="flex items-center"><span class="w-3 h-3 bg-red-500 inline-block rounded-full mr-2"></span> Entretien</p>
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

        // Initialisation de la carte Leaflet
        document.addEventListener("DOMContentLoaded", function () {
            // Centrer sur la France
            window.map = L.map('map').setView([46.603354, 1.888334], 6);

            // Fond de carte OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const blueIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // On place ici la fonction qui a besoin de blueIcon et redIcon
            function placerMarqueurs() {
                for (let item of depannages) {
                    ajouterMarqueur(item, blueIcon, 'Dépannage');
                }
                for (let item of entretiens) {
                    ajouterMarqueur(item, redIcon, 'Entretien');
                }
            }


            // Appeler après avoir défini les icônes
            placerMarqueurs();
        });

        function ajouterMarqueur(item, icon, type) {
            if (item.latitude && item.longitude) {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);

                const marker = L.marker([lat, lng], { icon }).addTo(map);

                // Petit texte dans le popup
                const popupText = `<strong>${item.nom ?? '—'}</strong><br>${item.adresse ?? ''}, ${item.code_postal ?? ''}`;

                marker.bindPopup(popupText);

                // Quand on clique sur le marker
                marker.on('click', () => {
                    document.getElementById('type').textContent = type;
                    document.getElementById('nom').textContent = item.nom ?? '—';
                    document.getElementById('adresse').textContent = item.adresse ?? '—';
                    document.getElementById('code_postal').textContent = item.code_postal ?? '—';
                    document.getElementById('contact_email').textContent = item.contact_email ?? '—';
                    document.getElementById('telephone').textContent = item.telephone ?? '—';
                    document.getElementById('type_materiel').textContent = item.type_materiel ?? '—';

                    // Différence selon le type
                    if (type === 'Dépannage') {
                        document.getElementById('probleme_vigilance').textContent = item.description_probleme ?? '—';
                    } else if (type === 'Entretien') {
                        document.getElementById('probleme_vigilance').textContent = item.panne_vigilance ?? '—';
                    }

                    document.getElementById('lat').textContent = lat.toFixed(6);
                    document.getElementById('lng').textContent = lng.toFixed(6);
                });
            }
        }

    </script>

</x-app-layout>
