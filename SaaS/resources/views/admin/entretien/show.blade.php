<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container-a4 bg-white overflow-hidden shadow-sm p-5 relative">
                <br>
                <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="absolute top-5 right-5 max-h-20 max-w-20">
                <h2 class="text-2xl font-bold mb-4 text-center">Détails de l'entretien de {{$entretien->nom}} : #ID{{$entretien->id}}</h2>
                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Information sur le client</h3>
                    <p class="text-xs"><strong>Nom:</strong> {{ $entretien->nom }}</p>
                    <p class="text-xs"><strong>Code postal:</strong> {{ $entretien->code_postal }}</p>
                    <p class="text-xs"><strong>Adresse:</strong> {{ $entretien->adresse }}</p>
                    <p class="text-xs"><strong>Contact:</strong> {{ $entretien->contact_email }}</p>
                    <p class="text-xs"><strong>Téléphone</strong> {{ $entretien->telephone  }}</p>
                </div>

                <div class="border border-black p-4 mb-4">
                    <h3 class="font-semibold mb-4">Description du problème</h3>
                    <p class="text-xs"><strong>Date de la prochaine intervention</strong>
                        @if($entretien->derniere_date == null)
                            : pas encore planifiée
                        @else
                            {{ \Carbon\Carbon::parse($entretien->derniere_date)->format('d/m/Y') }}
                        @endif
                    </p>
                    <p class="text-xs"><strong>Historique:</strong></p>
                    <ul>
                        @if($entretien->historiques->isNotEmpty())
                            @foreach($entretien->historiques as $histo)
                                <li class="text-xs"> - {{ \Carbon\Carbon::parse($histo->date)->format('d/m/Y') }}</li>
                            @endforeach
                        @else
                            <li class="text-xs">Aucun historique disponible.</li>
                        @endif
                    </ul>
                    <p class="text-xs"><strong>Type de matériel:</strong> {{ $entretien->type_materiel}}</p>
                    <p class="text-xs"><strong>Panne et vigilance:</strong> {{ $entretien->panne_vigilance}}</p>
                    <p class="text-xs"><strong>Commentaires :</strong> ... </p>
                </div>

                <h3 class="text-xl font-semibold mb-4">Photo(s)</h3>
                @if($entretien->photos->count() > 0)
                    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($entretien->photos as $photo)
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
