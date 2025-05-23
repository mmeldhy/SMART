<?php
namespace app\controllers;

use app\models\Announcement;

class AnnouncementController {
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
     * Announcement list for admin
     */
    public function index() {
        $announcementModel = new Announcement();

        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Filters
        $type = $_GET['type'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        $search = $_GET['search'] ?? '';

        $announcements = $announcementModel->getAll($limit, $offset, $type, $date_from, $date_to, $search);
        $totalAnnouncements = $announcementModel->countAll($type, $date_from, $date_to, $search);
        $totalPages = ceil($totalAnnouncements / $limit);

        $startRecord = ($page - 1) * $limit + 1;
        $endRecord = min($page * $limit, $totalAnnouncements);

        require_once BASE_PATH . '/app/views/admin/announcements.php';
    }
    
    /**
     * Add announcement page
     */
    public function addPage() {
        require_once BASE_PATH . '/app/views/admin/add_announcement.php';
    }
    
    /**
     * Add announcement process
     */
    public function add() {
        // Validate input
        $requiredFields = ['title', 'content'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: /admin/announcement/add');
                exit;
            }
        }
        
        // Sanitize input
        $title = $this->sanitizeInput($_POST['title']);
        $content = $_POST['content']; // Allow HTML in content, but consider sanitization
        $type = $_POST['type'] ?? 'general';
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;

        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/announcements/'; // New directory for announcements
            
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
                header('Location: /admin/announcement/add');
                exit;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = '/uploads/announcements/' . $filename;
            } else {
                $_SESSION['error'] = 'Gagal mengupload gambar';
                header('Location: /admin/announcement/add');
                exit;
            }
        }
        
        // Create announcement
        $announcementModel = new Announcement();
        $result = $announcementModel->create([
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_pinned' => $isPinned,
            'image_url' => $imagePath // Save image path
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Pengumuman berhasil ditambahkan';
            header('Location: /admin/announcements');
        } else {
            $_SESSION['error'] = 'Gagal menambahkan pengumuman';
            header('Location: /admin/announcement/add');
        }
    }
    
    /**
     * Edit announcement page
     */
    public function editPage($id) {
        $announcementModel = new Announcement();
        $announcement = $announcementModel->findById($id);
        
        if (!$announcement) {
            $_SESSION['error'] = 'Pengumuman tidak ditemukan';
            header('Location: /admin/announcements');
            exit;
        }
        
        $data = ['announcement' => $announcement];
        require_once BASE_PATH . '/app/views/admin/edit_announcement.php';
    }
    
    /**
     * Update announcement process
     */
    public function update($id) {
        // Validate input
        $requiredFields = ['title', 'content'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header("Location: /admin/announcement/edit/$id");
                exit;
            }
        }
        
        // Sanitize input
        $title = $this->sanitizeInput($_POST['title']);
        $content = $_POST['content']; // Allow HTML in content, but consider sanitization
        $type = $_POST['type'] ?? 'general';
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $removeImage = isset($_POST['remove_image']) ? 1 : 0;

        $announcementModel = new Announcement();
        $announcement = $announcementModel->findById($id);
        
        if (!$announcement) {
            $_SESSION['error'] = 'Pengumuman tidak ditemukan';
            header('Location: /admin/announcements');
            exit;
        }
        
        $imagePath = $announcement['image_url']; // Keep existing image path by default

        // Handle image removal
        if ($removeImage && !empty($imagePath)) {
            // Delete old image file
            if (file_exists(BASE_PATH . '/public' . $imagePath)) {
                unlink(BASE_PATH . '/public' . $imagePath);
            }
            $imagePath = null; // Clear image path in DB
        }

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/announcements/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Delete old image if a new one is uploaded
            if (!empty($announcement['image_url']) && file_exists(BASE_PATH . '/public' . $announcement['image_url'])) {
                unlink(BASE_PATH . '/public' . $announcement['image_url']);
            }
            
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $filename;
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = 'Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan';
                header("Location: /admin/announcement/edit/$id");
                exit;
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = '/uploads/announcements/' . $filename;
            } else {
                $_SESSION['error'] = 'Gagal mengupload gambar baru';
                header("Location: /admin/announcement/edit/$id");
                exit;
            }
        }

        // Update announcement
        $result = $announcementModel->update($id, [
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_pinned' => $isPinned,
            'image_url' => $imagePath // Update image path
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Pengumuman berhasil diperbarui';
            header('Location: /admin/announcements');
        } else {
            $_SESSION['error'] = 'Gagal memperbarui pengumuman';
            header("Location: /admin/announcement/edit/$id");
        }
    }
    
    /**
     * Delete announcement
     */
    public function delete($id) {
        $announcementModel = new Announcement();
        $announcement = $announcementModel->findById($id);
        
        if (!$announcement) {
            $_SESSION['error'] = 'Pengumuman tidak ditemukan';
            header('Location: /admin/announcements');
            exit;
        }
        
        // Delete associated image file
        if (!empty($announcement['image_url']) && file_exists(BASE_PATH . '/public' . $announcement['image_url'])) {
            unlink(BASE_PATH . '/public' . $announcement['image_url']);
        }

        $result = $announcementModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Pengumuman berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengumuman';
        }
        
        header('Location: /admin/announcements');
    }
    
    /**
     * View a single announcement
     */
    public function view($id) {
        $announcementModel = new Announcement();
        $announcement = $announcementModel->findById($id);

        if (!$announcement) {
            $_SESSION['error'] = 'Pengumuman tidak ditemukan';
            header('Location: /admin/announcements');
            exit;
        }

        $data = ['announcement' => $announcement];
        require_once BASE_PATH . '/app/views/admin/view_announcement.php'; // Create a view template for displaying a single announcement
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}