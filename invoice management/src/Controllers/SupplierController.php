<?php
// src/Controllers/SupplierController.php

namespace Controllers;

use Core\Controller;

class SupplierController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $data = [
            'title' => 'Supplier Management',
            'suppliers' => []
        ];

        $this->render('suppliers.index', $data);
    }

    public function create() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $data = ['title' => 'New Supplier'];
        $this->render('suppliers.create', $data);
    }
}
