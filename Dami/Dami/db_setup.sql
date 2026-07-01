-- Database Setup for Hospital Reservation System (Expanded & Robust)

CREATE DATABASE IF NOT EXISTS Gestion_rendez_vous;
USE Gestion_rendez_vous;

-- Drop existing tables to ensure clean slate
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS RendezVous;
DROP TABLE IF EXISTS Medecins;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Utilisateurs;
DROP TABLE IF EXISTS Roles;
SET FOREIGN_KEY_CHECKS = 1;

-- Table: Roles
CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) UNIQUE NOT NULL
);

-- Insert Roles
INSERT INTO Roles (id, nom) VALUES (1, 'patient'), (2, 'hopital'), (3, 'docteur');

-- Table: Utilisateurs
CREATE TABLE Utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES Roles(id)
);

-- Table: Patients
CREATE TABLE Patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE
);

-- Table: Services
CREATE TABLE Services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icone VARCHAR(50) DEFAULT 'fas fa-stethoscope'
);

-- Table: Medecins
CREATE TABLE Medecins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    CONSTRAINT fk_medecin_user FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE
);

-- Table: RendezVous
CREATE TABLE RendezVous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    medecin_id INT,
    service_id INT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    date_rv DATE NOT NULL,
    heure_rv TIME NOT NULL,
    message TEXT,
    statut VARCHAR(20) DEFAULT 'en attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rdv_user FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE,
    CONSTRAINT fk_rdv_medecin FOREIGN KEY (medecin_id) REFERENCES Medecins(id) ON DELETE SET NULL,
    CONSTRAINT fk_rdv_service FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE SET NULL
);

-- Seed Services
INSERT INTO Services (nom, description, icone) VALUES 
('Cardiologie', 'Soins experts pour votre cœur.', 'fas fa-heartbeat'),
('Pédiatrie', 'Soins pour vos enfants.', 'fas fa-child'),
('Dentisterie', 'Services dentaires complets.', 'fas fa-tooth'),
('Médecine Générale', 'Consultations quotidiennes.', 'fas fa-stethoscope'),
('Neurologie', 'Troubles du système nerveux.', 'fas fa-brain'),
('Ophtalmologie', 'Soins des yeux.', 'fas fa-eye');

-- Default Admin Account (Password: admin123)
INSERT INTO Utilisateurs (role_id, email, password) VALUES (2, 'admin@hopital.com', '$2y$10$nx44C5mS/QfcZQAR.y9NkelJ8kz0hh0lPn..I67Bsloaqcv8sFgCO');

-- Default Doctor Account (Password: docteur123)
INSERT INTO Utilisateurs (role_id, email, password) VALUES (3, 'docteur@hopital.com', '$2y$10$BsLpcvaijyzcwiC8PwFq7.ivCsxf5Es3t3FN/fYaX7kurot8FUr6i');
SET @last_user_id = LAST_INSERT_ID();
INSERT INTO Medecins (user_id, nom, specialite) VALUES (@last_user_id, 'Jean Dupont', 'Cardiologie');
