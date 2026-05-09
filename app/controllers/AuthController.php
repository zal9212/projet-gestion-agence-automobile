<?php
class AuthController {
    public static function login() {
        require 'app/views/front/login.php';
    }

    public static function doLogin() {
        $pdo = getPDO();
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') header('Location: index.php?action=admin_dashboard');
            else header('Location: index.php');
        } else {
            $_SESSION['error'] = "Identifiants invalides.";
            header('Location: index.php?action=login');
        }
    }

    public static function register() {
        require 'app/views/front/register.php';
    }

    public static function doRegister() {
        $pdo = getPDO();
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $password]);
            $_SESSION['success'] = "Inscription réussie.";
            header('Location: index.php?action=login');
        } catch(Exception $e) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header('Location: index.php?action=register');
        }
    }

    public static function logout() {
        session_destroy();
        header('Location: index.php');
    }
}