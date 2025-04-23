function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const messageSpan = document.getElementById('notification-message');

    messageSpan.textContent = message;

    notification.classList.remove('bg-green-500', 'bg-red-500', 'bg-yellow-500', 'hidden');

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

    notification.style.opacity = 1;
    setTimeout(() => {
        notification.style.opacity = 0;
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 300);
    }, 3000);
}
