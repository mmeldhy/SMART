<?php
/**
 * Router Class
 * 
 * Handles routing of requests to appropriate controllers
 */
class Router {
    private static $routes = [];
    
    /**
     * Register a route
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url URL pattern
     * @param string $controller Controller name
     * @param string $action Controller method/action
     */
    public static function register($method, $url, $controller, $action) {
        self::$routes[] = [
            'method' => $method,
            'url' => $url,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Register a GET route
     */
    public static function get($url, $controller, $action) {
        self::register('GET', $url, $controller, $action);
    }
    
    /**
     * Register a POST route
     */
    public static function post($url, $controller, $action) {
        self::register('POST', $url, $controller, $action);
    }
    
    /**
     * Route the request to the appropriate controller
     * 
     * @param string $url Requested URL
     */
    public static function route($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Default route
        if (empty($url)) {
            $url = 'home';
        }
        
        // Check for matching route
        foreach (self::$routes as $route) {
            // Convert route URL to regex pattern
            $pattern = self::convertRouteToRegex($route['url']);
            
            if ($route['method'] == $method && preg_match($pattern, $url, $matches)) {
                // Remove the first match (full string)
                array_shift($matches);
                
                // Create controller instance
                $controllerName = 'app\\controllers\\' . $route['controller'] . 'Controller';
                $controller = new $controllerName();
                
                // Call the action with parameters
                call_user_func_array([$controller, $route['action']], $matches);
                return;
            }
        }
        
        // No route found - 404
        header("HTTP/1.0 404 Not Found");
        echo '404 - Page Not Found';
    }
    
    /**
     * Convert route URL to regex pattern
     * 
     * @param string $route Route URL with placeholders
     * @return string Regex pattern
     */
    private static function convertRouteToRegex($route) {
        // Replace :param with regex capture group
        $route = preg_replace('/\:([a-zA-Z0-9]+)/', '([^/]+)', $route);
        
        // Escape slashes and add start/end markers
        return '/^' . str_replace('/', '\/', $route) . '$/';
    }
}

// Define routes
Router::get('', 'Auth', 'loginPage');
Router::get('home', 'Home', 'index');
Router::get('login', 'Auth', 'loginPage');
Router::post('login', 'Auth', 'login');
Router::get('register', 'Auth', 'registerPage');
Router::post('register', 'Auth', 'register');
Router::get('logout', 'Auth', 'logout');

// Admin routes
Router::get('admin/dashboard', 'Admin', 'dashboard');
Router::get('admin/residents', 'Admin', 'residents');
Router::get('admin/resident/add', 'Admin', 'addResidentPage');
Router::post('admin/resident/add', 'Admin', 'addResident');
Router::get('admin/resident/edit/:id', 'Admin', 'editResidentPage');
Router::post('admin/resident/edit/:id', 'Admin', 'updateResident');
Router::get('admin/resident/delete/:id', 'Admin', 'deleteResident');
Router::get('admin/resident/:id', 'Admin', 'viewResident');
    
// Fee management
Router::get('admin/fees', 'Fee', 'index');
Router::get('admin/fee/add', 'Fee', 'addPage');
Router::post('admin/fee/add', 'Fee', 'add');
Router::get('admin/fee/edit/:id', 'Fee', 'editPage');
Router::post('admin/fee/edit/:id', 'Fee', 'update');
Router::get('admin/fee/delete/:id', 'Fee', 'delete');
Router::get('admin/payments', 'Fee', 'payments');
Router::get('admin/payment/:id', 'Fee', 'paymentDetail'); // Correct route
Router::post('admin/payment/:id', 'Fee', 'updatePaymentStatus');

// Announcements
Router::get('admin/announcements', 'Announcement', 'index');
Router::get('admin/announcement/add', 'Announcement', 'addPage');
Router::post('admin/announcement/add', 'Announcement', 'add');
Router::get('admin/announcement/edit/:id', 'Announcement', 'editPage');
Router::post('admin/announcement/edit/:id', 'Announcement', 'update');
Router::post('admin/announcement/delete/:id', 'Announcement', 'delete');
Router::get('admin/announcement/view/:id', 'Announcement', 'view');

// Schedules
Router::get('admin/schedules', 'Schedule', 'index');
Router::get('admin/schedule/add', 'Schedule', 'addPage');
Router::post('admin/schedule/add', 'Schedule', 'add');
Router::get('admin/schedule/edit/:id', 'Schedule', 'editPage');
Router::post('admin/schedule/edit/:id', 'Schedule', 'update');
Router::post('admin/schedule/delete/:id', 'Schedule', 'delete');
Router::get('admin/schedule/view/:id', 'Schedule', 'view');

// Reports
Router::get('admin/reports', 'Report', 'adminIndex');
Router::get('admin/report/view/:id', 'Report', 'adminView'); // Corrected route
Router::post('admin/report/status/:id', 'Report', 'updateStatus');

//settings
Router::get('admin/settings', 'Settings', 'index');
Router::post('admin/settings/add', 'Settings', 'update');


// Resident routes
Router::get('dashboard', 'Resident', 'dashboard');
Router::get('profile', 'Resident', 'profile');
Router::post('profile', 'Resident', 'updateProfile');
Router::get('fees', 'Resident', 'fees');
Router::get('fee/:id', 'Resident', 'feeDetail');
Router::post('fee/pay/:id', 'Resident', 'payFee');
Router::get('announcements', 'Resident', 'announcements');
Router::get('announcement/:id', 'Resident', 'announcementDetail');
Router::get('schedules', 'Resident', 'schedules');
Router::get('reports', 'Resident', 'reports');
Router::get('report/add', 'Report', 'addPage');
Router::post('report/add', 'Report', 'add');
Router::get('report/:id', 'Report', 'view');
