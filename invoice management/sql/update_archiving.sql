-- Mise à jour du schéma pour l'archivage
USE always_tech_db;

ALTER TABLE projects 
ADD COLUMN is_archived BOOLEAN DEFAULT FALSE,
ADD COLUMN archived_at TIMESTAMP NULL,
ADD COLUMN archived_by INT NULL,
ADD FOREIGN KEY (archived_by) REFERENCES users(id);

ALTER TABLE invoices
ADD COLUMN is_archived BOOLEAN DEFAULT FALSE;
