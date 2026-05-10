<?php
require 'app/config.php';
$pdo = get_pdo();

// 1. Lister les voitures et leurs images
$cars = $pdo->query("SELECT id, marque, modele, image_principale FROM cars")->fetchAll();

echo "--- Etat actuel des images ---\n";
foreach ($cars as $car) {
    echo "ID: " . $car['id'] . " | " . $car['marque'] . " " . $car['modele'] . " | Image: " . $car['image_principale'] . "\n";
    
    // Si l'image est vide ou contient une URL externe cassée, on peut mettre une image par défaut
    if (empty($car['image_principale']) || strpos($car['image_principale'], 'http') !== false) {
        // On remplace par l'image locale que tu as à la racine
        $new_image = '3d-car-with-minimal-background.jpg';
        $pdo->prepare("UPDATE cars SET image_principale = ? WHERE id = ?")->execute([$new_image, $car['id']]);
        echo "   -> Mise à jour vers l'image locale : $new_image\n";
    }
}
?>
