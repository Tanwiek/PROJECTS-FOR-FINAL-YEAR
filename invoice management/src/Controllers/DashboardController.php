<?php
// src/Controllers/DashboardController.php

namespace Controllers;

use Core\Controller;

class DashboardController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        
        $invoiceModel = new \Models\Invoice();
        $projectModel = new \Models\Project();
        
        $data = [
            'title' => 'Dashboard',
            'user_name' => 'Administrator',
            'user_role' => $_SESSION['role'],
            'total_invoices' => $invoiceModel->count(),
            'revenue' => number_format($invoiceModel->getTotalRevenue() / 1000000, 1) . 'M',
            'pending_count' => $invoiceModel->getPendingCount(),
            'overdue_count' => $invoiceModel->getOverdueCount()
        ];
        
        $this->render('dashboard.index', $data);
    }
}
