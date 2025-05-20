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
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Schedule</h1>
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/admin/schedule/edit/<?php echo $schedule['id']; ?>" method="POST">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($schedule['title'] ?? ''); ?>" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($schedule['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="meeting" <?php echo ($schedule['type'] ?? '') === 'meeting' ? 'selected' : ''; ?>>Meeting</option>
                                <option value="cleanup" <?php echo ($schedule['type'] ?? '') === 'cleanup' ? 'selected' : ''; ?>>Cleanup</option>
                                <option value="social" <?php echo ($schedule['type'] ?? '') === 'social' ? 'selected' : ''; ?>>Social Event</option>
                                <option value="maintenance" <?php echo ($schedule['type'] ?? '') === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                <option value="other" <?php echo ($schedule['type'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" value="<?php echo isset($schedule['date']) ? date('Y-m-d', strtotime($schedule['date'])) : ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                <input type="time" id="start_time" name="start_time" value="<?php echo isset($schedule['start_time']) ? date('H:i', strtotime($schedule['start_time'])) : ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                <input type="time" id="end_time" name="end_time" value="<?php echo isset($schedule['end_time']) ? date('H:i', strtotime($schedule['end_time'])) : ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($schedule['location'] ?? ''); ?>" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="scheduled" <?php echo ($schedule['status'] ?? '') === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                                <option value="cancelled" <?php echo ($schedule['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="/admin/schedules" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
