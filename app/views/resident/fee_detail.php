<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Iuran - SMART</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Detail Iuran</h1>
                <a href="/fees" class="text-green-600 hover:text-green-800 flex items-center">
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
            
            <!-- Fee Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="border-b px-4 py-3">
                    <h2 class="font-medium text-gray-800">Informasi Iuran</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Nama Iuran</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900"><?= htmlspecialchars($data['fee']['name']) ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Jumlah</h3>
                            <p class="mt-1 text-lg font-medium text-green-600"><?= 'Rp ' . number_format($data['fee']['amount'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Jatuh Tempo</h3>
                            <p class="mt-1 text-gray-900"><?= date('d F Y', strtotime($data['fee']['due_date'])) ?></p>
                            <?php
                            $dueDate = new DateTime($data['fee']['due_date']);
                            $today = new DateTime();
                            $interval = $today->diff($dueDate);
                            $isPast = $today > $dueDate;
                            ?>
                            <?php if ($isPast): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Lewat <?= $interval->days ?> hari
                                </span>
                            <?php elseif ($interval->days <= 7): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?= $interval->days ?> hari lagi
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?= $interval->days ?> hari lagi
                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <?php if ($data['isPaid']): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Dibayar
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Dibayar
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
                        <p class="mt-1 text-gray-900"><?= htmlspecialchars($data['fee']['description']) ?></p>
                    </div>
                </div>
            </div>
            
            <?php if (!$data['isPaid']): ?>
                <!-- Payment Form -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="border-b px-4 py-3">
                        <h2 class="font-medium text-gray-800">Form Pembayaran</h2>
                    </div>
                    <form action="/fee/pay/<?= $data['fee']['id'] ?>" method="POST" enctype="multipart/form-data" data-validate>
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="proof_image" class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="proof_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                <span>Upload bukti pembayaran</span>
                                                <input id="proof_image" name="proof_image" type="file" class="sr-only" accept="image/*" required>
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
                            <div>
                                <p class="text-sm text-gray-500">
                                    Silahkan transfer ke rekening berikut:
                                </p>
                                <div class="mt-2 p-4 bg-gray-50 rounded-md">
                                    <p class="font-medium">Bank BCA</p>
                                    <p>No. Rekening: 1234567890</p>
                                    <p>Atas Nama: Bendahara RT</p>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Setelah melakukan pembayaran, upload bukti pembayaran di atas. Admin akan memverifikasi pembayaran Anda.
                                </p>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Kirim Bukti Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Payment Status -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="border-b px-4 py-3">
                        <h2 class="font-medium text-gray-800">Status Pembayaran</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center flex-col">
                            <?php if ($payment['status'] === 'pending'): ?>
                                <div class="rounded-full bg-yellow-100 p-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Menunggu Verifikasi</h3>
                                <p class="text-gray-500 text-center mb-4">
                                    Pembayaran Anda sedang diverifikasi oleh admin. Mohon tunggu.
                                </p>
                            <?php elseif ($payment['status'] === 'approved'): ?>
                                <div class="rounded-full bg-green-100 p-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Pembayaran Diverifikasi</h3>
                                <p class="text-gray-500 text-center mb-4">
                                    Pembayaran Anda telah diverifikasi oleh admin. Terima kasih.
                                </p>
                            <?php elseif ($payment['status'] === 'rejected'): ?>
                                <div class="rounded-full bg-red-100 p-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Pembayaran Ditolak</h3>
                                <p class="text-gray-500 text-center mb-4">
                                    Pembayaran Anda ditolak oleh admin. Silahkan hubungi admin untuk informasi lebih lanjut.
                                </p>
                            <?php endif; ?>
                            
                            <div class="mt-4 w-full max-w-md">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</h4>
                                <div class="border rounded-md overflow-hidden">
                                    <img src="<?= $payment['proof_image'] ?>" alt="Bukti Pembayaran" class="w-full h-auto">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Diunggah pada: <?= date('d F Y H:i', strtotime($payment['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
        const fileInput = document.getElementById('proof_image');
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
    </script>
</body>
</html>
