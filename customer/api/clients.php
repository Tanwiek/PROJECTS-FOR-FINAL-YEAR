<?php
// api/clients.php
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
            // Le gestionnaire voit tout + qui l'a créé
            $stmt = $pdo->query("SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as creator_name 
                               FROM clients c 
                               LEFT JOIN users u ON c.created_by = u.id 
                               ORDER BY c.created_at DESC");
        } else {
            // Les autres ne voient que leurs propres dossiers
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE created_by = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
        }
        
        $clients = $stmt->fetchAll();
        echo json_encode(["status" => "success", "data" => $clients]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to fetch clients"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['first_name']) || empty($data['last_name'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "First and last name are required"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO clients (first_name, last_name, email, phone, address, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([
            trim($data['first_name']),
            trim($data['last_name']),
            trim($data['email'] ?? ''),
            trim($data['phone'] ?? ''),
            trim($data['address'] ?? ''),
            $_SESSION['user_id']
        ]);
        logActivity($_SESSION['user_id'], "Création Client", "Nom: " . $data['first_name'] . " " . $data['last_name']);
        echo json_encode(["status" => "success", "message" => "Client created successfully"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to create client"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    
    if (empty($id) || empty($data['first_name']) || empty($data['last_name'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID, first name and last name are required"]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE clients SET first_name=?, last_name=?, email=?, phone=?, address=? WHERE id=?");
    try {
        $stmt->execute([
            trim($data['first_name']),
            trim($data['last_name']),
            trim($data['email'] ?? ''),
            trim($data['phone'] ?? ''),
            trim($data['address'] ?? ''),
            $id
        ]);
        logActivity($_SESSION['user_id'], "Modification Client", "ID: $id");
        echo json_encode(["status" => "success", "message" => "Client updated successfully"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to update client"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID is required"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        logActivity($_SESSION['user_id'], "Suppression Client", "ID: $id");
        echo json_encode(["status" => "success", "message" => "Client deleted successfully"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to delete client"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid action"]);
}
?>
