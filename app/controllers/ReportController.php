<?php
namespace app\controllers;

use app\models\Report;

class ReportController {
    /**
     * Constructor - Check if user is logged in
     */
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is admin for admin-only methods
        $adminMethods = ['adminIndex', 'adminView', 'updateStatus'];
        $currentMethod = debug_backtrace()[1]['function'];
        
        if (in_array($currentMethod, $adminMethods) && $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }
    
    /**
     * Report list for admin
     */
    public function adminIndex() {
        $reportModel = new Report();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search and filter
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        $reports = $reportModel->getAll($limit, $offset, $search, $status);
        $totalReports = $reportModel->countAll($search, $status);
        $totalPages = ceil($totalReports / $limit);
        
        $data = [
            'reports' => $reports,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status
        ];
        
        require_once BASE_PATH . '/app/views/admin/reports.php';
    }
    
    /**
     * View report details for admin
     */
    public function adminView($id) {
        $reportModel = new Report();
        $report = $reportModel->findById($id);
        
        if (!$report) {
            $_SESSION['error'] = 'Laporan tidak ditemukan';
            header('Location: /admin/reports');
            exit;
        }
        
        $data = ['report' => $report];
        require_once BASE_PATH . '/app/views/admin/view_report.php';
    }
    
    /**
     * Update report status
     */
    public function updateStatus($id) {
        // Validate input
        if (!isset($_POST['status']) || empty($_POST['status'])) {
            $_SESSION['error'] = 'Status harus dipilih';
            header("Location: /admin/report/$id");
            exit;
        }
        
        $status = $this->sanitizeInput($_POST['status']);
        $response = isset($_POST['response']) ? $this->sanitizeInput($_POST['response']) : '';
        
        // Validate status
        $validStatuses = ['pending', 'in_progress', 'resolved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Status tidak valid';
            header("Location: /admin/report/$id");
            exit;
        }
        
        $reportModel = new Report();
        $report = $reportModel->findById($id);
        
        if (!$report) {
            $_SESSION['error'] = 'Laporan tidak ditemukan';
            header('Location: /admin/reports');
            exit;
        }
        
        // Update status
        $result = $reportModel->updateStatus($id, $status, $response);
        
        if ($result) {
            $_SESSION['success'] = 'Status laporan berhasil diperbarui';
            header("Location: /admin/report/view/$id");
        } else {
            $_SESSION['error'] = 'Gagal memperbarui status laporan';
            header("Location: /admin/report/$id");
        }
    }
    
    /**
     * Add report page for resident
     */
    public function addPage() {
        require_once BASE_PATH . '/app/views/resident/add_report.php';
    }
    
    /**
     * Add report process for resident
     */
    public function add() {
        // Validate input
        $requiredFields = ['title', 'description', 'category'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /report/add');
                exit;
            }
        }
        
        // Sanitize input
        $title = $this->sanitizeInput($_POST['title']);
        $description = $this->sanitizeInput($_POST['description']);
        $category = $this->sanitizeInput($_POST['category']);
        
        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/reports/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate unique filename
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $filename;
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = 'Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan';
                header('Location: /report/add');
                exit;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = '/uploads/reports/' . $filename;
            } else {
                $_SESSION['error'] = 'Gagal mengupload gambar';
                header('Location: /report/add');
                exit;
            }
        }
        
        // Create report
        $reportModel = new Report();
        $result = $reportModel->create([
            'user_id' => $_SESSION['user_id'],
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'image' => $imagePath,
            'status' => 'pending' // Default status
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Laporan berhasil dikirim';
            header('Location: /reports');
        } else {
            $_SESSION['error'] = 'Gagal mengirim laporan';
            header('Location: /report/add');
        }
    }
    
    /**
     * View report details for resident
     */
    public function view($id) {
        $reportModel = new Report();
        $report = $reportModel->findById($id);
        
        if (!$report) {
            $_SESSION['error'] = 'Laporan tidak ditemukan';
            header('Location: /reports');
            exit;
        }
        
        // Check if user is owner of the report or admin
        if ($report['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke laporan ini';
            header('Location: /reports');
            exit;
        }
        
        $data = ['report' => $report];
        require_once BASE_PATH . '/app/views/resident/view_reports.php';
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
