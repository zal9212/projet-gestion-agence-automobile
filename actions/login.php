<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, nom, prenom, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: ../admin_cars.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        $_SESSION['error'] = "Identifiants invalides.";
        header('Location: ../login.php');
        exit;
    }
}