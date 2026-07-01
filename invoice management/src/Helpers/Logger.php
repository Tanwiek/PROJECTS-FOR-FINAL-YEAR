<?php
// src/Helpers/Logger.php

namespace Helpers;

use Database;
use PDO;

class Logger {
    public static function log($action, $details = null) {
        if (!isset($_SESSION)) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $action, $details]);
    }

    public static function getProjectLogs($projectId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT a.*, u.full_name FROM activity_logs a 
                             LEFT JOIN users u ON a.user_id = u.id 
                             WHERE details LIKE ? ORDER BY created_at DESC");
        $stmt->execute(["%project_id:$projectId%"]);
        return $stmt->fetchAll();
    }
}
