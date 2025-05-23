<?php
/**
 * SMART - Entry Point
 * 
 * This is the main entry point for the application.
 * All requests are routed through this file.
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/config/database.php';

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = BASE_PATH . DIRECTORY_SEPARATOR . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Start session
session_start();

// Load router
require_once BASE_PATH . '/routes/web.php';

// Get URL from the request
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');

// Route the request
Router::route($url);
