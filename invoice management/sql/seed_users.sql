-- Données initiales supplémentaires pour ALWAYS TECHNOLOGIES
USE always_tech_db;

-- Hash standard pour 'mot de passe'
SET @pass = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

INSERT INTO users (username, password, full_name, role_id) VALUES 
('assistante', @pass, 'Assistante de Direction', 2),
('comptable', @pass, 'Comptable Senior', 3),
('logistique', @pass, 'Responsable Logistique', 4)
ON DUPLICATE KEY UPDATE username=username;
