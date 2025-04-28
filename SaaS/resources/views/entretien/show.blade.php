<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container-a4 bg-white overflow-hidden shadow-sm p-5 relative">
                <br>
                <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="absolute top-5 right-5 max-h-20 max-w-20">
                <h2 class="text-2xl font-bold mb-4 text-center">Détails du dépannage de {{$depannage->nom}} : #ID{{$depannage->id}}</h2>
            </div>
        </div>
    </div>

    <button id="download-pdf" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Télécharger en PDF</button>

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
