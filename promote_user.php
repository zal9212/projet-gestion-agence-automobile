<?php
require_once 'app/config.php';
$pdo = get_pdo();
$email = 'admin@mixstore.fr'; // On suppose que c'est celui-là
$pdo->prepare("UPDATE users SET role = 'employee' WHERE email = ?")->execute([$email]);
echo "Rôle de $email mis à jour en 'employee'.\n";

// Fallback pour les autres si nécessaire
$pdo->prepare("UPDATE users SET role = 'employee' WHERE email = 'saliou@gmail.com'")->execute();
?>
