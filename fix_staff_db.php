<?php
require_once 'app/config.php';
$pdo = get_pdo();
try {
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'employee', 'client') DEFAULT 'client'");
    echo "Enum 'role' mis à jour avec 'employee'.\n";
    
    // On restaure le rôle pour les comptes de Mamadou
    $pdo->exec("UPDATE users SET role = 'employee' WHERE email IN ('admin@mixstore.fr', 'saliou1@gmail.com', 'saliou@gmail.com')");
    echo "Comptes promus au rôle 'employee'.\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
