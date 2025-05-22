<?php
namespace app\models;

class Fee {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find fee by ID
     * 
     * @param int $id
     * @return array|false Fee data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fees WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get all fees
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Fees
     */
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM fees";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE name LIKE :search1 OR description LIKE :search2";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $sql .= " ORDER BY due_date DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count total fees
     * 
     * @param string $search Search term
     * @return int Total count
     */
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM fees";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE name LIKE :search1 OR description LIKE :search2";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Create a new fee
     * 
     * @param array $data Fee data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO fees (name, description, amount, due_date, created_at)
            VALUES (:name, :description, :amount, :due_date, NOW())
        ");
        
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date']
        ]);
    }
    
    /**
     * Update fee
     * 
     * @param int $id Fee ID
     * @param array $data Fee data
     * @return bool Success status
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE fees SET 
                name = :name,
                description = :description,
                amount = :amount,
                due_date = :due_date,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date']
        ]);
    }
    
    /**
     * Delete fee
     * 
     * @param int $id Fee ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM fees WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get active fees (due date not passed)
     * 
     * @return array Active fees
     */
    public function getActive() {
        $stmt = $this->db->prepare("
            SELECT * FROM fees 
            WHERE due_date >= CURDATE()
            ORDER BY due_date ASC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get fees for a specific user with payment status
     * 
     * @param int $userId User ID
     * @return array Fees with payment status
     */
    public function getForUser($userId) {
        $stmt = $this->db->prepare("
            SELECT f.*, 
                CASE WHEN p.id IS NOT NULL THEN 1 ELSE 0 END AS is_paid,
                p.payment_date,
                p.status AS payment_status
            FROM fees f
            LEFT JOIN payments p ON f.id = p.fee_id AND p.user_id = :user_id
            ORDER BY f.due_date DESC
        ");
        
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
