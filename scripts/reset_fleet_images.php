<?php
require 'app/config.php';
$pdo = get_pdo();

// On réinitialise toutes les images de voitures vers l'image 3D premium pour l'audit final
$pdo->exec("UPDATE cars SET image_principale = '3d-car-with-minimal-background.jpg'");

echo "Toutes les images ont été réinitialisées vers l'identité visuelle 3D Premium.";
