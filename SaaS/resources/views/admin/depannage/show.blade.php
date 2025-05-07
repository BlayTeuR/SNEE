<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container-a4 bg-white overflow-hidden shadow-sm p-5 relative">
                <br>
                <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="absolute top-5 right-5 max-h-20 max-w-20">
                <h2 class="text-2xl font-bold mb-4 text-center">Détails du dépannage de {{$depannage->nom}} : #ID{{$depannage->id}}</h2>
                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Information sur le client</h3>
                    <p class="text-xs"><strong>Nom:</strong> {{ $depannage->nom }}</p>
                    <p class="text-xs"><strong>Adresse:</strong> {{ $depannage->adresse }}</p>
                    <p class="text-xs"><strong>Contact:</strong> {{ $depannage->contact_email }}</p>
                    <p class="text-xs"><strong>Téléphone</strong> {{ $depannage->telephone  }}</p>
                    <p class="text-xs"><strong>Type de client:</strong> {{$depannage->types->contrat}}, {{$depannage->types->garantie}}</p>
                    <p class="text-xs"><strong>Statut:</strong> {{ $depannage->statut }}</p>
                </div>
                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Description du problème</h3>
                    <p class="text-xs"><strong>Date d'intervention</strong>
                    @if($depannage->date_depannage == null)
                        : pas encore planifiée
                        @else
                            {{ \Carbon\Carbon::parse($depannage->date_depannage)->format('d/m/Y') }}
                        @endif
                    </p>
                    <p class="text-xs"><strong>Historique:</strong></p>
                    <ul>
                        @if($depannage->historiques->isNotEmpty())
                            @foreach($depannage->historiques as $histo)
                                <li> - {{ \Carbon\Carbon::parse($histo->date)->format('d/m/Y') }}</li>
                            @endforeach
                        @else
                            <li class="text-xs">Aucun historique disponible.</li>
                        @endif
                    </ul>
                    <p class="text-xs"><strong>Type de matériel:</strong> {{ $depannage->type_materiel}}</p>
                    <p class="text-xs"><strong>Panne rencontrée:</strong> {{ $depannage->description_probleme}}</p>
                    <p class="text-xs"><strong>Message d'erreur sur la carte électronique:</strong> {{ $depannage->message_erreur}}</p>
                    <p class="text-xs"><strong>Informations supplémentaires:</strong> {{ $depannage->infos_suppementaires}}</p>
                </div>

                <h3 class="text-xl font-semibold mb-4">Photo(s)</h3>
                @if($depannage->photos->count() > 0)
                    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($depannage->photos as $photo)
                            <li class="mb-4">
                                <img src="{{ asset('images/' . $photo->chemin_photo) }}"
                                     alt="Photo du dépannage"
                                     class="max-w-full max-h-64 object-cover" />
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs">Aucune photo disponible.</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const { jsPDF } = window.jspdf;

            document.getElementById('download-pdf').addEventListener('click', function() {
                const content = document.querySelector('.container-a4'); // Contenu à capturer

                // Capture avec html2canvas
                html2canvas(content, {
                    scale: 2,
                    useCORS: true,
                    logging: true,
                }).then(function(canvas) {
                    // Créer un PDF avec jsPDF
                    const doc = new jsPDF();
                    const imgData = canvas.toDataURL('image/png');
                    doc.addImage(imgData, 'PNG', 10, 10); // Ajouter l'image au PDF
                    doc.save('depannage_details.pdf'); // Sauvegarder le PDF
                }).catch(function(error) {
                    console.error('Erreur lors de la génération du PDF :', error);
                });
            });
        });

    </script>

    <style>
        .container-a4 {
            width: 210mm; /* Largeur du format A4 */
            height: 297mm; /* Hauteur du format A4 */
            margin: 0 auto;
            padding: 20mm; /* Espacement intérieur */
            background: #fff; /* Fond blanc */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombre pour simuler l'impression */
            overflow: auto; /* Si le contenu dépasse, afficher les barres de défilement */
            display: block;
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</x-app-layout>
