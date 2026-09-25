const CACHE_NAME = 'bless-pwa-cache-v3';
const urlsToCache = [
  '/',
  '/frontend/css/style.css',
  '/backend/images/logo.png',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
      .catch(() => {
        // Silently continue if some external assets fail to cache
      })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          // Immediately purge old caches (including old sses-pwa-cache-v1)
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      return self.clients.claim();
    })
  );
});

self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // 1. NEVER intercept non-GET requests (POST, PUT, DELETE, etc.)
  if (event.request.method !== 'GET') {
    return;
  }

  // 2. CRITICAL: NEVER intercept or cache Admin, Auth, API, or Dashboard requests
  if (
    url.pathname.startsWith('/admin') ||
    url.pathname.startsWith('/login') ||
    url.pathname.startsWith('/logout') ||
    url.pathname.startsWith('/password') ||
    url.pathname.startsWith('/sanctum') ||
    url.pathname.startsWith('/api') ||
    url.pathname.startsWith('/telescope')
  ) {
    return; // Pass through to network directly
  }

  // 3. For HTML navigation requests, ALWAYS let browser fetch directly (never serve stale HTML)
  if (event.request.mode === 'navigate' || (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'))) {
    return;
  }

  // 4. For static public assets (CSS, JS, fonts, images), use Cache-First with Network fallback
  event.respondWith(
    caches.match(event.request).then(cachedResponse => {
      if (cachedResponse) {
        return cachedResponse;
      }

      return fetch(event.request).then(networkResponse => {
        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
          return networkResponse;
        }

        // Only cache same-origin static assets
        if (url.origin === self.location.origin) {
          const isStaticAsset = /\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|webp|ico|gif)$/i.test(url.pathname);
          if (isStaticAsset) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseToCache);
            });
          }
        }

        return networkResponse;
      });
    }).catch(() => {
      // Offline fallback
    })
  );
});
