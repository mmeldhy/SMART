</div> <!-- End of #app -->
        
        <script src="/js/app.js"></script>
        <script>
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenuButton = document.getElementById('close-mobile-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu && closeMobileMenuButton) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
                
                closeMobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            }
            
            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', () => {
                    userMenu.classList.toggle('hidden');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', (event) => {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
            
            // Offline detection
            const offlineIndicator = document.getElementById('offline-indicator');
            
            if (offlineIndicator) {
                function updateOnlineStatus() {
                    if (navigator.onLine) {
                        offlineIndicator.classList.add('hidden');
                        document.body.classList.remove('offline');
                        document.body.classList.add('online');
                    } else {
                        offlineIndicator.classList.remove('hidden');
                        document.body.classList.add('offline');
                        document.body.classList.remove('online');
                    }
                }
                
                window.addEventListener('online', updateOnlineStatus);
                window.addEventListener('offline', updateOnlineStatus);
                updateOnlineStatus();
            }
        </script>
    </body>
</html>
