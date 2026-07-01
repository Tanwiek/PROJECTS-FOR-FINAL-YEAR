<?php
// simulate_data.php
require_once 'config/database.php';
try {
    $db = Database::getInstance();
    
    // Créer un projet pour la simulation
    $db->exec("INSERT INTO projects (project_code, title, status) VALUES ('SIM-001', 'Projet Simulation Archivage', 'Active')");
    $projectId = $db->lastInsertId();
    
    // Créer une facture payée pour ce projet
    $db->exec("INSERT INTO invoices (project_id, invoice_number, amount, status, issue_date, due_date) 
               VALUES ($projectId, 'FAC-SIM-001', 500000, 'Paid', '2026-03-01', '2026-03-31')");
               
    echo "Simulation data created. Project ID: $projectId\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
