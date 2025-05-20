<?php
namespace app\controllers;

use app\models\Fee;
use app\models\Payment;
use app\models\User;

class FeeController {
    /**
     * Constructor - Check if user is admin
     */
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin for admin-only methods
        $adminMethods = ['index', 'addPage', 'add', 'editPage', 'update', 'delete', 'payments'];
        $currentMethod = debug_backtrace()[1]['function'];
        
        if (in_array($currentMethod, $adminMethods) && $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }
    
    /**
     * Fee list for admin
     */
    public function index() {
        $feeModel = new Fee();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $fees = $feeModel->getAll($limit, $offset, $search);
        $totalFees = $feeModel->countAll($search);
        $totalPages = ceil($totalFees / $limit);
        
        $data = [
            'fees' => $fees,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];
        
        require_once BASE_PATH . '/app/views/admin/fees.php';
    }
    
    /**
     * Add fee page
     */
    public function addPage() {
        require_once BASE_PATH . '/app/views/admin/add_fee.php';
    }
    
    /**
     * Add fee process
     */
    public function add() {
        // Validate input
        $requiredFields = ['name', 'description', 'amount', 'due_date'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /admin/fee/add');
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $description = $this->sanitizeInput($_POST['description']);
        $amount = (float) $_POST['amount'];
        $dueDate = $_POST['due_date'];
        
        // Validate amount
        if ($amount <= 0) {
            $_SESSION['error'] = 'Jumlah iuran harus lebih dari 0';
            header('Location: /admin/fee/add');
            exit;
        }
        
        // Create fee
        $feeModel = new Fee();
        $result = $feeModel->create([
            'name' => $name,
            'description' => $description,
            'amount' => $amount,
            'due_date' => $dueDate
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Iuran berhasil ditambahkan';
            header('Location: /admin/fees');
        } else {
            $_SESSION['error'] = 'Gagal menambahkan iuran';
            header('Location: /admin/fee/add');
        }
    }
    
    /**
     * Edit fee page
     */
    public function editPage($id) {
        $feeModel = new Fee();
        $fee = $feeModel->findById($id);
        
        if (!$fee) {
            $_SESSION['error'] = 'Iuran tidak ditemukan';
            header('Location: /admin/fees');
            exit;
        }
        
        $data = ['fee' => $fee];
        require_once BASE_PATH . '/app/views/admin/edit_fee.php';
    }
    
    /**
     * Update fee process
     */
    public function update($id) {
        // Validate input
        $requiredFields = ['name', 'description', 'amount', 'due_date'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header("Location: /admin/fee/edit/$id");
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $description = $this->sanitizeInput($_POST['description']);
        $amount = (float) $_POST['amount'];
        $dueDate = $_POST['due_date'];
        
        // Validate amount
        if ($amount <= 0) {
            $_SESSION['error'] = 'Jumlah iuran harus lebih dari 0';
            header("Location: /admin/fee/edit/$id");
            exit;
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->findById($id);
        
        if (!$fee) {
            $_SESSION['error'] = 'Iuran tidak ditemukan';
            header('Location: /admin/fees');
            exit;
        }
        
        // Update fee
        $result = $feeModel->update($id, [
            'name' => $name,
            'description' => $description,
            'amount' => $amount,
            'due_date' => $dueDate
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Iuran berhasil diperbarui';
            header('Location: /admin/fees');
        } else {
            $_SESSION['error'] = 'Gagal memperbarui iuran';
            header("Location: /admin/fee/edit/$id");
        }
    }
    
    /**
     * Delete fee
     */
    public function delete($id) {
        $feeModel = new Fee();
        $fee = $feeModel->findById($id);
        
        if (!$fee) {
            $_SESSION['error'] = 'Iuran tidak ditemukan';
            header('Location: /admin/fees');
            exit;
        }
        
        $result = $feeModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Iuran berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus iuran';
        }
        
        header('Location: /admin/fees');
    }
    
    /**
     * Payment list for admin
     */
    public function payments() {
        $paymentModel = new Payment();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $payments = $paymentModel->getAll($limit, $offset, $search);
        $totalPayments = $paymentModel->countAll($search);
        $totalPages = ceil($totalPayments / $limit);
        
        $data = [
            'payments' => $payments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];
        
        require_once BASE_PATH . '/app/views/admin/payments.php';
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
