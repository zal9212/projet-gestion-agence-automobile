<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Gestion des Voitures</title></head>
<body>
    <h1>Gestion des Voitures</h1>
    <a href="index.php">Retour à l'accueil</a> | <a href="admin_car_add.php">Ajouter une voiture</a>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color:green;"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Immatriculation</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Prix/J</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= htmlspecialchars($car['id']) ?></td>
                <td><?= htmlspecialchars($car['immatriculation']) ?></td>
                <td><?= htmlspecialchars($car['marque']) ?></td>
                <td><?= htmlspecialchars($car['modele']) ?></td>
                <td><?= htmlspecialchars($car['prix_journalier']) ?> €</td>
                <td><?= htmlspecialchars($car['status']) ?></td>
                <td>
                    <a href="admin_car_edit.php?id=<?= $car['id'] ?>">Modifier</a> |
                    <a href="actions/admin_car_delete.php?id=<?= $car['id'] ?>" onclick="return confirm('Vraiment supprimer ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>