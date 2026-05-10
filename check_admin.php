<?php
require 'app/config.php';
$pdo = get_pdo();

// On cherche s'il y a un admin
$stmt = $pdo->query("SELECT email, role FROM users WHERE role = 'admin'");
$admin = $stmt->fetch();

if ($admin) {
    echo "Un compte admin existe déjà : " . $admin['email'] . "\n";
} else {
    // Si aucun admin, on en crée un par défaut
    $email = "admin@autorent.sn";
    $pass = password_hash("admin123", PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (nom, prenom, email, password, role) VALUES ('Admin', 'AutoRent', '$email', '$pass', 'admin')");
    echo "Compte admin créé !\n";
    echo "Email : $email\n";
    echo "Mot de passe : admin123\n";
}
echo "Rendez-vous sur : index.php?action=login\n";
