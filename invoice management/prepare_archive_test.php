<?php
require 'config/database.php';
$db = Database::getInstance();

// 1. Définir le projet 3 comme 'Completed' (Terminé)
$db->exec("UPDATE projects SET status = 'Completed' WHERE id = 3");

// 2. Ajouter une facture payée pour le projet 3 si elle n'existe pas
$stmt = $db->prepare("SELECT id FROM invoices WHERE project_id = 3");
$stmt->execute();
if (!$stmt->fetch()) {
    $db->prepare("INSERT INTO invoices (project_id, invoice_number, issue_date, due_date, amount, status) VALUES (?, ?, ?, ?, ?, ?)")
       ->execute([3, 'INV-TEST-001', date('Y-m-d'), date('Y-m-d'), 5000.00, 'Paid']);
    echo "Paid invoice created for project 3.\n";
} else {
    $db->exec("UPDATE invoices SET status = 'Paid' WHERE project_id = 3");
    echo "Invoice for project 3 set to Paid.\n";
}
echo "Project 3 is now ready for archiving.\n";
