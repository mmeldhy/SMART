<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-lg font-bold text-green-600">RT Management System</h1>
                </div>
                <nav class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="/fees" class="<?= strpos($_SERVER['REQUEST_URI'], '/fee') !== false ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Iuran
                    </a>
                    <a href="/announcements" class="<?= strpos($_SERVER['REQUEST_URI'], '/announcement') !== false ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Pengumuman
                    </a>
                    <a href="/schedules" class="<?= strpos($_SERVER['REQUEST_URI'], '/schedule') !== false ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Jadwal
                    </a>
                    <a href="/reports" class="<?= strpos($_SERVER['REQUEST_URI'], '/report') !== false ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Laporan
                    </a>
                </nav>
            </div>
            <div class="flex items-center">
                <div class="ml-3 relative">
                    <div>
                        <button type="button" class="flex items-center max-w-xs rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                <span class="text-sm font-medium leading-none text-green-700"><?= substr($_SESSION['name'], 0, 1) ?></span>
                            </span>
                            <span class="ml-2 text-gray-700"><?= htmlspecialchars($_SESSION['name']) ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Profil</a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Logout</a>
                    </div>
                </div>
                <div class="ml-4 md:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500" id="mobile-menu-toggle">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Dashboard
            </a>
            <a href="/fees" class="<?= strpos($_SERVER['REQUEST_URI'], '/fee') !== false ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Iuran
            </a>
            <a href="/announcements" class="<?= strpos($_SERVER['REQUEST_URI'], '/announcement') !== false ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Pengumuman
            </a>
            <a href="/schedules" class="<?= strpos($_SERVER['REQUEST_URI'], '/schedule') !== false ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Jadwal
            </a>
            <a href="/reports" class="<?= strpos($_SERVER['REQUEST_URI'], '/report') !== false ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Laporan
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                        <span class="text-sm font-medium leading-none text-green-700"><?= substr($_SESSION['name'], 0, 1) ?></span>
                    </span>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800"><?= htmlspecialchars($_SESSION['name']) ?></div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="/profile" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                    Profil
                </a>
                <a href="/logout" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                    Logout
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });
            
            // Close when clicking outside
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
        
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</header>
