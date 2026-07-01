<?php
// api/files.php - Gestion des dossiers clients et services
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$action = $_GET['action'] ?? 'list';
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    try {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role === 'manager') {
            $stmt = $pdo->query("
                SELECT cf.id, cf.status, cf.created_at, cf.remarks, 
                       s.name as service_name, 
                       c.first_name, c.last_name
                FROM client_files cf
                JOIN services s ON cf.service_id = s.id
                JOIN clients c ON cf.client_id = c.id
                ORDER BY cf.created_at DESC
            ");
        } else {
            $stmt = $pdo->prepare("
                SELECT cf.id, cf.status, cf.created_at, cf.remarks, 
                       s.name as service_name, 
                       c.first_name, c.last_name
                FROM client_files cf
                JOIN services s ON cf.service_id = s.id
                JOIN clients c ON cf.client_id = c.id
                WHERE c.created_by = ?
                ORDER BY cf.created_at DESC
            ");
            $stmt->execute([$userId]);
        }
        
        $files = $stmt->fetchAll();
        echo json_encode(["status" => "success", "data" => $files]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to fetch files"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'services') {
    try {
        $stmt = $pdo->query("SELECT id, name, description FROM services ORDER BY name ASC");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll()]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to fetch services"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['client_id']) || empty($data['service_id'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Client and Service are required"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO client_files (client_id, service_id, assigned_to, status, remarks) VALUES (?, ?, ?, 'pending', ?)");
    try {
        $stmt->execute([
            $data['client_id'],
            $data['service_id'],
            $_SESSION['user_id'],
            $data['remarks'] ?? ''
        ]);
        logActivity($_SESSION['user_id'], "Création Dossier", "Nouveau dossier client ID: " . $data['client_id']);
        echo json_encode(["status" => "success", "message" => "File created successfully"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to create file"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id']) || empty($data['status'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID and status are required"]);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE client_files SET status = ?, remarks = ? WHERE id = ?");
        $stmt->execute([$data['status'], $data['remarks'] ?? '', $data['id']]);
        logActivity($_SESSION['user_id'], "Mise à jour Dossier", "Dossier ID: " . $data['id'] . " -> " . $data['status']);
        echo json_encode(["status" => "success", "message" => "Dossier mis à jour"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to update file"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'service_create') {
    if ($_SESSION['role'] !== 'manager') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Reserved for managers"]);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['name'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Service name required"]);
        exit;
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['description'] ?? '']);
        logActivity($_SESSION['user_id'], "Création Service", "Service: " . $data['name']);
        echo json_encode(["status" => "success", "message" => "Service created"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to create service"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'service_update') {
    if ($_SESSION['role'] !== 'manager') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Reserved for managers"]);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id']) || empty($data['name'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID and name required"]);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['description'] ?? '', $data['id']]);
        logActivity($_SESSION['user_id'], "Modification Service", "Service ID: " . $data['id']);
        echo json_encode(["status" => "success", "message" => "Service updated"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to update service"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'service_delete') {
    if ($_SESSION['role'] !== 'manager') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Reserved for managers"]);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID required"]);
        exit;
    }
    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$data['id']]);
        logActivity($_SESSION['user_id'], "Suppression Service", "Service ID: " . $data['id']);
        echo json_encode(["status" => "success", "message" => "Service deleted"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to delete service. It might be used in dossiers."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>
