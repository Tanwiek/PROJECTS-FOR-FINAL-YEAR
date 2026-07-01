<?php
// src/Controllers/ReportController.php

namespace Controllers;

use Core\Controller;

class ReportController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $invoiceModel = new \Models\Invoice();
        $projectModel = new \Models\Project();

        $data = [
            'title' => 'Reports and Statistics',
            'stats' => [
                'total_revenue' => number_format($invoiceModel->getTotalRevenue(), 0, ',', ' ') . ' CFA',
                'pending_payments' => number_format($invoiceModel->getPendingCount() * 1000000, 0, ',', ' ') . ' CFA', // Logique de montant simulée
                'completed_projects' => $projectModel->getActiveCount(), // Utilise 'actif' comme 'terminé' pour l'instant
                'active_projects' => $projectModel->count()
            ]
        ];

        $this->render('reports.index', $data);
    }
}
