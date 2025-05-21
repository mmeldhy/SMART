<?php


namespace app\models;

use app\config\Database;
use PDO;

class Setting
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function getSettings()
    {
        $stmt = $this->conn->prepare('SELECT * FROM settings WHERE id = 1');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveSettings($data)
    {
        $stmt = $this->conn->prepare('UPDATE settings SET
            rt_name = :rt_name,
            rt_number = :rt_number,
            rw_number = :rw_number,
            district = :district,
            city = :city,
            province = :province,
            contact_email = :contact_email,
            contact_phone = :contact_phone,
            address = :address,
            maintenance_mode = :maintenance_mode,
            registration_enabled = :registration_enabled
            WHERE id = 1');

        $stmt->bindParam(':rt_name', $data['rt_name']);
        $stmt->bindParam(':rt_number', $data['rt_number']);
        $stmt->bindParam(':rw_number', $data['rw_number']);
        $stmt->bindParam(':district', $data['district']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':province', $data['province']);
        $stmt->bindParam(':contact_email', $data['contact_email']);
        $stmt->bindParam(':contact_phone', $data['contact_phone']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':maintenance_mode', $data['maintenance_mode'], PDO::PARAM_INT); // Specify data type
        $stmt->bindParam(':registration_enabled', $data['registration_enabled'], PDO::PARAM_INT); // Specify data type

        return $stmt->execute();
    }
}