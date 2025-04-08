<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Conteneur pour la carte Leaflet -->
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajouter d'abord le script Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Initialisation de la carte Leaflet -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([51.505, -0.09], 13);

            // Ajouter un fond de carte
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Ajouter un marqueur
            L.marker([51.5, -0.09]).addTo(map)
                .bindPopup("Hello World!")
                .openPopup();
        });
    </script>
</x-app-layout>
