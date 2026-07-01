<?php
// src/Controllers/ProgrammerController.php

namespace Controllers;

use Core\Controller;
use Models\User;
use Models\Role;
use Helpers\Logger;

class ProgrammerController extends Controller {
    public function __construct() {
        if (!isset($_SESSION)) session_start();
        $allowedRoles = ['Programmeur', 'Directeur Général'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowedRoles)) {
            $this->redirect('/');
            exit;
        }
    }

    public function index() {
        $this->render('programmer.dashboard', [
            'title' => 'Programmer Dashboard',
            'user_count' => count((new User())->getAll())
        ]);
    }

    public function users() {
        $userModel = new User();
        $db = \Database::getInstance();
        $roles = $db->query("SELECT * FROM roles")->fetchAll();
        
        $this->render('programmer.users', [
            'title' => 'User Management',
            'users' => $userModel->getAll(),
            'roles' => $roles
        ]);
    }

    public function storeUser() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $fullName = $_POST['full_name'] ?? '';
        $roleId = $_POST['role_id'] ?? '';
        
        if ($username && $password && $fullName && $roleId) {
            $userModel = new User();
            if ($userModel->create($username, $password, $fullName, $roleId)) {
                Logger::log("User Creation", "New user created: $username");
                $_SESSION['success'] = "User created successfully.";
            } else {
                $_SESSION['error'] = "Error during creation.";
            }
        }
        $this->redirect('/programmer/users');
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? null;
        // Don't allow user to delete themselves
        if ($id && $id != $_SESSION['user_id']) {
            $userModel = new User();
            if ($userModel->delete($id)) {
                Logger::log("User Deletion", "User ID #$id was deleted.");
                $_SESSION['success'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting user.";
            }
        } else if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account.";
        }
        $this->redirect('/programmer/users');
    }

    public function logs() {
        $db = \Database::getInstance();
        $logs = $db->query("SELECT a.*, u.full_name FROM activity_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY created_at DESC LIMIT 100")->fetchAll();
        
        $this->render('programmer.logs', [
            'title' => 'System & Error Logs',
            'logs' => $logs
        ]);
    }
}
