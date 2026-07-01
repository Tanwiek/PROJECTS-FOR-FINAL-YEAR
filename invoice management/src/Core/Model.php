<?php
// src/Core/Model.php

namespace Core;

use Database;
use PDO;

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $set = "";
        foreach (array_keys($data) as $key) {
            $set .= "$key = ?, ";
        }
        $set = rtrim($set, ', ');
        
        $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $params = array_values($data);
        $params[] = $id;
        
        return $stmt->execute($params);
    }

    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
