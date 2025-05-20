<?php
namespace app\controllers;

use app\models\Schedule;

class ScheduleController {
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
        $adminMethods = ['index', 'addPage', 'add', 'editPage', 'update', 'delete'];
        $currentMethod = debug_backtrace()[1]['function'];
        
        if (in_array($currentMethod, $adminMethods) && $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }
    
    /**
     * Schedule list for admin
     */
    public function index() {
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
        
        require_once BASE_PATH . '/app/views/admin/schedules.php';
    }
    
    /**
     * Add schedule page
     */
    public function addPage() {
        require_once BASE_PATH . '/app/views/admin/add_schedule.php';
    }
    
    /**
     * Add schedule process
     */
    public function add() {
        // Validate input
        $requiredFields = ['title', 'description', 'schedule_date', 'schedule_time'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /admin/schedule/add');
                exit;
            }
        }
        
        // Sanitize input
        $title = $this->sanitizeInput($_POST['title']);
        $description = $this->sanitizeInput($_POST['description']);
        $scheduleDate = $_POST['schedule_date'];
        $scheduleTime = $_POST['schedule_time'];
        
        // Create schedule
        $scheduleModel = new Schedule();
        $result = $scheduleModel->create([
            'title' => $title,
            'description' => $description,
            'schedule_date' => $scheduleDate,
            'schedule_time' => $scheduleTime
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Jadwal berhasil ditambahkan';
            header('Location: /admin/schedules');
        } else {
            $_SESSION['error'] = 'Gagal menambahkan jadwal';
            header('Location: /admin/schedule/add');
        }
    }
    
    /**
     * Edit schedule page
     */
    public function editPage($id) {
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->findById($id);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan';
            header('Location: /admin/schedules');
            exit;
        }
        
        $data = ['schedule' => $schedule];
        require_once BASE_PATH . '/app/views/admin/edit_schedule.php';
    }
    
    /**
     * Update schedule process
     */
    public function update($id) {
        // Validate input
        $requiredFields = ['title', 'description', 'schedule_date', 'schedule_time'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header("Location: /admin/schedule/edit/$id");
                exit;
            }
        }
        
        // Sanitize input
        $title = $this->sanitizeInput($_POST['title']);
        $description = $this->sanitizeInput($_POST['description']);
        $scheduleDate = $_POST['schedule_date'];
        $scheduleTime = $_POST['schedule_time'];
        
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->findById($id);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan';
            header('Location: /admin/schedules');
            exit;
        }
        
        // Update schedule
        $result = $scheduleModel->update($id, [
            'title' => $title,
            'description' => $description,
            'schedule_date' => $scheduleDate,
            'schedule_time' => $scheduleTime
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Jadwal berhasil diperbarui';
            header('Location: /admin/schedules');
        } else {
            $_SESSION['error'] = 'Gagal memperbarui jadwal';
            header("Location: /admin/schedule/edit/$id");
        }
    }
    
    /**
     * View schedule page
     */
    public function view($id) {
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->findById($id);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan';
            header('Location: /admin/schedules');
            exit;
        }
        
        $data = ['schedule' => $schedule];
        require_once BASE_PATH . '/app/views/admin/view_schedule.php';
    }
    
    /**
     * Delete schedule
     */
    public function delete($id) {
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->findById($id);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan';
            header('Location: /admin/schedules');
            exit;
        }
        
        $result = $scheduleModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Jadwal berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus jadwal';
        }
        
        header('Location: /admin/schedules');
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
