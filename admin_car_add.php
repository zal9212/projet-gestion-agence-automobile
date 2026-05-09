<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Ajouter Voiture</title></head>
<body>
    <h1>Ajouter une Voiture</h1>
    <form action="actions/admin_car_add.php" method="POST">
        <label>Catégorie: 
            <select name="category_id">
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Immatriculation: <input type="text" name="immatriculation" required></label><br><br>
        <label>Marque: <input type="text" name="marque" required></label><br><br>
        <label>Modèle: <input type="text" name="modele" required></label><br><br>
        <label>Année: <input type="number" name="annee"></label><br><br>
        <label>Type Carburant: 
            <select name="type_carburant">
                <option value="Essence">Essence</option>
                <option value="Diesel">Diesel</option>
                <option value="Electrique">Electrique</option>
                <option value="Hybride">Hybride</option>
            </select>
        </label><br><br>
        <label>Boîte de vitesse: 
            <select name="boite_vitesse">
                <option value="Manuelle">Manuelle</option>
                <option value="Automatique">Automatique</option>
            </select>
        </label><br><br>
        <label>Places: <input type="number" name="nb_places" value="5"></label><br><br>
        <label>Prix Journalier: <input type="number" step="0.01" name="prix_journalier" required></label><br><br>
        <label>Statut: 
            <select name="status">
                <option value="disponible">Disponible</option>
                <option value="maintenance">Maintenance</option>
                <option value="louée">Louée</option>
            </select>
        </label><br><br>
        <label>Climatisé: 
            <input type="radio" name="climatisé" value="1" checked> Oui
            <input type="radio" name="climatisé" value="0"> Non
        </label><br><br>
        <button type="submit">Ajouter</button>
    </form>
    <a href="admin_cars.php">Annuler</a>
</body>
</html>