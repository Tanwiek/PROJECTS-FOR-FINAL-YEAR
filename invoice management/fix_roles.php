<?php
require 'config/database.php';
$db = Database::getInstance();

// 1. Ajouter le rôle Logistique s'il n'existe pas
$stmt = $db->prepare("SELECT id FROM roles WHERE name = ?");
$stmt->execute(['Responsable Logistique']);
$role = $stmt->fetch();

if (!$role) {
    $db->exec("INSERT INTO roles (name) VALUES ('Responsable Logistique')");
    $roleId = $db->lastInsertId();
    echo "Role 'Responsable Logistique' created.\n";
} else {
    $roleId = $role['id'];
}

// 2. Assigner l'utilisateur logistique à ce rôle
$db->prepare("UPDATE users SET role_id = ? WHERE username = ?")->execute([$roleId, 'logistique']);
echo "User 'logistique' updated to role #$roleId.\n";
