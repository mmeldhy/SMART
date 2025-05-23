<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['announcement']['title']) ?> - SMART</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4CAF50">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include BASE_PATH . '/app/views/components/resident_header.php'; ?>
    
    <main class="flex-1 p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Pengumuman</h1>
                <a href="/announcements" class="text-green-600 hover:text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($data['announcement']['title']) ?></h2>
                    <p class="text-sm text-gray-500 mb-6"><?= date('d F Y', strtotime($data['announcement']['created_at'])) ?></p>
                    
                    <div class="prose max-w-none">
                        <?= $data['announcement']['content'] ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include BASE_PATH . '/app/views/components/resident_footer.php'; ?>
    
    <div id="offline-alert" class="fixed bottom-0 left-0 right-0 bg-red-500 text-white p-2 text-center hidden">
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>
    
    <script src="/js/app.js"></script>
    <script src="/js/pwa.js"></script>
</body>
</html>