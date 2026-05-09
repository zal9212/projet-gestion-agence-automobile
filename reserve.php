<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['car_id'])) {
    die("Voiture non spécifiée.");
}

$car_id = (int)$_GET['car_id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    die("Voiture introuvable.");
}
?>
<!DOCTYPE html>
<html>
<head><title>Réserver - <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></title></head>
<body>
    <h1>Réserver <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h1>
    <p>Prix par jour: <?= htmlspecialchars($car['prix_journalier']) ?> €</p>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color:green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form action="actions/reserve.php" method="POST">
        <input type="hidden" name="car_id" value="<?= $car_id ?>">
        <label>Date de début: <input type="date" name="date_debut" required></label><br><br>
        <label>Date de fin: <input type="date" name="date_fin" required></label><br><br>
        <button type="submit">Confirmer la réservation</button>
    </form>
    <a href="index.php">Retour</a>
</body>
</html>