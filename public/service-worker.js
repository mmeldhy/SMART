// Service Worker for RT Management System
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
  "/img/icon-192x192.png",
  "/img/icon-512x512.png",
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
})

// Push event - handle push notifications
self.addEventListener("push", (event) => {
  if (event.data) {
    const data = event.data.json()

    const options = {
      body: data.body,
      icon: "/img/icon-192x192.png",
      badge: "/img/icon-192x192.png",
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
