<x-app-layout>
    <div class="py-10 px-6 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Statistiques des dépannages</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-gray-500 mb-2">Total des dépannages</h2>
                <p class="text-3xl font-semibold text-purple-600">{{$depannages->count()}}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-gray-500 mb-2">À planifier</h2>
                <p class="text-3xl font-semibold text-red-500">{{$depannages->where('statut', '=', 'À planifier')->count()}}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-gray-500 mb-2">En approvisionnement</h2>
                <p class="text-3xl font-semibold text-blue-500">{{$depannages->where('statut', '=', 'Approvisionnement')->count()}}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-gray-500 mb-2">Affectés</h2>
                <p class="text-3xl font-semibold text-yellow-500">{{$depannages->where('statut', '=', 'Affecter')->count()}}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-gray-500 mb-2">À facturer</h2>
                <p class="text-3xl font-semibold text-green-500">{{$depannages->where('statut', '=', 'À facturer')->count()}}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition par provenance</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex justify-between items-center border rounded-lg px-4 py-3">
                    <span class="text-gray-700">Client</span>
                    <span class="text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded-full">65</span>
                </div>
                <div class="flex justify-between items-center border rounded-lg px-4 py-3">
                    <span class="text-gray-700">Chargé d'affaire</span>
                    <span class="text-sm bg-green-100 text-green-700 px-2 py-1 rounded-full">42</span>
                </div>
                <div class="flex justify-between items-center border rounded-lg px-4 py-3">
                    <span class="text-gray-700">Ajout manuel</span>
                    <span class="text-sm bg-gray-100 text-gray-700 px-2 py-1 rounded-full">17</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 mt-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition par type de matériel</h2>
            <ul class="space-y-2">
                <li class="flex justify-between text-gray-700">
                    <span>Portail</span><span>55</span>
                </li>
                <li class="flex justify-between text-gray-700">
                    <span>Portillon</span><span>38</span>
                </li>
                <li class="flex justify-between text-gray-700">
                    <span>Barrière</span><span>31</span>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>
s
