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
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Announcement</h1>
                    
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
                    
                    <form action="/admin/announcement/edit/<?php echo $announcement['id']; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($announcement['title'] ?? ''); ?>" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea id="content" name="content" rows="6" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required><?php echo htmlspecialchars($announcement['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="general" <?php echo ($announcement['type'] ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                                <option value="important" <?php echo ($announcement['type'] ?? '') === 'important' ? 'selected' : ''; ?>>Important</option>
                                <option value="event" <?php echo ($announcement['type'] ?? '') === 'event' ? 'selected' : ''; ?>>Event</option>
                                <option value="maintenance" <?php echo ($announcement['type'] ?? '') === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            </select>
                        </div>
                        
                        <?php if (!empty($announcement['image_url'])): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                                <div class="mt-1 flex items-center">
                                    <img src="<?php echo htmlspecialchars($announcement['image_url'] ?? ''); ?>" alt="Announcement Image" class="h-32 w-auto object-cover rounded-md">
                                    <div class="ml-4">
                                        <label for="remove_image" class="flex items-center">
                                            <input type="checkbox" id="remove_image" name="remove_image" value="1" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">New Image (Optional)</label>
                            <input type="file" id="image" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="mt-1 text-sm text-gray-500">Upload a new image to replace the current one (max 2MB, JPG/PNG only)</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo isset($announcement['start_date']) ? date('Y-m-d', strtotime($announcement['start_date'])) : ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        </div>
                        
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date (Optional)</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo isset($announcement['end_date']) && !empty($announcement['end_date']) ? date('Y-m-d', strtotime($announcement['end_date'])) : ''; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">Leave empty for announcements without an end date</p>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="is_pinned" name="is_pinned" value="1" <?php echo ($announcement['is_pinned'] ?? false) ? 'checked' : ''; ?> class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_pinned" class="ml-2 block text-sm text-gray-900">Pin this announcement to the top</label>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="/admin/announcements" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Update Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
