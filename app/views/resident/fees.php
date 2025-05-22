<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Iuran - SMART</title>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Iuran</h1>
            
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
            
            <!-- Fees List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 responsive-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Iuran
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jatuh Tempo
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
                            <?php if (empty($data['fees'])): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data iuran
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['fees'] as $fee): ?>
                                    <tr>
                                        <td class="px-6 py-4" data-label="Nama Iuran">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($fee['name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($fee['description']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="Jumlah">
                                            <div class="text-sm font-medium text-gray-900"><?= 'Rp ' . number_format($fee['amount'], 0, ',', '.') ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="Jatuh Tempo">
                                            <div class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($fee['due_date'])) ?></div>
                                            <?php
                                            $dueDate = new DateTime($fee['due_date']);
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-label="Status">
                                            <?php if ($fee['is_paid']): ?>
                                                <?php if ($fee['payment_status'] === 'pending'): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu Verifikasi
                                                    </span>
                                                <?php elseif ($fee['payment_status'] === 'approved'): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Lunas
                                                    </span>
                                                <?php elseif ($fee['payment_status'] === 'rejected'): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                <?php endif; ?>
                                                 <div class="text-xs text-gray-500 mt-1">
                                                     <?= isset($fee['payment_date']) ? date('d/m/Y', strtotime($fee['payment_date'])) : '' ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Belum Bayar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" data-label="Aksi">
                                            <a href="/fee/<?= $fee['id'] ?>" class="text-green-600 hover:text-green-900">
                                                <?php if (!$fee['is_paid']): ?>
                                                    Bayar
                                                <?php else: ?>
                                                    Detail
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
