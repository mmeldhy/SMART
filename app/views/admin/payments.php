<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Payments</h1>
                    <p class="text-gray-600">Manage resident payments and transactions</p>
                </div>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">Filter Payments</h2>
                </div>
                <div class="p-4">
                    <form action="/admin/payments" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="approved" <?php echo isset($_GET['status']) && $_GET['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="rejected" <?php echo isset($_GET['status']) && $_GET['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="fee_id" class="block text-sm font-medium text-gray-700 mb-1">Fee Type</label>
                            <select id="fee_id" name="fee_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="">All Fee Types</option>
                                <?php foreach ($fees as $fee): ?>
                                    <option value="<?php echo $fee['id']; ?>" <?php echo isset($_GET['fee_id']) && $_GET['fee_id'] == $fee['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($fee['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        </div>
                        
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Filter
                            </button>
                            <a href="/admin/payments" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No payments found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">#<?php echo isset($payment['id']) ? $payment['id'] : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo isset($payment['resident_name']) ? htmlspecialchars($payment['resident_name']) : ''; ?></div>
                                            <div class="text-sm text-gray-500"><?php echo isset($payment['resident_email']) ? htmlspecialchars($payment['resident_email']) : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo isset($payment['fee_name']) ? htmlspecialchars($payment['fee_name']) : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp <?php echo isset($payment['amount']) ? number_format($payment['amount'], 0, ',', '.') : '0'; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500"><?php echo isset($payment['payment_date']) ? date('d M Y', strtotime($payment['payment_date'])) : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'approved' => 'bg-green-100 text-green-800', // Changed from 'verified'
                                                    'rejected' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pending',
                                                    'approved' => 'Approved', // Changed from 'Verified'
                                                    'rejected' => 'Rejected'
                                                ];
                                                $status = isset($payment['status']) ? $payment['status'] : '';
                                                $statusClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                                $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                                <?php echo $statusLabel; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/admin/payment/<?php echo isset($payment['id']) ? $payment['id'] : ''; ?>" class="text-green-600 hover:text-green-900 mr-3">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($payments) && $totalPages > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?php echo $startRecord; ?></span> to <span class="font-medium"><?php echo $endRecord; ?></span> of <span class="font-medium"><?php echo $totalRecords; ?></span> payments
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($currentPage > 1): ?>
                                        <a href="/admin/payments?page=<?php echo $currentPage - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['fee_id']) ? '&fee_id=' . $_GET['fee_id'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <a href="/admin/payments?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['fee_id']) ? '&fee_id=' . $_GET['fee_id'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $currentPage ? 'text-green-600 bg-green-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="/admin/payments?page=<?php echo $currentPage + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['fee_id']) ? '&fee_id=' . $_GET['fee_id'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>

