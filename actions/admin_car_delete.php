<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['message'] = "Voiture supprimée avec succès.";
}

header('Location: ../admin_cars.php');
exit;