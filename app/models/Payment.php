<?php
namespace app\models;

class Payment {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }

    /**
     * Find payment by ID
     *
     * @param int $id
     * @return array|false Payment data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get all payments
     *
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $search Search term
     * @return array Payments
     */
    public function getAll($limit = 10, $offset = 0, $search = '') {
        $sql = "
            SELECT p.*, u.name as user_name, f.name as fee_name, f.amount
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE u.name LIKE :search OR f.name LIKE :search";
            $params['search'] = "%$search%";
        }

        $sql .= " ORDER BY p.payment_date DESC LIMIT :limit OFFSET :offset";

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
     * Count total payments
     *
     * @param string $search Search term
     * @return int Total count
     */
    public function countAll($search = '') {
        $sql = "
            SELECT COUNT(*)
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE u.name LIKE :search OR f.name LIKE :search";
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
     * Update payment status
     *
     * @param int $id Payment ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("
            UPDATE payments SET
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }

    /**
     * Get recent payments
     *
     * @param int $limit Limit
     * @return array Recent payments
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, f.name as fee_name, f.amount
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
            ORDER BY p.payment_date DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Count pending payments
     *
     * @return int Total count
     */
    public function countPending() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM payments WHERE status = 'pending'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
