<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="mb-6">
                <a href="/admin/announcements" class="text-green-600 hover:text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Announcements
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($announcement['title'] ?? ''); ?></h1>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span class="mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo date('d M Y', strtotime($announcement['created_at'] ?? '')); ?>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo date('H:i', strtotime($announcement['created_at'] ?? '')); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div>
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
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo $typeClass; ?>">
                                <?php echo $typeLabel; ?>
                            </span>
                            
                            <?php if ($announcement['is_pinned'] ?? false): ?>
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pinned
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($announcement['image_url'])): ?>
                        <div class="mt-6">
                            <img src="<?php echo htmlspecialchars($announcement['image_url'] ?? ''); ?>" alt="Announcement Image" class="max-w-full h-auto rounded-md shadow-sm">
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-6">
                        <div class="prose max-w-none text-gray-700">
                            <?php echo $announcement['content'] ?? ''; ?>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="/admin/announcement/edit/<?php echo $announcement['id']; ?>" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Edit Announcement
                            </a>
                            <form action="/admin/announcement/delete/<?php echo $announcement['id']; ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>