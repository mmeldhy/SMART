<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal -SMART</title>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Jadwal Kegiatan</h1>
            
            <!-- Search -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4">
                    <form action="/schedules" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="search" class="sr-only">Cari</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Cari jadwal..." value="<?= htmlspecialchars($data['search']) ?>">
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Schedules List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <?php if (empty($data['schedules'])): ?>
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Tidak ada jadwal kegiatan</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 responsive-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kegiatan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['schedules'] as $schedule): ?>
                                    <?php
                                    $scheduleDate = isset($schedule['schedule_datetime']) ? new DateTime($schedule['schedule_datetime']) : null;
                                    $today = new DateTime();
                                    $isPast = $scheduleDate && $scheduleDate < $today;
                                    ?>
                                    <tr class="<?= $isPast ? 'bg-gray-50' : '' ?>">
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="Tanggal">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full <?= $isPast ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-800' ?>">
                                                    <span class="text-sm font-medium"><?= $scheduleDate ? date('d', $scheduleDate->getTimestamp()) : '' ?></span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= $scheduleDate ? date('d F Y', $scheduleDate->getTimestamp()) : '' ?></div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php
                                                        if ($scheduleDate) {
                                                         $dayNames = [
                                                             'Sunday' => 'Minggu',
                                                             'Monday' => 'Senin',
                                                             'Tuesday' => 'Selasa',
                                                             'Wednesday' => 'Rabu',
                                                             'Thursday' => 'Kamis',
                                                             'Friday' => 'Jumat',
                                                             'Saturday' => 'Sabtu'
                                                         ];
                                                        $dayName = date('l', $scheduleDate->getTimestamp());
                                                         echo $dayNames[$dayName];
                                                         }
                                                          ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="Waktu">
                                            <div class="text-sm text-gray-900"><?= isset($schedule['schedule_datetime']) ? date('H:i', strtotime($schedule['schedule_datetime'])) : '' ?> WIB</div>
                                        </td>
                                        <td class="px-6 py-4" data-label="Kegiatan">
                                            <div class="text-sm font-medium text-gray-900"><?= isset($schedule['title']) ? htmlspecialchars($schedule['title']) : '' ?></div>
                                        </td>
                                        <td class="px-6 py-4" data-label="Deskripsi">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($schedule['description']) ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <!-- Pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium"><?= ($data['currentPage'] - 1) * 10 + 1 ?></span> sampai <span class="font-medium"><?= min($data['currentPage'] * 10, $data['totalSchedules']) ?></span> dari <span class="font-medium"><?= $data['totalSchedules'] ?></span> jadwal
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($data['currentPage'] > 1): ?>
                                        <a href="/schedules?page=<?= $data['currentPage'] - 1 ?>&search=<?= urlencode($data['search']) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                        <a href="/schedules?page=<?= $i ?>&search=<?= urlencode($data['search']) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $data['currentPage'] ? 'text-green-600 bg-green-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                        <a href="/schedules?page=<?= $data['currentPage'] + 1 ?>&search=<?= urlencode($data['search']) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Calendar View (Optional) -->
            <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="border-b px-4 py-3">
                    <h2 class="font-medium text-gray-800">Kalender Kegiatan</h2>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500 mb-4">Tampilan kalender akan membantu Anda melihat jadwal kegiatan dalam format kalender bulanan.</p>
                    <div class="text-center">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" id="show-calendar-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tampilkan Kalender
                        </button>
                    </div>
                    <div id="calendar-container" class="mt-4 hidden">
                        <!-- Calendar will be rendered here -->
                        <div class="text-center py-8 text-gray-500">
                            <p>Fitur kalender akan segera hadir.</p>
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
    <script>
        // Calendar toggle
        const showCalendarBtn = document.getElementById('show-calendar-btn');
        const calendarContainer = document.getElementById('calendar-container');
        
        if (showCalendarBtn && calendarContainer) {
            showCalendarBtn.addEventListener('click', () => {
                calendarContainer.classList.toggle('hidden');
                
                if (!calendarContainer.classList.contains('hidden')) {
                    showCalendarBtn.textContent = 'Sembunyikan Kalender';
                } else {
                    showCalendarBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>Tampilkan Kalender';
                }
            });
        }
    </script>
</body>
</html>
