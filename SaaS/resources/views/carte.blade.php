<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-5 gap-6">
                        <!-- Colonne des informations à gauche -->
                        <div class="col-span-1 bg-gray-100 p-4 rounded-lg">
                            <!-- Informations du point sur la carte -->
                            <h2 class="text-xl font-semibold mb-4">Informations du point</h2>
                            <div>
                                <p>Latitude: <span id="lat">51.505</span></p>
                                <p>Longitude: <span id="lng">-0.09</span></p>
                                <p>Description: <span id="description">Point intéressant sur la carte</span></p>
                            </div>
                        </div>

                        <!-- Colonne de la carte à droite -->
                        <div class="col-span-4">
                            <div id="map" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
