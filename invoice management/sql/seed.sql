-- Données initiales pour ALWAYS TECHNOLOGIES

USE always_tech_db;

-- Ajouter un administrateur par défaut s'il n'existe pas
INSERT INTO users (username, password, full_name, role_id) 
SELECT 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 1
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

-- Ajouter quelques dossiers (projets)
INSERT INTO projects (project_code, title, status) VALUES 
('PRJ-2026-001', 'Installation Réseau Siège', 'Active'),
('PRJ-2026-002', 'Audit Sécurité Informatique', 'Completed');

-- Ajouter quelques factures
INSERT INTO invoices (project_id, invoice_number, issue_date, due_date, amount, status) VALUES 
(1, 'FAC-2026-001', '2026-03-01', '2026-03-31', 1500000, 'Paid'),
(1, 'FAC-2026-002', '2026-03-15', '2026-04-15', 2500000, 'Sent'),
(2, 'FAC-2026-003', '2026-02-10', '2026-03-10', 3000000, 'Overdue');
