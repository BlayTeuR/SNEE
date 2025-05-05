function showNotification(message, type = 'success') {
    console.log('showNotification called with message:', message, 'and type:', type); // Debugging line
    const notification = document.getElementById('notification');
    const messageSpan = document.getElementById('notification-message');

    messageSpan.textContent = message;

    // Retirer les anciennes classes de couleur et de visibilité
    notification.classList.remove('bg-green-500', 'bg-red-500', 'bg-yellow-500', 'hidden', 'notification-visible', 'notification-hidden');

    // Appliquer la couleur en fonction du type
    switch(type) {
        case 'success':
            notification.classList.add('bg-green-500');
            break;
        case 'error':
            notification.classList.add('bg-red-500');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-500');
            break;
    }

    // Retirer la classe "hidden" pour rendre la notification visible
    notification.classList.remove('hidden');
    notification.style.opacity = 0; // Assure-toi que l'opacité commence à 0

    // Appliquer les styles directement pour la transition
    setTimeout(() => {
        notification.classList.add('notification-visible');
        notification.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
        notification.style.opacity = 1; // Rendre la notification visible
        notification.style.transform = 'translateY(0)';
    }, 10); // Petite temporisation pour s'assurer que le DOM a bien réagi avant d'appliquer la transition

    // Masquer la notification après 3 secondes avec l'effet de disparition
    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
        notification.style.opacity = 0; // Rendre la notification invisible
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => {
            notification.classList.add('hidden'); // Masquer totalement la notification après la transition
        }, 500); // Attendre que l'animation de disparition soit terminée
    }, 3000); // Délai avant que la notification ne disparaisse
}

// Fonction pour mémoriser la notification avant de recharger la page
export function saveNotificationBeforeReload(message, type = 'success') {
    console.log('saveNotificationBeforeReload called with message:', message, 'and type:', type); // Debugging line
    const notificationData = {
        message: message,
        type: type
    };
    sessionStorage.setItem('notification', JSON.stringify(notificationData));
}

// Lecture de la notification stockée au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    const notificationData = sessionStorage.getItem('notification');
    if (notificationData) {
        const { message, type } = JSON.parse(notificationData);
        showNotification(message, type);
        sessionStorage.removeItem('notification'); // Enlève la notification après l'affichage
    }
});
