<?php
// src/Models/Offer.php

namespace Models;

use PDO;

class Offer {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO offers (project_id, title, type, file_path, amount, status) 
                VALUES (:project_id, :title, :type, :file_path, :amount, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $data['project_id'],
            ':title' => $data['title'],
            ':type' => $data['type'],
            ':file_path' => $data['file_path'],
            ':amount' => $data['amount'] ?? 0,
            ':status' => $data['status'] ?? 'Pending'
        ]);
    }

    public function getByProject($projectId) {
        $sql = "SELECT * FROM offers WHERE project_id = :project_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
