<?php
// src/Models/Document.php

namespace Models;

use Core\Model;

class Document extends Model {
    protected $table = 'documents';

    public function getByProject($projectId) {
        $stmt = $this->db->prepare("SELECT d.*, u.full_name as uploader_name FROM documents d 
                                   LEFT JOIN users u ON d.uploaded_by = u.id 
                                   WHERE d.project_id = ? ORDER BY d.uploaded_at DESC");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO documents (project_id, file_name, file_path, file_type, uploaded_by) 
                                   VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['project_id'],
            $data['file_name'],
            $data['file_path'],
            $data['file_type'] ?? 'Other',
            $data['uploaded_by']
        ]);
    }
}
