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
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Votre demande a été enregistrée !</h1>
@vite('resources/js/app.js')

</body>
</html>
