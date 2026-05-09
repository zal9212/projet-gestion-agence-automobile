<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = $_POST['password'];
    $adresse = trim($_POST['adresse']);

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir les champs obligatoires.";
        header('Location: ../register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, telephone, password, adresse) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $telephone, $hash, $adresse]);
        header('Location: ../login.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
        } else {
            $_SESSION['error'] = "Erreur d'inscription.";
        }
        header('Location: ../register.php');
        exit;
    }
}