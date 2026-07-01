<?php
// src/Controllers/AuthController.php

namespace Controllers;

use Core\Controller;
use Models\User;

class AuthController extends Controller {
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->render('auth.login');
    }

    public function login() {
        if (!isset($_SESSION)) session_start();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $this->redirect('/dashboard');
        } else {
            $this->render('auth.login', ['error' => 'Invalid credentials']);
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirect('/');
    }
}
