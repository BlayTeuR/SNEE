@extends('layouts.technicien')

@section('content')
    <div class="min-h-screen flex flex-col">

        <div class="p-4 bg-white">
            <input type="text" id="search-input" placeholder="Rechercher un dépannage..."
                   class="w-full p-2 border border-gray-300 rounded" autocomplete="off" />
            <ul id="search-results" class="border border-gray-300 rounded mt-1 max-h-48 overflow-y-auto hidden bg-white z-50 absolute w-full"></ul>
        </div>

        <!-- Carte -->
        <div class="w-full md:w-4/5">
            <div class="map-wrapper">
                <div id="map" class="leaflet-map"></div>
            </div>
        </div>

        <!-- Légende -->
        <div class="p-4 bg-white md:h-screen overflow-y-auto border-t md:border-t-0 md:border-l text-sm text-gray-700">
            <p class="flex items-center"><span class="w-3 h-3 bg-blue-500 inline-block rounded-full mr-2"></span> Dépannage</p>
            <p class="flex items-center"><span class="w-3 h-3 bg-red-500 inline-block rounded-full mr-2"></span> Entretien</p>
        </div>
    </div>

    <!-- Modal -->
    <div id="info-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
        <div class="relative bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
            <!-- Croix de fermeture -->
            <button onclick="closeModal()"
                    class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-xl font-bold focus:outline-none">
                &times;
            </button>

            <h2 class="text-xl font-bold mb-4">Informations du point</h2>
            <div class="space-y-1 text-sm">
                <p><strong>Type :</strong> <span id="modal-type">—</span></p>
                <p><strong>Nom :</strong> <span id="modal-nom">—</span></p>
                <p><strong>Adresse :</strong> <span id="modal-adresse">—</span></p>
                <p><strong>Code Postal :</strong> <span id="modal-code_postal">—</span></p>
                <p><strong>Email :</strong> <span id="modal-contact_email">—</span></p>
                <p><strong>Téléphone :</strong> <span id="modal-telephone">—</span></p>
                <p><strong>Problème / Vigilance :</strong> <span id="modal-probleme_vigilance">—</span></p>
                <p><strong>Matériel :</strong> <span id="modal-type_materiel">—</span></p>
                <p><strong>Latitude :</strong> <span id="modal-lat">—</span></p>
                <p><strong>Longitude :</strong> <span id="modal-lng">—</span></p>
            </div>

            <div class="mt-6 px-2">
                <div class="grid grid-cols-1 gap-3">
                    <a id="btn-itineraire-google" href="#" target="_blank"
                       class="w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm text-center">
                        Google Maps
                    </a>
                    <a id="btn-itineraire-waze" href="#" target="_blank"
                       class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm text-center">
                        Waze
                    </a>
                    <a id="btn-itineraire-apple" href="#" target="_blank"
                       class="w-full px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 text-sm text-center">
                        Plans (Apple)
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script>
        const depannages = @json($depannage);
        const entretiens = @json($entretien);

        document.addEventListener("DOMContentLoaded", function () {

            window.map = L.map('map').setView([46.603354, 1.888334], 6);

            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');

            // Combiner dépannages et entretiens avec un type pour recherche
            const allPoints = [
                ...depannages.map((item, i) => ({ ...item, type: 'Dépannage', index: i })),
                ...entretiens.map((item, i) => ({ ...item, type: 'Entretien', index: i }))
            ];

            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                if (!query) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    return;
                }

                const filtered = allPoints.filter(item => item.nom && item.nom.toLowerCase().includes(query));

                if (filtered.length === 0) {
                    searchResults.innerHTML = '<li class="p-2 text-gray-500">Aucun résultat</li>';
                    searchResults.classList.remove('hidden');
                    return;
                }

                searchResults.innerHTML = filtered.map(item => `
            <li class="p-2 cursor-pointer hover:bg-blue-100" data-type="${item.type}" data-index="${item.index}">
                ${item.nom} <small class="text-gray-500">(${item.type})</small>
            </li>
        `).join('');
                searchResults.classList.remove('hidden');
            });

            // Gestion du clic sur un résultat de recherche
            searchResults.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (!li || li.classList.contains('text-gray-500')) return;

                const type = li.getAttribute('data-type');
                const index = parseInt(li.getAttribute('data-index'));
                const data = (type === 'Dépannage') ? depannages[index] : entretiens[index];

                if (data) {
                    openModal(data, type, parseFloat(data.latitude), parseFloat(data.longitude));
                    searchResults.classList.add('hidden');
                    searchInput.value = '';  // Reset input après sélection
                }
            });

            // Cacher le menu si on clique en dehors
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
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

            depannages.forEach((item, index) => {
                ajouterMarqueur(item, blueIcon, 'Dépannage', index);
            });
            entretiens.forEach((item, index) => {
                ajouterMarqueur(item, redIcon, 'Entretien', index);
            });

            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('btn-voir-plus')) {
                    const type = e.target.getAttribute('data-type');
                    const index = parseInt(e.target.getAttribute('data-index'));
                    let data = (type === 'Dépannage') ? depannages[index] : entretiens[index];
                    if (data) {
                        openModal(data, type, parseFloat(data.latitude), parseFloat(data.longitude));
                    }
                }
            });

            window.addEventListener('resize', () => {
                setTimeout(() => {
                    map.invalidateSize();
                }, 200);
            });
        });

        function ajouterMarqueur(item, icon, type, index) {
            if (item.latitude && item.longitude) {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                const marker = L.marker([lat, lng], { icon }).addTo(map);
                const popup = `
                    <strong>${item.nom ?? '—'}</strong><br>
                    ${item.adresse ?? ''}, ${item.code_postal ?? ''}<br>
                    <button class="btn-voir-plus mt-2 px-4 py-2 bg-blue-600 text-white rounded" data-type="${type}" data-index="${index}">
                        Voir +
                    </button>
                `;
                marker.bindPopup(popup);
            }
        }

        function openModal(data, type, lat, lng) {
            document.getElementById('modal-type').textContent = type;
            document.getElementById('modal-nom').textContent = data.nom ?? '—';
            document.getElementById('modal-adresse').textContent = data.adresse ?? '—';
            document.getElementById('modal-code_postal').textContent = data.code_postal ?? '—';
            document.getElementById('modal-contact_email').textContent = data.contact_email ?? '—';
            document.getElementById('modal-telephone').textContent = data.telephone ?? '—';
            document.getElementById('modal-type_materiel').textContent = data.type_materiel ?? '—';
            document.getElementById('modal-probleme_vigilance').textContent = type === 'Dépannage' ? (data.description_probleme ?? '—') : (data.panne_vigilance ?? '—');
            document.getElementById('modal-lat').textContent = lat.toFixed(6);
            document.getElementById('modal-lng').textContent = lng.toFixed(6);
            document.getElementById('info-modal').classList.remove('hidden');
            const itineraireUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            document.getElementById('btn-itineraire-google').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            document.getElementById('btn-itineraire-waze').href = `https://waze.com/ul?ll=${lat},${lng}&navigate=yes`;
            document.getElementById('btn-itineraire-apple').href = `http://maps.apple.com/?daddr=${lat},${lng}`;
        }

        function closeModal() {
            document.getElementById('info-modal').classList.add('hidden');
        }

        setTimeout(() => {
            map.invalidateSize();
        }, 500);

    </script>

    <style>
        .map-wrapper {
            position: relative; /* ou rien du tout si non nécessaire */
            width: 100%;
            height: 500px; /* ou autre hauteur souhaitée */
            z-index: 0;
        }

        .leaflet-map {
            width: 100%;
            height: 100%;
        }

        #search-results {
            position: absolute;
            background: white;
            list-style: none;
            margin: 0;
            padding: 0;
            border: 1px solid #d1d5db;
            max-height: 12rem;
            overflow-y: auto;
            z-index: 10000;
            width: 100%;
            box-sizing: border-box;
        }

        #search-results li {
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        #search-results li:hover {
            background-color: #bfdbfe;
        }

    </style>
@endsection
