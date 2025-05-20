<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - RT Management System</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Detail Laporan</h1>
                <a href="/reports" class="text-green-600 hover:text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
            
            <!-- Report Status -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($data['report']['title']) ?></h2>
                        <span class="badge badge-<?= $data['report']['status'] ?>">
                            <?php
                            switch ($data['report']['status']) {
                                case 'pending':
                                    echo 'Menunggu';
                                    break;
                                case 'process':
                                    echo 'Diproses';
                                    break;
                                case 'completed':
                                    echo 'Selesai';
                                    break;
                                default:
                                    echo ucfirst($data['report']['status']);
                            }
                            ?>
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Kategori</p>
                            <p class="font-medium"><?= htmlspecialchars($data['report']['category']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Laporan</p>
                            <p class="font-medium"><?= date('d F Y H:i', strtotime($data['report']['created_at'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-2">Deskripsi</p>
                        <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($data['report']['description']) ?></p>
                    </div>
                    
                    <?php if (!empty($data['report']['image'])): ?>
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 mb-2">Foto</p>
                            <div class="border rounded-md overflow-hidden">
                                <img src="<?= $data['report']['image'] ?>" alt="Foto Laporan" class="w-full h-auto">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['report']['admin_response'])): ?>
                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-500 mb-2">Tanggapan Admin</p>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($data['report']['admin_response']) ?></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <?= !empty($data['report']['updated_at']) ? 'Diperbarui pada: ' . date('d F Y H:i', strtotime($data['report']['updated_at'])) : '' ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Status Timeline -->
                    <div class="border-t pt-4 mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Status Laporan</h3>
                        <div class="relative">
                            <!-- Timeline line -->
                            <div class="absolute h-full w-0.5 bg-gray-200 left-5 top-0"></div>
                            
                            <!-- Timeline items -->
                            <div class="ml-12 relative pb-8">
                                <div class="absolute -left-7 mt-1.5">
                                    <span class="h-5 w-5 rounded-full bg-green-500 flex items-center justify-center ring-4 ring-white">
                                        <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Laporan Dibuat</p>
                                    <p class="text-sm text-gray-500"><?= date('d F Y H:i', strtotime($data['report']['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($data['report']['status'] === 'process' || $data['report']['status'] === 'completed'): ?>
                                <div class="ml-12 relative pb-8">
                                    <div class="absolute -left-7 mt-1.5">
                                        <span class="h-5 w-5 rounded-full bg-blue-500 flex items-center justify-center ring-4 ring-white">
                                            <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Sedang Diproses</p>
                                        <p class="text-sm text-gray-500">Laporan Anda sedang ditindaklanjuti</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="ml-12 relative pb-8">
                                    <div class="absolute -left-7 mt-1.5">
                                        <span class="h-5 w-5 rounded-full bg-gray-300 flex items-center justify-center ring-4 ring-white">
                                            <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-400">Sedang Diproses</p>
                                        <p class="text-sm text-gray-400">Menunggu tindak lanjut</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($data['report']['status'] === 'completed'): ?>
                                <div class="ml-12 relative">
                                    <div class="absolute -left-7 mt-1.5">
                                        <span class="h-5 w-5 rounded-full bg-green-500 flex items-center justify-center ring-4 ring-white">
                                            <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Selesai</p>
                                        <p class="text-sm text-gray-500">Laporan telah diselesaikan</p>
                                        <p class="text-sm text-gray-500"><?= !empty($data['report']['updated_at']) ? date('d F Y H:i', strtotime($data['report']['updated_at'])) : '' ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="ml-12 relative">
                                    <div class="absolute -left-7 mt-1.5">
                                        <span class="h-5 w-5 rounded-full bg-gray-300 flex items-center justify-center ring-4 ring-white">
                                            <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-400">Selesai</p>
                                        <p class="text-sm text-gray-400">Menunggu penyelesaian</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
</body>
</html>
