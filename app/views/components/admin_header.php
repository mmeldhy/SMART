<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RT Management System - Admin</title>
    <meta name="description" content="RT Environmental Management Information System">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4CAF50">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <script>
        // Check if service worker is supported
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(error => {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div id="app">
        <!-- Offline indicator -->
        <div id="offline-indicator" class="offline-indicator hidden">
            You are currently offline. Some features may be limited.
        </div>
        
        <!-- Mobile header -->
        <div class="bg-green-600 text-white md:hidden">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center">
                    <button id="mobile-menu-button" class="mr-2 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="font-semibold text-xl">RT Management</span>
                </div>
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center focus:outline-none">
                        <span class="mr-2 hidden sm:inline-block"><?php echo $_SESSION['name'] ?? 'Admin'; ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                        <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                        <a href="/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                    </div>
                </div>
            </div>
        </div>
