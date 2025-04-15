<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de contact</title>

    {{-- Tailwind (via Vite) --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<!-- Conteneur principal avec un alignement vertical -->
<div class="flex flex-col items-center justify-center space-y-8">
    <!-- Image (au-dessus du formulaire) -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="h-40 w-auto">

    <!-- Formulaire (dans un bloc à part) -->
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-4xl space-y-6">
        <!-- Titre de la section -->
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Votre demande a été enregistrée avec succès !</h1>

        <!-- Message de succès -->
        <div class="bg-green-100 text-green-800 p-4 rounded-lg border border-green-300">
            <p class="text-lg">Merci, votre demande a été enregistrée. Nous vous contacterons sous peu.</p>
        </div>

        <!-- Bouton de retour ou autre action -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('caform') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">
                Retour au formulaire
            </a>
        </div>
    </div>
</div>

{{-- Scripts --}}
@vite('resources/js/app.js')
</body>
</html>
