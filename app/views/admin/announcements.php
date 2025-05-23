<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
                    <p class="text-gray-600">Manage community announcements and notices</p>
                </div>
                <a href="/admin/announcement/add" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Announcement
                    </span>
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">Filter Announcements</h2>
                </div>
                <div class="p-4">
                    <form action="/admin/announcements" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="">All Types</option>
                                <option value="general" <?php echo isset($_GET['type']) && $_GET['type'] === 'general' ? 'selected' : ''; ?>>General</option>
                                <option value="important" <?php echo isset($_GET['type']) && $_GET['type'] === 'important' ? 'selected' : ''; ?>>Important</option>
                                <option value="event" <?php echo isset($_GET['type']) && $_GET['type'] === 'event' ? 'selected' : ''; ?>>Event</option>
                                <option value="maintenance" <?php echo isset($_GET['type']) && $_GET['type'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
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
                        
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Filter
                            </button>
                            <a href="/admin/announcements" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($announcements)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No announcements found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($announcements as $announcement): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if ($announcement['is_pinned'] ?? false): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($announcement['title'] ?? ''); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo substr(strip_tags($announcement['content'] ?? ''), 0, 50) . (strlen(strip_tags($announcement['content'] ?? '')) > 50 ? '...' : ''); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                                $typeClasses = [
                                                    'general' => 'bg-gray-100 text-gray-800',
                                                    'important' => 'bg-red-100 text-red-800',
                                                    'event' => 'bg-blue-100 text-blue-800',
                                                    'maintenance' => 'bg-yellow-100 text-yellow-800'
                                                ];
                                                $typeLabels = [
                                                    'general' => 'General',
                                                    'important' => 'Important',
                                                    'event' => 'Event',
                                                    'maintenance' => 'Maintenance'
                                                ];
                                                $typeClass = $typeClasses[$announcement['type'] ?? 'general'] ?? 'bg-gray-100 text-gray-800';
                                                $typeLabel = $typeLabels[$announcement['type'] ?? 'general'] ?? ucfirst($announcement['type'] ?? 'general');
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $typeClass; ?>">
                                                <?php echo $typeLabel; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('d M Y', strtotime($announcement['start_date'] ?? '')); ?>
                                                <?php if (!empty($announcement['end_date'])): ?>
                                                    <span class="mx-1">-</span>
                                                    <?php echo date('d M Y', strtotime($announcement['end_date'])); ?>
                                                <?php else: ?>
                                                    <span class="ml-1">(No end date)</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                                $now = new DateTime();
                                                $startDate = new DateTime($announcement['start_date'] ?? 'now');
                                                $endDate = !empty($announcement['end_date']) ? new DateTime($announcement['end_date']) : null;

                                                $isActive = $startDate <= $now && ($endDate === null || $endDate >= $now);
                                                $isFuture = $startDate > $now;
                                                $isExpired = $endDate !== null && $endDate < $now;
                                            ?>

                                            <?php if ($isActive): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            <?php elseif ($isFuture): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Scheduled
                                                </span>
                                            <?php elseif ($isExpired): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Expired
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="/admin/announcement/edit/<?php echo $announcement['id']; ?>" class="text-green-600 hover:text-green-900">Edit</a>
                                                <a href="/admin/announcement/view/<?php echo $announcement['id']; ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                                <form action="/admin/announcement/delete/<?php echo $announcement['id']; ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($announcements) && $totalPages > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?php echo $startRecord; ?></span> to <span class="font-medium"><?php echo $endRecord; ?></span> of <span class="font-medium"><?php echo $totalRecords; ?></span> announcements
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($currentPage > 1): ?>
                                        <a href="/admin/announcements?page=<?php echo $currentPage - 1; ?><?php echo isset($_GET['type']) ? '&type=' . $_GET['type'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <a href="/admin/announcements?page=<?php echo $i; ?><?php echo isset($_GET['type']) ? '&type=' . $_GET['type'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $currentPage ? 'text-green-600 bg-green-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="/admin/announcements?page=<?php echo $currentPage + 1; ?><?php echo isset($_GET['type']) ? '&type=' . $_GET['type'] : ''; ?><?php echo isset($_GET['date_from']) ? '&date_from=' . $_GET['date_from'] : ''; ?><?php echo isset($_GET['date_to']) ? '&date_to=' . $_GET['date_to'] : ''; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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