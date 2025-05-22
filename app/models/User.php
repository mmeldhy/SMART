<?php
namespace app\models;

class User {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|false User data or false if not found
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
    
    /**
     * Find user by ID
     * 
     * @param int $id
     * @return array|false User data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Check if username already exists
     * 
     * @param string $username
     * @return bool
     */
    public function usernameExists($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }
    
    /**
     * Create a new user
     * 
     * @param array $data User data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, username, password, role, address, phone, created_at)
            VALUES (:name, :username, :password, :role, :address, :phone, NOW())
        ");
        
        return $stmt->execute([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => $data['role'],
            'address' => $data['address'],
            'phone' => $data['phone']
        ]);
    }
    
    /**
     * Update user
     * 
     * @param int $id User ID
     * @param array $data User data
     * @return bool Success status
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        // Build dynamic update fields
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        $stmt = $this->db->prepare("
            UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute($params);
    }
    
    /**
     * Delete user
     * 
     * @param int $id User ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get all residents (users with role 'warga')
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Residents
     */
    public function getResidents($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT * FROM users WHERE role = 'warga'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search1 OR username LIKE :search2 OR address LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        $sql .= " ORDER BY name ASC LIMIT :limit OFFSET :offset";

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
     * Count total residents
     * 
     * @param string $search Search term
     * @return int Total count
     */
    public function countResidents($search = '') {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'warga'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search1 OR username LIKE :search2 OR address LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
