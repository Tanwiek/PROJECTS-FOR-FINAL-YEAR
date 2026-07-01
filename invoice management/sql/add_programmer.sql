-- Ajouter le rôle et l'utilisateur Programmeur
USE always_tech_db;

INSERT INTO roles (name) VALUES ('Programmeur')
ON DUPLICATE KEY UPDATE name=name;

SET @role_id = (SELECT id FROM roles WHERE name = 'Programmeur');
SET @pass = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; -- 'password' (mot de passe par défaut)

INSERT INTO users (username, password, full_name, role_id) 
VALUES ('programmer', @pass, 'Développeur Système', @role_id)
ON DUPLICATE KEY UPDATE username=username;
