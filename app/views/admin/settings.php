<?php require_once __DIR__ . '/../components/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <?php require_once __DIR__ . '/../components/admin_sidebar.php'; ?>
    
    <div class="flex-1 overflow-auto">
        <main class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">System Settings</h1>
            
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
                    <h2 class="text-lg font-medium text-gray-800 mb-4">General Settings</h2>
                    
                    <form action="/admin/settings/add" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="rt_name" class="block text-sm font-medium text-gray-700 mb-1">RT Name</label>
                                <input type="text" id="rt_name" name="rt_name" value="<?php echo htmlspecialchars($settings->rt_name ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The name of your RT community</p>
                            </div>
                            
                            <div>
                                <label for="rt_number" class="block text-sm font-medium text-gray-700 mb-1">RT Number</label>
                                <input type="text" id="rt_number" name="rt_number" value="<?php echo htmlspecialchars($settings->rt_number ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The RT number (e.g., RT 001)</p>
                            </div>
                            
                            <div>
                                <label for="rw_number" class="block text-sm font-medium text-gray-700 mb-1">RW Number</label>
                                <input type="text" id="rw_number" name="rw_number" value="<?php echo htmlspecialchars($settings->rw_number ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The RW number (e.g., RW 010)</p>
                            </div>
                            
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($settings->district ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The district name</p>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($settings->city ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The city name</p>
                            </div>
                            
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <input type="text" id="province" name="province" value="<?php echo htmlspecialchars($settings->province ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">The province name</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-md font-medium text-gray-800 mb-4">Contact Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                                    <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings->contact_email ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                                    <input type="text" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings->contact_phone ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"><?php echo htmlspecialchars($settings->address ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-md font-medium text-gray-800 mb-4">System Settings</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="maintenance_mode" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Mode</label>
                                    <select id="maintenance_mode" name="maintenance_mode" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                        <option value="0" <?php echo ($settings->maintenance_mode ?? 0) == 0 ? 'selected' : ''; ?>>Off</option>
                                        <option value="1" <?php echo ($settings->maintenance_mode ?? 0) == 1 ? 'selected' : ''; ?>>On</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">When enabled, only admins can access the system</p>
                                </div>
                                
                                <div>
                                    <label for="registration_enabled" class="block text-sm font-medium text-gray-700 mb-1">Allow Registration</label>
                                    <select id="registration_enabled" name="registration_enabled" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                        <option value="0" <?php echo ($settings->registration_enabled ?? 1) == 0 ? 'selected' : ''; ?>>Disabled</option>
                                        <option value="1" <?php echo ($settings->registration_enabled ?? 1) == 1 ? 'selected' : ''; ?>>Enabled</option>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Allow new residents to register</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">System Information</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">System Version</dt>
                                <dd class="mt-1 text-sm text-gray-900">1.0.0</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo phpversion(); ?></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Database</dt>
                                <dd class="mt-1 text-sm text-gray-900">MySQL <?php echo $dbVersion ?? 'Unknown'; ?></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Server</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Last Update</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo date('d M Y, H:i', strtotime($settings->updated_at ?? 'now')); ?></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Total Residents</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo $totalResidents ?? 0; ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
