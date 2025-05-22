<?php
namespace app\models;

class Report {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find report by ID
     * 
     * @param int $id
     * @return array|false Report data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name as user_name
            FROM reports r
            JOIN users u ON r.user_id = u.id
            WHERE r.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all reports
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @param string $status Filter by status
     * @return array Reports
     */
    public function getAll($limit = 10, $offset = 0, $search = '', $status = '') {
        $sql = "
            SELECT r.*, u.name as user_name
            FROM reports r 
            JOIN users u ON r.user_id = u.id
            WHERE 1=1
        ";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (r.title LIKE :search OR r.description LIKE :search OR u.name LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if (!empty($status)) {
            $sql .= " AND r.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY r.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count total reports
     * 
     * @param string $search Search term
     * @param string $status Filter by status
     * @return int Total count
     */
    public function countAll($search = '', $status = '') {
        $sql = "
            SELECT COUNT(*)
            FROM reports r
            JOIN users u ON r.user_id = u.id
            WHERE 1=1
        ";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (r.title LIKE :search OR r.description LIKE :search OR u.name LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if (!empty($status)) {
            $sql .= " AND r.status = :status";
            $params['status'] = $status;
        }
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Count reports by status
     * 
     * @param string $status Status
     * @return int Count
     */
    public function countByStatus($status) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM reports WHERE status = :status
        ");
        
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Create a new report
     * 
     * @param array $data Report data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO reports (user_id, title, description, category, image, status, created_at)
            VALUES (:user_id, :title, :description, :category, :image, :status, NOW())
        ");
        
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'image' => $data['image'],
            'status' => $data['status']
        ]);
    }
    
    /**
     * Update report status
     * 
     * @param int $id Report ID
     * @param string $status New status
     * @param string $response Admin response
     * @return bool Success status
     */
    public function updateStatus($id, $status, $response = '') {
        $stmt = $this->db->prepare("
            UPDATE reports SET 
                status = :status,
                admin_response = :response,
                updated_at = NOW()
            WHERE id = :id
        ");
        

        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'response' => $response
        ]);
    }
    
    /**
     * Get recent reports
     * 
     * @param int $limit Limit
     * @return array Recent reports
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name as user_name
            FROM reports r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get reports for a specific user
     * 
     * @param int $userId User ID
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Reports
     */
    public function getForUser($userId, $limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM reports
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count reports for a specific user
     * 
     * @param int $userId User ID
     * @return int Count
     */
    public function countForUser($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM reports
            WHERE user_id = :user_id
        ");
        
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }
}
