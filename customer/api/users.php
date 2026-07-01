<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Vérifier si authentifié et gestionnaire
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Accès refusé. Réservé aux administrateurs."]);
    exit;
}

$action = $_GET['action'] ?? '';
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    try {
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        
        echo json_encode(["status" => "success", "data" => $users]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erreur de base de données"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $firstName = $data['first_name'] ?? '';
    $lastName = $data['last_name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? '';

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($role)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Tous les champs sont requis."]);
        exit;
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Cet email est déjà utilisé."]);
        exit;
    }

    try {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $hashed, $role]);
        logActivity($_SESSION['user_id'], "Création Utilisateur", "Email: $email, Rôle: $role");
        echo json_encode(["status" => "success", "message" => "Utilisateur créé avec succès."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erreur lors de la création de l'utilisateur"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    $firstName = $data['first_name'] ?? '';
    $lastName = $data['last_name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? '';

    if (empty($id) || empty($firstName) || empty($lastName) || empty($email) || empty($role)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Champs manquants."]);
        exit;
    }

    try {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, password_hash=?, role=? WHERE id=?");
            $stmt->execute([$firstName, $lastName, $email, $hashed, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=? WHERE id=?");
            $stmt->execute([$firstName, $lastName, $email, $role, $id]);
        }
        logActivity($_SESSION['user_id'], "Modification Utilisateur", "ID: $id");
        echo json_encode(["status" => "success", "message" => "Utilisateur mis à jour."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erreur lors de la mise à jour."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID requis."]);
        exit;
    }

    // Empêcher de se supprimer soi-même
    if ($id == $_SESSION['user_id']) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Vous ne pouvez pas supprimer votre propre compte."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        logActivity($_SESSION['user_id'], "Suppression Utilisateur", "ID: $id");
        echo json_encode(["status" => "success", "message" => "Utilisateur supprimé."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Action invalide"]);
}
?>
