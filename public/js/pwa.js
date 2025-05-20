// PWA functionality for RT Management System

// Register service worker
if ("serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    navigator.serviceWorker
      .register("/service-worker.js")
      .then((registration) => {
        console.log("ServiceWorker registration successful with scope: ", registration.scope)

        // Check for updates
        registration.addEventListener("updatefound", () => {
          const newWorker = registration.installing
          console.log("Service worker update found!")

          newWorker.addEventListener("statechange", () => {
            if (newWorker.state === "installed" && navigator.serviceWorker.controller) {
              // New service worker available, show update notification
              showUpdateNotification()
            }
          })
        })
      })
      .catch((error) => {
        console.error("ServiceWorker registration failed: ", error)
      })
  })
}

// Show update notification
function showUpdateNotification() {
  const notification = document.createElement("div")
  notification.className = "fixed bottom-0 left-0 right-0 bg-green-600 text-white p-4 flex justify-between items-center"
  notification.innerHTML = `
    <p>New version available! Click to update.</p>
    <button id="update-btn" class="bg-white text-green-600 px-4 py-2 rounded">Update</button>
  `

  document.body.appendChild(notification)

  document.getElementById("update-btn").addEventListener("click", () => {
    window.location.reload()
  })
}

// Request notification permission
function requestNotificationPermission() {
  if ("Notification" in window) {
    Notification.requestPermission().then((permission) => {
      if (permission === "granted") {
        console.log("Notification permission granted")
        subscribeToPushNotifications()
      }
    })
  }
}

// Subscribe to push notifications (simulation)
function subscribeToPushNotifications() {
  console.log("Subscribed to push notifications (simulation)")
  // In a real app, this would register with a push service
  // and send the subscription to the server
}

// Show notification (simulation)
function showNotification(title, body, url = "/") {
  if ("Notification" in window && Notification.permission === "granted") {
    const notification = new Notification(title, {
      body: body,
      icon: "/img/icon-192x192.png",
    })

    notification.onclick = () => {
      window.open(url)
    }
  }
}

// Check if app is installed
window.addEventListener("DOMContentLoaded", () => {
  // Add install button if app can be installed
  window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault()
    const installBtn = document.getElementById("install-btn")

    if (installBtn) {
      installBtn.classList.remove("hidden")

      installBtn.addEventListener("click", () => {
        e.prompt()
        e.userChoice.then((choiceResult) => {
          if (choiceResult.outcome === "accepted") {
            console.log("User accepted the install prompt")
            installBtn.classList.add("hidden")
          }
        })
      })
    }
  })

  // Request notification permission when user clicks allow notifications
  const notificationBtn = document.getElementById("notification-btn")
  if (notificationBtn) {
    notificationBtn.addEventListener("click", requestNotificationPermission)
  }

  // Check if app is in standalone mode (installed)
  if (window.matchMedia("(display-mode: standalone)").matches) {
    document.body.classList.add("pwa-installed")
  }
})

// Offline/online status handling
window.addEventListener("online", () => {
  document.body.classList.remove("offline")
  document.body.classList.add("online")

  const offlineAlert = document.getElementById("offline-alert")
  if (offlineAlert) {
    offlineAlert.classList.add("hidden")
  }

  // Sync data when coming back online
  syncData()
})

window.addEventListener("offline", () => {
  document.body.classList.remove("online")
  document.body.classList.add("offline")

  const offlineAlert = document.getElementById("offline-alert")
  if (offlineAlert) {
    offlineAlert.classList.remove("hidden")
  }
})

// Sync data when coming back online (simulation)
function syncData() {
  // In a real app, this would sync cached data with the server
  console.log("Syncing data after coming back online")
}

// Cache form submissions when offline
function cacheFormSubmission(formData, endpoint) {
  if (!navigator.onLine) {
    // Store in localStorage for later submission
    const cachedSubmissions = JSON.parse(localStorage.getItem("cachedSubmissions") || "[]")
    cachedSubmissions.push({
      endpoint: endpoint,
      data: formData,
      timestamp: new Date().getTime(),
    })

    localStorage.setItem("cachedSubmissions", JSON.stringify(cachedSubmissions))
    return true
  }

  return false
}

// Process cached submissions when online
function processCachedSubmissions() {
  if (navigator.onLine) {
    const cachedSubmissions = JSON.parse(localStorage.getItem("cachedSubmissions") || "[]")

    if (cachedSubmissions.length > 0) {
      // Process each submission
      // In a real app, this would send the data to the server
      console.log("Processing cached submissions:", cachedSubmissions)

      // Clear cached submissions
      localStorage.setItem("cachedSubmissions", "[]")
    }
  }
}

// Check for cached submissions when coming online
window.addEventListener("online", processCachedSubmissions)
