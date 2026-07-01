<?php
// src/Models/Archive.php

namespace Models;

use Database;
use PDO;

class Archive {
    public function getArchivedProjects($search = '', $year = null) {
        $db = Database::getInstance();
        $query = "SELECT p.*, u.full_name as archived_by_name 
                  FROM projects p 
                  LEFT JOIN users u ON p.archived_by = u.id 
                  WHERE p.is_archived = 1";
        
        if ($search) {
            $query .= " AND (p.title LIKE :search OR p.project_code LIKE :search)";
        }
        if ($year) {
            $query .= " AND YEAR(p.archived_at) = :year";
        }
        
        $stmt = $db->prepare($query);
        if ($search) $stmt->bindValue(':search', "%$search%");
        if ($year) $stmt->bindValue(':year', $year);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function archiveProject($projectId, $userId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE projects SET is_archived = 1, status = 'Archived', archived_at = NOW(), archived_by = ? WHERE id = ?");
        return $stmt->execute([$userId, $projectId]);
    }

    public function unarchiveProject($projectId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE projects SET is_archived = 0, archived_at = NULL, archived_by = NULL WHERE id = ?");
        return $stmt->execute([$projectId]);
    }
}
