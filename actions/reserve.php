<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("Non autorisé.");
    }

    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_POST['car_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($date_debut > $date_fin) {
        $_SESSION['error'] = "La date de fin doit être postérieure à la date de début.";
        header("Location: ../reserve.php?car_id=$car_id");
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id FROM reservations 
        WHERE car_id = ? 
        AND status_reservation NOT IN ('annulee', 'terminee')
        AND date_debut <= ? 
        AND date_fin >= ?
    ");
    $stmt->execute([$car_id, $date_fin, $date_debut]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Cette voiture est déjà réservée sur ce créneau.";
        header("Location: ../reserve.php?car_id=$car_id");
        exit;
    }

    $car_stmt = $pdo->prepare("SELECT prix_journalier FROM cars WHERE id = ?");
    $car_stmt->execute([$car_id]);
    $car = $car_stmt->fetch();
    
    $datetime1 = new DateTime($date_debut);
    $datetime2 = new DateTime($date_fin);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days + 1; 
    $prix_total = $days * $car['prix_journalier'];

    $insert_stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, car_id, date_debut, date_fin, prix_total) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert_stmt->execute([$user_id, $car_id, $date_debut, $date_fin, $prix_total]);

    $_SESSION['success'] = "Réservation confirmée avec succès. Montant total : $prix_total €.";
    header("Location: ../reserve.php?car_id=$car_id");
    exit;
}