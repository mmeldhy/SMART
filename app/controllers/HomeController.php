<?php
namespace app\controllers;

class HomeController {
    /**
     * Index page - redirect to appropriate dashboard
     */
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /dashboard');
        }
    }
}
