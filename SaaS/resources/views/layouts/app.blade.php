<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <meta name="apple-mobile-web-app-title" content="Nom de ton app" />
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png" />
        <link rel="manifest" href="/manifest.json" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @include('vendor.laravelpwa.meta')

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="w-full">
                {{ $slot }}
            </main>
        </div>

        <!-- Mini barre de chargement -->
        <div id="progress-bar" class="fixed top-0 left-0 h-1 bg-blue-500 z-50 transition-width duration-600 ease-out w-0"></div>
        <!-- Spinner Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-gray-700 bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </div>


        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script>
            window.onerror = function(message, source, lineno, colno, error) {
                console.groupCollapsed("🛑 Erreur JavaScript capturée");
                console.log("📛 Message :", message);
                console.log("📄 Fichier :", source);
                console.log("📍 Ligne :", lineno + ":" + colno);
                console.log("💥 Erreur complète :", error);
                console.trace(); // Affiche la pile d'appels
                console.groupEnd();
            };

            // Intercepte toutes les erreurs dans les promesses (ex : fetch().then().catch())
            window.onunhandledrejection = function(event) {
                console.groupCollapsed("🚨 Rejection de promesse non gérée");
                console.log("💥 Raison :", event.reason);
                console.trace();
                console.groupEnd();
            };
            
                window.addEventListener("pageshow", function (event) {
                if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                location.reload();
            }
            });

        </script>

    </body>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
</html>
