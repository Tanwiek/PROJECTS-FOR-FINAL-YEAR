<?php
// src/Controllers/OrderController.php

namespace Controllers;

use Core\Controller;

class OrderController extends Controller {
    public function index() {
        session_start();
        $data = ['title' => 'Purchase Orders', 'orders' => []];
        $this->render('orders.index', $data);
    }

    public function create() {
        session_start();
        $data = ['title' => 'New Purchase Order'];
        $this->render('orders.create', $data);
    }
}
