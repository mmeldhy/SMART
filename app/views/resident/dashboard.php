<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga - SMART</title>
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
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Warga</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 alert" role="alert">
                    <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 alert-close">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="/fees" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Iuran</span>
                </a>
                <a href="/announcements" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Pengumuman</span>
                </a>
                <a href="/schedules" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Jadwal</span>
                </a>
                <a href="/report/add" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Buat Laporan</span>
                </a>
            </div>
            
            <!-- Active Fees -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b px-4 py-3">
                    <h2 class="font-medium text-gray-800">Iuran Aktif</h2>
                </div>
                <div class="p-4">
                    <?php if (empty($data['activeFees'])): ?>
                        <p class="text-gray-500 text-center py-4">Tidak ada iuran aktif saat ini</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($data['activeFees'] as $fee): ?>
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-800"><?= htmlspecialchars($fee['name']) ?></h3>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($fee['description']) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-800"><?= 'Rp ' . number_format($fee['amount'], 0, ',', '.') ?></p>
                                            <p class="text-sm text-gray-500">Jatuh tempo: <?= date('d/m/Y', strtotime($fee['due_date'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <a href="/fee/<?= $fee['id'] ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="/fees" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Iuran</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Recent Announcements -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-4 py-3">
                        <h2 class="font-medium text-gray-800">Pengumuman Terbaru</h2>
                    </div>
                    <div class="p-4">
                        <?php if (empty($data['recentAnnouncements'])): ?>
                            <p class="text-gray-500 text-center py-4">Tidak ada pengumuman terbaru</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($data['recentAnnouncements'] as $announcement): ?>
                                    <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                        <h3 class="font-medium text-gray-800"><?= htmlspecialchars($announcement['title']) ?></h3>
                                        <p class="text-sm text-gray-500 mb-2"><?= date('d/m/Y', strtotime($announcement['created_at'])) ?></p>
                                        <p class="text-gray-600 line-clamp-2"><?= substr(strip_tags($announcement['content']), 0, 100) ?>...</p>
                                        <div class="mt-2">
                                            <a href="/announcement/<?= $announcement['id'] ?>" class="text-sm text-green-600 hover:text-green-800">Baca Selengkapnya</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="/announcements" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Pengumuman</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Upcoming Schedules -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-4 py-3">
                        <h2 class="font-medium text-gray-800">Jadwal Mendatang</h2>
                    </div>
                    <div class="p-4">
                        <?php if (empty($data['upcomingSchedules'])): ?>
                            <p class="text-gray-500 text-center py-4">Tidak ada jadwal mendatang</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($data['upcomingSchedules'] as $schedule): ?>
                                    <div class="flex items-start border-b pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 text-center mr-4">
                                           <?php if(isset($schedule['schedule_datetime'])): ?>
                                             <span class="block text-sm font-bold text-green-800"><?= date('d', strtotime($schedule['schedule_datetime'])) ?></span>
                                             <span class="block text-xs text-green-600"><?= date('M', strtotime($schedule['schedule_datetime'])) ?></span>
                                           <?php endif; ?>
                                         </div>
                                         <div>
                                             <h3 class="font-medium text-gray-800"><?= htmlspecialchars($schedule['title']) ?></h3>
                                            <p class="text-sm text-gray-500"><?= isset($schedule['schedule_datetime']) ? date('H:i', strtotime($schedule['schedule_datetime'])) : '' ?> WIB</p>
                                             <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($schedule['description']) ?></p>
                                         </div>
                                     </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="/schedules" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Jadwal</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Reports -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-4 py-3">
                    <h2 class="font-medium text-gray-800">Laporan Terbaru Anda</h2>
                </div>
                <div class="p-4">
                    <?php if (empty($data['recentReports'])): ?>
                        <p class="text-gray-500 text-center py-4">Anda belum membuat laporan</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 responsive-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($data['recentReports'] as $report): ?>
                                        <tr>
                                            <td class="px-6 py-4" data-label="Judul">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($report['title']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="Kategori">
                                                <div class="text-sm text-gray-900"><?= htmlspecialchars($report['category']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-label="Tanggal">
                                                <?= date('d/m/Y', strtotime($report['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="Status">
                                                <span class="badge badge-<?= $report['status'] ?>">
                                                    <?php
                                                    switch ($report['status']) {
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
                                                            echo ucfirst($report['status']);
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" data-label="Aksi">
                                                <a href="/report/<?= $report['id'] ?>" class="text-green-600 hover:text-green-900">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="/reports" class="text-sm text-green-600 hover:text-green-800">Lihat Semua Laporan</a>
                        </div>
                    <?php endif; ?>
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
