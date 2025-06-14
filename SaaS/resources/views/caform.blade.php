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
    <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Formulaire de contact</h1>

    <form action="{{route('depannage.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_source" value="formulaire_ca">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nom <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        @error('email')
        <div class="text-red-500 text-sm mt-1">
            {{ $message }}
        </div>
        @enderror
        <div class="mb-4">
            <label for="tel" class="block text-gray-700">Numéro de téléphone <span class="text-red-500">*</span></label>
            <input type="tel" id="tel" name="tel" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4 relative">
            <label for="add" class="block text-gray-700">Adresse d'intervention <span class="text-red-500">*</span></label>
            <input type="text" id="add" name="add" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" autocomplete="off" required>
            <ul id="suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden"></ul>
        </div>

        <div class="mb-4 relative">
            <label for="add-code-postal" class="block text-gray-700">Code postal <span class="text-red-500">*</span></label>
            <input type="text" id="add-code-postal" name="add-code-postal" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" autocomplete="off" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Type de matériel <span class="text-red-500">*</span></label>
            <div class="flex flex-wrap space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="demande_type" value="portail" class="text-green-500" required>
                    <span>Portail</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="demande_type" value="tourniquet" class="text-green-500">
                    <span>Tourniquet</span>
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
            <label for="panne" class="block text-gray-700">Tâche(s) à effectuer <span class="text-red-500">*</span></label>
            <textarea id="panne" name="panne" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nécessite un plan de prévention ? <span class="text-red-500">*</span></label>
            <div class="flex space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="prevention" value="1" class="text-green-500" required>
                    <span>Oui</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="prevention" value="0" class="text-green-500">
                    <span>Non</span>
                </label>
            </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('add');
        const suggestionsBox = document.getElementById('suggestions');

        let debounceTimeout;

        input.addEventListener('input', function () {
            const query = input.value.trim();

            clearTimeout(debounceTimeout);
            if (query.length < 3) {
                suggestionsBox.classList.add('hidden');
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=15`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        data.features.forEach(feature => {
                            const li = document.createElement('li');
                            li.classList.add('px-4', 'py-2', 'cursor-pointer', 'hover:bg-gray-100');
                            li.textContent = feature.properties.label;

                            li.addEventListener('click', () => {
                                input.value = feature.properties.label;
                                suggestionsBox.classList.add('hidden');
                            });

                            suggestionsBox.appendChild(li);
                        });

                        if (data.features.length > 0) {
                            suggestionsBox.classList.remove('hidden');
                        } else {
                            suggestionsBox.classList.add('hidden');
                        }
                    });
            }, 300); // délai pour éviter trop d'appels à l'API
        });

        // Cacher les suggestions si on clique en dehors
        document.addEventListener('click', function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== input) {
                suggestionsBox.classList.add('hidden');
            }
        });
    });

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

    function resizeImage(file, maxWidth = 800, quality = 0.7) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            const reader = new FileReader();

            reader.onload = (e) => {
                img.src = e.target.result;
            };

            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = height * (maxWidth / width);
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        // Création d'un nouveau fichier compressé
                        const resizedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now(),
                        });
                        resolve(resizedFile);
                    },
                    'image/jpeg',
                    quality
                );
            };

            reader.onerror = error => reject(error);
            reader.readAsDataURL(file);
        });
    }

    async function handleFileInputChange(event) {
        const input = event.target;
        const files = Array.from(input.files);
        const resizedFiles = [];

        for (const file of files) {
            if (!file.type.startsWith('image/')) {
                resizedFiles.push(file); // garder les fichiers non-images tels quels
                continue;
            }
            try {
                const resizedFile = await resizeImage(file, 1024, 0.7);
                resizedFiles.push(resizedFile);
            } catch (err) {
                console.error('Erreur redimension image', err);
                resizedFiles.push(file); // fallback : garder fichier original
            }
        }

        // Créer un DataTransfer pour remplacer les fichiers de l'input (simulateur d’upload)
        const dataTransfer = new DataTransfer();
        resizedFiles.forEach(f => dataTransfer.items.add(f));

        input.files = dataTransfer.files;

        toggleDeleteButton(); // ta fonction pour afficher le bouton supprimer
    }

    // Attacher ce handler à chaque input de type file
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', handleFileInputChange);
    });

</script>

</body>
</html>
