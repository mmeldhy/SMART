<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Warga - RT Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4CAF50">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Admin Header -->
    <?php include BASE_PATH . '/app/views/components/admin_header.php'; ?>
    
    <div class="flex flex-1">
        <!-- Admin Sidebar -->
        <?php include BASE_PATH . '/app/views/components/admin_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Edit Warga</h1>
                    <a href="/admin/residents" class="text-green-600 hover:text-green-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 alert" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 alert-close">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <!-- Edit Resident Form -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <form action="/admin/resident/edit/<?= $data['resident']['id'] ?>" method="POST" data-validate>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($data['resident']['name']) ?>" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($data['resident']['username']) ?>" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" id="password" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($data['resident']['phone']) ?>" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="address" id="address" rows="3" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required><?= htmlspecialchars($data['resident']['address']) ?></textarea>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Offline alert -->
    <div id="offline-alert" class="fixed bottom-0 left-0 right-0 bg-red-500 text-white p-2 text-center hidden">
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>
    
    <script src="/js/app.js"></script>
    <script src="/js/pwa.js"></script>
</body>
</html>
