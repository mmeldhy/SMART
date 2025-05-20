<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="mb-6">
                <a href="/admin/schedules" class="text-green-600 hover:text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Schedules
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($schedule['title'] ?? ''); ?></h1>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span class="mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo date('d M Y', strtotime($schedule['date'] ?? '')); ?>
                                </span>
                                <span class="mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo date('H:i', strtotime($schedule['start_time'] ?? '')); ?> - 
                                    <?php echo date('H:i', strtotime($schedule['end_time'] ?? '')); ?>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?php echo htmlspecialchars($schedule['location'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <?php 
                                $typeClasses = [
                                    'meeting' => 'bg-blue-100 text-blue-800',
                                    'cleanup' => 'bg-green-100 text-green-800',
                                    'social' => 'bg-purple-100 text-purple-800',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800',
                                    'other' => 'bg-gray-100 text-gray-800'
                                ];
                                $typeLabels = [
                                    'meeting' => 'Meeting',
                                    'cleanup' => 'Cleanup',
                                    'social' => 'Social Event',
                                    'maintenance' => 'Maintenance',
                                    'other' => 'Other'
                                ];
                                $typeClass = $typeClasses[$schedule['type'] ?? 'other'] ?? 'bg-gray-100 text-gray-800';
                                $typeLabel = $typeLabels[$schedule['type'] ?? 'other'] ?? ucfirst($schedule['type'] ?? 'other');
                            ?>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo $typeClass; ?>">
                                <?php echo $typeLabel; ?>
                            </span>
                            
                            <?php 
                                $now = new DateTime();
                                $scheduleDate = new DateTime(($schedule['date'] ?? 'now') . ' ' . ($schedule['end_time'] ?? '00:00'));
                                $isPast = $scheduleDate < $now;
                                
                                if (($schedule['status'] ?? '') === 'cancelled'): 
                            ?>
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            <?php elseif ($isPast): ?>
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Completed
                                </span>
                            <?php else: ?>
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Upcoming
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Description</h2>
                        <div class="bg-gray-50 p-4 rounded-md text-gray-700">
                            <?php echo empty($schedule['description'] ?? '') ? 'No description provided.' : nl2br(htmlspecialchars($schedule['description'] ?? '')); ?>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="/admin/schedule/edit/<?php echo $schedule['id']; ?>" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Edit Schedule
                            </a>
                            <form action="/admin/schedule/delete/<?php echo $schedule['id']; ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
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
