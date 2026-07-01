<?php
// api/reports.php - API pour les rapports d'activité
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Accès refusé."]);
    exit;
}

$pdo = getDB();

try {
    $stmt = $pdo->query("
        SELECT al.id, al.action, al.details, al.timestamp, 
               u.first_name, u.last_name, u.role
        FROM activity_logs al
        JOIN users u ON al.user_id = u.id
        ORDER BY al.timestamp DESC
        LIMIT 100
    ");
    $logs = $stmt->fetchAll();
    echo json_encode(["status" => "success", "data" => $logs]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to fetch logs"]);
}
?>
