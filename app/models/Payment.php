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
     * @param string $fee_id Fee ID
     * @param string $date_from Date from
     * @param string $date_to Date to
     * @return array Payments
     */
    public function getAll($limit = 10, $offset = 0, $search = '', $status = '', $fee_id = '', $date_from = '', $date_to = '') {
        $sql = "
            SELECT p.*, u.name as resident_name, f.name as fee_name, f.amount
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.name LIKE :search1 OR f.name LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        if (!empty($fee_id)) {
            $sql .= " AND p.fee_id = :fee_id";
            $params['fee_id'] = $fee_id;
        }

        if (!empty($date_from)) {
            $sql .= " AND p.payment_date >= :date_from";
            $params['date_from'] = $date_from;
        }

        if (!empty($date_to)) {
            $sql .= " AND p.payment_date <= :date_to";
            $params['date_to'] = $date_to;
        }

        $sql .= " ORDER BY p.payment_date DESC LIMIT :limit OFFSET :offset";

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
     * Count total payments
     *
     * @param string $search Search term
     * @param string $status Status
     * @param string $fee_id Fee ID
     * @param string $date_from Date from
     * @param string $date_to Date to
     * @return int Total count
     */
    public function countAll($search = '', $status = '', $fee_id = '', $date_from = '', $date_to = '') {
        $sql = "
            SELECT COUNT(*)
            FROM payments p
            JOIN users u ON p.user_id = u.id
            JOIN fees f ON p.fee_id = f.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.name LIKE :search1 OR f.name LIKE :search2)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        if (!empty($fee_id)) {
            $sql .= " AND p.fee_id = :fee_id";
            $params['fee_id'] = $fee_id;
        }

        if (!empty($date_from)) {
            $sql .= " AND p.payment_date >= :date_from";
            $params['date_from'] = $date_from;
        }

        if (!empty($date_to)) {
            $sql .= " AND p.payment_date <= :date_to";
            $params['date_to'] = $date_to;
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
     * Count pending payments
     *
     * @return int Total count
     */
    public function countPending() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM payments WHERE status = 'pending'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Update payment status (AND admin_response)
     *
     * @param int $id Payment ID
     * @param string $status New status
     * @param string $adminNotes Admin's notes/response
     * @return bool Success status
     */
    public function updateStatus($id, $status, $adminNotes = null) { // Added adminNotes parameter
        $stmt = $this->db->prepare("
            UPDATE payments SET
                status = :status,
                admin_response = :admin_response, -- Changed to admin_response
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'admin_response' => $adminNotes // Passed adminNotes
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
     * Check if user has paid a fee with approved status
     *
     * @param int $userId User ID
     * @param int $feeId Fee ID
     * @return bool True if paid and approved, false otherwise
     */
    public function hasPaid($userId, $feeId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM payments WHERE user_id = :user_id AND fee_id = :fee_id AND status = 'approved'");
        $stmt->execute([
            'user_id' => $userId,
            'fee_id' => $feeId
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Create a new payment
     * * @param array $data Payment data
     * @return bool Success status
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO payments (user_id, fee_id, payment_date, proof_image, status, created_at)
            VALUES (:user_id, :fee_id, :payment_date, :proof_image, :status, NOW())
        ");
         
         return $stmt->execute([
             'user_id' => $data['user_id'],
             'fee_id' => $data['fee_id'],
             'payment_date' => $data['payment_date'],
             'proof_image' => $data['proof_image'],
             'status' => $data['status']
         ]);
     }
}