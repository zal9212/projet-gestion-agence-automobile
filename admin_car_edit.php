<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

if (!isset($_GET['id'])) {
    die("ID non fourni.");
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    die("Voiture introuvable.");
}

$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Modifier Voiture</title></head>
<body>
    <h1>Modifier une Voiture</h1>
    <form action="actions/admin_car_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $car['id'] ?>">
        
        <label>Catégorie: 
            <select name="category_id">
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $car['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Immatriculation: <input type="text" name="immatriculation" value="<?= htmlspecialchars($car['immatriculation']) ?>" required></label><br><br>
        <label>Marque: <input type="text" name="marque" value="<?= htmlspecialchars($car['marque']) ?>" required></label><br><br>
        <label>Modèle: <input type="text" name="modele" value="<?= htmlspecialchars($car['modele']) ?>" required></label><br><br>
        <label>Année: <input type="number" name="annee" value="<?= htmlspecialchars($car['annee']) ?>"></label><br><br>
        <label>Type Carburant: 
            <select name="type_carburant">
                <option value="Essence" <?= $car['type_carburant'] == 'Essence' ? 'selected' : '' ?>>Essence</option>
                <option value="Diesel" <?= $car['type_carburant'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                <option value="Electrique" <?= $car['type_carburant'] == 'Electrique' ? 'selected' : '' ?>>Electrique</option>
                <option value="Hybride" <?= $car['type_carburant'] == 'Hybride' ? 'selected' : '' ?>>Hybride</option>
            </select>
        </label><br><br>
        <label>Boîte de vitesse: 
            <select name="boite_vitesse">
                <option value="Manuelle" <?= $car['boite_vitesse'] == 'Manuelle' ? 'selected' : '' ?>>Manuelle</option>
                <option value="Automatique" <?= $car['boite_vitesse'] == 'Automatique' ? 'selected' : '' ?>>Automatique</option>
            </select>
        </label><br><br>
        <label>Places: <input type="number" name="nb_places" value="<?= htmlspecialchars($car['nb_places']) ?>"></label><br><br>
        <label>Prix Journalier: <input type="number" step="0.01" name="prix_journalier" value="<?= htmlspecialchars($car['prix_journalier']) ?>" required></label><br><br>
        <label>Statut: 
            <select name="status">
                <option value="disponible" <?= $car['status'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="maintenance" <?= $car['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                <option value="louée" <?= $car['status'] == 'louée' ? 'selected' : '' ?>>Louée</option>
            </select>
        </label><br><br>
        <label>Climatisé: 
            <input type="radio" name="climatisé" value="1" <?= $car['climatisé'] == 1 ? 'checked' : '' ?>> Oui
            <input type="radio" name="climatisé" value="0" <?= $car['climatisé'] == 0 ? 'checked' : '' ?>> Non
        </label><br><br>
        <button type="submit">Modifier</button>
    </form>
    <a href="admin_cars.php">Annuler</a>
</body>
</html>