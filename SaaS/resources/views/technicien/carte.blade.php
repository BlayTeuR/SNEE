@extends('layouts.technicien')

@section('content')
    <div class="min-h-screen flex flex-col">
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
        <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
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
            <div class="flex justify-end gap-2 mt-4">
                <div class="flex flex-wrap justify-end gap-2 mt-4">
                    <a id="btn-itineraire-google" href="#" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                        Google Maps
                    </a>
                    <a id="btn-itineraire-waze" href="#" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Waze
                    </a>
                    <a id="btn-itineraire-apple" href="#" target="_blank" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 text-sm">
                        Plans (Apple)
                    </a>
                    <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const depannages = @json($depannage);
        const entretiens = @json($entretien);

        document.addEventListener("DOMContentLoaded", function () {
            window.map = L.map('map').setView([46.603354, 1.888334], 6);

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
    </style>
@endsection
