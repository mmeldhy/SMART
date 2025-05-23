const CACHE_NAME = "rt-management-v1"
const OFFLINE_URL = "/offline.html"

// Assets to cache
const ASSETS_TO_CACHE = [
  "/",
  "/offline.html",
  "/css/tailwind.min.css",
  "/js/app.js",
  "/js/pwa.js",
  "/img/logo.png",
  "/img/tes.png",
  "/img/tes.png",
  "/manifest.json",
]

// Install event - cache assets
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches
      .open(CACHE_NAME)
      .then((cache) => {
        console.log("Opened cache")
        return cache.addAll(ASSETS_TO_CACHE)
      })
      .then(() => self.skipWaiting()),
  )
})

// Activate event - clean up old caches
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames
            .filter((cacheName) => {
              return cacheName !== CACHE_NAME
            })
            .map((cacheName) => {
              return caches.delete(cacheName)
            }),
        )
      })
      .then(() => self.clients.claim()),
  )
})

// Fetch event - serve from cache or network
self.addEventListener("fetch", (event) => {
  // Skip cross-origin requests
  if (event.request.url.startsWith(self.location.origin)) {
    // Handle image requests with cache-first strategy
    if (event.request.url.match(/\.(png|jpg|jpeg|gif|svg)$/)) {
      event.respondWith(
        caches.match(event.request).then(response => {
          return response || fetch(event.request).then(fetchResponse => {
            return caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, fetchResponse.clone());
              return fetchResponse;
            });
          });
        })
      );
      return; // Prevent other fetch handlers from interfering
    }

    const url = new URL(event.request.url);

    // Define routes that should use stale-while-revalidate
    const dynamicRoutes = [
      '/admin/announcements', '/admin/fees', '/admin/payments', '/admin/reports', '/admin/schedules',
      '/announcements', '/fees', '/schedules', '/reports'
    ];

    let useStaleWhileRevalidate = false;
    for (const route of dynamicRoutes) {
      if (url.pathname.startsWith(route)) {
        useStaleWhileRevalidate = true;
        break;
      }
    }

    if (useStaleWhileRevalidate) {
      event.respondWith(
        caches.open(CACHE_NAME).then(async (cache) => {
          const cachedResponse = await cache.match(event.request);
          const fetchPromise = fetch(event.request).then((networkResponse) => {
            if (networkResponse.ok) {
              cache.put(event.request, networkResponse.clone());
            }
            return networkResponse;
          }).catch((error) => {
            // Fallback to cached response if network fails
            return cachedResponse;
          });

          return cachedResponse || fetchPromise;
        })
      );
    } else {
      // Fallback to existing cache-first strategy for other assets
      event.respondWith(
        caches.match(event.request).then((response) => {
          // Cache hit - return response
          if (response) {
            return response
          }

          // Clone the request
          const fetchRequest = event.request.clone()

          return fetch(fetchRequest)
            .then((response) => {
              // Check if valid response
              if (!response || response.status !== 200 || response.type !== "basic") {
                return response
              }

              // Clone the response
              const responseToCache = response.clone()

              // Cache the fetched response
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(event.request, responseToCache)
              })

              return response
            })
            .catch(() => {
              // Network failed, serve offline page for HTML requests
              if (event.request.headers.get("Accept").includes("text/html")) {
                return caches.match(OFFLINE_URL)
              }
            })
        }),
      )
    }
  }
})

// Push event - handle push notifications
self.addEventListener("push", (event) => {
  if (event.data) {
    const data = event.data.json()

    const options = {
      body: data.body,
      icon: "/img/tespng",
      badge: "/img/tes.png",
      data: {
        url: data.url,
      },
    }

    event.waitUntil(self.registration.showNotification(data.title, options))
  }
})

// Notification click event - open the app
self.addEventListener("notificationclick", (event) => {
  event.notification.close()

  if (event.notification.data && event.notification.data.url) {
    event.waitUntil(clients.openWindow(event.notification.data.url))
  } else {
    event.waitUntil(clients.openWindow("/"))
  }
})

// Background sync event - process cached form submissions
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-form-data') {
    event.waitUntil(processCachedSubmissionsInBackground());
  }
});

async function processCachedSubmissionsInBackground() {

  if (!self.navigator.onLine) {
    console.log('Still offline, cannot process cached submissions yet.');
    return;
  }

  const cachedSubmissions = JSON.parse(localStorage.getItem("cachedSubmissions") || "[]");
  if (cachedSubmissions.length === 0) {
    console.log('No cached submissions to process.');
    return;
  }

  console.log('Processing cached submissions in background:', cachedSubmissions);

  const failedSubmissions = [];
  for (const submission of cachedSubmissions) {
    try {
      const response = await fetch(submission.endpoint, {
        method: submission.method,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(submission.data).toString()
      });

      if (response.ok) {
        console.log('Cached submission sent successfully:', submission);
        self.registration.showNotification('Data Berhasil Dikirim', {
          body: `Laporan atau pembayaran Anda yang tertunda berhasil dikirim.`,
          icon: '/img/tes.png',
        });
      } else {
        console.error('Failed to send cached submission:', submission, response.statusText);
        failedSubmissions.push(submission);
      }
    } catch (error) {
      console.error('Error sending cached submission:', submission, error);
      failedSubmissions.push(submission);
    }
  }
  localStorage.setItem("cachedSubmissions", JSON.stringify(failedSubmissions));
}
