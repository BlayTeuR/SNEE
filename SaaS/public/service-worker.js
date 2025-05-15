self.addEventListener('install', event => {
    console.log('[Service Worker] Install event');
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    console.log('[Service Worker] Activate event');
});

self.addEventListener('fetch', event => {
    console.log('[Service Worker] Fetching:', event.request.url);
});
