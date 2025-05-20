<?php
namespace app\models;

class Schedule {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find schedule by ID
     * 
     * @param int $id
     * @return array|false Schedule data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM schedules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all schedules
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Schedules
     */
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM schedules";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search OR description LIKE :search";
            $params['search'] = "%$search%";
        }
        
        $sql .= " ORDER BY schedule_date ASC LIMIT :limit OFFSET :offset";
        
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
     * Count total schedules
     * 
     * @param string $search Search term
     * @return int Total count
     */
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM schedules";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE title LIKE :search OR description LIKE :search";
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
     * Create a new schedule
     * 
     * @param array $data Schedule data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO schedules (title, description, schedule_date, schedule_time, created_at)
            VALUES (:title, :description, :schedule_date, :schedule_time, NOW())
        ");
        
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'schedule_date' => $data['schedule_date'],
            'schedule_time' => $data['schedule_time']
        ]);
    }
    
    /**
     * Update schedule
     * 
     * @param int $id Schedule ID
     * @param array $data Schedule data
     * @return bool Success status
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE schedules SET 
                title = :title,
                description = :description,
                schedule_date = :schedule_date,
                schedule_time = :schedule_time,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'schedule_date' => $data['schedule_date'],
            'schedule_time' => $data['schedule_time']
        ]);
    }
    
    /**
     * Delete schedule
     * 
     * @param int $id Schedule ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM schedules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get upcoming schedules
     * 
     * @param int $limit Limit
     * @return array Upcoming schedules
     */
    public function getUpcoming($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT * FROM schedules
            WHERE schedule_date >= CURDATE()
            ORDER BY schedule_date ASC, schedule_time ASC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
