<?php
// src/Controllers/PaymentController.php

namespace Controllers;

use Core\Controller;

class PaymentController extends Controller {
    public function index() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $paymentModel = new \Models\Payment();
        $data = [
            'title' => 'Payment Tracking',
            'payments' => $paymentModel->all(),
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['success'], $_SESSION['error']);

        $this->render('payments.index', $data);
    }

    public function record() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $invoiceModel = new \Models\Invoice();
        $invoices = $invoiceModel->all(); // On pourrait filtrer par status != 'Paid'
        
        $data = [
            'title' => 'Record Payment',
            'invoices' => $invoices
        ];
        $this->render('payments.record', $data);
    }

    public function store() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentModel = new \Models\Payment();
            $invoiceModel = new \Models\Invoice();

            $data = [
                'invoice_id' => $_POST['invoice_id'],
                'amount' => $_POST['amount'],
                'payment_date' => $_POST['payment_date'],
                'payment_method' => $_POST['payment_method'],
                'reference' => $_POST['reference']
            ];

            if ($paymentModel->create($data)) {
                // Vérifier si la facture est maintenant entièrement payée
                $invoice = $invoiceModel->find($_POST['invoice_id']);
                $totalPaid = $paymentModel->getTotalPaidByInvoice($_POST['invoice_id']);
                
                if ($totalPaid >= $invoice['amount']) {
                    $invoiceModel->update($_POST['invoice_id'], ['status' => 'Paid']);
                }

                $_SESSION['success'] = "Payment successfully recorded.";
                $this->redirect('/payments');
            } else {
                $_SESSION['error'] = "Error recording payment.";
                $this->redirect('/payments/record');
            }
        }
    }

    public function receipt() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/payments');

        $paymentModel = new \Models\Payment();
        $payment = $paymentModel->find($id);

        if (!$payment) $this->redirect('/payments');

        $data = [
            'title' => 'Payment Receipt #' . $id,
            'payment' => $payment
        ];
        
        // On utilise un layout vide ou spécifique pour le reçu (printable)
        $this->render('payments.receipt', $data);
    }
}
