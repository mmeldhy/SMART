<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - RT Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4CAF50">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Resident Header -->
    <?php include BASE_PATH . '/app/views/components/resident_header.php'; ?>
    
    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Buat Laporan</h1>
                <a href="/reports" class="text-green-600 hover:text-green-800 flex items-center">
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
            
            <!-- Report Form -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <form action="/report/add" method="POST" enctype="multipart/form-data" data-validate>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Laporan</label>
                            <input type="text" name="title" id="title" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category" id="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Infrastruktur">Infrastruktur</option>
                                <option value="Lingkungan">Lingkungan</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Kebersihan">Kebersihan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="5" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required></textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Jelaskan secara detail permasalahan yang Anda laporkan. Sertakan lokasi dan waktu kejadian jika relevan.
                            </p>
                        </div>
                        
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Foto (Opsional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload foto</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="/placeholder.svg" alt="Preview" class="h-32 object-cover rounded-md">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <?php include BASE_PATH . '/app/views/components/resident_footer.php'; ?>
    
    <!-- Offline alert -->
    <div id="offline-alert" class="fixed bottom-0 left-0 right-0 bg-red-500 text-white p-2 text-center hidden">
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>
    
    <script src="/js/app.js"></script>
    <script src="/js/pwa.js"></script>
    <script>
        // Image preview
        const fileInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImage = imagePreview.querySelector('img');
        
        if (fileInput && imagePreview && previewImage) {
            fileInput.addEventListener('change', function() {
                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    }
                    
                    reader.readAsDataURL(fileInput.files[0]);
                }
            });
        }
        
        // Offline form submission
        const form = document.querySelector('form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!navigator.onLine) {
                    e.preventDefault();
                    
                    // Cache form data for later submission
                    const formData = new FormData(form);
                    const endpoint = form.getAttribute('action');
                    
                    if (cacheFormSubmission(formData, endpoint)) {
                        showToast('Laporan disimpan dan akan dikirim saat online kembali', 'info');
                        setTimeout(() => {
                            window.location.href = '/reports';
                        }, 2000);
                    }
                }
            });
        }
    </script>
</body>
</html>
