<?php
namespace app\models;

class Announcement {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find announcement by ID
     * 
     * @param int $id
     * @return array|false Announcement data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM announcements WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all announcements
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Announcements
     */
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM announcements";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search OR content LIKE :search";
            $params['search'] = "%$search%";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
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
     * Count total announcements
     * 
     * @param string $search Search term
     * @return int Total count
     */
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM announcements";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search OR content LIKE :search";
            $params['search'] = "%$search%";
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
     * Create a new announcement
     * 
     * @param array $data Announcement data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO announcements (title, content, created_at)
            VALUES (:title, :content, NOW())
        ");
        
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    }
    
    /**
     * Update announcement
     * 
     * @param int $id Announcement ID
     * @param array $data Announcement data
     * @return bool Success status
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE announcements SET 
                title = :title,
                content = :content,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'content' => $data['content']
        ]);
    }
    
    /**
     * Delete announcement
     * 
     * @param int $id Announcement ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get recent announcements
     * 
     * @param int $limit Limit
     * @return array Recent announcements
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT * FROM announcements
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
