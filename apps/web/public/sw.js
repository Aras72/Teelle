const CACHE_VERSION = 'teelle-static-v2';
const OFFLINE_URL = '/offline.html';
const ASSET_INDEX_URL = '/teelle-asset-index.txt';
const PRECACHE = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/images/marbles/heartbeat-cobalt-v1.webp',
    '/images/marbles/jigari-ruby-v1.webp',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION).then((cache) => cache.addAll(PRECACHE).then(() => {
            const deployedVersion = new URLSearchParams(self.location.search).get('v') ?? '0';

            return cache.put(ASSET_INDEX_URL, new Response(deployedVersion));
        })),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_VERSION).map((key) => caches.delete(key))))
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data?.type === 'CLEAR_CACHES') {
        event.waitUntil(caches.keys().then((keys) => Promise.all(keys.map((key) => caches.delete(key)))));
    }
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin || url.pathname.startsWith('/api/')) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
        return;
    }

    const cacheableDestinations = new Set(['style', 'script', 'font', 'image']);
    if (!cacheableDestinations.has(request.destination)) {
        return;
    }

    event.respondWith(
        caches.match(request).then((cached) => {
            const network = fetch(request)
                .then((response) => {
                    if (response.ok && response.type === 'basic') {
                        const copy = response.clone();
                        caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy));
                    }

                    return response;
                })
                .catch((error) => cached ?? Promise.reject(error));

            return cached ?? network;
        }),
    );
});

// تازه‌سازی پس‌زمینه‌ی کش استاتیک؛ پاسخ همین درخواست دست‌نخورده باقی می‌ماند.
self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET' || request.mode === 'navigate') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    const cacheableDestinations = new Set(['style', 'script', 'font', 'image']);
    if (!cacheableDestinations.has(request.destination)) {
        return;
    }

    event.respondWith(fetch(request).then((response) => {
        if (response.ok && response.type === 'basic') {
            const copy = response.clone();
            caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy));
        }

        return response;
    }).catch(() => caches.match(request)));
});
