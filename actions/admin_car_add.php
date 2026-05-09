<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        die("Accès refusé.");
    }

    $category_id = $_POST['category_id'] ?: null;
    $immatriculation = trim($_POST['immatriculation']);
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $annee = $_POST['annee'] ?: null;
    $type_carburant = $_POST['type_carburant'];
    $boite_vitesse = $_POST['boite_vitesse'];
    $nb_places = (int)$_POST['nb_places'];
    $prix_journalier = (float)$_POST['prix_journalier'];
    $status = $_POST['status'];
    $climatise = $_POST['climatisé'];

    $stmt = $pdo->prepare("
        INSERT INTO cars (category_id, immatriculation, marque, modele, annee, type_carburant, boite_vitesse, nb_places, prix_journalier, status, climatisé)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$category_id, $immatriculation, $marque, $modele, $annee, $type_carburant, $boite_vitesse, $nb_places, $prix_journalier, $status, $climatise]);

    $_SESSION['message'] = "Voiture ajoutée avec succès.";
    header('Location: ../admin_cars.php');
    exit;
}