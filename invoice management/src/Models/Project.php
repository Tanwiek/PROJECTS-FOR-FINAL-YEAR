<?php
// src/Models/Project.php

namespace Models;

use Core\Model;

class Project extends Model {
    protected $table = 'projects';
    
    public function getActiveCount() {
        return $this->db->query("SELECT COUNT(*) FROM projects WHERE status = 'Active' AND is_archived = 0")->fetchColumn() ?: 0;
    }

    public function allActive() {
        return $this->db->query("SELECT * FROM projects WHERE is_archived = 0 ORDER BY created_at DESC")->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
