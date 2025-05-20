<?php
namespace app\controllers;

use app\models\User;
use app\models\Fee;
use app\models\Payment;
use app\models\Announcement;
use app\models\Schedule;
use app\models\Report;

class ResidentController {
    /**
     * Constructor - Check if user is logged in and is a resident
     */
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user is a resident (not admin)
        if ($_SESSION['role'] === 'admin') {
            header('Location: /admin/dashboard');
            exit;
        }
    }
    
    /**
     * Resident dashboard
     */
    public function dashboard() {
        $feeModel = new Fee();
        $announcementModel = new Announcement();
        $scheduleModel = new Schedule();
        $reportModel = new Report();
        
        // Get recent data for dashboard
        $data = [
            'activeFees' => $feeModel->getActive(),
            'recentAnnouncements' => $announcementModel->getRecent(3),
            'upcomingSchedules' => $scheduleModel->getUpcoming(3),
            'recentReports' => $reportModel->getForUser($_SESSION['user_id'], 3, 0)
        ];
        
        require_once BASE_PATH . '/app/views/resident/dashboard.php';
    }
    
    /**
     * User profile
     */
    public function profile() {
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan';
            header('Location: /dashboard');
            exit;
        }
        
        $data = ['user' => $user];
        require_once BASE_PATH . '/app/views/resident/profile.php';
    }
    
    /**
     * Update profile
     */
    public function updateProfile() {
        // Validate input
        $requiredFields = ['name', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /profile');
                exit;
            }
        }
        
        // Sanitize input
        $name = $this->sanitizeInput($_POST['name']);
        $address = $this->sanitizeInput($_POST['address']);
        $phone = $this->sanitizeInput($_POST['phone']);
        
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan';
            header('Location: /dashboard');
            exit;
        }
        
        // Prepare update data
        $updateData = [
            'name' => $name,
            'address' => $address,
            'phone' => $phone
        ];
        
        // Update password if provided
        if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Password tidak cocok';
                header('Location: /profile');
                exit;
            }
            
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        
        // Update user
        $result = $userModel->update($_SESSION['user_id'], $updateData);
        
        if ($result) {
            $_SESSION['success'] = 'Profil berhasil diperbarui';
            // Update session name
            $_SESSION['name'] = $name;
            header('Location: /profile');
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profil';
            header('Location: /profile');
        }
    }
    
    /**
     * Fee list for resident
     */
    public function fees() {
        $feeModel = new Fee();
        $fees = $feeModel->getForUser($_SESSION['user_id']);
        
        $data = ['fees' => $fees];
        require_once BASE_PATH . '/app/views/resident/fees.php';
    }
    
    /**
     * Fee detail for resident
     */
    public function feeDetail($id) {
        $feeModel = new Fee();
        $fee = $feeModel->findById($id);
        
        if (!$fee) {
            $_SESSION['error'] = 'Iuran tidak ditemukan';
            header('Location: /fees');
            exit;
        }
        
        // Check if already paid
        $paymentModel = new Payment();
        $isPaid = $paymentModel->hasPaid($_SESSION['user_id'], $id);
        
        $data = [
            'fee' => $fee,
            'isPaid' => $isPaid
        ];
        
        require_once BASE_PATH . '/app/views/resident/fee_detail.php';
    }
    
    /**
     * Pay fee process
     */
    public function payFee($id) {
        $feeModel = new Fee();
        $fee = $feeModel->findById($id);
        
        if (!$fee) {
            $_SESSION['error'] = 'Iuran tidak ditemukan';
            header('Location: /fees');
            exit;
        }
        
        // Check if already paid
        $paymentModel = new Payment();
        if ($paymentModel->hasPaid($_SESSION['user_id'], $id)) {
            $_SESSION['error'] = 'Iuran ini sudah dibayar';
            header("Location: /fee/$id");
            exit;
        }
        
        // Handle proof image upload
        $imagePath = '';
        if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/payments/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate unique filename
            $filename = uniqid() . '_' . basename($_FILES['proof_image']['name']);
            $uploadFile = $uploadDir . $filename;
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['proof_image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = 'Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan';
                header("Location: /fee/$id");
                exit;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $uploadFile)) {
                $imagePath = '/uploads/payments/' . $filename;
            } else {
                $_SESSION['error'] = 'Gagal mengupload bukti pembayaran';
                header("Location: /fee/$id");
                exit;
            }
        }
        
        // Create payment
        $result = $paymentModel->create([
            'user_id' => $_SESSION['user_id'],
            'fee_id' => $id,
            'payment_date' => date('Y-m-d'),
            'proof_image' => $imagePath,
            'status' => 'pending' // Default status
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Pembayaran berhasil disubmit dan menunggu verifikasi admin';
            header('Location: /fees');
        } else {
            $_SESSION['error'] = 'Gagal melakukan pembayaran';
            header("Location: /fee/$id");
        }
    }
    
    /**
     * Announcement list for resident
     */
    public function announcements() {
        $announcementModel = new Announcement();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $announcements = $announcementModel->getAll($limit, $offset, $search);
        $totalAnnouncements = $announcementModel->countAll($search);
        $totalPages = ceil($totalAnnouncements / $limit);
        
        $data = [
            'announcements' => $announcements,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];
        
        require_once BASE_PATH . '/app/views/resident/announcements.php';
    }
    
    /**
     * Announcement detail for resident
     */
    public function announcementDetail($id) {
        $announcementModel = new Announcement();
        $announcement = $announcementModel->findById($id);
        
        if (!$announcement) {
            $_SESSION['error'] = 'Pengumuman tidak ditemukan';
            header('Location: /announcements');
            exit;
        }
        
        $data = ['announcement' => $announcement];
        require_once BASE_PATH . '/app/views/resident/announcement_detail.php';
    }
    
    /**
     * Schedule list for resident
     */
    public function schedules() {
        $scheduleModel = new Schedule();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $schedules = $scheduleModel->getAll($limit, $offset, $search);
        $totalSchedules = $scheduleModel->countAll($search);
        $totalPages = ceil($totalSchedules / $limit);
        
        $data = [
            'schedules' => $schedules,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ];
        
        require_once BASE_PATH . '/app/views/resident/schedules.php';
    }
    
    /**
     * Report list for resident
     */
    public function reports() {
        $reportModel = new Report();
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $reports = $reportModel->getForUser($_SESSION['user_id'], $limit, $offset);
        $totalReports = $reportModel->countForUser($_SESSION['user_id']);
        $totalPages = ceil($totalReports / $limit);
        
        $data = [
            'reports' => $reports,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
        
        require_once BASE_PATH . '/app/views/resident/reports.php';
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
