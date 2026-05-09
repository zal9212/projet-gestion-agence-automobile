-- Suppression de la base si elle existe pour repartir à neuf
DROP DATABASE IF EXISTS car_rental_db;
CREATE DATABASE car_rental_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_rental_db;

-- -----------------------------------------------------
-- 1. Table des Catégories (Economique, Luxe, SUV, etc.)
-- -----------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 2. Table des Utilisateurs
-- -----------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    adresse TEXT,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 3. Table des Véhicules
-- -----------------------------------------------------
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT,
    type_carburant ENUM('Essence', 'Diesel', 'Electrique', 'Hybride') NOT NULL,
    boite_vitesse ENUM('Manuelle', 'Automatique') DEFAULT 'Manuelle',
    nb_places INT DEFAULT 5,
    prix_journalier DECIMAL(10, 2) NOT NULL,
    image_principale VARCHAR(255),
    status ENUM('disponible', 'maintenance', 'louée') DEFAULT 'disponible',
    climatisé BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4. Table des Réservations
-- -----------------------------------------------------
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10, 2) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_reservation ENUM('en_attente', 'confirmee', 'payee', 'terminee', 'annulee') DEFAULT 'en_attente',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5. Table des Paiements (Optionnel mais recommandé)
-- -----------------------------------------------------
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    methode_paiement ENUM('Carte Bancaire', 'Espèces', 'Mobile Money') NOT NULL,
    transaction_id VARCHAR(100), -- Pour les APIs de paiement
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6. Table des Avis (Pour le front-office)
-- -----------------------------------------------------
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- INSERTION DE DONNÉES DE TEST (Pour tes premiers tests PHP)
-- -----------------------------------------------------

INSERT INTO categories (nom, description) VALUES 
('Économique', 'Petites voitures pour la ville'),
('SUV', 'Véhicules spacieux pour la famille'),
('Luxe', 'Voitures haut de gamme pour événements');

INSERT INTO users (nom, prenom, email, password, role) VALUES 
('Admin', 'Global', 'admin@agence.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- mdp: password
('Dupont', 'Jean', 'jean@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

INSERT INTO cars (category_id, immatriculation, marque, modele, type_carburant, prix_journalier, status) VALUES 
(1, 'AA-123-BB', 'Renault', 'Clio 5', 'Diesel', 35.00, 'disponible'),
(2, 'CC-456-DD', 'Peugeot', '3008', 'Essence', 65.00, 'disponible'),
(3, 'EE-789-FF', 'Mercedes', 'Classe C', 'Hybride', 120.00, 'maintenance');