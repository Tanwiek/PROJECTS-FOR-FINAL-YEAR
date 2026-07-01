<?php
// src/Controllers/DeliveryController.php

namespace Controllers;

use Core\Controller;

class DeliveryController extends Controller {
    public function index() {
        session_start();
        $data = ['title' => 'Delivery Slips', 'deliveries' => []];
        $this->render('deliveries.index', $data);
    }

    public function create() {
        session_start();
        $data = ['title' => 'New Delivery / Deployment'];
        $this->render('deliveries.create', $data);
    }
}
