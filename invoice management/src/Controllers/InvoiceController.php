<?php
// src/Controllers/InvoiceController.php

namespace Controllers;

use Core\Controller;

class InvoiceController extends Controller {
    public function index() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $invoiceModel = new \Models\Invoice();
        $data = [
            'title' => 'Invoice Management',
            'invoices' => $invoiceModel->all(),
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['success'], $_SESSION['error']);

        $this->render('invoices.index', $data);
    }

    public function create() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $projectModel = new \Models\Project();
        $projects = $projectModel->allActive();

        $data = [
            'title' => 'Generate New Invoice',
            'projects' => $projects,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['error']);

        $this->render('invoices.create', $data);
    }

    public function store() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $invoiceModel = new \Models\Invoice();
            
            $data = [
                'project_id' => $_POST['project_id'],
                'invoice_number' => $_POST['invoice_number'],
                'issue_date' => $_POST['issue_date'],
                'due_date' => $_POST['due_date'],
                'amount' => $_POST['amount'],
                'status' => 'Draft'
            ];

            try {
                $invoiceModel->create($data);
                $_SESSION['success'] = "Invoice " . htmlspecialchars($data['invoice_number']) . " successfully generated.";
                $this->redirect('/invoices');
            } catch (\Exception $e) {
                $_SESSION['error'] = "Generation error: " . $e->getMessage();
                $this->redirect('/invoices/create');
            }
        }
    }
}
