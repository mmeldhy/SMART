<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Edit Fee</h1>
                <p class="text-gray-600">Update fee information</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                    </div>
                <?php endif; ?>
                
                <form action="/admin/fee/edit/<?php echo $fee['id']; ?>" method="POST" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Fee Title</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($fee['title'] ?? ''); ?>" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50"><?php echo htmlspecialchars($fee['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="amount" name="amount" min="0" step="1000" value="<?php echo $fee['amount'] ?? ''; ?>" required
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" id="due_date" name="due_date" value="<?php echo isset($fee['due_date']) ? date('Y-m-d', strtotime($fee['due_date'])) : ''; ?>" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                            <option value="">Select a category</option>
                            <option value="monthly" <?php echo ($fee['category'] ?? '') === 'monthly' ? 'selected' : ''; ?>>Monthly Dues</option>
                            <option value="special" <?php echo ($fee['category'] ?? '') === 'special' ? 'selected' : ''; ?>>Special Assessment</option>
                            <option value="maintenance" <?php echo ($fee['category'] ?? '') === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            <option value="event" <?php echo ($fee['category'] ?? '') === 'event' ? 'selected' : ''; ?>>Event</option>
                            <option value="other" <?php echo ($fee['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                            <option value="active" <?php echo ($fee['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($fee['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="/admin/fees" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Update Fee
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
