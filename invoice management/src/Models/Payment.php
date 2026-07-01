<?php
// src/Models/Payment.php

namespace Models;

use PDO;

class Payment {
    private $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function all() {
        $sql = "SELECT p.*, i.invoice_number, proj.title as project_title, proj.project_code 
                FROM payments p
                JOIN invoices i ON p.invoice_id = i.id
                JOIN projects proj ON i.project_id = proj.id
                ORDER BY p.payment_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT p.*, i.invoice_number, i.amount as invoice_total, proj.title as project_title, proj.project_code 
                FROM payments p
                JOIN invoices i ON p.invoice_id = i.id
                JOIN projects proj ON i.project_id = proj.id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO payments (invoice_id, amount, payment_date, payment_method, reference) 
                VALUES (:invoice_id, :amount, :payment_date, :payment_method, :reference)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':invoice_id' => $data['invoice_id'],
            ':amount' => $data['amount'],
            ':payment_date' => $data['payment_date'],
            ':payment_method' => $data['payment_method'],
            ':reference' => $data['reference']
        ]);
    }

    public function getTotalPaidByInvoice($invoiceId) {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE invoice_id = :invoice_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':invoice_id' => $invoiceId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'] ?? 0;
    }
}
