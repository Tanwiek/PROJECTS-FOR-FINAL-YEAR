<?php
// src/Models/User.php

namespace Models;

use Database;
use PDO;

class User {
    public function findByUsername($username) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC");
        return $stmt->fetchAll();
    }

    public function create($username, $password, $fullName, $roleId, $email = null) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, password, full_name, role_id, email) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $fullName,
            $roleId,
            $email
        ]);
    }

    public function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
