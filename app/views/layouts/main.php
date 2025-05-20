<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RT Management System</title>
    <meta name="description" content="RT Environmental Management Information System">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4CAF50">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <?php echo $content; ?>
    
    <script src="/js/app.js"></script>
    <script src="/js/pwa.js"></script>
</body>
</html>
