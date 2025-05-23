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
        $adminMethods = ['index', 'addPage', 'add', 'editPage', 'update', 'delete', 'view']; // Added 'view' to admin methods
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
        
        // Calculate start and end record numbers for display
        $startRecord = ($page - 1) * $limit + 1;
        $endRecord = min($page * $limit, $totalSchedules);

        $data = [
            'schedules' => $schedules,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalSchedules,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
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
        $requiredFields = ['title', 'description', 'schedule_date', 'schedule_time', 'location', 'type', 'status'];
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
        $location = $this->sanitizeInput($_POST['location']);
        $type = $_POST['type'];
        $status = $_POST['status'];

        $scheduleDatetime = $scheduleDate . ' ' . $scheduleTime;
         
         // Create schedule
         $scheduleModel = new Schedule();
         $result = $scheduleModel->create([
             'title' => $title,
             'description' => $description,
			 'schedule_datetime' => $scheduleDatetime,
             'location' => $location,
             'type' => $type,
             'status' => $status
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
        
        // Parse datetime into separate date and time for form fields
        $schedule['date'] = date('Y-m-d', strtotime($schedule['schedule_datetime']));
        $schedule['start_time'] = date('H:i', strtotime($schedule['schedule_datetime']));
        // Assume end_time is not stored, or is derived from start_time + duration
        // For simplicity, let's just use a default or derive from description if possible.
        // Or if end_time is *always* stored, add it to the DB schema.
        // For now, let's make end_time a default for the form.
        $schedule['end_time'] = date('H:i', strtotime($schedule['schedule_datetime'] . ' +2 hours')); // Example: default 2 hours later

        $data = ['schedule' => $schedule];
        require_once BASE_PATH . '/app/views/admin/edit_schedule.php';
    }
    
    /**
     * Update schedule process
     */
    public function update($id) {
        // Validate input
        $requiredFields = ['title', 'description', 'schedule_date', 'schedule_time', 'location', 'type', 'status'];
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
        $location = $this->sanitizeInput($_POST['location']);
        $type = $_POST['type'];
        $status = $_POST['status'];

        $scheduleDatetime = $scheduleDate . ' ' . $scheduleTime;
         
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
			 'schedule_datetime' => $scheduleDatetime,
             'location' => $location,
             'type' => $type,
             'status' => $status
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
     * View schedule page (Admin)
     */
    public function view($id) {
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->findById($id);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan';
            header('Location: /admin/schedules');
            exit;
        }
        
        // Parse datetime into separate date and time for display
        $schedule['date'] = date('Y-m-d', strtotime($schedule['schedule_datetime']));
        $schedule['start_time'] = date('H:i', strtotime($schedule['schedule_datetime']));
        $schedule['end_time'] = date('H:i', strtotime($schedule['schedule_datetime'] . ' +2 hours')); // Assuming a default end time

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