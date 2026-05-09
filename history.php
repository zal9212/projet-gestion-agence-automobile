<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.*, c.marque, c.modele 
    FROM reservations r 
    JOIN cars c ON r.car_id = c.id 
    WHERE r.user_id = ? 
    ORDER BY r.date_creation DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Mon Historique</title></head>
<body>
    <h1>Mon Historique de Réservations</h1>
    <a href="index.php">Retour à l'accueil</a>
    <hr>
    <?php if (count($reservations) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Voiture</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                    <th>Date de Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['marque'] . ' ' . $res['modele']) ?></td>
                    <td><?= htmlspecialchars($res['date_debut']) ?></td>
                    <td><?= htmlspecialchars($res['date_fin']) ?></td>
                    <td><?= htmlspecialchars($res['prix_total']) ?> €</td>
                    <td><?= htmlspecialchars($res['status_reservation']) ?></td>
                    <td><?= htmlspecialchars($res['date_creation']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Vous n'avez effectué aucune réservation.</p>
    <?php endif; ?>
</body>
</html>