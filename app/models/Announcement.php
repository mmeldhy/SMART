<?php
namespace app\models;

class Announcement {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find announcement by ID
     * * @param int $id
     * @return array|false Announcement data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM announcements WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all announcements
     * * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Announcements
     */
    public function getAll($limit = 10, $offset = 0, $type = '', $date_from = '', $date_to = '', $search = '') {
        $sql = "SELECT * FROM announcements WHERE 1=1";
        $params = [];

        if (!empty($type)) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        if (!empty($date_from)) {
            $sql .= " AND (start_date IS NULL OR start_date >= :date_from)";
            $params['date_from'] = $date_from;
        }
        if (!empty($date_to)) {
            $sql .= " AND (end_date IS NULL OR end_date <= :date_to)";
            $params['date_to'] = $date_to;
        }
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search1 OR content LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count total announcements
     * * @param string $search Search term
     * @return int Total count
     */
    public function countAll($type = '', $date_from = '', $date_to = '', $search = '') {
        $sql = "SELECT COUNT(*) FROM announcements WHERE 1=1";
        $params = [];

        if (!empty($type)) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        if (!empty($date_from)) {
            $sql .= " AND (start_date IS NULL OR start_date >= :date_from)";
            $params['date_from'] = $date_from;
        }
        if (!empty($date_to)) {
            $sql .= " AND (end_date IS NULL OR end_date <= :date_to)";
            $params['date_to'] = $date_to;
        }
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search1 OR content LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Create a new announcement
     * * @param array $data Announcement data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO announcements (title, content, type, start_date, end_date, is_pinned, image_url, created_at)
            VALUES (:title, :content, :type, :start_date, :end_date, :is_pinned, :image_url, NOW())
        ");
        
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_pinned' => $data['is_pinned'],
            'image_url' => $data['image_url']
        ]);
    }
    
    /**
     * Update announcement
     * * @param int $id Announcement ID
     * @param array $data Announcement data
     * @return bool Success status
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE announcements SET 
                title = :title,
                content = :content,
                type = :type,
                start_date = :start_date,
                end_date = :end_date,
                is_pinned = :is_pinned,
                image_url = :image_url,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_pinned' => $data['is_pinned'],
            'image_url' => $data['image_url']
        ]);
    }
    
    /**
     * Delete announcement
     * * @param int $id Announcement ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get recent announcements
     * * @param int $limit Limit
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