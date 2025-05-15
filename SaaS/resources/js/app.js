import './bootstrap';

import Alpine from 'alpinejs';

import './loading.js';
import '../css/notifications.css';
import { saveNotificationBeforeReload } from './notifications';
window.saveNotificationBeforeReload = saveNotificationBeforeReload;

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(registration => {
            console.log('Service Worker enregistré avec succès:', registration);
        })
        .catch(error => {
            console.error('Erreur d’enregistrement du Service Worker:', error);
        });

    // Écouter l'événement beforeinstallprompt (proposition d'ajout à l'écran d'accueil)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // Empêche la boîte par défaut
        console.log('beforeinstallprompt event fired');
        // Tu peux stocker l'événement pour déclencher l'installation plus tard
        window.deferredPrompt = e;
        // Et afficher un bouton perso, etc.
    });

    window.addEventListener('appinstalled', () => {
        console.log('App installée avec succès !');
    });
}

