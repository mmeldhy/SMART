<?php
namespace app\controllers;

use app\models\User;

class AuthController {
    /**
     * Display login page
     */
    public function loginPage() {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
            exit;
        }
        
        require_once BASE_PATH . '/app/views/auth/login.php';
    }
    
    /**
     * Process login
     */
    public function login() {
        // Validate input
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            $_SESSION['error'] = 'Username dan password harus diisi';
            header('Location: /login');
            exit;
        }
        
        $username = $this->sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        
        // Authenticate user
        $user = new User();
        $userData = $user->findByUsername($username);
        
        if ($userData && password_verify($password, $userData['password'])) {
            // Set session
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            $_SESSION['name'] = $userData['name'];
            
            // Redirect based on role
            $this->redirectBasedOnRole();
        } else {
            $_SESSION['error'] = 'Username atau password salah';
            header('Location: /login');
        }
    }
    
    /**
     * Display registration page
     */
    public function registerPage() {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
            exit;
        }
        
        require_once BASE_PATH . '/app/views/auth/register.php';
    }
    
    /**
     * Process registration
     */
    public function register() {
        // Validate input
        $requiredFields = ['name', 'username', 'password', 'confirm_password', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /register');
                exit;
            }
        }
        
        // Validate password match
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = 'Password tidak cocok';
            header('Location: /register');
            exit;
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $username = $this->sanitizeInput($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $address = $this->sanitizeInput($_POST['address']);
        $phone = $this->sanitizeInput($_POST['phone']);
        
        // Check if username already exists
        $user = new User();
        if ($user->usernameExists($username)) {
            $_SESSION['error'] = 'Username sudah digunakan';
            header('Location: /register');
            exit;
        }
        
        // Create user
        $result = $user->create([
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'role' => 'warga', // Default role is resident
            'address' => $address,
            'phone' => $phone
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Registrasi berhasil, silahkan login';
            header('Location: /login');
        } else {
            $_SESSION['error'] = 'Registrasi gagal, silahkan coba lagi';
            header('Location: /register');
        }
    }
    
    /**
     * Process logout
     */
    public function logout() {
        // Destroy session
        session_unset();
        session_destroy();
        
        // Redirect to login
        header('Location: /login');
    }
    
    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole() {
        if ($_SESSION['role'] === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /dashboard');
        }
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
