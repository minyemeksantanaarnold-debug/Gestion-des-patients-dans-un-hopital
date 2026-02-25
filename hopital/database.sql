-- ============================================
-- BASE DE DONNÉES : Gestion des Patients
-- Hôpital - XAMPP / MySQL
-- ============================================

CREATE DATABASE IF NOT EXISTS hopital_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hopital_db;

-- Table SERVICE
CREATE TABLE service (
    id_service INT AUTO_INCREMENT PRIMARY KEY,
    nom_service VARCHAR(100) NOT NULL,
    description TEXT,
    batiment VARCHAR(50),
    capacite INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table MEDECIN
CREATE TABLE medecin (
    id_medecin INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100),
    telephone VARCHAR(20),
    email VARCHAR(150),
    externe BOOLEAN DEFAULT FALSE,
    id_service INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_service) REFERENCES service(id_service) ON DELETE SET NULL
);

-- Table RECEPTIONNISTE (utilisateurs du système)
CREATE TABLE receptionniste (
    id_reception INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    login VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table PATIENT
CREATE TABLE patient (
    id_patient INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    sexe ENUM('M','F') NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    groupe_sanguin VARCHAR(5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table DOSSIER
CREATE TABLE dossier (
    id_dossier INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    id_reception INT,
    date_creation DATE DEFAULT (CURDATE()),
    antecedents TEXT,
    allergies TEXT,
    statut ENUM('Ouvert','Clôturé','En cours') DEFAULT 'Ouvert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_patient) REFERENCES patient(id_patient) ON DELETE CASCADE,
    FOREIGN KEY (id_reception) REFERENCES receptionniste(id_reception) ON DELETE SET NULL
);

-- Table MALADIE
CREATE TABLE maladie (
    id_maladie INT AUTO_INCREMENT PRIMARY KEY,
    nom_maladie VARCHAR(150) NOT NULL,
    code_CIM VARCHAR(20),
    description TEXT,
    categorie VARCHAR(100)
);

-- Table DOSSIER_MALADIE (relation N,N : figurer)
CREATE TABLE dossier_maladie (
    id_dossier INT NOT NULL,
    id_maladie INT NOT NULL,
    date_diagnostic DATE,
    PRIMARY KEY (id_dossier, id_maladie),
    FOREIGN KEY (id_dossier) REFERENCES dossier(id_dossier) ON DELETE CASCADE,
    FOREIGN KEY (id_maladie) REFERENCES maladie(id_maladie) ON DELETE CASCADE
);

-- Table CONSULTATION (relation N,N médecin-dossier avec attributs)
CREATE TABLE consultation (
    id_consultation INT AUTO_INCREMENT PRIMARY KEY,
    id_medecin INT NOT NULL,
    id_dossier INT NOT NULL,
    date_consultation DATETIME DEFAULT CURRENT_TIMESTAMP,
    diagnostic TEXT,
    observations TEXT,
    FOREIGN KEY (id_medecin) REFERENCES medecin(id_medecin),
    FOREIGN KEY (id_dossier) REFERENCES dossier(id_dossier) ON DELETE CASCADE
);

-- Table ORDONNANCE
CREATE TABLE ordonnance (
    id_ordonnance INT AUTO_INCREMENT PRIMARY KEY,
    id_dossier INT NOT NULL,
    id_medecin INT NOT NULL,
    date_prescription DATE DEFAULT (CURDATE()),
    medicaments TEXT NOT NULL,
    posologie TEXT,
    duree INT COMMENT 'Durée en jours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_dossier) REFERENCES dossier(id_dossier) ON DELETE CASCADE,
    FOREIGN KEY (id_medecin) REFERENCES medecin(id_medecin)
);

-- ============================================
-- DONNÉES DE TEST
-- ============================================

INSERT INTO service (nom_service, batiment, capacite) VALUES
('Médecine Générale', 'Bâtiment A', 30),
('Pédiatrie', 'Bâtiment B', 20),
('Urgences', 'Bâtiment C', 15),
('Cardiologie', 'Bâtiment A', 25);

INSERT INTO medecin (nom, prenom, specialite, telephone, email, externe, id_service) VALUES
('Mbarga', 'Jean-Paul', 'Médecine Générale', '699001122', 'jp.mbarga@hopital.cm', FALSE, 1),
('Nkolo', 'Marie', 'Pédiatrie', '677334455', 'm.nkolo@hopital.cm', FALSE, 2),
('Bello', 'Amadou', 'Cardiologie', '655778899', 'a.bello@extern.cm', TRUE, 4);

INSERT INTO receptionniste (nom, prenom, login, mot_de_passe, telephone) VALUES
('Ateba', 'Sophie', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '699000001');
-- Mot de passe par défaut: "password"

INSERT INTO maladie (nom_maladie, code_CIM, description, categorie) VALUES
('Paludisme', 'B54', 'Infection parasitaire transmise par les moustiques', 'Infectieuse'),
('Hypertension artérielle', 'I10', 'Pression artérielle élevée chronique', 'Cardiovasculaire'),
('Diabète type 2', 'E11', 'Trouble métabolique du glucose', 'Endocrinologie'),
('Typhoïde', 'A01.0', 'Infection bactérienne à Salmonella typhi', 'Infectieuse');

INSERT INTO patient (nom, prenom, date_naissance, sexe, telephone, adresse, groupe_sanguin) VALUES
('Essomba', 'Paul', '1985-03-12', 'M', '699112233', 'Yaoundé, Bastos', 'O+'),
('Ngo Bilong', 'Carine', '1992-07-25', 'F', '677445566', 'Douala, Akwa', 'A+');
