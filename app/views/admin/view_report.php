<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="mb-6">
                <a href="/admin/reports" class="inline-flex items-center text-sm text-green-600 hover:text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Reports
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($report['title'] ?? ''); ?></h1>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span class="mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo date('d M Y, H:i', strtotime($report['created_at'] ?? '')); ?>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <?php echo htmlspecialchars($report['resident_name'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <?php 
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'in_progress' => 'In Progress',
                                    'resolved' => 'Resolved',
                                    'rejected' => 'Rejected'
                                ];
                                $statusClass = $statusClasses[$report['status'] ?? ''] ?? 'bg-gray-100 text-gray-800';
                                $statusLabel = $statusLabels[$report['status'] ?? ''] ?? ucfirst($report['status'] ?? '');
                            ?>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo $statusLabel; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <h2 class="text-lg font-medium text-gray-800 mb-4">Report Details</h2>
                                
                                <div class="prose max-w-none">
                                    <p><?php echo nl2br(htmlspecialchars($report['description'] ?? '')); ?></p>
                                </div>
                                
                                <?php if (!empty($report['image'])): ?>
                                    <div class="mt-6">
                                        <h3 class="text-md font-medium text-gray-800 mb-2">Attached Image</h3>
                                        <div class="mt-2">
                                            <a href="<?php echo $report['image']; ?>" target="_blank" class="block w-full sm:w-auto">
                                                <img src="<?php echo $report['image']; ?>" alt="Report Image" class="max-w-full h-auto rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300" style="max-height: 300px;">
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($report['admin_response'])): ?>
                                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                                        <h3 class="text-md font-medium text-gray-800 mb-2">Admin Response</h3>
                                        <div class="prose max-w-none">
                                            <p><?php echo nl2br(htmlspecialchars($report['admin_response'] ?? '')); ?></p>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-2">
                                            <?php if (!empty($report['updated_at'])): ?>
                                                <span>Last updated: <?php echo date('d M Y, H:i', strtotime($report['updated_at'] ?? '')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-medium text-gray-800 mb-4">Update Status</h2>
                                
                                <form action="/admin/report/status/<?php echo $report['id']; ?>" method="POST" class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                            <option value="pending" <?php echo ($report['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="in_progress" <?php echo ($report['status'] ?? '') === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="resolved" <?php echo ($report['status'] ?? '') === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                            <option value="rejected" <?php echo ($report['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="response" class="block text-sm font-medium text-gray-700 mb-1">Response</label>
                                        <textarea id="response" name="response" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"><?php echo htmlspecialchars($report['admin_response'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div>
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Update Status
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="mt-8">
                                    <h2 class="text-lg font-medium text-gray-800 mb-4">Report Information</h2>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="space-y-3 text-sm">
                                            <div class="grid grid-cols-3 gap-2">
                                                <dt class="font-medium text-gray-500">Report ID:</dt>
                                                <dd class="col-span-2 text-gray-900">#<?php echo $report['id']; ?></dd>
                                            </div>
                                            
                                            <div class="grid grid-cols-3 gap-2">
                                                <dt class="font-medium text-gray-500">Category:</dt>
                                                <dd class="col-span-2 text-gray-900">
                                                    <?php 
                                                        $categoryLabels = [
                                                            'infrastructure' => 'Infrastructure',
                                                            'security' => 'Security',
                                                            'environment' => 'Environment',
                                                            'noise' => 'Noise',
                                                            'other' => 'Other'
                                                        ];
                                                        echo $categoryLabels[$report['category'] ?? ''] ?? ucfirst($report['category'] ?? '');
                                                    ?>
                                                </dd>
                                            </div>
                                            
                                            <div class="grid grid-cols-3 gap-2">
                                                <dt class="font-medium text-gray-500">Reported By:</dt>
                                                <dd class="col-span-2 text-gray-900"><?php echo htmlspecialchars($report['resident_name'] ?? ''); ?></dd>
                                            </div>
                                            
                                            <div class="grid grid-cols-3 gap-2">
                                                <dt class="font-medium text-gray-500">Contact:</dt>
                                                <dd class="col-span-2 text-gray-900"><?php echo htmlspecialchars($report['resident_email'] ?? ''); ?></dd>
                                            </div>
                                            
                                            <div class="grid grid-cols-3 gap-2">
                                                <dt class="font-medium text-gray-500">Submitted:</dt>
                                                <dd class="col-span-2 text-gray-900"><?php echo date('d M Y, H:i', strtotime($report['created_at'] ?? '')); ?></dd>
                                            </div>
                                            
                                            <?php if (!empty($report['updated_at'])): ?>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <dt class="font-medium text-gray-500">Last Updated:</dt>
                                                    <dd class="col-span-2 text-gray-900"><?php echo date('d M Y, H:i', strtotime($report['updated_at'] ?? '')); ?></dd>
                                                </div>
                                            <?php endif; ?>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
