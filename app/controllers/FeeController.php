<?php
// filepath: app/controllers/FeeController.php

namespace app\controllers;

use app\models\Fee;
use app\models\Payment;
use app\models\User;

class FeeController {
    /**
     * Database connection
     * @var \PDO
     */
    private $db;

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
        $adminMethods = ['index', 'addPage', 'add', 'editPage', 'update', 'delete', 'payments', 'paymentDetail', 'updatePaymentStatus'];
        $currentMethod = debug_backtrace()[1]['function'];
        
        if (in_array($currentMethod, $adminMethods) && $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        // Get database connection
        try {
            $this->db = \Database::getInstance()->getConnection();
        } catch (\Exception $e) {
            // Log the error
            error_log('Database connection error: ' . $e->getMessage());
            // Display a user-friendly error message
            $_SESSION['error'] = 'Terjadi kesalahan pada koneksi database. Silakan coba lagi nanti.';
            header('Location: /admin/payments'); // Redirect to a safe page
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
        $requiredFields = ['title', 'description', 'amount', 'due_date'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /admin/fee/add');
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['title']);
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
        $requiredFields = ['title', 'description', 'amount', 'due_date'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header("Location: /admin/fee/edit/$id");
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['title']);
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
        $feeModel = new Fee();
    
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        // Filters
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $fee_id = $_GET['fee_id'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
    
        $payments = $paymentModel->getAll($limit, $offset, $search, $status, $fee_id, $date_from, $date_to);
        $totalPayments = $paymentModel->countAll($search, $status, $fee_id, $date_from, $date_to);
        $totalPages = ceil($totalPayments / $limit);
    
        // Fetch fees for the filter dropdown
        $fees = $feeModel->getAll(); // This gets all fees, might need a more specific method if you have many fees
    
        // Calculate start and end record numbers for display
        $startRecord = ($page - 1) * $limit + 1;
        $endRecord = min($page * $limit, $totalPayments);
    
        $data = [
            'payments' => $payments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalPayments,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'fees' => $fees,
            'status' => $status,
            'fee_id' => $fee_id,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];
    
        require_once BASE_PATH . '/app/views/admin/payments.php';
    }

    /**
     * Payment detail for admin
     */
    public function paymentDetail($id) {
        $paymentModel = new Payment();
        
        // Fetch payment details including user and fee info
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as resident_name, u.phone as resident_phone, u.address as resident_address,
                   f.name as fee_name, f.amount as fee_amount, f.description as fee_description
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $payment = $stmt->fetch();
    
        if (!$payment) {
            $_SESSION['error'] = 'Payment not found';
            header('Location: /admin/payments');
            exit;
        }
    
        $data = [
            'payment' => $payment // Correctly passing $payment to the view
        ];
    
        require_once BASE_PATH . '/app/views/admin/view_payments.php';
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus($id) {
        // Validate input
        if (!isset($_POST['status']) || empty($_POST['status'])) {
            $_SESSION['error'] = 'Status harus dipilih';
            header("Location: /admin/payment/$id");
            exit;
        }

        $status = $this->sanitizeInput($_POST['status']);
        $admin_notes = isset($_POST['admin_notes']) ? $this->sanitizeInput($_POST['admin_notes']) : '';

        // Validate status
        $validStatuses = ['pending', 'approved', 'rejected']; // Changed 'verified' to 'approved' to match ENUM in DB
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Status tidak valid';
            header("Location: /admin/payment/$id");
            exit;
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->findById($id);

        if (!$payment) {
            $_SESSION['error'] = 'Pembayaran tidak ditemukan';
            header('Location: /admin/payments');
            exit;
        }

        // Update status and admin_notes in one go
        $stmt = $this->db->prepare("
            UPDATE payments SET
                status = :status,
                admin_response = :admin_notes, -- Changed from admin_notes to admin_response to match DB schema
                updated_at = NOW()
            WHERE id = :id
        ");

        $result = $stmt->execute([
            'id' => $id,
            'status' => $status,
            'admin_notes' => $admin_notes // Using the variable name from form input
        ]);

        if ($result) {
            $_SESSION['success'] = 'Status pembayaran berhasil diperbarui';
            header("Location: /admin/payment/$id"); 
        } else {
            $_SESSION['error'] = 'Gagal memperbarui status pembayaran';
            header("Location: /admin/payment/$id"); 
        }
    }

    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}