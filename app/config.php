<?php
/**
 * CONFIGURATION GENERALE (VANILLA PHP)
 */

// Paramètres BDD
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'car_rental_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Paramètres Agence
define('AGENCY_NAME', 'AutoRent ERP');
define('AGENCY_CITY', 'Dakar, Sénégal');
define('CURRENCY', 'FCFA');

/**
 * Connexion à la base de données
 */
function get_pdo() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    return $pdo;
}

/**
 * Aide à l'affichage des pages (Vue)
 */
function render_view($path, $data = []) {
    extract($data);
    require 'app/views/' . $path . '.php';
}

/**
 * Redirection simple
 */
function redirect($url) {
    header("Location: $url");
    exit();
}