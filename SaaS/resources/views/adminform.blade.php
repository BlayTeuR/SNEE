<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de contact</title>

    {{-- Tailwind (via Vite) --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-200 min-h-screen flex flex-col items-center justify-center p-4 space-y-6">
<!-- Image (hors du formulaire) -->
<img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="h-50 w-auto">

<!-- Formulaire (dans un bloc à part) -->
<div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Formulaire de création d'un dépannage</h1>

    <form action="{{route('depannage.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nom client<span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email client<span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        @error('email')
        <div class="text-red-500 text-sm mt-1">
            {{ $message }}
        </div>
        @enderror
        <div class="mb-4">
            <label for="tel" class="block text-gray-700">Numéro de téléphone client<span class="text-red-500">*</span></label>
            <input type="tel" id="tel" name="tel" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="add" class="block text-gray-700">Adresse d'intervention client<span class="text-red-500">*</span></label>
            <input type="text" id="add" name="add" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Type de matériel <span class="text-red-500">*</span></label>
            <div class="flex space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="demande_type" value="portail" class="text-green-500" required>
                    <span>Portail</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="demande_type" value="portillon" class="text-green-500">
                    <span>Portillon</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="demande_type" value="barrière" class="text-green-500">
                    <span>Barrière</span>
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label for="panne" class="block text-gray-700">Panne rencontrée <span class="text-red-500">*</span></label>
            <textarea type="text" id="panne" name="panne" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required></textarea>
        </div>

        <div class="mb-4">
            <label for="elec" class="block text-gray-700">Message d'erreur sur la carte électronique</label>
            <input type="text" id="elec" name="elec" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="infos" class="block text-gray-700">Informations supplémentaires</label>
            <textarea id="infos" name="infos" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <div id="image-container" class="mb-4">
            <label for="image" class="block text-gray-700">Ajouter une image</label>
            <div class="flex items-center space-x-2">
                <input type="file" id="image" name="images[]" accept="image/*" class="mt-1 w-full">
                <button type="button" class="text-red-500 hidden" id="delete-img-btn">❌</button>
            </div>
        </div>

        <button type="button" id="add-image-btn" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600" style="display:none;">
            Ajouter une autre image
        </button>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Envoyer
        </button>
    </form>
</div>

@vite('resources/js/app.js')

<script>
    let addImageBtn = document.getElementById('add-image-btn');
    let imageContainer = document.getElementById('image-container');
    let deleteImgBtn = document.getElementById('delete-img-btn');
    let fileInput = document.getElementById('image');

    // Fonction pour afficher/masquer le bouton "Ajouter une autre image" et la croix
    function toggleDeleteButton() {
        if (fileInput.value) {
            // Si une image est téléchargée, montrer la croix et le bouton pour ajouter une autre image
            deleteImgBtn.style.display = 'inline-block';
            addImageBtn.style.display = 'inline-block';
        } else {
            // Sinon, cacher la croix et le bouton pour ajouter une autre image
            deleteImgBtn.style.display = 'none';
            addImageBtn.style.display = 'none';
        }
    }

    // Afficher ou masquer la croix et le bouton lorsque l'image est téléchargée
    fileInput.addEventListener('change', function() {
        toggleDeleteButton();
    });

    // Ajouter un champ de téléchargement d'image supplémentaire
    addImageBtn.addEventListener('click', function() {
        var newInputContainer = document.createElement('div');
        newInputContainer.classList.add('flex', 'items-center', 'space-x-2');

        var newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.name = 'images[]';
        newInput.accept = 'image/*';
        newInput.classList.add('mt-1', 'w-full');

        var newDeleteButton = document.createElement('button');
        newDeleteButton.type = 'button';
        newDeleteButton.classList.add('text-red-500');
        newDeleteButton.innerHTML = '❌';
        newDeleteButton.addEventListener('click', function() {
            newInputContainer.remove();
            checkIfEmpty();
        });

        newInputContainer.appendChild(newInput);
        newInputContainer.appendChild(newDeleteButton);
        imageContainer.appendChild(newInputContainer);

        checkIfEmpty();
    });

    // Vérifier si la liste d'images est vide pour masquer le bouton
    function checkIfEmpty() {
        if (imageContainer.querySelectorAll('input[type="file"]').length === 0) {
            addImageBtn.style.display = 'none';
        }
    }

    // Effacer l'image de la première entrée (croix)
    deleteImgBtn.addEventListener('click', function() {
        fileInput.value = ''; // Effacer le fichier
        toggleDeleteButton();  // Mettre à jour l'affichage
    });

    // Initialisation du formulaire
    toggleDeleteButton(); // Vérifier si un fichier est déjà sélectionné à l'initialisation
</script>

</body>
</html>
