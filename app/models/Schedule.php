<?php
namespace app\models;

class Schedule {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find schedule by ID
     * * @param int $id
     * @return array|false Schedule data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM schedules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all schedules
     * * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Schedules
     */
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM schedules WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search1 OR description LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $sql .= " ORDER BY schedule_datetime ASC LIMIT :limit OFFSET :offset";

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
     * Count total schedules
     * * @param string $search Search term
     * @return int Total count
     */
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM schedules WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search1 OR description LIKE :search2)";
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
     * Create a new schedule
     * * @param array $data Schedule data
     * @return bool Success status
     */
    public function create($data) {
		$stmt = $this->db->prepare("
            INSERT INTO schedules (title, description, schedule_datetime, location, type, status, created_at)
            VALUES (:title, :description, :schedule_datetime, :location, :type, :status, NOW())
        ");
         
         return $stmt->execute([
             'title' => $data['title'],
             'description' => $data['description'],
			 'schedule_datetime' => $data['schedule_datetime'],
             'location' => $data['location'],
             'type' => $data['type'],
             'status' => $data['status']        ]);
     }
     
     /**
      * Update schedule
      * * @param int $id Schedule ID
      * @param array $data Schedule data
      * @return bool Success status
      */
     public function update($id, $data) {
		$stmt = $this->db->prepare("
             UPDATE schedules SET 
                 title = :title,
                 description = :description,
				schedule_datetime = :schedule_datetime,
                location = :location,
                type = :type,
                status = :status,
                 updated_at = NOW()
             WHERE id = :id
         ");
 
		return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'schedule_datetime' => $data['schedule_datetime'],
            'location' => $data['location'],
            'type' => $data['type'],
            'status' => $data['status']
        ]);
      }
     
     /**
     * Delete schedule
     * * @param int $id Schedule ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM schedules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get upcoming schedules
     * * @param int $limit Limit
     * @return array Upcoming schedules
     */
    public function getUpcoming($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT * FROM schedules 
            WHERE schedule_datetime >= NOW()
            ORDER BY schedule_datetime ASC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}