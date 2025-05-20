<?php
namespace app\controllers;

use app\models\User;
use app\models\Fee;
use app\models\Payment;
use app\models\Announcement;
use app\models\Report;

class AdminController {
    /**
     * Constructor - Check if user is admin
     */
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Get counts for dashboard
        $userModel = new User();
        $feeModel = new Fee();
        $paymentModel = new Payment();
        $announcementModel = new Announcement();
        $reportModel = new Report();
        
        $data = [
            'residentCount' => $userModel->countResidents(),
            'feeCount' => $feeModel->countAll(),
            'paymentCount' => $paymentModel->countAll(),
            'pendingPaymentCount' => $paymentModel->countPending(),
            'announcementCount' => $announcementModel->countAll(),
            'reportCount' => $reportModel->countAll(),
            'pendingReportCount' => $reportModel->countByStatus('pending')
        ];
        
        // Get recent activities
        $data['recentPayments'] = $paymentModel->getRecent(5);
        $data['recentReports'] = $reportModel->getRecent(5);
        
        require_once BASE_PATH . '/app/views/admin/dashboard.php';
    }
    
    /**
     * Residents list
     */
    public function residents() {
        $userModel = new User();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $residents = $userModel->getResidents($limit, $offset, $search);
        $totalResidents = $userModel->countResidents($search);
        $totalPages = ceil($totalResidents / $limit);
        
        $data = [
            'residents' => $residents,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];
        
        require_once BASE_PATH . '/app/views/admin/residents.php';
    }
    
    /**
     * Add resident page
     */
    public function addResidentPage() {
        require_once BASE_PATH . '/app/views/admin/add_resident.php';
    }
    
    /**
     * Add resident process
     */
    public function addResident() {
        // Validate input
        $requiredFields = ['name', 'username', 'password', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /admin/resident/add');
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $username = $this->sanitizeInput($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $address = $this->sanitizeInput($_POST['address']);
        $phone = $this->sanitizeInput($_POST['phone']);
        
        // Check if username already exists
        $userModel = new User();
        if ($userModel->usernameExists($username)) {
            $_SESSION['error'] = 'Username sudah digunakan';
            header('Location: /admin/resident/add');
            exit;
        }
        
        // Create user
        $result = $userModel->create([
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'role' => 'warga',
            'address' => $address,
            'phone' => $phone
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Warga berhasil ditambahkan';
            header('Location: /admin/residents');
        } else {
            $_SESSION['error'] = 'Gagal menambahkan warga';
            header('Location: /admin/resident/add');
        }
    }
    
    /**
     * Edit resident page
     */
    public function editResidentPage($id) {
        $userModel = new User();
        $resident = $userModel->findById($id);
        
        if (!$resident) {
            $_SESSION['error'] = 'Warga tidak ditemukan';
            header('Location: /admin/residents');
            exit;
        }
        
        $data = ['resident' => $resident];
        require_once BASE_PATH . '/app/views/admin/edit_resident.php';
    }
    
    /**
     * Update resident process
     */
    public function updateResident($id) {
        // Validate input
        $requiredFields = ['name', 'username', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header("Location: /admin/resident/edit/$id");
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $username = $this->sanitizeInput($_POST['username']);
        $address = $this->sanitizeInput($_POST['address']);
        $phone = $this->sanitizeInput($_POST['phone']);
        
        $userModel = new User();
        $resident = $userModel->findById($id);
        
        if (!$resident) {
            $_SESSION['error'] = 'Warga tidak ditemukan';
            header('Location: /admin/residents');
            exit;
        }
        
        // Check if username already exists (if changed)
        if ($username !== $resident['username'] && $userModel->usernameExists($username)) {
            $_SESSION['error'] = 'Username sudah digunakan';
            header("Location: /admin/resident/edit/$id");
            exit;
        }
        
        // Prepare update data
        $updateData = [
            'name' => $name,
            'username' => $username,
            'address' => $address,
            'phone' => $phone
        ];
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        
        // Update user
        $result = $userModel->update($id, $updateData);
        
        if ($result) {
            $_SESSION['success'] = 'Data warga berhasil diperbarui';
            header('Location: /admin/residents');
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data warga';
            header("Location: /admin/resident/edit/$id");
        }
    }
    
    /**
     * Delete resident
     */
    public function deleteResident($id) {
        $userModel = new User();
        $resident = $userModel->findById($id);
        
        if (!$resident) {
            $_SESSION['error'] = 'Warga tidak ditemukan';
            header('Location: /admin/residents');
            exit;
        }
        
        $result = $userModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Warga berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus warga';
        }
        
        header('Location: /admin/residents');
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
