<?php
// src/Models/Invoice.php

namespace Models;

use Core\Model;

class Invoice extends Model {
    protected $table = 'invoices';

    public function getTotalRevenue() {
        return $this->db->query("SELECT SUM(amount) FROM invoices WHERE status = 'Paid'")->fetchColumn() ?: 0;
    }

    public function getPendingCount() {
        return $this->db->query("SELECT COUNT(*) FROM invoices WHERE status = 'Sent'")->fetchColumn() ?: 0;
    }

    public function getOverdueCount() {
        $today = date('Y-m-d');
        return $this->db->query("SELECT COUNT(*) FROM invoices WHERE status != 'Paid' AND due_date < '$today'")->fetchColumn() ?: 0;
    }

    public function getByProject($projectId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE project_id = ? ORDER BY issue_date DESC");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }

    public function all() {
        return $this->db->query("SELECT i.*, p.title as project_title FROM invoices i LEFT JOIN projects p ON i.project_id = p.id ORDER BY issue_date DESC")->fetchAll();
    }

    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM invoices")->fetchColumn() ?: 0;
    }
}
