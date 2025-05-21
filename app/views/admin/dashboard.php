<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin -SMART</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-icons@0.171.0/dist/umd/lucide.min.js">
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
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Residents Card -->
                    <div class="bg-white rounded-lg shadow p-4 flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total Warga</p>
                            <p class="text-xl font-bold"><?= $data['residentCount'] ?></p>
                        </div>
                    </div>
                    
                    <!-- Fees Card -->
                    <div class="bg-white rounded-lg shadow p-4 flex items-center">
                        <div class="rounded-full bg-green-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total Iuran</p>
                            <p class="text-xl font-bold"><?= $data['feeCount'] ?></p>
                        </div>
                    </div>
                    
                    <!-- Payments Card -->
                    <div class="bg-white rounded-lg shadow p-4 flex items-center">
                        <div class="rounded-full bg-purple-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Pembayaran</p>
                            <p class="text-xl font-bold"><?= $data['paymentCount'] ?></p>
                        </div>
                    </div>
                    
                    <!-- Reports Card -->
                    <div class="bg-white rounded-lg shadow p-4 flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Laporan</p>
                            <p class="text-xl font-bold"><?= $data['reportCount'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Items -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Pending Payments -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="border-b px-4 py-3 flex justify-between items-center">
                            <h2 class="font-medium text-gray-800">Pembayaran Menunggu Verifikasi</h2>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?= $data['pendingPaymentCount'] ?></span>
                        </div>
                        <div class="p-4">
                            <?php if (empty($data['recentPayments'])): ?>
                                <p class="text-gray-500 text-center py-4">Tidak ada pembayaran yang menunggu verifikasi</p>
                            <?php else: ?>
                                <ul class="divide-y">
                                    <?php foreach ($data['recentPayments'] as $payment): ?>
                                        <li class="py-3">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-medium"><?= htmlspecialchars($payment['user_name']) ?></p>
                                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($payment['fee_name']) ?></p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-medium"><?= 'Rp ' . number_format($payment['amount'], 0, ',', '.') ?></p>
                                                    <p class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></p>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex justify-end">
                                                <a href="/admin/payments" class="text-sm text-green-600 hover:text-green-800">Lihat Detail</a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="mt-4 text-center">
                                    <a href="/admin/payments" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Pembayaran</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Pending Reports -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="border-b px-4 py-3 flex justify-between items-center">
                            <h2 class="font-medium text-gray-800">Laporan Menunggu Tindakan</h2>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?= $data['pendingReportCount'] ?></span>
                        </div>
                        <div class="p-4">
                            <?php if (empty($data['recentReports'])): ?>
                                <p class="text-gray-500 text-center py-4">Tidak ada laporan yang menunggu tindakan</p>
                            <?php else: ?>
                                <ul class="divide-y">
                                    <?php foreach ($data['recentReports'] as $report): ?>
                                        <li class="py-3">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-medium"><?= htmlspecialchars($report['title']) ?></p>
                                                    <p class="text-sm text-gray-500">Oleh: <?= htmlspecialchars($report['user_name']) ?></p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="badge badge-<?= $report['status'] ?>">
                                                        <?= ucfirst($report['status']) ?>
                                                    </span>
                                                    <p class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($report['created_at'])) ?></p>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex justify-end">
                                                <a href="/admin/report/<?= $report['id'] ?>" class="text-sm text-green-600 hover:text-green-800">Lihat Detail</a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="mt-4 text-center">
                                    <a href="/admin/reports" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Laporan</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="border-b px-4 py-3">
                        <h2 class="font-medium text-gray-800">Aksi Cepat</h2>
                    </div>
                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="/admin/announcement/add" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <span class="text-sm text-gray-700">Buat Pengumuman</span>
                        </a>
                        <a href="/admin/fee/add" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="raound" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm text-gray-700">Tambah Iuran</span>
                        </a>
                        <a href="/admin/schedule/add" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700">Tambah Jadwal</span>
                        </a>
                        <a href="/admin/resident/add" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span class="text-sm text-gray-700">Tambah Warga</span>
                        </a>
                    </div>
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
