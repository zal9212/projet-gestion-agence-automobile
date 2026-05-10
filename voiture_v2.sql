-- Suppression de la base si elle existe pour repartir à neuf
DROP DATABASE IF EXISTS car_rental_db;
CREATE DATABASE car_rental_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_rental_db;

-- -----------------------------------------------------
-- 1. Table des Catégories
-- -----------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 2. Table des Lieux (Prise en charge / Restitution)
-- -----------------------------------------------------
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    frais_supplementaire DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 3. Table des Utilisateurs (Clients & Admins)
-- -----------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee', 'client') DEFAULT 'client', -- AJOUT EMPLOYEE
    adresse TEXT,
    num_permis VARCHAR(100),
    piece_identite VARCHAR(255),
    is_blacklisted BOOLEAN DEFAULT FALSE, -- NOUVEAU
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4. Table des Véhicules
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
    caution DECIMAL(10, 2) DEFAULT 0,
    kilometrage INT DEFAULT 0,
    date_assurance DATE,
    image_principale VARCHAR(255),
    brand_logo VARCHAR(255),
    status ENUM('disponible', 'maintenance', 'louée') DEFAULT 'disponible',
    climatisé BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5. Table des Images Supplémentaires (Galerie Voiture)
-- -----------------------------------------------------
CREATE TABLE car_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6. Table des Réservations
-- -----------------------------------------------------
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    lieu_prise_id INT,
    lieu_retour_id INT,
    avec_chauffeur BOOLEAN DEFAULT FALSE,
    prix_total DECIMAL(10, 2) NOT NULL,
    status_reservation ENUM('en_attente', 'validee', 'en_cours', 'terminee', 'annulee') DEFAULT 'en_attente',
    -- NOUVEAUX CHAMPS POUR LE CHECKOUT
    kilometrage_depart INT,
    niveau_carburant_depart VARCHAR(50),
    signature_base64 LONGTEXT,
    validated_by INT, -- NOUVEAU : ID de l'employé/admin
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    FOREIGN KEY (lieu_prise_id) REFERENCES locations(id) ON DELETE SET NULL,
    FOREIGN KEY (lieu_retour_id) REFERENCES locations(id) ON DELETE SET NULL,
    FOREIGN KEY (validated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 7. Table des Options de Réservation (Siège bébé, GPS...)
-- -----------------------------------------------------
CREATE TABLE reservation_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    nom_option VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 8. Table d'État des Lieux (Check-in / Check-out)
-- -----------------------------------------------------
CREATE TABLE check_in_out (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    type ENUM('check_in', 'check_out') NOT NULL,
    kilometrage INT NOT NULL,
    niveau_carburant ENUM('Reserve', '1/4', '1/2', '3/4', 'Plein') NOT NULL,
    etat_vehicule TEXT,
    date_enregistrement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 9. Table des Paiements
-- -----------------------------------------------------
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    methode_paiement ENUM('Carte Bancaire', 'Espèces', 'Mobile Money', 'Virement') NOT NULL,
    transaction_id VARCHAR(100),
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 10. Table des Notifications
-- -----------------------------------------------------
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 11. Table des Favoris
-- -----------------------------------------------------
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    UNIQUE KEY unique_fav (user_id, car_id)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- INSERTION DE DONNÉES DE TEST
-- -----------------------------------------------------

INSERT INTO locations (nom, frais_supplementaire) VALUES 
('Agence Centre-Ville', 0),
('Aéroport AIBD', 15000),
('Hôtel (Livraison)', 10000);

INSERT INTO categories (nom, description) VALUES 
('Économique', 'Petites voitures idéales pour la ville.'),
('SUV', 'Véhicules spacieux et robustes.'),
('Luxe', 'Véhicules haut de gamme.');

INSERT INTO users (nom, prenom, email, password, role) VALUES 
('Admin', 'Global', 'admin@agence.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Client', 'Test', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

INSERT INTO cars (category_id, immatriculation, marque, modele, type_carburant, prix_journalier, caution, kilometrage, image_principale) VALUES 
(1, 'DK-1234-A', 'Renault', 'Clio 5', 'Essence', 25000, 150000, 15000, 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fd?auto=format&fit=crop&q=80&w=800'),
(2, 'DK-5678-B', 'Toyota', 'RAV4', 'Diesel', 45000, 300000, 45000, 'https://images.unsplash.com/photo-1625049581845-f09dfd7abdb3?auto=format&fit=crop&q=80&w=800'),
(3, 'DK-9012-C', 'Mercedes', 'Classe C', 'Hybride', 85000, 500000, 10000, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&q=80&w=800');
